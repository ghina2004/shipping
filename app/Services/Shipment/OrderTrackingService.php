<?php

namespace App\Services\Shipment;

use App\Enums\Status\OrderTrackingStatus;
use App\Http\Requests\Rout\OrderRouteRequest;
use App\Models\Order;
use App\Models\OrderRoute;
use App\Traits\ResponseTrait;
use Illuminate\Support\Str;

class OrderTrackingService
{
    use ResponseTrait;

    public function store(array $data)
    {

        $data['tracking_number'] = Str::upper(Str::random(5));

        return OrderRoute::create($data);

    }

    public function show(OrderRoute $orderRoute): OrderRoute
    {
        return $orderRoute;
    }

    public function update(OrderRoute $orderRoute, array $data): OrderRoute
    {

        $orderRoute->update($data);
        return $orderRoute;
    }

    public function delete(OrderRoute $orderRoute): void
    {
        $orderRoute->delete();
    }

    public function showByOrderId(int $orderId): array
    {

        $order = Order::with(['shipments', 'trackingLogs', 'orderRoutes'])->findOrFail($orderId);

        $shippingMethod = $order->shipments->first()->shipping_method ?? null;

        if ($shippingMethod === 'land') {
            return [
                'type' => 'land',
                'logs' => $order->trackingLogs,
            ];
        }

        return [
            'type' => $shippingMethod,
            'routes' => $order->orderRoutes,
        ];
    }

}
