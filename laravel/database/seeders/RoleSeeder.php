<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Tạo Super Admin Role
        $superAdmin = Role::create([
            'name' => 'super-admin',
            'display_name' => 'Super Administrator',
            'description' => 'Full access to all system features'
        ]);

        // Tạo Admin Role
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator', 
            'description' => 'Admin access with some restrictions'
        ]);

        // Tạo Manager Role
        $manager = Role::create([
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => 'Can manage products and categories'
        ]);

        // Tạo User Role
        $user = Role::create([
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Basic user access'
        ]);

        // Gán tất cả permissions cho Super Admin
        $allPermissions = Permission::all();
        $superAdmin->permissions()->attach($allPermissions);

        // Gán một số permissions cho Admin
        $adminPermissions = Permission::whereNotIn('name', ['delete-users', 'delete-roles'])->get();
        $admin->permissions()->attach($adminPermissions);

        // Gán permissions cho Manager
        $managerPermissions = Permission::whereIn('name', [
            'view-products', 'create-products', 'edit-products',
            'view-categories', 'create-categories', 'edit-categories'
        ])->get();
        $manager->permissions()->attach($managerPermissions);
    }
}