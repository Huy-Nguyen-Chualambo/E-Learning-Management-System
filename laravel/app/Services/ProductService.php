<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Traits\HandleImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductService
{
    use HandleImage;

    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->productRepository->all();
    }

    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }

    public function getActiveProducts()
    {
        return $this->productRepository->getActiveProducts();
    }

    public function getFeaturedProducts()
    {
        return $this->productRepository->getFeaturedProducts();
    }

    public function createProduct(array $data): Product
    {
        $validator = Validator::make($data, [
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
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        // Tạo slug từ name
        $data['slug'] = Str::slug($data['name']);

        // Xử lý ảnh nếu có
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image'], 'products', 800, 600);
        }

        // Tách categories ra khỏi data chính
        $categories = $data['categories'] ?? [];
        unset($data['categories']);

        // Tạo product
        $product = $this->productRepository->create($data);

        // Gán categories
        if (!empty($categories)) {
            $product->categories()->attach($categories);
        }

        return $product;
    }

    public function updateProduct(int $id, array $data): ?Product
    {
        $validator = Validator::make($data, [
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
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        $product = $this->productRepository->find($id);
        if (!$product) {
            return null;
        }

        // Tạo slug từ name
        $data['slug'] = Str::slug($data['name']);

        // Xử lý ảnh nếu có
        if (isset($data['image'])) {
            $data['image'] = $this->updateImage($data['image'], $product->image, 'products', 800, 600);
        }

        // Tách categories ra khỏi data chính
        $categories = $data['categories'] ?? [];
        unset($data['categories']);

        // Cập nhật product
        $product = $this->productRepository->update($id, $data);

        // Cập nhật categories
        if ($product) {
            $product->categories()->sync($categories);
        }

        return $product;
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->productRepository->find($id);
        if ($product) {
            // Xóa ảnh nếu có
            if ($product->image) {
                $this->deleteImage($product->image, 'products');
            }
            return $this->productRepository->delete($id);
        }
        return false;
    }

    public function searchProducts(string $keyword)
    {
        return $this->productRepository->searchProducts($keyword);
    }

    public function getProductsByCategory(int $categoryId)
    {
        return $this->productRepository->getProductsByCategory($categoryId);
    }

    public function getProductsByPriceRange(float $minPrice, float $maxPrice)
    {
        return $this->productRepository->getProductsByPriceRange($minPrice, $maxPrice);
    }
} 