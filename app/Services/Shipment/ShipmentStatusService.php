<?php

namespace App\Services\Shipment;

use App\Enums\Status\ShipmentStatusEnum;
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
        if (!$shipment->is_information_complete || $shipment->is_confirm) {
            throw new CustomException(__('shipment.cannot_confirm_shipment'));
        }

        if ($shipment->is_confirm) {
            throw new CustomException(__('shipment.cannot_confirm'));
        }

        $shipment->update(['is_confirm' => 1]);
        return $shipment;
    }

    public function changeStatus(Shipment $shipment, ShipmentStatusEnum $status): Shipment
    {
        $shipment->update(['status' => 'delivered']);
        return $shipment;
    }

}
