<?php

namespace App\Services\Auth;

use App\Exceptions\Types\CustomException;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class VerificationService
{
    public function generateCode(User $user,int $time): int
    {
        $cacheKey = 'user_code_' . $user['id'] ;

        Cache::forget($cacheKey);

        $code = mt_rand(100000, 999999);

        Cache::put($cacheKey, [
            'code' => $code
        ], now()->addMinutes($time));

        return $code;
    }

    /**
     * @throws CustomException
     */

    public function verifyCode(array $request, int $userId)
    {
        $user     = User::query()->findOrFail($userId);
        $cacheKey = 'user_code_' . $userId;
        $data     = Cache::get($cacheKey);

        if (!$data) {
            throw new CustomException(__('auth.code_expired'), 422);
        }
        if ($data['code'] != $request['code']) {
            throw new CustomException(__('auth.wrong_code'), 422);
        }
        Cache::forget($cacheKey);

        return $user;
    }


    public function refreshCode($userId): array
    {
        $cacheKey = 'user_code_' . $userId;

        Cache::forget($cacheKey);

        $user = User::query()->findOrFail($userId);

        $code = $this->generateCode($user,5);

        return [$user, $code];
    }
}
