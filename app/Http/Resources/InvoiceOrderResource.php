<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;

class InvoiceOrderResource extends JsonResource
{
    public function toArray($request): array
    {
        $total = round((float) $this->total_final_amount, 2);

        $payment = $this->orderPayment ?? null;
        $paid    = $payment ? round((float) $payment->paid_amount, 2) : 0;
        $due     = max(0, round($total - $paid, 2));

        if ($due <= 0) {
            $nextAmount = 0.0;
        } elseif ($paid > 0) {
            $nextAmount = $due;
        } else {
            $userStatus = optional($this->order->customer)->status ?? 0;
            $pct = ($userStatus === 0) ? 0.75 : 0.25;

            $initialAmount = round($total * $pct, 2);
            $nextAmount = min($due, $initialAmount);
        }

        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'total_initial_amount' => $this->total_initial_amount,
            'total_customs_fee' => $this->total_customs_fee,
            'total_service_fee' => $this->total_service_fee,
            'total_company_profit' => $this->total_company_profit,
            'total_final_amount' => $this->total_final_amount,
            'notes' => $this->notes,

            'next_payment_amount' => $nextAmount,
        ];
    }
}
