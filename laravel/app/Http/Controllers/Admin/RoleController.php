<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[1] ?? 'general';
        });
        
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        if ($request->permissions) {
            $role->permissions()->attach($request->permissions);
        }

        return redirect()->route('admin.roles.index')
                        ->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[1] ?? 'general';
        });
        
        $role->load('permissions');
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array'
        ]);

        $role->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
                        ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        // Prevent deletion of system roles
        if (in_array($role->name, ['super-admin', 'admin'])) {
            return redirect()->route('admin.roles.index')
                            ->with('error', 'System roles cannot be deleted.');
        }

        $role->permissions()->detach();
        $role->users()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')
                        ->with('success', 'Role deleted successfully.');
    }
}