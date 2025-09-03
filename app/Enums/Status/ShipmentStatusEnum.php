<?php

namespace App\Enums\Status;

enum ShipmentStatusEnum: string
{
    case Pending   = 'pending';
    case InProcess = 'in_process';
    case Delivered = 'delivered';

    public function label(): string
    {
        return match ($this) {
            self::Pending   => __('enum.shipment_status.pending'),
            self::InProcess => __('enum.order_status.in_process'),
            self::Delivered => __('enum.shipment_status.delivered'),
        };
    }
}
