<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'cart_id'=>$this->cart_id ,
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
            'original_company_id'   => $this->original_company_id,
            'number' => $this->number,
            'shipping_date' => $this->shipping_date,
            'service_type' => $this->service_type,
            'origin_country' => $this->origin_country,
            'destination_country' => $this->destination_country,
            'shipping_method' => $this->shipping_method,
            'cargo_weight' => $this->cargo_weight,
            'containers_size' => $this->containers_size,
            'containers_numbers' => $this->containers_numbers,
            'employee_notes' => $this->employee_notes,
            'customer_notes' => $this->customer_notes,
            'is_information_complete' => (int) $this->is_information_complete,
            'is_confirm' => (int) $this->is_confirm,
            'having_supplier' => (int) $this->having_supplier,
        ];
    }
}

