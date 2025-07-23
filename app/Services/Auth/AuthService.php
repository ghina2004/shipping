<?php

namespace App\Services\Auth;

use App\Data\RegisterUserData;
use App\Enums\Status\CustomerStatus;
use App\Exceptions\Types\CustomException;
use App\Models\User;
use App\Services\Cart\CartService;
use App\Services\Media\UserMediaService;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected UserMediaService $mediaService,
        protected CartService $cartService
    ) {}
    public function createCustomer(RegisterUserData $data,$commercial_register)
    {
        $this->checkCustomer($data);

        $user = User::query()->create([
            ...$data->toArray(),
            'status' => CustomerStatus::NEW,
        ]);

        $this->mediaService->uploadCommercialRegister($commercial_register, $user['id']);


        $user->assignRole('customer');

        return $user;
    }

    /**
     * @throws CustomException
     */

    public function login(array $request)
    {
        $user = User::query()->where('email', $request['email'])->first();

        if (! $user || ! Hash::check($request['password'], $user['password'])) {
            throw new CustomException(__('auth.incorrect_credentials'), 400);
        }
        if($user->email_verified_at == null) {
            throw new CustomException(__('auth.not_verify_code'), 400);
        }
        if($user->is_verified == 0) {
            throw new CustomException(__('auth.not_verified'), 400);
        }

        if(!$user->cart()) $this->cartService->createCart($user);

        return $user;
    }

    public function generateToken($user)
    {
        return $user->createToken("token")->plainTextToken;
    }

    private function checkCustomer($data): void
    {
        $existingUser = User::query()->where('email', $data->email)
            ->whereNull('email_verified_at')
            ->first();

        if ($existingUser) $existingUser->delete();
    }

}
