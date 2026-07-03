<?php

namespace App\Repositories\Contracts;

interface MatiereRepositoryInterface extends BaseRepositoryInterface
{
    public function findWithRelations(int $id);

    public function filter(array $filters);
}