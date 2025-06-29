<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ShipmentData extends Data
{
    public function __construct(
        public ?int $order_id,
        public int $category_id,
        public int $Supplier_id,
        public int $number,
        public string $shipping_date,
        public ?string $service_type,
        public string $origin_country,
        public string $destination_country,
        public string $shipping_method,
        public ?int $cargo_weight,
        public ?int $containers_size,
        public ?int $containers_numbers,
        public string $notes,
        public int $status,
    ) {}
}

