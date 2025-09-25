<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): JsonResponse
    {
        $products = Product::with('categories')
            ->active()
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products->map(function ($p) {
                $p->append('image_path');
                return $p;
            })
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $product = Product::with('categories')->find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        $product->append('image_path');
        return response()->json(['success' => true, 'data' => $product]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $product = $this->productService->createProduct($validated);
        $product->load('categories')->append('image_path');

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $product = $this->productService->updateProduct($id, $validated);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $product->load('categories')->append('image_path');
        return response()->json(['success' => true, 'message' => 'Product updated successfully', 'data' => $product]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->productService->deleteProduct($id);
        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
    }

    public function search(Request $request): JsonResponse
    {
        $keyword = (string) $request->get('keyword', '');
        $categoryName = (string) $request->get('category_name', '');
        $minPrice = $request->has('min_price') ? (float) $request->get('min_price') : null;
        $maxPrice = $request->has('max_price') ? (float) $request->get('max_price') : null;

        $query = Product::with('categories')->active();

        if (trim($keyword) !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if (trim($categoryName) !== '') {
            $query->whereHas('categories', function ($q) use ($categoryName) {
                $q->where('name', 'like', "%{$categoryName}%");
            });
        }

        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        $products = $query->orderBy('sort_order')->get();

        return response()->json([
            'success' => true,
            'data' => $products->map(function ($p) {
                $p->append('image_path');
                return $p;
            })
        ]);
    }

    public function getFeaturedProducts(): JsonResponse
    {
        $products = Product::with('categories')->active()->featured()->orderBy('sort_order')->get();
        return response()->json(['success' => true, 'data' => $products]);
    }

    public function getProductsByCategory(Category $category): JsonResponse
    {
        $products = Product::with('categories')
            ->active()
            ->whereHas('categories', fn($q) => $q->where('categories.id', $category->id))
            ->orderBy('sort_order')
            ->get();

        return response()->json(['success' => true, 'data' => $products]);
    }
}