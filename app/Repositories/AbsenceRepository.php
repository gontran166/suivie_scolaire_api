<?php

namespace App\Repositories;

use App\Models\Absence;
use App\Repositories\Contracts\AbsenceRepositoryInterface;

class AbsenceRepository extends BaseRepository implements AbsenceRepositoryInterface
{
    public function __construct(Absence $model)
    {
        parent::__construct($model);
    }

    public function findWithRelations(int $id)
    {
        return $this->model
            ->with([
                'eleve'
            ])
            ->findOrFail($id);
    }

    public function filter(array $filters)
    {
        return $this->model
            ->with([
                'eleve.classe'
            ])
            ->when(
                $filters['eleve_id'] ?? null,
                fn ($query, $eleveId) => $query->where(
                    'eleve_id',
                    $eleveId
                )
            )
            ->when(
                $filters['classe_id'] ?? null,
                fn ($query, $classeId) => $query->whereHas(
                    'eleve',
                    fn ($q) => $q->where(
                        'classe_id',
                        $classeId
                    )
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