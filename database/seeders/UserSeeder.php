<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\QuestionOptions;
use App\Models\ShipmentQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


class UserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            User::query()->create([
                'first_name' => 'customer',
                'second_name' => 'client',
                'third_name' => 'user',
                'email' => 'customer@gmail.com',
                'password' => bcrypt('customer123'),
                'email_verified_at' => now(),
                'is_verified' => 1,
                'role' => 'customer',

            ]);
        }

    }
}
