<?php

namespace App\Repositories;

use App\Models\Eleve;
use App\Repositories\Contracts\EleveRepositoryInterface;

class EleveRepository extends BaseRepository implements EleveRepositoryInterface
{
    public function __construct(Eleve $model)
    {
        parent::__construct($model);
    }

    public function findWithRelations(int $id)
    {
        return $this->model
            ->with([
                'classe',
                'parent',
                'paiements',
                'notes',
                'absences'
            ])
            ->findOrFail($id);
    }

    public function paginate1(int $perPage){
        return $this->model->with(['classe','parent'])->paginate($perPage);
    }

    public function filter(array $filters)
    {
        return $this->model
            ->with(['classe', 'parent'])
            ->when(
                $filters['classe_id'] ?? null,
                fn ($query, $classeId) => $query->where(
                    'classe_id',
                    $classeId
                )
            )
            ->paginate(
                min(
                    (int) ($filters['per_page'] ?? 15),
                    200
                )
            );
    }
}