<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $permission
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = Auth::user();
        
        // Super admin có tất cả quyền
        if ($user && $user->hasRole('super-admin')) {
            return $next($request);
        }
        
        // Kiểm tra quyền cụ thể
        if ($user && $user->hasPermission($permission)) {
            return $next($request);
        }
        
        // Nếu là AJAX request, trả về JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to perform this action.'
            ], 403);
        }
        
        // Nếu không phải AJAX, redirect với thông báo
        return redirect()->back()->with('error', 'You do not have permission to perform this action.');
    }
}
