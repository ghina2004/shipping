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

        $total = round((float) $invoice->total_final_amount, 2);
        $paid  = round((float) $payment->paid_amount, 2);
        $due   = max(0, round($total - $paid, 2));

        $nextPhase = null;
        if ($due > 0) {
            $nextPhase = ($paid > 0) ? 'remaining' : 'initial';
        }

        // احسبي الدفعة التالية
        if ($nextPhase === 'initial') {
            $userStatus = optional($order->customer)->status ?? (optional(\Illuminate\Support\Facades\Auth::user())->status ?? 0);
            $pct = ($userStatus === 0) ? 0.75 : 0.25;

            $initialAmount = round($total * $pct, 2);
            $nextAmount = min($due, $initialAmount);
        } elseif ($nextPhase === 'remaining') {
            $nextAmount = $due;
        } else {
            $nextAmount = 0.0;
        }

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

