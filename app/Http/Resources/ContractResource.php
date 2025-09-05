<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'shipment_id' => $this->shipment_id,

            'type'        => $this->type,
            'type_label'  => $this->typeEnum()->label(),
            'title'       => $this->title,

            'status'       => $this->statusEnum()->label(),

            'unsigned_file_url' => $this->unsigned_file_path, // مسار نسبي داخل public
            'signed_file_url'   => $this->signed_file_path,
            'signed_at'         => optional($this->signed_at)?->toDateTimeString(),

            'show_signed_to_customer' => (bool) $this->show_signed_to_customer,
            'show_unsigned_to_customer' => (bool) $this->show_unsigned_to_customer,

            'created_at' => optional($this->created_at)?->toDateTimeString(),
        ];
    }
}
