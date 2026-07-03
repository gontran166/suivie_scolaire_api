<?php

namespace App\Repositories;

use App\Models\Classe;
use App\Repositories\Contracts\ClasseRepositoryInterface;

class ClasseRepository extends BaseRepository implements ClasseRepositoryInterface
{
    public function __construct(Classe $model)
    {
        parent::__construct($model);
    }

    public function findWithRelations(int $id)
    {
        return $this->model
            ->with([
                'enseignant',
                'eleves',
                'matieres'
            ])
            ->findOrFail($id);
    }

    public function paginate1(int $perPage){
        return $this->model->with(['enseignant'])->paginate($perPage);
    }
}