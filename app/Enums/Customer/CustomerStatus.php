<?php

namespace App\Enums\Customer;

enum CustomerStatus: int
{
    case NEW = 1;
    case OLD = 0;

    public function label(): string
    {
        return match ($this) {
            self::NEW  => __('enum.status.new'),
            self::OLD  => __('enum.status.old'),
        };
    }

}
