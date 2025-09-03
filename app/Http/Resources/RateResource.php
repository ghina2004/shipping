<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'order_id'      => $this->order_id,
            'customer_id'   => $this->customer_id,
            'employee_id'   => $this->employee_id,
            'service_rate'  => (int) $this->service_rate,
            'employee_rate' => (int) $this->employee_rate,
            'comment'       => $this->comment,
            'created_at'    => $this->created_at?->toDateTimeString(),
        ];
    }
}
