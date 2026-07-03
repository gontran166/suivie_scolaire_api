<?php

namespace App\Services;

use App\Models\Paiement;
use App\Models\Eleve;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\DTOs\Paiement\CreatePaiementDto;
use App\DTOs\Paiement\UpdatePaiementDto;
use App\Repositories\Contracts\PaiementRepositoryInterface;
use App\Repositories\Contracts\EleveRepositoryInterface;
use App\Repositories\Contracts\ClasseRepositoryInterface;


class PaiementService
{
    public function __construct(
        private PaiementRepositoryInterface $paiementRepository,
        private EleveRepositoryInterface $eleveRepository,
        private ClasseRepositoryInterface $classeRepository,
        private PdfReceiptService $pdfReceiptService,
    ) {
    }

    public function filter(array $filters)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Sécurité Parent
        |--------------------------------------------------------------------------
        */

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

        return $this->paiementRepository->filter(
            $filters
        );
    }

    public function paginate(int $perPage = 15)
    {
        return $this->paiementRepository->paginate1($perPage);
    }

    public function find(int $id)
    {
        return $this->paiementRepository->findWithRelations($id);
    }
    public function findOrFail(int $id)
    {
        return $this->paiementRepository->findOrFail($id);
    }

    public function create(CreatePaiementDto $dto)
    {
        $data = $dto->toArray();
        $eleve = $this->eleveRepository->find($data['eleve_id']);
        $classe = $this->classeRepository->find($eleve->classe_id);

        if($classe->frais_scolarite < $data['montant'] || $eleve->resteAPayer() < $data['montant']){
            return null;
        }
        $paiement = $this->paiementRepository->create($data);
        $pdfPath = $this->pdfReceiptService
            ->generate($paiement);

        $paiement->update([
            'recu_pdf' => $pdfPath
        ]);

        return $paiement->fresh();
    }

    public function totalPaye(int $eleveId): float
    {
        $eleve = $this->eleveRepository
            ->findOrFail($eleveId);

        return $eleve->totalPaye();
    }

    public function resteAPayer(int $eleveId): float
    {
        $eleve = $this->eleveRepository
            ->findOrFail($eleveId);

        return $eleve->resteAPayer();
    }

    public function delete(int $id)
    {
        return $this->paiementRepository->delete($id);
    }

    public function summary(int $eleveId): array
    {
        $user = Auth::user();
        $eleve = Eleve::with('classe')
            ->findOrFail($eleveId);

        /*
        |--------------------------------------------------------------------------
        | Sécurité Parent
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === User::ROLE_PARENT
            && $eleve->user_id !== $user->id
        ) {
            abort(
                403,
                'Accès non autorisé.'
            );
        }

        return [
            'total_paye' => $eleve->totalPaye(),
            'frais_scolarite' => $eleve->classe->frais_scolarite,
            'reste_a_payer' => $eleve->resteAPayer(),
        ];
    }
}