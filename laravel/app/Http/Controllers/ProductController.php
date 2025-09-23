<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->getActiveProducts();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        }
        
        return view('admin.products.index', compact('products'));
    }

    public function show(int $id)
    {
        $product = $this->productService->getProductById($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $product->load('categories')
        ]);
    }

    public function store(Request $request)
    {
        try {
            $product = $this->productService->createProduct($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->load('categories')
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
            $product = $this->productService->updateProduct($id, $request->all());
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->load('categories')
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
        $deleted = $this->productService->deleteProduct($id);
        
        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->get('keyword', '');
        $categoryId = $request->get('category_id');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        
        $products = $this->productService->searchProducts($keyword);
        
        if ($categoryId) {
            $products = $products->filter(function($product) use ($categoryId) {
                return $product->categories->contains('id', $categoryId);
            });
        }
        
        if ($minPrice || $maxPrice) {
            $products = $products->filter(function($product) use ($minPrice, $maxPrice) {
                $price = $product->final_price;
                if ($minPrice && $price < $minPrice) return false;
                if ($maxPrice && $price > $maxPrice) return false;
                return true;
            });
        }
        
        return response()->json([
            'success' => true,
            'data' => $products->values()
        ]);
    }

    public function getFeaturedProducts()
    {
        $products = $this->productService->getFeaturedProducts();
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function getProductsByCategory(int $categoryId)
    {
        $products = $this->productService->getProductsByCategory($categoryId);
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
} 