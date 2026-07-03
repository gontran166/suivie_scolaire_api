<?php

namespace App\Repositories;

use App\Models\Paiement;
use App\Repositories\Contracts\PaiementRepositoryInterface;

class PaiementRepository extends BaseRepository implements PaiementRepositoryInterface
{
    public function __construct(Paiement $model)
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

    public function paginate1(int $perPage){
        return $this->model->with(['eleve'])->paginate($perPage);
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
            ->latest('date_paiement')
            ->paginate(
                min(
                    (int) ($filters['per_page'] ?? 15),
                    200
                )
            );
    }
}