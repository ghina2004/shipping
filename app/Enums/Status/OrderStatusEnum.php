<?php

namespace App\Enums\Status;

enum OrderStatusEnum: string
{
    case InProcess         = 'In process';
    case Preparing         = 'preparing';
    case WithEmployee      = 'with employee';
    case WithShipmentMgr   = 'with shipment_mgr';
    case WithAccountant    = 'with accountant';
    case Delivered         = 'delivered';


    public function label(): string
    {
        return match ($this) {
            self::Preparing       => __('enum.order_status.preparing'),
            self::InProcess       => __('enum.order_status.in_process'),
            self::WithEmployee    => __('enum.order_status.with_employee'),
            self::WithShipmentMgr => __('enum.order_status.with_shipment_mgr'),
            self::WithAccountant  => __('enum.order_status.with_accountant'),
            self::Delivered       => __('enum.order_status.delivered'),
        };
    }
}
