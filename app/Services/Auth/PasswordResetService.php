<?php

namespace App\Services\Auth;

use App\Enums\Status\Verification;
use App\Exceptions\Types\CustomException;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Pest\Support\Str;

class PasswordResetService
{
    /**
     * @throws CustomException
     */
    public function forgetPassword($request)
    {
        $user = User::query()->where('email', $request['email'])->first();

        if (!$user ) {
            throw new CustomException(__('auth.wrong_email'), 400);
        }

        return $user;
    }
    public function resetPassword($request,$userId)
    {
        $user = User::query()->findOrFail($userId);
        $user['password'] = $request['password'];
        $user->save();
        return $user;
    }

}
