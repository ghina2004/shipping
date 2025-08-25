<?php

namespace App\Http\Resources\Question;

use App\Http\Resources\InvoiceOrderResource;
use App\Http\Resources\Question\QuestionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MyFatoorahResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'phase'         => $this->resource['phase'] ?? null,
            'payment_link'  => $this->resource['payment_link'] ?? null,
            'mf_invoice_id' => $this->resource['mf_invoice_id'] ?? null,
            'amount'        => $this->resource['amount'] ?? null,
            'currency'      => $this->resource['currency'] ?? null,
            'invoice'       => new InvoiceOrderResource($this->resource['invoice']),
        ];
    }
}
