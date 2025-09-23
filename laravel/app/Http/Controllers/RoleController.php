<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::with('permissions')->get();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $roles
            ]);
        }
        
        return view('admin.roles.index', compact('roles'));
    }

    public function show(int $id)
    {
        $role = Role::with('permissions')->find($id);
        
        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create($request->only(['name', 'display_name', 'description']));
        
        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data' => $role->load('permissions')
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::find($id);
        
        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        $role->update($request->only(['name', 'display_name', 'description']));
        
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'data' => $role->load('permissions')
        ]);
    }

    public function destroy(int $id)
    {
        $role = Role::find($id);
        
        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        // Không cho phép xóa super-admin role
        if ($role->name === 'super-admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete super-admin role'
            ], 422);
        }

        $role->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }
} 