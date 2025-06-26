<?php

namespace App\Services\Shipment;

use App\Models\Shipment;
use App\Services\Order\OrderService;

class ShipmentService
{
    public function __construct(protected ShipmentUpdateStrategy $updateStrategy, protected OrderService $orderService) {}

    public function show($shipmentId)
    {
        return Shipment::query()->findOrFail($shipmentId);
    }

    public function update(array $data, $shipmentId): Shipment
    {
        return $this->updateStrategy->handle(auth()->user(), $data, $shipmentId);
    }


    public function delete(Shipment $shipment)
    {
        $shipment->delete();
    }

    public function confirm()
    {

    }

}
