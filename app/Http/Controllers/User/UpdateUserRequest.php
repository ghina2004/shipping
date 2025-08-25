<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddUserRequest;
use App\Http\Resources\UserResource;
use App\Services\User\UserManagement;
use App\Traits\ResponseTrait;

class UpdateUserRequest extends Controller
{
    use ResponseTrait;

    public function __construct(protected UserManagement $service) {}

    public function addEmployee(AddUserRequest $request)
    {
        $user = $this->service->createEmployee($request->validated());
        return self::Success(new UserResource($user), 'Employee created successfully');
    }

    public function employees()
    {
        return self::Success(UserResource::collection($this->service->listEmployees()), 'Employees list');
    }

    public function addAccountant(AddUserRequest $request)
    {
        $user = $this->service->createAccountant($request->validated());
        return self::Success(new UserResource($user), 'Accountant created successfully');
    }

    public function accountants()
    {
        return self::Success(UserResource::collection($this->service->listAccountants()), 'Accountants list');
    }

    public function addShipmentManager(AddUserRequest $request)
    {
        $user = $this->service->createShipmentManager($request->validated());
        return self::Success(new UserResource($user), 'Shipment Manager created successfully');
    }

    public function shipmentManagers()
    {
        return self::Success(UserResource::collection($this->service->listShipmentManagers()), 'Shipment Managers list');
    }

    public function show($id)
    {
        return self::Success(new UserResource($this->service->show($id)), 'User details');
    }

    public function delete($id)
    {
        $this->service->delete($id);
        return self::Success([], 'User deleted successfully');
    }
}
