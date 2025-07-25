<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentFullResource extends JsonResource
{

    public function toArray( $request): array
    { return [
        'shipment' => new ShipmentResource($this),
        'supplier' => new SupplierResource($this->whenLoaded('shipmentSupplier')),
            'answers' => ShipmentAnswerResource::collection($this->whenLoaded('answersShipment')),
        'documents' =>  new ShipmentDocumentResource($this->whenLoaded('shipmentDocuments')),
        ];
    }
}
