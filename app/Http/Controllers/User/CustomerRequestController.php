<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\CustomersRequestsService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class CustomerRequestController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected CustomersRequestsService $userRequestService,
    ) {}

    public function show(): JsonResponse
    {
        $users = $this->userRequestService->showCustomersRequest();

        return self::Success([
            'user' => UserResource::collection($users),
        ],'user requests shown successfully.');
    }

    public function showRequestDetails(User $user): JsonResponse
    {
        return self::Success([
            'user' => new UserResource($user),
        ],'user request shown successfully.');
    }

    public function accept(User $user): JsonResponse
    {
        $user = $this->userRequestService->acceptCustomer($user);

        return self::Success([
            'user' => new UserResource($user),
        ],'user accepted successfully.');
    }

    public function reject(User $user): JsonResponse
    {
        $this->userRequestService->rejectCustomer($user);

        return self::Success([
        ],'user rejected successfully.');
    }

}
