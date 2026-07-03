<?php

namespace App\Services;

use App\Models\Eleve;
use App\Models\User;
use App\Models\Note;
use App\Models\Classe;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\DTOs\Note\CreateNoteDto;
use App\DTOs\Note\UpdateNoteDto;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\Contracts\NoteRepositoryInterface;
use App\Repositories\Contracts\EleveRepositoryInterface;


class NoteService
{
    public function __construct(
        private NoteRepositoryInterface $noteRepository,
        private EleveRepositoryInterface $eleveRepository,
    ) {
    }

    public function filter(array $filters)
    {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Sécurité Enseignant
        |--------------------------------------------------------------------------
        */
        if (
            $user->role === User::ROLE_ENSEIGNANT
            && isset($filters['classe_id'])
        ) {
            $classe = Classe::findOrFail(
                $filters['classe_id']
            );

            if ($classe->user_id !== $user->id) {
                abort(
                    403,
                    'Accès non autorisé: vous n\'êtes pas responsable de cette classe.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sécurité Parent
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === User::ROLE_PARENT
            && isset($filters['eleve_id'])
        ) {

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

        return $this->noteRepository->filter(
            $filters
        );
    }

        public function paginate(int $perPage = 15)
    {
        return $this->noteRepository->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->noteRepository->findWithRelations($id);
    }
    public function findOrFail(int $id)
    {
        return $this->noteRepository->findOrFail($id);
    }

    public function create(CreateNoteDto $data,User $enseignant): Note
    {
        $eleve = $this->eleveRepository
            ->findOrFail($data->eleve_id);

        if (
            $eleve->classe->user_id !== $enseignant->id
        ) {
            throw new AuthorizationException(
                'Vous n’êtes pas responsable de cette classe.'
            );
        }

        return $this->noteRepository
            ->create($data->toArray());
    }

    public function update(int $id, UpdateNoteDto $data, User $enseignant): Note
    {
        $note = $this->noteRepository
            ->findWithRelations($id);

        if (
            $note->eleve->classe->user_id !== $enseignant->id
        ) {
            throw new AuthorizationException(
                'Vous n’êtes pas responsable de cette classe.'
            );
        }

        return $this->noteRepository
            ->update($id, $data->toArray());
    }

    public function delete(int $id, User $enseignant): bool
    {
        $note = $this->noteRepository
            ->findWithRelations($id);

        if (
            $note->eleve->classe->user_id !== $enseignant->id
        ) {
            throw new AuthorizationException(
                'Vous n’êtes pas responsable de cette classe.'
            );
        }

        return $this->noteRepository
            ->delete($id);
    }
}