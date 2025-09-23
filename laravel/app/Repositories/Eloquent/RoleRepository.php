<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function getRolesWithPermissions(): Collection
    {
        return $this->model->with('permissions')->get();
    }

    public function assignPermission(int $roleId, int $permissionId): bool
    {
        $role = $this->find($roleId);
        if ($role && !$role->permissions()->where('permission_id', $permissionId)->exists()) {
            $role->permissions()->attach($permissionId);
            return true;
        }
        return false;
    }

    public function removePermission(int $roleId, int $permissionId): bool
    {
        $role = $this->find($roleId);
        if ($role) {
            $role->permissions()->detach($permissionId);
            return true;
        }
        return false;
    }

    public function syncPermissions(int $roleId, array $permissionIds): bool
    {
        $role = $this->find($roleId);
        if ($role) {
            $role->permissions()->sync($permissionIds);
            return true;
        }
        return false;
    }
} 