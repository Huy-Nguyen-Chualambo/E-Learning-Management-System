<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanDeleteUsers
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->hasPermission('delete-users')) {
            abort(403, 'You do not have permission to delete users.');
        }

        return $next($request);
    }
}