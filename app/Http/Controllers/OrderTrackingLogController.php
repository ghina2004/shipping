<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderTrackingLogRequest;
use App\Http\Requests\UpdateOrderTrackingLogRequest;
use App\Http\Resources\OrderTrackingLogResource;
use App\Models\Order;
use App\Models\OrderTrackingLog;
use App\Traits\ResponseTrait;
use App\Services\OrderTrackingLogService;
use Illuminate\Http\Request;

class OrderTrackingLogController extends Controller
{
    use ResponseTrait;

    public function __construct(private OrderTrackingLogService $service)
    {
    }

    public function store(OrderTrackingLogRequest $request)
    {
        $route = $this->service->create($request->validated());
        return self::Success(new OrderTrackingLogResource($route), __('order route created successfully'));
    }

    public function show(OrderTrackingLog $orderTracking)
    {
        $route = $this->service->show($orderTracking);
        return self::Success(new OrderTrackingLogResource($route), __('success'));
    }


    public function update(UpdateOrderTrackingLogRequest $request, OrderTrackingLog $orderTracking)
    {
        $route = $this->service->update($orderTracking, $request->validated());
        return self::Success(new OrderTrackingLogResource($route), __('order route updated successfully'));
    }

    public function destroy(OrderTrackingLog $orderTracking)
    {
        $this->service->delete($orderTracking);
        return self::Success([], __('Deleted successfully.'));
    }

    public function addTrackingLog(OrderTrackingLogRequest $request,  Order $order)
    {
        $log = $this->service->addTrackingLogByOrderId($order, $request->validated());
        return self::Success(new OrderTrackingLogResource($log), __('Tracking log added successfully'));
    }
}
