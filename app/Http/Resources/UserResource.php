<?php

namespace App\Http\Resources;

use App\Enums\Media\MediaType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'           => $this->id,
            'first_name'   => $this->first_name,
            'second_name'  => $this->second_name,
            'third_name'   => $this->third_name,
            'email'        => $this->email,
            'phone'        => $this->phone,
        ];

        if ($this->hasRole('customer')) {
            $data['commercial_register'] = optional($this->media->firstWhere('type', MediaType::COMMERCIAL_REGISTER))?->url;
        }

        return $data;
    }
}
