<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getActiveProducts(): Collection
    {
        return $this->model->active()->with('categories')->orderBy('sort_order')->get();
    }

    public function getFeaturedProducts(): Collection
    {
        return $this->model->active()->featured()->with('categories')->orderBy('sort_order')->get();
    }

    public function searchProducts(string $keyword): Collection
    {
        return $this->model->search($keyword)->active()->with('categories')->get();
    }

    public function getProductsByCategory(int $categoryId): Collection
    {
        return $this->model->byCategory($categoryId)->active()->with('categories')->get();
    }

    public function getProductsByPriceRange(float $minPrice, float $maxPrice): Collection
    {
        return $this->model->byPriceRange($minPrice, $maxPrice)->active()->with('categories')->get();
    }

    public function assignCategory(int $productId, int $categoryId): bool
    {
        $product = $this->find($productId);
        if ($product && !$product->categories()->where('category_id', $categoryId)->exists()) {
            $product->categories()->attach($categoryId);
            return true;
        }
        return false;
    }

    public function removeCategory(int $productId, int $categoryId): bool
    {
        $product = $this->find($productId);
        if ($product) {
            $product->categories()->detach($categoryId);
            return true;
        }
        return false;
    }
}