<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'cart_number' => $this->cart_number,
            'shipments'   => ShipmentResource::collection(
                $this->whenLoaded('cartShipments')
            ),
        ];
    }
}
