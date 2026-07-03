<?php

namespace App\Repositories\Contracts;

interface AnnonceRepositoryInterface extends BaseRepositoryInterface
{
    public function findWithRelations(int $id);

    public function getActive();

    public function filter(array $filters);
}