<?php

namespace App\Repositories\Contracts;

interface ClasseRepositoryInterface extends BaseRepositoryInterface
{
    public function findWithRelations(int $id);
}