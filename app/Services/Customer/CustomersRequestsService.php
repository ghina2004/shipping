<?php

namespace App\Services\Customer;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CustomersRequestsService
{

    public function showCustomersRequest(): Collection
    {
        return User::query()
            ->where('is_verified', 0)
            ->where('email_verified_at', '!=', null)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'customer');
            })
            ->get();
    }

    public function acceptCustomer(User $user): User
    {
        $user->update(['is_verified' => 1]);
        return $user;
    }

    public function rejectCustomer(User $user): void
    {
        $user->delete();
    }
}
