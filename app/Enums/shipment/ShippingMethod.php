<?php

namespace App\Enums\shipment;

enum ShippingMethod : string

{
    case LAND = 'Land';
    case SEA = 'sea';
    case AIR = 'air';
}
