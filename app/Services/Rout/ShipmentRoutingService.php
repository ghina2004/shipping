<?php

namespace App\Services\Rout;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShipmentRoute;
use App\Models\ShipmentTrackingLog;
use App\Services\Notification\NotificationService;
use App\Traits\ResponseTrait;
use Illuminate\Support\Str;

class ShipmentRoutingService
{
    use ResponseTrait;

    public function store(array $data)
    {
        $route = ShipmentRoute::create($data);

        $shipment = $route->shipmentTracking;

        $customer = $shipment?->shipmentCart?->customerCart;

        if ($customer && $customer->fcm_token) {
            app(NotificationService::class)->send(
                $customer,
                'تتبع جديد',
                'قام مدير الشحن بإضافة تحديث جديد لتتبع شحنتك رقم: ' . $shipment->number,
                'tracking'
            );
        }

        return $route;
    }

    public function show(ShipmentRoute $shipmentRoute): ShipmentRoute
    {
        return $shipmentRoute;
    }

    public function update(ShipmentRoute $shipmentRoute, array $data): ShipmentRoute
    {

        $shipmentRoute->update($data);
        return $shipmentRoute;
    }


    public function delete(ShipmentRoute $shipmentRoute): void
    {
        $shipmentRoute->delete();
    }

    public function showById(int $shipmentId)
    {
       $route= ShipmentRoute::query()->where('shipment_id', $shipmentId)->get();
        $log= ShipmentTrackingLog::query()->where('shipment_id', $shipmentId)->get();
        return
        [
            'ShipmentRoute'=>$route ,
            'ShipmentTracking'=>$log
        ];
    }

}
