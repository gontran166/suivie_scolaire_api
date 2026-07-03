<?php

namespace App\Repositories\Contracts;

interface PaiementRepositoryInterface extends BaseRepositoryInterface
{
    public function findWithRelations(int $id);

    public function filter(array $filters);
}