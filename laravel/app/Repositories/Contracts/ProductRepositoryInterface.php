<?php

namespace App\Repositories\Contracts;

use App\Models\Product;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveProducts(): \Illuminate\Database\Eloquent\Collection;
    public function getFeaturedProducts(): \Illuminate\Database\Eloquent\Collection;
    public function searchProducts(string $keyword): \Illuminate\Database\Eloquent\Collection;
    public function getProductsByCategory(int $categoryId): \Illuminate\Database\Eloquent\Collection;
    public function getProductsByPriceRange(float $minPrice, float $maxPrice): \Illuminate\Database\Eloquent\Collection;
    public function assignCategory(int $productId, int $categoryId): bool;
    public function removeCategory(int $productId, int $categoryId): bool;
} 