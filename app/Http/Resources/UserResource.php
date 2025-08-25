<?php

namespace App\Http\Resources;

use App\Enums\Customer\CustomerStatus;
use App\Enums\Customer\VerificationStatus;
use App\Enums\Media\MediaType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'second_name' => $this->second_name,
            'third_name' => $this->third_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->getRoleNames()->first(),
        ];

        if ($this->hasRole('customer')) {
            $data['commercial_register'] = optional(
                $this->media?->firstWhere('type', MediaType::COMMERCIAL_REGISTER->value)
            )?->url;

            $data['profile_image'] = optional(
                $this->media?->firstWhere('type', MediaType::USER_PROFILE->value)
            )?->url;

            $data['orders_count'] = (int)($this->order_customers_count ?? 0);
            $data['shipments_count'] = (int)($this->shipments_count ?? 0);

            $data['customer_status'] = $this->status !== null
                ? CustomerStatus::from((int)$this->status)->label()
                : null;
        }

        if ($request->user()?->hasRole('admin')) {
            $data['verification_status'] = $this->is_verified !== null
                ? VerificationStatus::from((int)$this->is_verified)->label()
                : null;
        }

        return $data;
    }
}
