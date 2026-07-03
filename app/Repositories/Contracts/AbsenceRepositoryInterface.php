<?php

namespace App\Repositories\Contracts;

interface AbsenceRepositoryInterface extends BaseRepositoryInterface
{
    public function findWithRelations(int $id);

    public function filter(array $filters);
}