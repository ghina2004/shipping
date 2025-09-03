<?php

namespace App\Http\Resources\Question;

use App\Enums\Media\MediaType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerProfileRecource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $profileImg = optional(
            $this->media?->firstWhere('type', MediaType::USER_PROFILE->value)
        )?->url;

        return [
            'id'           => $this->id,
            'first_name'   => $this->first_name,
            'second_name'  => $this->second_name,
            'third_name'   => $this->third_name,
            'email'        => $this->email,
            'phone'        => $this->phone,
            'profile_image'=> $profileImg,
            'orders_count'    => (int) ($this->orders_count ?? 0),
            'shipments_count' => (int) ($this->shipments_count ?? 0),
        ];
    }
}
