<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('q');
        $categorySlug = $request->get('category');

        $query = Product::with(['categories', 'instructor'])
            ->active()
            ->search($keyword)
            ->orderBy('sort_order');

        if ($categorySlug) {
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->orderBy('sort_order')->get();

        return view('user.courses.index', compact('products', 'categories', 'keyword', 'categorySlug'));
    }

    public function category(string $slug, Request $request)
    {
        $request->merge(['category' => $slug]);
        return $this->index($request);
    }

    public function show(int $productId)
    {
        $product = Product::with(['categories', 'instructor'])->active()->findOrFail($productId);
        return view('user.courses.show', compact('product'));
    }
}


