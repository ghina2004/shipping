<?php

namespace App\Services\Rout;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShipmentRoute;
use App\Models\ShipmentTrackingLog;
use App\Traits\ResponseTrait;
use Illuminate\Support\Str;

class ShipmentRoutingService
{
    use ResponseTrait;

    public function store(array $data)
    {
        return ShipmentRoute::create($data);

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
