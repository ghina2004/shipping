<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin' => ['add.employee'],
            'shipment manager' => [],
            'employee' => [],
            'customer' => ['create.shipment' , 'create.answer','show.shipment.full','update.shipment.full',
                'delete.shipment.full','show.confirmed.order' ,'show.unconfirmed.order','show.shipments.cart' ,'send.shipments.cart'],
            'accountant' => [],
        ];

        $allPermissions = collect($roles)->flatten()->unique()->filter();

        foreach ($allPermissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $roleModels = [];
        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->givePermissionTo($permissions);
            $roleModels[$roleName] = $role;
        }

        $users = [
            [
                'first_name' => 'admin',
                'second_name' => 'admin',
                'third_name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin123'),
                'email_verified_at' => now(),
                'is_verified' => 1,
                'role' => 'admin',
            ],
            [
                'first_name' => 'shipment',
                'second_name' => 'manager',
                'third_name' => 'user',
                'email' => 'shipment@gmail.com',
                'password' => bcrypt('shipment123'),
                'email_verified_at' => now(),
                'is_verified' => 1,
                'role' => 'shipment manager',
            ],
            [
                'first_name' => 'employee',
                'second_name' => 'staff',
                'third_name' => 'user',
                'email' => 'employee@gmail.com',
                'password' => bcrypt('employee123'),
                'email_verified_at' => now(),
                'is_verified' => 1,
                'role' => 'employee',
            ],
            [
                'first_name' => 'customer',
                'second_name' => 'client',
                'third_name' => 'user',
                'email' => 'customer@gmail.com',
                'password' => bcrypt('customer123'),
                'email_verified_at' => now(),
                'is_verified' => 1,
                'role' => 'customer',
            ],
            [
                'first_name' => 'customer1',
                'second_name' => 'client',
                'third_name' => 'user1',
                'email' => 'customer1@gmail.com',
                'password' => bcrypt('customer123'),
                'email_verified_at' => now(),
                'is_verified' => 1,
                'role' => 'customer',
            ],
            [
                'first_name' => 'customer2',
                'second_name' => 'client',
                'third_name' => 'user2',
                'email' => 'customer2@gmail.com',
                'password' => bcrypt('customer123'),
                'email_verified_at' => now(),
                'is_verified' => 0,
                'role' => 'customer',
            ],
            [
                'first_name' => 'customer3',
                'second_name' => 'client',
                'third_name' => 'user3',
                'email' => 'customer3@gmail.com',
                'password' => bcrypt('customer123'),
                'email_verified_at' => now(),
                'is_verified' => 0,
                'role' => 'customer',
            ],
            [
                'first_name' => 'accountant',
                'second_name' => 'finance',
                'third_name' => 'user',
                'email' => 'accountant@gmail.com',
                'password' => bcrypt('accountant123'),
                'email_verified_at' => now(),
                'is_verified' => 1,
                'role' => 'accountant',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::query()->create($userData);
            $user->assignRole($roleModels[$role]);
        }
    }
}
