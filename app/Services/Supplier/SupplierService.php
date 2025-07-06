<?php

namespace App\Services\Supplier;

use App\Http\Requests\supplier\SupplierRequest;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SupplierService
{

    public function create(array $data, User $user): Supplier
    { return DB::transaction(function () use ($data, $user) {
        $data['user_id'] = $user->id;
        return Supplier::create($data);
    });
    }

    public function update(Supplier $supplier , array $data): Supplier
    {
        $supplier->update($data);
        return $supplier;
    }

    public function delete(Supplier $supplier): void
    {
        $supplier->delete();
    }

    public function show(Supplier $supplier): Supplier
    {
        return $supplier;
    }

}
