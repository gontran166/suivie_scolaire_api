<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findWithRelations(int $id)
    {
        return $this->model
            ->with([
                'classes',
                'eleves'
            ])
            ->findOrFail($id);
    }

    public function filter(array $filters)
    {
        return $this->model
            ->with(['eleves','classes'])
            ->when(
                $filters['role'] ?? null,
                fn ($query, $role) => $query->where('role', $role)
            )
            ->paginate(
                $filters['per_page'] ?? 15
            );
    }
}