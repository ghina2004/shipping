<?php

namespace App\Services\Shipment;

use App\Models\Shipment;

class ShipmentService
{
    public function show($shipmentId)
    {
        return Shipment::query()->findOrFail($shipmentId);
    }

    public function update($request,$shipmentId)
    {
        $shipment = Shipment::query()->findOrFail($shipmentId);

        $shipment->update($request->all());

        $shipment->save();

        return $shipment;
    }



    public function updateShipmentStatus()
    {

    }


}
