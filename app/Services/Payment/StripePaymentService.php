<?php

namespace App\Services\Payment;

use App\Models\OrderInvoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripePaymentService
{
    public function pay(OrderInvoice $invoice): array
    {
        $payment = $invoice->orderPayment;

        if (! $payment || $payment->paid_amount <= 0) {
            $result = $this->handleInitialPayment($invoice);
            $result['phase'] = 'initial';
            return [
                'data'    => $result,
                'message' => 'Initial payment intent created successfully.'
            ];
        }

        $result = $this->handleRemainingPayment($invoice);
        $result['phase'] = 'remaining';
        return [
            'data'    => $result,
            'message' => 'Remaining payment intent created successfully.'
        ];
    }
    public function handleInitialPayment(OrderInvoice $invoice): array
    {
        $user = Auth::user();
        $percentage = $user->status === 0 ? 0.75 : 0.25;

        $amount = round($invoice->total_final_amount * $percentage, 2);

        $intent = $this->createStripeIntent(
            amount: (int) round($amount * 100),
            metadata: [
                'phase' => 'initial',
                'invoice_id' => (string) $invoice->id,
                'order_id' => (string) $invoice->order_id,
                'user_id' => (string) $user->id,
            ]
        );

        $payment = $invoice->orderPayment()->firstOrCreate([], [
            'paid_amount' => 0,
            'due_amount'  => $invoice->total_final_amount,
            'status'      => 'pending',
            'due_date'    => now()->addDays(15)->toDateString(),
        ]);

        $payment->update([
            'stripe_payment_intent_id' => $intent->id,
        ]);

        return [
            'client_secret'      => $intent->client_secret,
            'payment_intent_id'  => $intent->id,
            'amount'             => $amount,
            'currency'           => 'usd',
        ];
    }

    public function handleRemainingPayment(OrderInvoice $invoice): array
    {
        $payment = $invoice->orderPayment;
        if (! $payment) {
            return ['error' => 'no_initial_payment'];
        }

        $alreadyPaid = (float) $payment->paid_amount;
        $total       = (float) $invoice->total_final_amount;
        $dueAmount   = round($total - $alreadyPaid, 2);

        if ($dueAmount <= 0) {
            return ['already_paid' => true];
        }

        $intent = $this->createStripeIntent(
            amount: (int) round($dueAmount * 100),
            metadata: [
                'phase' => 'remaining',
                'invoice_id' => (string) $invoice->id,
                'order_id' => (string) $invoice->order_id,
                'user_id' => (string) Auth::id(),
            ]
        );

        $payment->update([
            'stripe_payment_intent_id' => $intent->id,
            'status' => 'pending+',
        ]);

        return [
            'client_secret'      => $intent->client_secret,
            'payment_intent_id'  => $intent->id,
            'amount'             => $dueAmount,
            'currency'           => 'usd',
        ];
    }

    public function verifyAndMarkPaid(OrderInvoice $invoice, string $paymentIntentId): array
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $pi = PaymentIntent::retrieve($paymentIntentId);

            if ($pi->status !== 'succeeded') {
                return ['status' => $pi->status];
            }

            DB::transaction(function () use ($pi, $invoice) {
                $capturedAmount = $pi->amount_received / 100.0;
                $payment = $invoice->orderPayment ?? $invoice->orderPayment()->create([
                    'paid_amount' => 0,
                    'due_amount'  => $invoice->total_final_amount,
                    'status'      => 'pending',
                    'due_date'    => now()->addDays(15)->toDateString(),
                ]);

                $newPaid = min($invoice->total_final_amount, round($payment->paid_amount + $capturedAmount, 2));

                $payment->update([
                    'paid_amount' => $newPaid,
                    'due_amount'  => max(0, round($invoice->total_final_amount - $newPaid, 2)),
                    'status'      => $newPaid >= $invoice->total_final_amount ? 'complete' : 'partial',
                    'paid_at'     => now()->toDateString(),
                ]);
            });

            return ['status' => 'succeeded'];

        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function createStripeIntent(int $amount, array $metadata = []): PaymentIntent
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        return PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'metadata' => $metadata,
        ]);
    }
}
