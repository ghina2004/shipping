<?php

namespace App\Http\Controllers\Customer;

use App\Http\Requests\Image\UploadProfileImageRequest;
use App\Http\Requests\User\UpdateCustomerProfileRequest;
use App\Http\Resources\Question\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Services\Customer\CustomerProfileService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController
{
    use ResponseTrait;

    public function __construct(protected CustomerProfileService $service) {}


    public function show(): JsonResponse
    {
        $user = Auth::user();
        $profile = $this->service->getProfile($user);

        return self::Success(new UserProfileResource($profile), 'Profile shown successfully.');
    }

    public function update(UpdateCustomerProfileRequest $request): JsonResponse
    {
        $user = Auth::user();
        $updated = $this->service->updateProfile($user, $request->validated());

        return self::Success(new UserProfileResource($updated), 'Profile updated successfully.');
    }


    public function uploadImage(UploadProfileImageRequest $request): JsonResponse
    {
        $user = Auth::user();
        $updated = $this->service->uploadProfileImage($user, $request->file('image'));

        return self::Success(new UserProfileResource($updated), 'Profile image uploaded successfully.');
    }


    public function deleteImage(): JsonResponse
    {
        $user = Auth::user();
        $updated = $this->service->deleteProfileImage($user);

        return self::Success(new UserProfileResource($updated), 'Profile image deleted successfully.');
    }

}
