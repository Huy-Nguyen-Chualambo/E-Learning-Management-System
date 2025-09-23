<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User Management
            ['name' => 'view-users', 'display_name' => 'View Users', 'description' => 'Can view users list'],
            ['name' => 'create-users', 'display_name' => 'Create Users', 'description' => 'Can create new users'],
            ['name' => 'edit-users', 'display_name' => 'Edit Users', 'description' => 'Can edit existing users'],
            ['name' => 'delete-users', 'display_name' => 'Delete Users', 'description' => 'Can delete users'],
            
            // Role Management
            ['name' => 'view-roles', 'display_name' => 'View Roles', 'description' => 'Can view roles list'],
            ['name' => 'create-roles', 'display_name' => 'Create Roles', 'description' => 'Can create new roles'],
            ['name' => 'edit-roles', 'display_name' => 'Edit Roles', 'description' => 'Can edit existing roles'],
            ['name' => 'delete-roles', 'display_name' => 'Delete Roles', 'description' => 'Can delete roles'],
            
            // Category Management
            ['name' => 'view-categories', 'display_name' => 'View Categories', 'description' => 'Can view categories list'],
            ['name' => 'create-categories', 'display_name' => 'Create Categories', 'description' => 'Can create new categories'],
            ['name' => 'edit-categories', 'display_name' => 'Edit Categories', 'description' => 'Can edit existing categories'],
            ['name' => 'delete-categories', 'display_name' => 'Delete Categories', 'description' => 'Can delete categories'],
            
            // Product Management
            ['name' => 'view-products', 'display_name' => 'View Products', 'description' => 'Can view products list'],
            ['name' => 'create-products', 'display_name' => 'Create Products', 'description' => 'Can create new products'],
            ['name' => 'edit-products', 'display_name' => 'Edit Products', 'description' => 'Can edit existing products'],
            ['name' => 'delete-products', 'display_name' => 'Delete Products', 'description' => 'Can delete products'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']], // Find by name
                $permission // Create with these attributes if not found
            );
        }
    }
}