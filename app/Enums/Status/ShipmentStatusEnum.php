<?php

namespace App\Enums\Status;

enum ShipmentStatusEnum: string
{
    case Pending   = 'pending';
    case Delivered = 'delivered';

    public function label(): string
    {
        return match ($this) {
            self::Pending   => __('enum.shipment_status.pending'),
            self::Delivered => __('enum.shipment_status.delivered'),
        };
    }
}
