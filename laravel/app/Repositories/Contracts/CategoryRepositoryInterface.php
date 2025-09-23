<?php

namespace App\Repositories\Contracts;

use App\Models\Category;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function getRootCategories(): \Illuminate\Database\Eloquent\Collection;
    public function getChildCategories(int $parentId): \Illuminate\Database\Eloquent\Collection;
    public function getActiveCategories(): \Illuminate\Database\Eloquent\Collection;
    public function searchCategories(string $keyword): \Illuminate\Database\Eloquent\Collection;
    public function getCategoryTree(): \Illuminate\Database\Eloquent\Collection;
} 