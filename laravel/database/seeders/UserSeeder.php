<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Tạo Super Admin User
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@deha-soft.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Gán role super-admin
        $superAdminRole = Role::where('name', 'super-admin')->first();
        $superAdmin->roles()->attach($superAdminRole);

        // Tạo Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@deha-soft.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole);

        // Tạo Manager User
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@deha-soft.com', 
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $managerRole = Role::where('name', 'manager')->first();
        $manager->roles()->attach($managerRole);
    }
}