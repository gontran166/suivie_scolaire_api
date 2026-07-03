<?php

namespace App\Repositories;

use App\Models\Note;
use App\Repositories\Contracts\NoteRepositoryInterface;

class NoteRepository extends BaseRepository implements NoteRepositoryInterface
{
    public function __construct(Note $model)
    {
        parent::__construct($model);
    }

    public function findWithRelations(int $id)
    {
        return $this->model
            ->with([
                'eleve',
                'matiere'
            ])
            ->findOrFail($id);
    }

    public function filter(array $filters)
    {
        return $this->model
            ->with([
                'eleve.classe',
                'matiere'
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
                    fn ($q) => $q->where('classe_id', $classeId)
                )
            )
            ->when(
                $filters['trimestre'] ?? null,
                fn ($query, $trimestre) => $query->where(
                    'trimestre',
                    $trimestre
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