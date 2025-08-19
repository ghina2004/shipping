<?php

namespace App\Http\Controllers\Route;

use App\Http\Controllers\Controller;
use App\Services\Shipment\OrderTrackingService;
use App\Http\Requests\Rout\OrderRouteRequest;
use App\Http\Requests\Rout\UpdateOrderRouteRequest;
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

        $shipments = $data['shipments']->map(function ($shipment) {
            if ($shipment['type'] === 'Land') {
                return [
                    'type' => 'Land',
                    'tracking' => OrderTrackingLogResource::collection($shipment['logs']),
                ];
            }

            return [
                'type' => $shipment['type'],
                'tracking' => new OrderRouteResource($shipment['routes']),
            ];
        });

        return self::Success($shipments, __('tracking retrieved successfully'));
    }

    public function store(OrderRouteRequest $request)
    {
        $route= $this->service->store($request->validated());
        return self::Success(new OrderRouteResource($route), 'Order route created successfully');
    }

    public function update(UpdateOrderRouteRequest $request, OrderRoute $orderRoute)
    {
        $route= $this->service->update($orderRoute ,$request->validated());
        return self::Success(new OrderRouteResource($route), 'Order route updated successfully');
    }

    public function destroy(OrderRoute $orderRoute)
    {
        $this->service->delete($orderRoute);
        return self::Success([], __('Deleted successfully.'));
    }
}
