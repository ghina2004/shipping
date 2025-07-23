<?php

namespace App\Services\Payment;

use App\Models\OrderInvoice;
use App\Models\OrderPayment;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripePaymentService
{
    public function handleInitialPayment(OrderInvoice $invoice): OrderPayment
    {
        $user = Auth::user();
        $percentage = $user->status === 0 ? 0.75 : 0.25;

        $paidAmount = round($invoice->total_final_amount * $percentage, 2);
        $dueAmount = $invoice->total_final_amount - $paidAmount;

        $paymentIntent = $this->createStripeIntent($paidAmount * 100); // Stripe uses cents

        return OrderPayment::create([
            'order_invoice_id' => $invoice->id,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'status' => 'partial',
            'paid_at' => now()->format('Y-m-d'),
            'due_date' => now()->format('Y-m-d'),
        ]);
    }

    public function handleRemainingPayment(OrderInvoice $invoice): OrderPayment
    {
        $payment = $invoice->orderPayment;

        $dueAmount = round($invoice->total_final_amount - $payment->paid_amount, 2);

        $paymentIntent = $this->createStripeIntent($dueAmount * 100);

        $payment->update([
            'paid_amount' => $dueAmount,
            'due_amount' => 0,
            'status' => 'completed',
            'paid_at' => now()->format('Y-m-d'),
        ]);

        return $payment;
    }

    private function createStripeIntent($amount): PaymentIntent
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        return PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);
    }
}
