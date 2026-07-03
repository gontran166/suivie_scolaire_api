<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\DTOs\User\CreateUserDto;
use App\DTOs\User\UpdateUserDto;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function filter(array $filters)
    {
        return $this->repository->filter($filters);
    }

    public function paginate(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->repository->findWithRelations($id);
    }
    public function findOrFail(int $id)
    {
        return $this->repository->findOrFail($id);
    }

    public function create(CreateUserDto $data)
    {
        return $this->repository->create($data->toArray());
    }

    public function update(int $id, UpdateUserDto $data)
    {
        return $this->repository->update($id, $data->toArray());
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}