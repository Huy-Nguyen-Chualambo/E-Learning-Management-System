<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function searchUsers(string $keyword): Collection
    {
        return $this->model->where(function($query) use ($keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhereHas('roles', function($q) use ($keyword) {
                      $q->where('name', 'like', "%{$keyword}%");
                  });
        })->with('roles')->get();
    }

    public function getUsersByRole(string $roleName): Collection
    {
        return $this->model->whereHas('roles', function($query) use ($roleName) {
            $query->where('name', $roleName);
        })->with('roles')->get();
    }

    public function assignRole(int $userId, int $roleId): bool
    {
        $user = $this->find($userId);
        if ($user && !$user->roles()->where('role_id', $roleId)->exists()) {
            $user->roles()->attach($roleId);
            return true;
        }
        return false;
    }

    public function removeRole(int $userId, int $roleId): bool
    {
        $user = $this->find($userId);
        if ($user) {
            $user->roles()->detach($roleId);
            return true;
        }
        return false;
    }

    public function hasRole(int $userId, string $roleName): bool
    {
        $user = $this->find($userId);
        return $user ? $user->hasRole($roleName) : false;
    }

    public function hasPermission(int $userId, string $permissionName): bool
    {
        $user = $this->find($userId);
        return $user ? $user->hasPermission($permissionName) : false;
    }
} 