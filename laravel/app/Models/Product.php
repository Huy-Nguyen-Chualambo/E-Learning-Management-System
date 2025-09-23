<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'status',
        'instructor_id', // Thêm trường này
        'duration_hours', // Thêm trường này
        'level', // Thêm trường này
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
    public function scopePublished($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
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