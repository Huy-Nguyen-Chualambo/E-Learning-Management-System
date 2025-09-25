<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Load courses với pivot data
        $user->load(['courses' => function ($query) {
            $query->withPivot(['status', 'progress_percentage', 'enrolled_at', 'completed_at'])
                  ->with(['categories', 'instructor']);
        }]);

        // Lấy available courses
        $availableCourses = Product::active()
            ->whereNotIn('id', $user->courses->pluck('id'))
            ->with(['categories', 'instructor'])
            ->limit(6)
            ->get();

        // Statistics
        $stats = [
            'total_courses' => $user->courses->count(),
            'completed_courses' => $user->courses->where('pivot.status', 'completed')->count(),
            'in_progress_courses' => $user->courses->where('pivot.status', 'in_progress')->count(),
            'certificates' => $user->courses->where('pivot.status', 'completed')->count(),
        ];
        
        return view('user.dashboard', [
            'user' => $user,
            'roles' => $user->roles,
            'enrolledCourses' => $user->courses,
            'availableCourses' => $availableCourses,
            'stats' => $stats,
        ]);
    }
}