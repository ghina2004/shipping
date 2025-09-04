<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contract\UploadBillOfLadingRequest;
use App\Http\Requests\Contract\UploadSignedServiceRequest;
use App\Http\Resources\ContractResource;
use App\Models\Shipment;
use App\Services\Contract\ContractService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ContractController extends Controller
{
    use ResponseTrait;

    public function __construct(protected ContractService $service) {}

    public function index(Shipment $shipment): JsonResponse
    {
        $contracts = $shipment->contracts()->latest()->get();
        return self::Success(ContractResource::collection($contracts), 'Contracts list.');
    }

    public function uploadBillOfLading(UploadBillOfLadingRequest $request, Shipment $shipment): JsonResponse
    {
        $user     = auth()->user();
        $contract = $this->service->employeeUploadBillOfLading(
            shipment:   $shipment,
            file:       $request->file('file'),
            title:      $request->string('title', 'Bill of Lading'),
            employeeId: $user->id
        );

        return self::Success(new ContractResource($contract), 'Bill of Lading uploaded.');
    }

    public function uploadSignedService(UploadSignedServiceRequest $request, Shipment $shipment): JsonResponse
    {
        $contract = $this->service->customerUploadSignedService($shipment, $request->file('file'));
        return self::Success(new ContractResource($contract), 'Signed service contract uploaded.');
    }
}
