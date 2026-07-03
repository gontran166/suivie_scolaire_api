<?php

namespace App\Services;

use App\Repositories\Contracts\ClasseRepositoryInterface;
use App\Repositories\ClasseRepository;
use App\DTOs\Classe\CreateClasseDto;
use App\DTOs\Classe\UpdateClasseDto;

class ClasseService
{
    public function __construct(
        private ClasseRepositoryInterface $repository,
        private ClasseRepository $classeRepository
    ) {}

    public function paginate(int $perPage = 15)
    {
        return $this->classeRepository->paginate1($perPage);
    }

    public function find(int $id)
    {
        return $this->repository->findWithRelations($id);
    }
    public function findOrFail(int $id)
    {
        return $this->repository->findOrFail($id);
    }

    public function create(CreateClasseDto $data)
    {
        return $this->repository->create($data->toArray());
    }

    public function update(int $id, UpdateClasseDto $data)
    {
        return $this->repository->update($id, $data->toArray());
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}