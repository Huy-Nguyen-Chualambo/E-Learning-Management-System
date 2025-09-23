<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $categories = $this->categoryService->getCategoryTree();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        }
        
        return view('admin.categories.index', compact('categories'));
    }

    public function show(int $id)
    {
        $category = $this->categoryService->getCategoryById($id);
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $category->load('parent', 'children')
        ]);
    }

    public function store(Request $request)
    {
        try {
            $category = $this->categoryService->createCategory($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $category
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
            $category = $this->categoryService->updateCategory($id, $request->all());
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $category
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
        $deleted = $this->categoryService->deleteCategory($id);
        
        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->get('keyword', '');
        $categories = $this->categoryService->searchCategories($keyword);
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function getRootCategories()
    {
        $categories = $this->categoryService->getRootCategories();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function getChildCategories(int $parentId)
    {
        $categories = $this->categoryService->getChildCategories($parentId);
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
} 