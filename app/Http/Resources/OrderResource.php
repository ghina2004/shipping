<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = auth()->user();

        $data =  [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'order_number' => $this->order_number,
            'status'=> $this->status,
            'original_company_id'   => $this->original_company_id,
            'shipments'   => ShipmentResource::collection(
                $this->whenLoaded('shipments')
            ),
        ];

        if (! $user->hasRole('customer')) {
            $data['employee_id'] = $this->employee_id;
            $data['accountant_id'] = $this->accountant_id ?? null;
            $data['shipping_manager_id'] = $this->shipping_manager_id;
        }

        return $data;
    }
}
