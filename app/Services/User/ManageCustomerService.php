<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Collection;

class ManageCustomerService
{

    public function list(): Collection
    {
        $customers = User::role('customer')
            ->withCount('orderCustomers')
            ->get();

        $customers->each(function ($customer) {
            $customer->shipments_count = $customer->orderCustomers()
                ->withCount('shipments')
                ->get()
                ->sum('shipments_count');
        });

        return $customers;
    }


    public function show(User $customer): User
    {
        $customer->loadCount('orderCustomers');

        $customer->shipments_count = $customer->orderCustomers()
            ->withCount('shipments')
            ->get()
            ->sum('shipments_count');

        return $customer;
    }


    public function delete(User $customer): bool
    {
        return (bool) $customer->delete();
    }
}
