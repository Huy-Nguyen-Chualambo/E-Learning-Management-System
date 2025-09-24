<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasPermission($permissionName)
    {
        // More efficient query for SQLite
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionName) {
                $query->where('permissions.name', $permissionName);
            })
            ->exists();
    }

    public function getAllPermissions()
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id');
    }

    // Local scopes for searching/filtering
    public function scopeSearch($query, string $keyword)
    {
        if (trim($keyword) === '') {
            return $query;
        }

        return $query->where(function ($subQuery) use ($keyword) {
            $subQuery->where('name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%");
        });
    }

    public function scopeByRole($query, ?string $roleName)
    {
        if (!$roleName) {
            return $query;
        }

        return $query->whereHas('roles', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    // Thêm vào User model
public function courses()
{
    return $this->belongsToMany(Product::class, 'product_user')
                ->withPivot(['status', 'progress_percentage', 'enrolled_at', 'completed_at'])
                ->withTimestamps();
}

public function instructedCourses()
{
    return $this->hasMany(Product::class, 'instructor_id');
}

public function enrolledCourses()
{
    return $this->courses()->wherePivot('status', '!=', 'dropped');
}

public function completedCourses()
{
    return $this->courses()->wherePivot('status', 'completed');
}

public function inProgressCourses()
{
    return $this->courses()->wherePivot('status', 'in_progress');
}
    
}