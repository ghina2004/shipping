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
        $order = Order::with(['trackingLogs', 'orderRoute'])
            ->findOrFail($orderId);


        $shipmentsData = $order->shipments->map(function ($shipment) use ($order) {
            if ($shipment->shipping_method->value === 'Land') {
                return [
                    'type' => 'Land',
                    'logs' => $order->trackingLogs,
                ];
            }

            return [
                'type' => $shipment->shipping_method->value,
                'routes' => $order->orderRoute,
            ];
        });

        return [
            'order_id' => $order->id,
            'shipments' => $shipmentsData,
        ];
    }

}
