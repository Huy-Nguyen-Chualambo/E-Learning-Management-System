<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    // Relationship với User (Many-to-Many)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    // Relationship với Permission (Many-to-Many)
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
}