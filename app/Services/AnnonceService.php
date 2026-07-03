<?php

namespace App\Services;

use App\Repositories\Contracts\AnnonceRepositoryInterface;
use App\DTOs\Annonce\CreateAnnonceDto;
use App\DTOs\Annonce\UpdateAnnonceDto;
use App\Models\User;
use App\Services\NotificationService;
use App\Models\Annonce;
use Illuminate\Support\Collection;


class AnnonceService
{
    public function __construct(
        private AnnonceRepositoryInterface $repository,
        private NotificationService $notificationService
    ) {}

    public function filter(array $filters)
    {
        return $this->repository->filter(
            $filters
        );
    }

    public function paginate(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function findOrFail(int $id)
    {
        return $this->repository->findOrFail($id);
    }

    public function find(int $id)
    {
        return $this->repository->findWithRelations($id);
    }

    public function create(CreateAnnonceDto $data)
    {
        $annonce = $this->repository->create(
            $data->toArray()
        );

        $recipients = $this->getRecipients(
            $annonce
        );

        $this->notificationService->sendToUsers(
            $recipients,
            $annonce->titre,
            $annonce->contenu
        );

        return $annonce;
    }

    public function update(int $id, UpdateAnnonceDto $data)
    {
        return $this->repository->update($id, $data->toArray());
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    public function getActive()
    {
        return $this->repository->getActive();
    }

    private function getRecipients(
        Annonce $annonce
    ): Collection {

        $query = User::query()
            ->where('role', User::ROLE_PARENT)
            ->whereNotNull('fcm_token');

        if ($annonce->classe_id !== null) {
            $query->whereHas('eleves', function ($query) use ($annonce) {
                $query->where('classe_id', $annonce->classe_id);
            });
        }

        return $query
            ->distinct()
            ->get();
    }
}