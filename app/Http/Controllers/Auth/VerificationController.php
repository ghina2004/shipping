<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendVerificationCode;
use App\Exceptions\Types\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserCodeCheckRequest;
use App\Http\Requests\Auth\UserRefreshCodeRequest;
use App\Http\Resources\UserIdResource;
use App\Mail\SendCodeMail;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\TokenService;
use App\Services\Auth\VerificationService;
use App\Services\Email\EmailService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    use ResponseTrait;
    private VerificationService $verificationService;
    private AuthService $authService;
    private EmailService $emailService;

    public function __construct(VerificationService  $verificationService,AuthService $authService,EmailService $emailService){
        $this->verificationService = $verificationService;
        $this->authService = $authService;
        $this->emailService = $emailService;
    }

    /**
     * @throws CustomException
     */
    public function verifyAuthCode(UserCodeCheckRequest $request, $userId): JsonResponse
    {
        $user = $this->verificationService->verifyCode($request->validated(),$userId);

        $token = $this->authService->generateToken($user);

        return self::Success([
            'user' => new UserIdResource($user),
            'token' => $token,
        ],__('auth.code_verified'));
    }

    /**
     * @throws CustomException
     */
    public function verifyPasswordCode(UserCodeCheckRequest $request, $userId): JsonResponse
    {
        $user = $this->verificationService->verifyCode($request->validated(),$userId);

        return self::Success([
            'user' => new UserIdResource($user),
        ],__('auth.code_verified'));
    }

    public function refreshCode($userId): JsonResponse
    {
        $data = $this->verificationService->refreshCode($userId);

        [$user, $code] = $data;

        $this->emailService->sendEmail($user,new SendCodeMail($code));

        return self::Success([
            'user' => new UserIdResource($user),
            'code' => $code,
        ],__('auth.code_refreshed'));
    }

}
