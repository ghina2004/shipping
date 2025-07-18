<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = auth()->user();

        $data = [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'cart_number' => $this->cart_number,
            'created_at' => $this->created_at,
            'is_submit' => $this->is_submit,
            'shipments'   => OrderResource::collection(
                $this->whenLoaded('cartShipments')
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
