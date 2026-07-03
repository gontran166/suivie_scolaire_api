<?php

namespace App\Repositories\Contracts;

interface EleveRepositoryInterface extends BaseRepositoryInterface
{
    public function findWithRelations(int $id);

    public function filter(array $filters);
}