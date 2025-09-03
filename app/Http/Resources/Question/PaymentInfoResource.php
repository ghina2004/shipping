<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'      => $this->id,
            'status'  => (string) $this->status,
            'currency'=> $this->currency ?? 'usd',

            'paid_amount' => (float) $this->paid_amount,
            'due_amount'  => (float) $this->due_amount,
            'paid_at'     => optional($this->paid_at)->toDateString(),
            'due_date'    => optional($this->due_date)->toDateString(),

            'next_payment_phase'  => $this->next_payment_phase ?? null,
            'next_payment_amount' => $this->next_payment_amount ?? null,
        ];
    }
}
