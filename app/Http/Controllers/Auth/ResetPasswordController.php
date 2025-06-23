<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Status\Verification;
use App\Exceptions\Types\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserForgetPasswordRequest;
use App\Http\Requests\Auth\UserResetPasswordRequest;
use App\Http\Resources\UserIdResource;
use App\Http\Resources\UserResource;
use App\Mail\SendCodeMail;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\PasswordResetService;
use App\Services\Auth\VerificationService;
use App\Services\Email\EmailService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ResetPasswordController extends Controller
{

    use ResponseTrait;

    private AuthService $authService;
    private PasswordResetService $passwordResetService;
    private VerificationService $verificationService;
    private EmailService $emailService;

    public function __construct(AuthService $authService, PasswordResetService $passwordResetService, VerificationService $verificationService, EmailService $emailService){
        $this->authService = $authService;
        $this->passwordResetService = $passwordResetService;
        $this->verificationService = $verificationService;
        $this->emailService = $emailService;
    }

    /**
     * @throws CustomException
     */
    public function forgetPassword(UserForgetPasswordRequest $request): JsonResponse
    {
        $user = $this->passwordResetService->forgetPassword($request);

        $code = $this->verificationService->generateCode($user,5);

        $this->emailService->sendEmail($user,new SendCodeMail($code));

        return self::Success([
            'user' => new UserResource($user),
            'code' => $code
        ],  __('auth.code_sent'));
    }

    public function resetPassword(UserResetPasswordRequest $request,int $userId): JsonResponse
    {
        $this->passwordResetService->resetPassword($request->validated(),$userId);

        return self::Success([],__('auth.password_reset'));
    }

}
