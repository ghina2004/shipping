<?php

namespace App\Services\Auth;

use App\Data\RegisterUserData;
use App\Enums\Status\CustomerStatus;
use App\Exceptions\Types\CustomException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function createCustomer(RegisterUserData $data)
    {
        $this->checkCustomer($data);

        $user = User::query()->create([
            ...$data->toArray(),
            'status' => CustomerStatus::NEW,
        ]);

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
