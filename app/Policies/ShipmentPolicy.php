<?php

namespace App\Policies;

use App\Enums\Status\ShipmentStatus;
use App\Models\Shipment;
use App\Models\User;

class ShipmentPolicy
{
    public function update(User $user, Shipment $shipment): bool
    {
        if ($user->hasRole('employee')) {
            return true;
        }

        if ($user->hasRole('customer') && $shipment['status'] === ShipmentStatus::Pending || $shipment['status'] === ShipmentStatus::UnderReview) {
            return true;
        }

        if($user->hasRole('shipment_manager')){
            return true;
        }

        return false;


    }

}
