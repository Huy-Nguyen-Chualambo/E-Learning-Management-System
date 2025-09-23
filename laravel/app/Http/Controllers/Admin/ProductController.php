<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['instructor', 'categories'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $instructors = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'super-admin', 'manager']);
        })->get();
        
        $categories = Category::where('is_active', 1)->get();

        return view('admin.products.create', compact('instructors', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'level' => 'required|in:beginner,intermediate,advanced',
            'instructor_id' => 'required|exists:users,id',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'content' => $request->content,
            'price' => $request->price,
            'duration_hours' => $request->duration_hours,
            'level' => $request->level,
            'instructor_id' => $request->instructor_id,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'sort_order' => Product::max('sort_order') + 1,
        ]);

        // Attach categories
        if ($request->has('categories')) {
            $product->categories()->attach($request->categories);
        }

        return redirect()->route('admin.products.index')
                        ->with('success', 'Course created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['instructor', 'categories', 'users']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $instructors = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'super-admin', 'manager']);
        })->get();
        
        $categories = Category::where('is_active', 1)->get();
        $product->load('categories');

        return view('admin.products.edit', compact('product', 'instructors', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1',
            'level' => 'required|in:beginner,intermediate,advanced',
            'instructor_id' => 'required|exists:users,id',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'content' => $request->content,
            'price' => $request->price,
            'duration_hours' => $request->duration_hours,
            'level' => $request->level,
            'instructor_id' => $request->instructor_id,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
        ]);

        // Sync categories
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        } else {
            $product->categories()->detach();
        }

        return redirect()->route('admin.products.index')
                        ->with('success', 'Course updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->categories()->detach();
        $product->users()->detach();
        $product->delete();

        return redirect()->route('admin.products.index')
                        ->with('success', 'Course deleted successfully.');
    }
}