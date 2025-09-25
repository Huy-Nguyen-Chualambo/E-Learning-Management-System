<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function enroll(Product $product)
    {
        $user = Auth::user();
        
        // Check if user is already enrolled
        if ($user->courses()->where('product_id', $product->id)->exists()) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }
        
        // Enroll user in the course
        $user->courses()->attach($product->id, [
            'status' => 'in_progress',
            'progress_percentage' => 0,
            'enrolled_at' => now(),
        ]);
        
        return redirect()->route('user.dashboard')->with('success', 'Successfully enrolled in ' . $product->name . '!');
    }
}


