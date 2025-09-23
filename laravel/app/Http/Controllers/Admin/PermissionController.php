<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->paginate(15);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.permissions.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'roles' => 'array'
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        if ($request->roles) {
            $permission->roles()->attach($request->roles);
        }

        return redirect()->route('admin.permissions.index')
                        ->with('success', 'Permission created successfully.');
    }

    public function show(Permission $permission)
    {
        $permission->load('roles');
        return view('admin.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        $roles = Role::all();
        $permission->load('roles');
        return view('admin.permissions.edit', compact('permission', 'roles'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'roles' => 'array'
        ]);

        $permission->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        $permission->roles()->sync($request->roles ?? []);

        return redirect()->route('admin.permissions.index')
                        ->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->roles()->detach();
        $permission->delete();

        return redirect()->route('admin.permissions.index')
                        ->with('success', 'Permission deleted successfully.');
    }
}