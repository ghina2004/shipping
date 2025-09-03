<?php

namespace App\Http\Resources;

use App\Http\Resources\Question\QuestionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentAnswerResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shipment_id' => $this->shipment_id,
            'question'    => new QuestionResource($this->whenLoaded('shipmentQuestion')),
            'answer' => $this->answer,
        ];
    }
}
