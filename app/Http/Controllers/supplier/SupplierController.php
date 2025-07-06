<?php

namespace App\Http\Controllers\supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\supplier\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Services\Supplier\SupplierService;
use App\Traits\ResponseTrait;

class SupplierController extends Controller
{
    use ResponseTrait;

    public function __construct(protected SupplierService $supplierService ) {}

    public function store(SupplierRequest $request)
    {
        $user = auth()->user();
        $supplier = $this->supplierService->create($request->validated(), $user);
        return self::Success(new SupplierResource($supplier), __('Supplier created successfully.'));
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $supplier = $this->supplierService->update( $supplier,$request->validated());
        return self::Success(new SupplierResource($supplier), __('Supplier updated successfully.'));
    }

    public function show(Supplier $supplier)
    {
        return self::Success(new SupplierResource($supplier), __('Supplier retrieved successfully.'));
    }

    public function destroy(Supplier $supplier)
    {
        $this->supplierService->delete($supplier);
        return self::Success([], __('Supplier deleted successfully.'));
    }
}
