<?php

namespace App\Repositories\Contracts;

interface NoteRepositoryInterface extends BaseRepositoryInterface
{
    public function findWithRelations(int $id);

    public function filter(array $filters);
}