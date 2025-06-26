<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shipment\UpdateShipmentRequest;
use App\Http\Resources\ShipmentResource;
use App\Models\Shipment;
use App\Services\Shipment\ShipmentService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ShipmentController extends Controller
{
    use ResponseTrait;
    public function __construct(private ShipmentService $shipmentService) {}

    public function show($shipmentId): JsonResponse
    {
        $shipment = $this->shipmentService->show($shipmentId);
        return self::Success([
            'shipment' => new ShipmentResource($shipment)
        ], __('shipment.found'));
    }

    public function update(UpdateShipmentRequest $request, $shipmentId): JsonResponse
    {
        $shipment = Shipment::query()->findOrFail($shipmentId);

        Gate::authorize('update', $shipment);

        $updated = $this->shipmentService->update($request->validated(), $shipmentId);

        return self::Success([
            'shipment' => new ShipmentResource($updated)
        ], __('shipment.updated'));
    }




}
