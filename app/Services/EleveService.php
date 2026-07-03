<?php

namespace App\Services;

use App\DTOs\Eleve\CreateEleveDto;
use App\DTOs\Eleve\UpdateEleveDto;
use App\Repositories\Contracts\EleveRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class EleveService
{
    public function __construct(
        private EleveRepositoryInterface $repository
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

    public function create(CreateEleveDto $data)
    {
        $payload = $data->toArray();

        if ($data->photo) {
            $payload['photo'] = $data->photo->store(
                'eleves',
                'public'
            );
        }

        return $this->repository->create($payload);
    }

    public function update(int $id, UpdateEleveDto $data)
    {
        $eleve = $this->repository->findOrFail($id);

        $payload = $data->toArray();

        if ($data->photo) {

            // Suppression de l'ancienne photo
            if ($eleve->photo) {
                Storage::disk('public')->delete(
                    $eleve->photo
                );
            }

            $payload['photo'] = $data->photo->store(
                'eleves',
                'public'
            );
        }

        return $this->repository->update(
            $id,
            $payload
        );
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Suppression définitive.
     * On supprime également la photo du disque.
     */
    public function forceDelete(int $id)
    {
        $eleve = $this->repository->findOrFail($id);

        if ($eleve->photo) {
            Storage::disk('public')->delete(
                $eleve->photo
            );
        }

        return $eleve->forceDelete();
    }
}