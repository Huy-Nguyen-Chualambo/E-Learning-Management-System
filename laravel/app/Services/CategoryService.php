<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Traits\HandleImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryService
{
    use HandleImage;

    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->all();
    }

    public function getCategoryById(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    public function getRootCategories()
    {
        return $this->categoryRepository->getRootCategories();
    }

    public function getChildCategories(int $parentId)
    {
        return $this->categoryRepository->getChildCategories($parentId);
    }

    public function getCategoryTree()
    {
        return $this->categoryRepository->getCategoryTree();
    }

    public function createCategory(array $data): Category
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        // Tạo slug từ name
        $data['slug'] = Str::slug($data['name']);

        // Xử lý ảnh nếu có
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image'], 'categories', 400, 300);
        }

        return $this->categoryRepository->create($data);
    }

    public function updateCategory(int $id, array $data): ?Category
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return null;
        }

        // Tạo slug từ name
        $data['slug'] = Str::slug($data['name']);

        // Xử lý ảnh nếu có
        if (isset($data['image'])) {
            $data['image'] = $this->updateImage($data['image'], $category->image, 'categories', 400, 300);
        }

        return $this->categoryRepository->update($id, $data);
    }

    public function deleteCategory(int $id): bool
    {
        $category = $this->categoryRepository->find($id);
        if ($category) {
            // Xóa ảnh nếu có
            if ($category->image) {
                $this->deleteImage($category->image, 'categories');
            }
            return $this->categoryRepository->delete($id);
        }
        return false;
    }

    public function searchCategories(string $keyword)
    {
        return $this->categoryRepository->searchCategories($keyword);
    }
} 