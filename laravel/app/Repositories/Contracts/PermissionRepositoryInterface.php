<?php

namespace App\Repositories\Contracts;

interface PermissionRepositoryInterface extends BaseRepositoryInterface
{
    public function getPermissionsByGroup(): \Illuminate\Database\Eloquent\Collection;
} 