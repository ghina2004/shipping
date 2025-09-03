<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\User\UserManagement;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class UserManagementController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected UserManagement $service
    ) {}

    // ===== Employees =====
    public function addEmployee(AddUserRequest $request): JsonResponse
    {
        $user = $this->service->createEmployee($request->validated());
        return self::Success(new UserResource($user), 'Employee created successfully.');
    }

    public function employees(): JsonResponse
    {
        return self::Success(UserResource::collection($this->service->listEmployees()), 'Employee List');
    }

    public function updateEmployee(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->service->update($id, $request->validated());
        return self::Success(new UserResource($user), 'Employee updated successfully.');
    }

    // ===== Accountants =====
    public function addAccountant(AddUserRequest $request): JsonResponse
    {
        $user = $this->service->createAccountant($request->validated());
        return self::Success(new UserResource($user), 'Accountant created successfully.');
    }

    public function accountants(): JsonResponse
    {
        return self::Success(UserResource::collection($this->service->listAccountants()), 'Accountant List');
    }

    public function updateAccountant(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->service->update($id, $request->validated());
        return self::Success(new UserResource($user), 'Accountant updated successfully.');
    }

    // ===== Shipment Managers =====
    public function addShipmentManager(AddUserRequest $request): JsonResponse
    {
        $user = $this->service->createShipmentManager($request->validated());
        return self::Success(new UserResource($user), 'Shipment Manager created successfully.');
    }

    public function shipmentManagers(): JsonResponse
    {
        return self::Success(UserResource::collection($this->service->listShipmentManagers()), 'Shipment Manager List');
    }

    public function updateShipmentManager(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->service->update($id, $request->validated());
        return self::Success(new UserResource($user), 'Shipment Manager updated successfully.');
    }

    // ===== Common: show & delete =====
    public function show(int $id): JsonResponse
    {
        $user = $this->service->show($id);
        return self::Success(new UserResource($user), 'User details.');
    }

    public function delete(int $id): JsonResponse
    {
        $this->service->delete($id);
        return self::Success([], 'User deleted successfully.');
    }
}
