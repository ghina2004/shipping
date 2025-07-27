<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentDocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shipment_id' => $this->shipment_id,
            'type' => $this->type,
            'file_path' => asset($this->file_path),
            'uploaded_by' => $this->uploaded_by,
            'visible_to_customer' => $this->visible_to_customer,

        ];
    }
}
