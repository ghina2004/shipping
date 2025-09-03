<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;

class InvoiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'invoice_type' => $this->invoice_type,
            'initial_amount' => $this->initial_amount,
            'customs_fee' => $this->customs_fee,
            'service_fee' => $this->service_fee,
            'company_profit' => $this->company_profit,
            'final_amount' => $this->final_amount,
            'notes' => $this->notes,
        ];
    }
}
