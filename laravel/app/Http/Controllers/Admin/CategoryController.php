<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
                            ->orderBy('sort_order')
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::where('parent_id', null)
                                  ->where('is_active', 1)
                                  ->orderBy('name')
                                  ->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'image' => $request->image,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'products']);
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::where('parent_id', null)
                                  ->where('id', '!=', $category->id)
                                  ->where('is_active', 1)
                                  ->orderBy('name')
                                  ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'image' => $request->image,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Kiểm tra xem category có products không
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                           ->with('error', 'Cannot delete category that has products assigned.');
        }

        // Kiểm tra xem có subcategories không
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')
                           ->with('error', 'Cannot delete category that has subcategories.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category deleted successfully.');
    }
}