<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'message'    => $this->message,
            'sender_id'  => $this->sender_id,
            'sender_name' => optional($this->sender)->first_name ?? '---',
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
