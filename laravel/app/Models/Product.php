<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'price',
        'image',
        // Status/flags and ordering
        'is_active',
        'is_featured',
        'sort_order',
        // Relations and course meta
        'instructor_id',
        'duration_hours',
        'duration',
        'level',
        'sale_price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relationships
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'product_user')
                    ->withPivot(['status', 'progress_percentage', 'enrolled_at', 'completed_at'])
                    ->withTimestamps();
    }

    public function enrolledUsers()
    {
        return $this->users()->wherePivot('status', '!=', 'dropped');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function scopeSearch($query, ?string $keyword)
    {
        if (!$keyword || trim($keyword) === '') {
            return $query;
        }
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    public function scopeByCategory($query, ?int $categoryId)
    {
        if (!$categoryId) { return $query; }
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    public function scopeByPriceRange($query, ?float $minPrice, ?float $maxPrice)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return $this->price == 0 ? 'Free' : '$' . number_format($this->price, 2);
    }

    public function getEnrollmentCountAttribute()
    {
        return $this->enrolledUsers()->count();
    }

    // Accessor for image full path
    public function getImagePathAttribute()
    {
        if ($this->image) {
            return asset('storage/products/' . $this->image);
        }
        return asset('images/default-product.png');
    }

    // Alias methods for better semantics
    public function getTitle()
    {
        return $this->name;
    }

    public function getCourseDescription()
    {
        return $this->description;
    }
}