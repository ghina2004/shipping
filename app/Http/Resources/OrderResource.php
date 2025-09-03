<?php

namespace App\Http\Resources;

use App\Enums\Status\OrderPaymentStatusEnum;
use App\Enums\Status\OrderStatusEnum;
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
            'status'=>(int) $this->status,
            'payment_status' => OrderPaymentStatusEnum::from($this->payment_status)->label(),
            'order_status' => OrderStatusEnum::from($this->order_status)->label(),
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
