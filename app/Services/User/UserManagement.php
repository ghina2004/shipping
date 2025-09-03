<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserManagement
{
    private function createUser(array $data, string $role): User
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $data['is_verified']      = 1;
        $data['email_verified_at'] = now();

        $user = User::query()->create($data);
        $user->assignRole($role);

        return $user->fresh('roles');
    }

    public function createEmployee(array $data): User
    {
        return $this->createUser($data, 'employee');
    }

    public function createAccountant(array $data): User
    {
        return $this->createUser($data, 'accountant');
    }

    public function createShipmentManager(array $data): User
    {
        return $this->createUser($data, 'shipment manager');
    }

    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update([
            'first_name'  => $data['first_name']  ?? $user->first_name,
            'second_name' => $data['second_name'] ?? $user->second_name,
            'third_name'  => $data['third_name']  ?? $user->third_name,
            'email'       => $data['email']       ?? $user->email,
            'phone'       => $data['phone']       ?? $user->phone,
            'password'    => $data['password']    ?? $user->password,
        ]);

        return $user->fresh('roles');
    }

    public function delete(int $id): bool
    {
        $user = User::findOrFail($id);
        return (bool) $user->delete();
    }

    public function show(int $id): User
    {
        return User::with('roles')->findOrFail($id);
    }

    public function listEmployees()
    {
        return User::role('employee')->get();
    }

    public function listAccountants()
    {
        return User::role('accountant')->get();
    }

    public function listShipmentManagers()
    {
        return User::role('shipment manager')->get();
    }
}
