<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['name' => 'create-users', 'display_name' => 'Create Users'],
            ['name' => 'edit-users', 'display_name' => 'Edit Users'],
            ['name' => 'delete-users', 'display_name' => 'Delete Users'],
            ['name' => 'view-users', 'display_name' => 'View Users'],
            ['name' => 'manage-roles', 'display_name' => 'Manage Roles'],
            ['name' => 'manage-products', 'display_name' => 'Manage Products'],
            ['name' => 'manage-categories', 'display_name' => 'Manage Categories'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}