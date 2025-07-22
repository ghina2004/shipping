<?php

namespace App\Http\Controllers\Auth;

use App\Data\RegisterUserData;
use App\Exceptions\Types\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Mail\SendCodeMail;
use App\Services\Auth\AuthService;
use App\Services\Auth\VerificationService;
use App\Services\Cart\CartService;
use App\Services\Email\EmailService;
use App\Services\Media\UserMediaService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;


class AuthController extends Controller
{

    use ResponseTrait;
    private AuthService $authService;
    private VerificationService $verificationService;
    private EmailService $emailService;
    private UserMediaService $userImageService;

    private CartService $cartService;

    public function __construct(AuthService $authService, VerificationService $verificationService, EmailService $emailService, UserMediaService $userImageService, CartService $cartService){
        $this->authService = $authService;
        $this->verificationService = $verificationService;
        $this->emailService = $emailService;
        $this->userImageService = $userImageService;
        $this->cartService = $cartService;
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $userData = RegisterUserData::from($request->validated());

        $commercialFile = $request->file('commercial_register');

        $user = $this->authService->createCustomer($userData, $commercialFile);

        $code = $this->verificationService->generateCode($user, 5);

        $this->emailService->sendEmail($user, new SendCodeMail($code));

        return self::Success([
            'user' => new UserResource($user),
            'code' => $code
        ], __('auth.register_success'));
    }

    /**
     * @throws CustomException
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $user = $this->authService->login($request->validated());

        $token = $this->authService->generateToken($user);

        return self::Success([
            'user' => new UserResource($user),
            'token' => $token
        ],__('auth.login'));
    }


    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return self::Success('', __('auth.logout'));
    }

}
