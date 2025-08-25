<?php

namespace App\Services\Payment;

use App\Exceptions\Types\CustomException;
use App\Models\OrderInvoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MyFatoorahPaymentService
{
    public function pay(OrderInvoice $invoice, string $currency = 'USD'): array
    {
        $payment = $invoice->orderPayment;
        $currency = strtoupper($currency ?: 'USD');

        if($payment && $payment->status == 'complete') throw new CustomException('The invoice has already been fully paid',409);

        if (!$payment || $payment->paid_amount <= 0) {
            $res = $this->handleInitialPayment($invoice, $currency);
            $res['phase'] = 'initial';
            return ['data' => $res, 'message' => 'Initial payment link created successfully.'];
        }

        $res = $this->handleRemainingPayment($invoice, $currency);
        $res['phase'] = 'remaining';
        return ['data' => $res, 'message' => 'Remaining payment link created successfully.'];
    }

    private function handleInitialPayment(OrderInvoice $invoice, string $currency): array
    {
        $user = Auth::user();
        $percentage = ($user && $user->status === 0) ? 0.75 : 0.25;

        $amount = round($invoice->total_final_amount * $percentage, 2);

        $resp = $this->createMyFatoorahInvoice($invoice, $amount, $currency, 'initial');

        $invoice->orderPayment()->firstOrCreate([], [
            'paid_amount' => 0,
            'due_amount' => $invoice->$amount,
            'status' => 'pending',
        ]);

        return [
            'payment_link'  => $resp['payment_url'],
            'mf_invoice_id' => $resp['mf_invoice_id'],
            'amount'        => $amount,
            'currency'      => $currency,
        ];
    }

    private function handleRemainingPayment(OrderInvoice $invoice, string $currency): array
    {
        $payment = $invoice->orderPayment;
        if (!$payment) {
            return ['error' => 'no_initial_payment'];
        }

        $alreadyPaid = (float)$payment->paid_amount;
        $total = (float)$invoice->total_final_amount;
        $dueAmount = round($total - $alreadyPaid, 2);

        if ($dueAmount <= 0) {
            return ['already_paid' => true];
        }

        $resp = $this->createMyFatoorahInvoice($invoice, $dueAmount, $currency, 'remaining');

        $payment->update(['status' => 'pending+']);

        return [
            'payment_link'  => $resp['payment_url'],
            'mf_invoice_id' => $resp['mf_invoice_id'],
            'amount'        => $dueAmount,
            'currency'      => $currency,
        ];
    }


    private function createMyFatoorahInvoice(
        OrderInvoice $invoice,
        float $amount,
        string $displayCurrency, // يجي من الطلب: EGP أو JOD أو USD...
        string $phase
    ): array {
        $user = Auth::user();

        [$mobileCode, $mobile] = $this->splitFullPhone((string)($user->phone ?? '+96500000000'));

        $payload = [
            'NotificationOption' => 'LNK',
            'InvoiceValue'       => $amount,
            'DisplayCurrencyIso' => strtoupper($displayCurrency),
            'CustomerName'       => $user->first_name ?? 'Guest',
            'CustomerEmail'      => $user->email ?? 'test@example.com',
            'MobileCountryCode'  => $mobileCode,
            'CustomerMobile'     => $mobile,
            'CustomerReference'  => (string) $invoice->id,
            'UserDefinedField'   => (string) $invoice->order_id,
            'CallBackUrl'        => 'https://example.com/payment-success',
            'ErrorUrl'           => 'https://example.com/payment-failed',
            'Language'           => app()->getLocale() === 'ar' ? 'ar' : 'en',
        ];

        $res = Http::withToken(config('services.myfatoorah.api_key'))
            ->post(config('services.myfatoorah.base_url').'/v2/SendPayment', $payload)
            ->json();

        if (empty($res['IsSuccess']) || !$res['IsSuccess']) {
            throw new \Exception('MyFatoorah SendPayment failed: '.json_encode($res));
        }

        return [
            'payment_url'  => (string)($res['Data']['InvoiceURL'] ?? ''),
            'mf_invoice_id'=> (string)($res['Data']['InvoiceId'] ?? ''),
        ];
    }

    public function verifyAndMarkPaid(OrderInvoice $invoice, ?string $paymentId = null, ?string $mfInvoiceId = null): array
    {
        $payload = $mfInvoiceId
            ? ['KeyType' => 'InvoiceId', 'Key' => $mfInvoiceId]
            : ['KeyType' => 'PaymentId',  'Key' => $paymentId];

        $res = Http::withToken(config('services.myfatoorah.api_key'))
            ->post(config('services.myfatoorah.base_url') . '/v2/GetPaymentStatus', $payload)
            ->json();

        if (empty($res['IsSuccess']) || !$res['IsSuccess']) {
            return ['status' => 'failed', 'message' => $res['Message'] ?? 'Payment not completed'];
        }

        $data = $res['Data'] ?? [];
        if (strtoupper($data['InvoiceStatus'] ?? '') !== 'PAID') {
            return ['status' => strtolower($data['InvoiceStatus'] ?? 'unknown')];
        }

        $phase = strtolower($data['UserDefinedField'] ?? '');
        if (!in_array($phase, ['initial', 'remaining'])) {
            $phase = $invoice->orderPayment && $invoice->orderPayment->status === 'partial'
                ? 'remaining'
                : 'initial';
        }

        $total = round((float) $invoice->total_final_amount, 2);

        if ($phase === 'initial') {
            $newPaid = round($total * (($invoice->order->user->status ?? 0) === 0 ? 0.75 : 0.25), 2);
            $dueAmount = max(0, round($total - $newPaid, 2));
        } else {
            $newPaid = $total;
            $dueAmount = 0.00;
        }

        DB::transaction(function () use ($invoice, $newPaid, $paymentId, $data, $total, $dueAmount) {
            $payment = $invoice->orderPayment ?? $invoice->orderPayment()->create([
                'paid_amount' => 0,
                'due_amount'  => $total,
                'status'      => 'pending',
                'due_date'    => now()->addDays(30)->toDateString(),
            ]);

            $payment->update([
                'gateway'            => 'myfatoorah',
                'gateway_invoice_id' => $data['InvoiceId'] ?? $payment->gateway_invoice_id,
                'gateway_payment_id' => $paymentId ?? ($data['InvoiceTransactions'][0]['PaymentId'] ?? null),
                'paid_amount'        => $newPaid,
                'due_amount'         => $dueAmount,
                'status'             => $newPaid >= $total ? 'complete' : 'partial',
                'paid_at'            => now()->toDateTimeString(),
            ]);
        });

        return ['status' => 'succeeded'];
    }

    private function splitFullPhone(string $fullPhone): array
    {
        if (preg_match('/^\+(\d{1,3})(\d{5,15})$/', $fullPhone, $m)) {
            return ['+' . $m[1], $m[2]];
        }
        return ['+965', ltrim($fullPhone, '+')];
    }
}
