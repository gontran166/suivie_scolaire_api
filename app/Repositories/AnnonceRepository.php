<?php

namespace App\Repositories;

use App\Models\Annonce;
use App\Repositories\Contracts\AnnonceRepositoryInterface;

class AnnonceRepository extends BaseRepository implements AnnonceRepositoryInterface
{
    public function __construct(Annonce $model)
    {
        parent::__construct($model);
    }

    public function findWithRelations(int $id)
    {
        return $this->model
            ->with('classe')
            ->findOrFail($id);
    }

    public function getActive()
    {
        return $this->model
            ->where('active', true)
            ->latest()
            ->get();
    }

    public function filter(array $filters)
    {
        return $this->model
            ->with('classe')
            ->when(
                $filters['classe_id'] ?? null,
                function ($query, $classeId) {
                    $query->where(function ($q) use ($classeId) {
                        $q->whereNull('classe_id')
                        ->orWhere('classe_id', $classeId);
                    });
                }
            )
            ->when(
                $filters['active_only'] ?? false,
                fn ($query) => $query->where(
                    'active',
                    true
                )
            )
            ->latest()
            ->paginate(
                min(
                    (int) ($filters['per_page'] ?? 15),
                    200
                )
            );
    }
}