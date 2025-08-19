<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShipmentResource;
use App\Models\Shipment;
use App\Services\Shipment\ShipmentStatusService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ShipmentStatusController extends Controller
{
    use ResponseTrait;
    public function __construct(private ShipmentStatusService $shipmentStatusService) {}


    public function changeToComplete(Shipment $shipment): JsonResponse
    {
        $shipment = $this->shipmentStatusService->changeStatusToCompletedInf($shipment);

        return self::success([
            'shipment'   => new ShipmentResource($shipment),
    ], 'Shipment status changed successfully.');
    }

    public function ChangeToConfirm(Shipment $shipment): JsonResponse
    {

        $shipment = $this->shipmentStatusService->changeStatusToConfirm($shipment);

        return self::success([
            'shipment'   => new ShipmentResource($shipment),
        ],'Shipment status changed successfully.');
    }
}
