<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function searchUsers(string $keyword): \Illuminate\Database\Eloquent\Collection;
    public function getUsersByRole(string $roleName): \Illuminate\Database\Eloquent\Collection;
    public function assignRole(int $userId, int $roleId): bool;
    public function removeRole(int $userId, int $roleId): bool;
    public function hasRole(int $userId, string $roleName): bool;
    public function hasPermission(int $userId, string $permissionName): bool;
} 