<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\ManageCustomerService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ManageCustomerController extends Controller
{
    use ResponseTrait;
    public function __construct(
        protected ManageCustomerService $service
    ) {}

    public function index(): JsonResponse
    {
        $customers = $this->service->list();

        return self::Success(UserResource::collection($customers),'Customer List');
    }

    public function show(User $customer): JsonResponse
    {
        $customer = $this->service->show($customer);

        return self::Success(new UserResource($customer),'Customer Details');

    }

    public function destroy(User $customer): JsonResponse
    {
        $this->service->delete($customer);

        return self::Success([],'Customer deleted successfully.');
    }
}
