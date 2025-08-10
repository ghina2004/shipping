<?php

namespace App\Services\Payment;

use App\Enums\Payment\PaymentStatus;
use App\Models\Order;
use App\Models\OrderInvoice;
use App\Models\OrderPayment;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentInfoService
{
    public function show(Order $order): OrderPayment
    {
        $invoice = $order->orderInvoice()
            ->with('orderPayment')
            ->firstOrFail();

        $payment = $invoice->orderPayment ?? $invoice->orderPayment()->create([
            'paid_amount' => 0,
            'due_amount'  => $invoice->total_final_amount,
            'status'      => 'pending',
            'due_date'    => now()->addDays(15)->toDateString(),
        ]);

        $total = (float) $invoice->total_final_amount;
        $paid  = (float) $payment->paid_amount;
        $due   = max(0, round($total - $paid, 2));

        $nextPhase  = $paid > 0 && $due > 0 ? 'remaining' : ($due > 0 ? 'initial' : null);
        $nextAmount = $due;

        $payment->setAttribute('total', $total);
        $payment->setAttribute('paid', $paid);
        $payment->setAttribute('due', $due);
        $payment->setAttribute('next_payment_phase', $nextPhase);
        $payment->setAttribute('next_payment_amount', $nextAmount);

        return $payment;
    }

    public function syncAfterOrderInvoiceChange(OrderInvoice $invoice): ?OrderPayment
    {
        return DB::transaction(function () use ($invoice) {
            $payment = $invoice->orderPayment()->lockForUpdate()->first();
            if (! $payment) {
                return null;
            }

            $total = (float) $invoice->total_final_amount;
            $paid  = (float) $payment->paid_amount;
            $due   = max(0, round($total - $paid, 2));

            $status = $due <= 0
                ? PaymentStatus::COMPLETE->value ?? 'completed'
                : ($paid > 0
                    ? (PaymentStatus::PARTIAL->value ?? 'partial')
                    : (PaymentStatus::PENDING->value ?? 'pending'));

            $payment->update([
                'currency'    => $payment->currency ?? 'usd',
                'due_amount'  => $due,
                'status'      => $status,
            ]);


            try {
                if ($payment->stripe_payment_intent_id && $due > 0 && $status !== 'completed') {
                    Stripe::setApiKey(config('services.stripe.secret'));
                    PaymentIntent::update($payment->stripe_payment_intent_id, [
                        'amount' => (int) round($due * 100),
                    ]);
                }
            } catch (\Throwable $e) {
            }

            return $payment->refresh();
        });
    }
}

