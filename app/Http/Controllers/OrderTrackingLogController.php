<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipmentTrackingLogRequest;
use App\Http\Requests\UpdateShipmentTrackingLogRequest;
use App\Http\Resources\ShipmentTrackingLogResource;
use App\Models\Order;
use App\Models\ShipmentTrackingLog;
use App\Traits\ResponseTrait;
use App\Services\OrderTrackingLogService;
use Illuminate\Http\Request;

class OrderTrackingLogController extends Controller
{
    use ResponseTrait;

    public function __construct(private OrderTrackingLogService $service)
    {
    }

    public function store(ShipmentTrackingLogRequest $request)
    {
        $route = $this->service->create($request->validated());
        return self::Success(new ShipmentTrackingLogResource($route), __('shipment route created successfully'));
    }

    public function show(ShipmentTrackingLog $shipmentTracking)
    {
        $route = $this->service->show($shipmentTracking);
        return self::Success(new ShipmentTrackingLogResource($route), __('success'));
    }


    public function update(UpdateShipmentTrackingLogRequest $request, ShipmentTrackingLog $shipmentTracking)
    {
        $route = $this->service->update($shipmentTracking, $request->validated());
        return self::Success(new ShipmentTrackingLogResource($route), __('shipment route updated successfully'));
    }

    public function destroy(ShipmentTrackingLog $shipmentTracking)
    {
        $this->service->delete($shipmentTracking);
        return self::Success([], __('Deleted successfully.'));
    }

//    public function addTrackingLog(UpdateShipmentTrackingLogRequest $request, Order $order)
//    {
//        $log = $this->service->addTrackingLogByOrderId($order, $request->validated());
//        return self::Success(new ShipmentTrackingLogResource($log), __('Tracking log added successfully'));
//    }
}
