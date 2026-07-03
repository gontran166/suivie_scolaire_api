<?php

namespace App\Services;

use App\Models\User;
use App\Models\Classe;
use App\Models\Absence;
use App\Models\Eleve;
use Illuminate\Support\Facades\Auth;
use App\DTOs\Absence\CreateAbsenceDto;
use App\DTOs\Absence\UpdateAbsenceDto;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\Contracts\AbsenceRepositoryInterface;
use App\Repositories\Contracts\EleveRepositoryInterface;

class AbsenceService
{
    public function __construct(
        private AbsenceRepositoryInterface $absenceRepository,
        private EleveRepositoryInterface $eleveRepository
    ) {}

    public function filter(array $filters)
    {
        $user = Auth::user();

        if ($user->role === User::ROLE_ENSEIGNANT) {

            if (! isset($filters['classe_id'])) {
                abort(
                    403,
                    'Vous devez préciser une classe.'
                );
            }

            $classe = Classe::findOrFail(
                $filters['classe_id']
            );

            if ($classe->user_id !== $user->id) {
                abort(
                    403,
                    'Accès non autorisé: vous n\'êtes pas responsable de cette classe'
                );
            }
        }

        if ($user->role === User::ROLE_PARENT) {

            if (! isset($filters['eleve_id'])) {
                abort(
                    403,
                    'Vous devez préciser un élève.'
                );
            }

            $eleve = Eleve::findOrFail(
                $filters['eleve_id']
            );

            if ($eleve->user_id !== $user->id) {
                abort(
                    403,
                    'Accès non autorisé.'
                );
            }
        }

        return $this->absenceRepository->filter(
            $filters
        );
    }

    public function paginate(int $perPage = 15)
    {
        return $this->absenceRepository->paginate($perPage);
    }

    public function findOrFail(int $id)
    {
        return $this->absenceRepository->findOrFail($id);
    }

    public function find(int $id)
    {
        return $this->absenceRepository->findWithRelations($id);
    }

    public function create(CreateAbsenceDto $data, User $enseignant): Absence
    {
        $eleve = $this->eleveRepository
            ->findOrFail($data->eleve_id);

        if (
            $eleve->classe->user_id !== $enseignant->id
        ) {
            throw new AuthorizationException(
                'Classe non autorisée.'
            );
        }

        return $this->absenceRepository
            ->create($data->toArray());
    }
    public function update(int $id, UpdateAbsenceDto $data, User $enseignant): Absence
    {
        $absence = $this->absenceRepository
            ->findWithRelations($id);

        if (
            $absence->eleve->classe->user_id !== $enseignant->id
        ) {
            throw new AuthorizationException(
                'Classe non autorisée.'
            );
        }

        return $this->absenceRepository
            ->update($id,$data->toArray());
    }

    public function delete(int $id,User $enseignant): bool 
    {
        $absence = $this->absenceRepository
            ->findWithRelations($id);

        if (
            $absence->eleve->classe->user_id !== $enseignant->id
        ) {
            throw new AuthorizationException(
                'Vous n’êtes pas responsable de cette classe.'
            );
        }

        return $this->absenceRepository
            ->delete($id);
    }
}