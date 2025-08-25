<?php

namespace App\Services\Customer;

use App\Models\User;
use App\Services\Media\UserMediaService;
use Illuminate\Http\UploadedFile;

class CustomerSegmentationService
{
    public function __construct(
        protected UserMediaService $userMediaService
    ) {}


    public function getProfile(User $customer): User
    {
        $customer->loadCount('orders');

        $customer->shipments_count = $customer->orders()
            ->withCount('shipments')
            ->get()
            ->sum('shipments_count');

        $customer->loadMissing('media');

        return $customer;
    }


    public function updateProfile(User $customer, array $data): User
    {
        $customer->update([
            'first_name'  => $data['first_name']  ?? $customer->first_name,
            'second_name' => $data['second_name'] ?? $customer->second_name,
            'third_name'  => $data['third_name']  ?? $customer->third_name,
            'email'       => $data['email']       ?? $customer->email,
            'phone'       => $data['phone']       ?? $customer->phone,
        ]);

        return $this->getProfile($customer);
    }


    public function uploadProfileImage(User $customer, UploadedFile $image): User
    {
        $this->userMediaService->uploadCustomerProfileImage($image, $customer->id);

        return $this->getProfile($customer);
    }


    public function deleteProfileImage(User $customer): User
    {
        $media = $customer->media()
            ->where('type', \App\Enums\Media\MediaType::USER_PROFILE->value)
            ->first();

        if ($media) {
            app(\App\Services\Media\MediaService::class)->deleteFile($media);
        }

        return $this->getProfile($customer);
    }
}
