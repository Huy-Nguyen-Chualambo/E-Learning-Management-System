<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::all();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $permissions
            ]);
        }
        
        return view('admin.permissions.index', compact('permissions'));
    }
} 