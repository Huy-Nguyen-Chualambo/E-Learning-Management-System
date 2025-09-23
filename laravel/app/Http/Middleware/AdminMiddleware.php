<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Kiểm tra user có role admin, manager, hoặc super-admin
        if ($user->hasRole('super-admin') || $user->hasRole('admin') || $user->hasRole('manager')) {
            return $next($request);
        }

        // Nếu không có quyền admin, redirect về user dashboard
        return redirect()->route('user.dashboard')->with('error', 'You do not have permission to access admin panel.');
    }
}