<?php

namespace App\Http\Controllers\Route;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderRouteService;
use App\Http\Requests\Rout\OrderRouteRequest;
use App\Http\Requests\Rout\UpdateOrderRouteStatusRequest;
use App\Http\Resources\OrderRouteResource;
use App\Http\Resources\OrderTrackingLogResource;
use App\Models\Order;
use App\Models\OrderRoute;
use App\Traits\ResponseTrait;

class OrderRouteController extends Controller
{
    use ResponseTrait;

    public function __construct(private OrderTrackingService $service) {}

    public function showByOrder(Order $order)
    {
        $data = $this->service->showByOrderId($order->id);

        if ($data['type'] === 'land') {

            return self::Success(new OrderTrackingLogResource($data['logs']), __('land tracking retrieved successfully'));
        }


        return self::Success(new OrderRouteResource($data['routes']), __('tracking retrieved successfully'));
    }

    public function store(OrderRouteRequest $request)
    {
        $route= $this->service->store($request->validated());
        return self::Success(new OrderRouteResource($route), 'Order route created successfully');
    }

    public function update(OrderRouteRequest $request, OrderRoute $orderRoute)
    {
        $route= $this->service->update($orderRoute ,$request->validated());
        return self::Success(new OrderRouteResource($route), 'Order updated created successfully');
    }

    public function destroy(OrderRoute $orderRoute)
    {
        $this->service->delete($orderRoute);
        return self::Success([], __('Deleted successfully.'));
    }
}
