<?php

namespace App\Repositories;

use App\Models\Matiere;
use App\Repositories\Contracts\MatiereRepositoryInterface;

class MatiereRepository extends BaseRepository implements MatiereRepositoryInterface
{
    public function __construct(Matiere $model)
    {
        parent::__construct($model);
    }

    public function findWithRelations(int $id)
    {
        return $this->model
            ->with([
                'classe'
            ])
            ->findOrFail($id);
    }

    public function paginate1(int $perPage){
        return $this->model->with(['classe'])->paginate($perPage);
    }

    public function filter(array $filters)
    {
        return $this->model
            ->with('classe')
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