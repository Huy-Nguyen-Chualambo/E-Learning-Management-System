<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->getAllUsers();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        }
        
        return view('admin.users.index', compact('users'));
    }

    public function show(int $id)
    {
        $user = $this->userService->getUserById($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $user->load('roles')
        ]);
    }

    public function store(Request $request)
    {
        try {
            $user = $this->userService->createUser($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $user = $this->userService->updateUser($id, $request->all());
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(int $id)
    {
        $deleted = $this->userService->deleteUser($id);
        
        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->get('keyword', '');
        $users = $this->userService->searchUsers($keyword);
        
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function assignRole(Request $request, int $userId)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);
        
        $assigned = $this->userService->assignRole($userId, $request->role_id);
        
        return response()->json([
            'success' => $assigned,
            'message' => $assigned ? 'Role assigned successfully' : 'Failed to assign role'
        ]);
    }

    public function removeRole(Request $request, int $userId)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);
        
        $removed = $this->userService->removeRole($userId, $request->role_id);
        
        return response()->json([
            'success' => $removed,
            'message' => $removed ? 'Role removed successfully' : 'Failed to remove role'
        ]);
    }
} 