<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RegisterUserData extends Data
{
    public function __construct(
        public string $first_name,
        public string $second_name,
        public string $third_name,
        public string $email,
        public string $phone,
        public string $password
    ) {}
}
