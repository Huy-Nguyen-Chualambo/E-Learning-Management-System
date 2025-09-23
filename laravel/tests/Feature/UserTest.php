<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_roles()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'admin']);

        $user->roles()->attach($role);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('user'));
    }

    public function test_user_can_have_permissions_through_roles()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'admin']);
        $permission = Permission::factory()->create(['name' => 'create-users']);

        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->assertTrue($user->hasPermission('create-users'));
        $this->assertFalse($user->hasPermission('delete-users'));
    }

    public function test_super_admin_has_all_permissions()
    {
        $user = User::factory()->create();
        $superAdminRole = Role::factory()->create(['name' => 'super-admin']);
        $permission = Permission::factory()->create(['name' => 'create-users']);

        $superAdminRole->permissions()->attach($permission);
        $user->roles()->attach($superAdminRole);

        $this->assertTrue($user->hasRole('super-admin'));
        $this->assertTrue($user->hasPermission('create-users'));
    }
} 