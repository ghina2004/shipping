<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CartData extends Data
{
    public function __construct(
        public int $customer_id,
        public int $employee_id,
        public int $shipping_manager_id,
        public int $cart_number,
    ) {}
}
