<?php

namespace App\Services\Shipment;

use App\Exceptions\Types\CustomException;
use App\Models\Shipment;

class ShipmentStatusService
{

    public function changeStatusToCompletedInf(Shipment $shipment): Shipment
    {
        $shipment->update(['is_information_complete'=>1]);
        return $shipment;
    }

    public function changeStatusToConfirm(Shipment $shipment): Shipment
    {
        if (!$shipment->is_information_complete) {
            throw new CustomException(__('shipment.cannot_confirm_shipment'));
        }

        $shipment->update(['is_confirm' => 1]);
        return $shipment;
    }


}
