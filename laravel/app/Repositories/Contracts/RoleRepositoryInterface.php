<?php

namespace App\Repositories\Contracts;

use App\Models\Role;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function getRolesWithPermissions(): \Illuminate\Database\Eloquent\Collection;
    public function assignPermission(int $roleId, int $permissionId): bool;
    public function removePermission(int $roleId, int $permissionId): bool;
    public function syncPermissions(int $roleId, array $permissionIds): bool;
} 