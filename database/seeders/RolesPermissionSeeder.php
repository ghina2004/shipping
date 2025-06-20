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
            'customer' => [],
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
                'role' => 'admin',
            ],
            [
                'first_name' => 'shipment',
                'second_name' => 'manager',
                'third_name' => 'user',
                'email' => 'shipment@gmail.com',
                'password' => bcrypt('shipment123'),
                'role' => 'shipment manager',
            ],
            [
                'first_name' => 'employee',
                'second_name' => 'staff',
                'third_name' => 'user',
                'email' => 'employee@gmail.com',
                'password' => bcrypt('employee123'),
                'role' => 'employee',
            ],
            [
                'first_name' => 'customer',
                'second_name' => 'client',
                'third_name' => 'user',
                'email' => 'customer@gmail.com',
                'password' => bcrypt('customer123'),
                'role' => 'customer',
            ],
            [
                'first_name' => 'accountant',
                'second_name' => 'finance',
                'third_name' => 'user',
                'email' => 'accountant@gmail.com',
                'password' => bcrypt('accountant123'),
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
