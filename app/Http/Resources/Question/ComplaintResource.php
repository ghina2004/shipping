<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{
    public function toArray($request): array {
        return [
            'id'          => $this->id,
            'subject'     => $this->subject,
            'message'     => $this->message,
            'status'      => $this->status,
            'status_label'=> $this->statusEnum()->label(),
            'admin_reply' => $this->admin_reply,
            'replied_at'  => optional($this->replied_at)?->toDateTimeString(),
            'created_at'  => optional($this->created_at)?->toDateTimeString(),
        ];
    }
}
