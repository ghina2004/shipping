<?php

namespace App\Enums\Status;

enum OrderPaymentStatusEnum: string
{
    case Unpaid = 'unpaid';
    case Partial = 'partial';
    case Paid   = 'paid';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid  => __('enum.order_payment_status.unpaid'),
            self::Partial => __('enum.order_payment_status.partial'),
            self::Paid    => __('enum.order_payment_status.paid'),
        };
    }
}
