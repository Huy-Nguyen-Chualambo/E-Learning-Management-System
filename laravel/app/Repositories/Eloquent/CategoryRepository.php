<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getRootCategories(): Collection
    {
        return $this->model->rootCategories()->active()->orderBy('sort_order')->get();
    }

    public function getChildCategories(int $parentId): Collection
    {
        return $this->model->where('parent_id', $parentId)
                          ->active()
                          ->orderBy('sort_order')
                          ->get();
    }

    public function getActiveCategories(): Collection
    {
        return $this->model->active()->orderBy('sort_order')->get();
    }

    public function searchCategories(string $keyword): Collection
    {
        return $this->model->search($keyword)->active()->get();
    }

    public function getCategoryTree(): Collection
    {
        return $this->model->with(['children' => function($query) {
            $query->active()->orderBy('sort_order');
        }])->rootCategories()->active()->orderBy('sort_order')->get();
    }
} 