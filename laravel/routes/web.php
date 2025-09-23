<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('user.dashboard');
    }
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// User Routes (sau khi đăng nhập)
Route::middleware('auth')->group(function () {
    // User Dashboard - trang chính cho user thường
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    
    // User profile routes
    Route::get('/profile', function () {
        return view('user.profile');
    })->name('user.profile');
    
    Route::get('/courses', function () {
        return view('user.courses');
    })->name('user.courses');
});

// Admin Routes - chỉ admin mới vào được
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Users Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    
    // Roles Management  
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
    
    // Permissions Management
    Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class);
    
    // Products Management (if needed)
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    
    // Categories Management (if needed)
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
});