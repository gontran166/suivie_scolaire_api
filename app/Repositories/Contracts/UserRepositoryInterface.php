<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findWithRelations(int $id);

    public function filter(array $filters);
}