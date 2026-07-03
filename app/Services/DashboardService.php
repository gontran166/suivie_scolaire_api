<?php

namespace App\Services;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Paiement;
use App\Services\Calculs\MoyenneService;
use App\Services\Calculs\ClassementService;

class DashboardService
{
    public function __construct(
        private MoyenneService $moyenneService,
        private ClassementService $classementService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD GESTIONNAIRE
    |--------------------------------------------------------------------------
    */

    public function totalFraisAttendus(): float
    {
        return Classe::all()
            ->sum(fn ($classe) => $classe->totalFraisAttendus());
    }

    public function totalFraisCollectes(): float
    {
        return (float) Paiement::sum('montant');
    }

    public function montantImpayes(): float
    {
        return $this->totalFraisAttendus()
            - $this->totalFraisCollectes();
    }

    public function elevesEnRetard()
    {
        return Eleve::with(['classe', 'paiements'])
            ->get()
            ->filter(fn (Eleve $eleve) => $eleve->resteAPayer() > 0)
            ->values();
    }

    public function classementAnnuelParClasse(string $annee): array 
    {

        return Classe::with('eleves')
            ->get()
            ->mapWithKeys(function (Classe $classe) use ($annee) {

                return [
                    $classe->nom => $this->classementService
                        ->calculerClassementAnnuel(
                            $classe,
                            $annee
                        )
                ];
            })
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ENSEIGNANT
    |--------------------------------------------------------------------------
    */

    public function classementTrimestrielClasse(Classe $classe, int $trimestre, string $annee): array 
    {

        return $this->classementService
            ->calculerClassementTrimestriel(
                $classe,
                $trimestre,
                $annee
            );
    }

    public function classementAnnuelClasse(
        Classe $classe,
        string $annee
    ): array {
        return $this->classementService
            ->calculerClassementAnnuel(
                $classe,
                $annee
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PARENT
    |--------------------------------------------------------------------------
    */


    public function moyenneTrimestrielle(
        Eleve $eleve,
        int $trimestre,
        string $annee
    ): ?float {

        return $this->moyenneService
            ->calculerMoyenneTrimestrielle(
                $eleve,
                $trimestre,
                $annee
            );
    }

    public function moyenneAnnuelle(
        Eleve $eleve,
        string $annee
    ) {
        $moyenne = $this->moyenneService
            ->calculerMoyenneAnnuelle(
                $eleve,
                $annee
            );
        if($moyenne !== null){
            return number_format($moyenne, 2, '.', '');
        }
        return null;
    }

    public function rangTrimestriel(Eleve $eleve,int $trimestre,string $annee): ?int 
    {

        return $this->classementService
            ->rangTrimestriel(
                $eleve,
                $trimestre,
                $annee
            );
    }

    public function rangAnnuel(Eleve $eleve,string $annee): ?int 
    {
        return $this->classementService
            ->rangAnnuel(
                $eleve,
                $annee
            );
    }

    public function dernieresNotes(Eleve $eleve, int $limit = 5)
    {
        return $eleve->notes()
            ->with('matiere')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function historiquePaiements(Eleve $eleve)
    {
        return $eleve->paiements()
            ->latest()
            ->get();
    }

    public function absences(Eleve $eleve)
    {
        return $eleve->absences()
            ->latest()
            ->get();
    }

    public function moyennesTrimestrielles(Eleve $eleve,string $annee): array 
    {
        $resultats = [];

        $totalEleves = $eleve->classe
            ->eleves()
            ->count();

        for ($trimestre = 1; $trimestre <= 3; $trimestre++) 
        {
            $moyenne = $this->moyenneService
                    ->calculerMoyenneTrimestrielle(
                        $eleve,
                        $trimestre,
                        $annee
                    );
            $resultats[] = [
                'trimestre' => $trimestre,

                'moyenne' => $moyenne !== null
                    ? number_format($moyenne, 2, '.', '')
                    : null,

                'rang' => $this->rangTrimestriel(
                    $eleve,
                    $trimestre,
                    $annee
                ),

                'total_eleves' => $totalEleves,
            ];
        }

        return $resultats;
    }
}