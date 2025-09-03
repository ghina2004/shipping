<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{
    public function toArray($request): array {
        $data = [
            'id'           => $this->id,
            'subject'      => $this->subject,
            'message'      => $this->message,
            'status'       => $this->status,
            'status_label' => $this->statusEnum()->label(),
            'admin_reply'  => $this->admin_reply,
            'replied_at'   => optional($this->replied_at)?->toDateTimeString(),
            'created_at'   => optional($this->created_at)?->toDateTimeString(),
        ];

        if (auth()->check() && auth()->user()->hasRole('admin')) {
            $data['customer'] = [
                'id'          => $this->customer?->id,
                'first_name'  => $this->customer?->first_name,
                'second_name' => $this->customer?->second_name,
                'third_name'  => $this->customer?->third_name,
                'email'       => $this->customer?->email,
                'phone'       => $this->customer?->phone,
            ];
        }

        return $data;
    }
}
