<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Rules\DehaSoftEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->all();
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function createUser(array $data): User
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users', new DehaSoftEmail],
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        $data['password'] = Hash::make($data['password']);
        
        return $this->userRepository->create($data);
    }

    public function updateUser(int $id, array $data): ?User
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($id), new DehaSoftEmail],
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function searchUsers(string $keyword)
    {
        return $this->userRepository->searchUsers($keyword);
    }

    public function assignRole(int $userId, int $roleId): bool
    {
        return $this->userRepository->assignRole($userId, $roleId);
    }

    public function removeRole(int $userId, int $roleId): bool
    {
        return $this->userRepository->removeRole($userId, $roleId);
    }

    public function hasRole(int $userId, string $roleName): bool
    {
        return $this->userRepository->hasRole($userId, $roleName);
    }

    public function hasPermission(int $userId, string $permissionName): bool
    {
        return $this->userRepository->hasPermission($userId, $permissionName);
    }
} 