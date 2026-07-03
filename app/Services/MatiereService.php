<?php

namespace App\Services;

use App\Repositories\Contracts\MatiereRepositoryInterface;
use App\DTOs\Matiere\CreateMatiereDto;
use App\DTOs\Matiere\UpdateMatiereDto;

class MatiereService
{
    public function __construct(
        private MatiereRepositoryInterface $repository
    ) {}

    public function filter(array $filters)
    {
        return $this->repository->filter($filters);
    }

    public function paginate(int $perPage = 15)
    {
        return $this->repository->paginate1($perPage);
    }

    public function find(int $id)
    {
        return $this->repository->findWithRelations($id);
    }
    public function findOrFail(int $id)
    {
        return $this->repository->findOrFail($id);
    }

    public function create(CreateMatiereDto $data)
    {
        return $this->repository->create($data->toArray());
    }

    public function update(int $id, UpdateMatiereDto $data)
    {
        return $this->repository->update($id, $data->toArray());
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}