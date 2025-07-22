<?php

namespace App\Http\Controllers;

use App\Http\Requests\OriginalShippingCompanyRequest;
use App\Http\Resources\OriginalShippingCompanyResource;
use App\Models\Order;
use App\Models\OriginalShippingCompany;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class OriginalShippingCompanyController extends Controller
{
    use ResponseTrait;

    public function __construct(private OriginalShippingCompanyService $service)
    {
    }

    public function index()
    {
        $companies = $this->service->all();
        return self::Success(OriginalShippingCompanyResource::collection($companies), __('companies showed successfully'));
    }

    public function store(OriginalShippingCompanyRequest $request)
    {
        $company = $this->service->create($request->validated());
        return self::Success(new OriginalShippingCompanyResource($company), __('companies created successfully'));
    }

    public function show(OriginalShippingCompany $originalShippingCompany)
    {
        return self::Success(new OriginalShippingCompanyResource($originalShippingCompany), __('success'));
    }

    public function update(OriginalShippingCompanyRequest $request, OriginalShippingCompany $originalShippingCompany)
    {
        $company = $this->service->update($originalShippingCompany, $request->validated());
        return self::Success(new OriginalShippingCompanyResource($company), __('company updated successfully'));
    }

    public function destroy(OriginalShippingCompany $originalShippingCompany)
    {
        $this->service->delete($originalShippingCompany);
        return self::Success([], __('Deleted successfully.'));
    }

    public function addAndAssignCompany(
        OriginalShippingCompanyRequest $request,
        Order $order)
    {
        $company = $this->service->create($request->validated());

        $order->update([
            'original_company_id' => $company->id,
        ]);

        return self::Success(new OriginalShippingCompanyResource($company), __('company created and order updated successfully'));

    }
}
