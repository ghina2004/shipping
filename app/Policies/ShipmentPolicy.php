<?php

namespace App\Policies;

use App\Enums\Status\OrderStatus;
use App\Models\Shipment;
use App\Models\User;

class ShipmentPolicy
{
    public function update(User $user, Shipment $shipment): bool
    {
        if ($user->hasRole('employee')) {
            return true;
        }

        if ($user->hasRole('customer') && $shipment['is_confirm'] == 0) {
            return true;
        }

        if($user->hasRole('shipment_manager')){
            return true;
        }

        return false;


    }



}
