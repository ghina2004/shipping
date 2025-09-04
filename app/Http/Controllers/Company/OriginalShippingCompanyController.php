<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\OriginalShippingCompanyRequest;
use App\Http\Requests\Company\UpdateOriginalShippingCompanyRequest;

use App\Http\Resources\OrderResource;
use App\Http\Resources\OriginalShippingCompanyResource;
use App\Http\Resources\ShipmentResource;
use App\Models\Order;
use App\Models\OriginalShippingCompany;
use App\Models\Shipment;
use App\Services\Company\OriginalShippingCompanyService;
use App\Traits\ResponseTrait;

class OriginalShippingCompanyController extends Controller
{
    use ResponseTrait;

    public function __construct(private OriginalShippingCompanyService $service)
    {
    }

    public function index()
    {
        $companies = $this->service->index();
        return self::Success(OriginalShippingCompanyResource::collection($companies), __('companies showed successfully'));
    }

    public function store(OriginalShippingCompanyRequest $request)
    {
        $company = $this->service->create($request->validated());
        return self::Success(new OriginalShippingCompanyResource($company), __('companies created successfully'));
    }

    public function show(OriginalShippingCompany $originalShippingCompany)
    {
        return self::Success(new OriginalShippingCompanyResource($originalShippingCompany), __('company showed success'));
    }

    public function update(UpdateOriginalShippingCompanyRequest $request, OriginalShippingCompany $originalShippingCompany)
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
        shipment $shipment)
    {
        $company = $this->service->create($request->validated());

        $shipment->update([
            'original_company_id' => $company->id,
        ]);

        return self::Success(new OriginalShippingCompanyResource($company), __('company created and order updated successfully'));

    }
    public function selectCompany( shipment $shipment, OriginalShippingCompany $originalShippingCompany){
        if ($shipment->original_company_id) {
            return self::Error([],__('A company is already assigned to this shipment.'));
        }

        $shipment->update([
            'original_company_id' => $originalShippingCompany->id,
        ]);

        return self::Success(new ShipmentResource($shipment), __('company selected  successfully'));
    }

    public function showShipmentWithCompany(Shipment $shipment)
    {
        $shipment->load('originalCompany');

        return self::Success(
            [
                'shipment' => new ShipmentResource($shipment),
                'company' => $shipment->originalCompany
                    ? new OriginalShippingCompanyResource($shipment->originalCompany)
                    : null,
            ],
            __('shipment with company showed successfully')
        );
    }



}
