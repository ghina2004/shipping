<?php

namespace App\Http\Controllers\Route;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rout\ShipmentRouteRequest;
use App\Http\Requests\Rout\UpdateShipmentRouteRequest;
use App\Http\Resources\ShipmentRouteResource;
use App\Http\Resources\ShipmentTrackingLogResource;
use App\Models\Shipment;
use App\Models\ShipmentRoute;
use App\Services\Rout\ShipmentRoutingService;
use App\Traits\ResponseTrait;

class ShipmentRouteController extends Controller
{
    use ResponseTrait;

    public function __construct(private ShipmentRoutingService $service) {}
    public function showByShipment(Shipment $shipment)
    {
        $data = $this->service->showById($shipment->id);

        return self::Success([
            'routes' => ShipmentRouteResource::collection($data['ShipmentRoute']),
            'logs'   => ShipmentTrackingLogResource::collection($data['ShipmentTracking']),
        ], __('tracking retrieved successfully'));
    }

    public function store(ShipmentRouteRequest $request)
    {
        $route= $this->service->store($request->validated());
        return self::Success(new ShipmentRouteResource($route), 'Shipment route created successfully');
    }

    public function update(UpdateShipmentRouteRequest $request, ShipmentRoute $ShipmentRoute)
    {
        $route= $this->service->update($ShipmentRoute ,$request->validated());
        return self::Success(new ShipmentRouteResource($route), 'Shipment route updated successfully');
    }

    public function show(ShipmentRoute $ShipmentRoute)
    {
        $route = $this->service->show($ShipmentRoute);
        return self::Success(new ShipmentRouteResource($route), __('Shipment route showed successfully'));
    }

    public function destroy(ShipmentRoute $ShipmentRoute)
    {
        $this->service->delete($ShipmentRoute);
        return self::Success([], __('Deleted successfully.'));
    }
}
