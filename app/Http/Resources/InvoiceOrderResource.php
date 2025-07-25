<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;

class InvoiceOrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'total_initial_amount' => $this->total_initial_amount,
            'total_customs_fee' => $this->total_customs_fee,
            'total_service_fee' => $this->total_service_fee,
            'total_company_profit' => $this->total_company_profit,
            'total_final_amount' => $this->total_final_amount,
            'notes' => $this->notes,
        ];
    }
}
