<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contract\UploadGenericContractRequest;
use App\Http\Resources\ContractResource;
use App\Models\Contract;
use App\Models\Shipment;
use App\Services\Contract\ContractService;
use App\Services\Contract\ContractDownloadService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ContractController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected ContractService $service,
        protected ContractDownloadService $downloadService
    ) {}

    /** قائمة عقود الشحنة */
    public function index(Shipment $shipment): JsonResponse
    {
        $contracts = $shipment->contracts()->latest()->get();
        return self::Success(ContractResource::collection($contracts), 'Contracts list.');
    }

    public function uploadGeneric(UploadGenericContractRequest $request, Shipment $shipment): JsonResponse
    {
        $user = auth()->user();

        $contract = $this->service->employeeUploadGenericContract(
            shipment         : $shipment,
            file             : $request->file('file'),
            title            : $request->string('title'),
            employeeId       : $user->id,
            visibleToCustomer: (bool) $request->input('visible_to_customer', true),
        );

        return self::Success(new ContractResource($contract), 'Contract uploaded.');
    }

    public function uploadSignedService(Shipment $shipment): JsonResponse
    {
        $contract = $this->service->customerUploadSignedService($shipment, request()->file('file'));
        return self::Success(new ContractResource($contract), 'Signed service contract uploaded.');
    }

    public function resetServiceSignature(Shipment $shipment): JsonResponse
    {
        $user = auth()->user();
        $contract = $this->service->resetServiceSignature($shipment, $user->id);
        return self::Success(new ContractResource($contract), 'Service contract reset to pending signature.');
    }

    public function destroy(Contract $contract): JsonResponse
    {
        $ok = $this->service->deleteUploadedContract($contract, auth()->id());
        return self::Success(['deleted' => (bool)$ok], 'Contract deleted.');
    }

    public function downloadGeneric(Contract $contract)
    {
        return $this->downloadService->downloadGeneric($contract);
    }
}
