<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $productsCount = Product::count();
        $categoriesCount = Category::count();
        $rolesCount = Role::count();

        $recentProducts = Product::orderBy('created_at', 'desc')->limit(5)->get();
        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact(
            'usersCount', 'productsCount', 'categoriesCount', 'rolesCount', 'recentProducts', 'recentUsers'
        ));
    }
}


