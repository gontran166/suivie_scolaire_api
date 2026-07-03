<?php

namespace App\Services\Calculs;

use App\Models\Classe;
use App\Models\Eleve;

class ClassementService
{
    public function __construct(
        private MoyenneService $moyenneService
    ) {}

    public function calculerClassementTrimestriel(
        Classe $classe,
        int $trimestre,
        string $annee
    ): array {
        return $this->construireClassement(
            $classe,
            fn(Eleve $eleve) =>
                $this->moyenneService
                    ->calculerMoyenneTrimestrielle(
                        $eleve,
                        $trimestre,
                        $annee
                    )
        );
    }

    public function calculerClassementAnnuel(
        Classe $classe,
        string $annee
    ): array {

        return $this->construireClassement(
            $classe,
            fn(Eleve $eleve) =>
                $this->moyenneService
                    ->calculerMoyenneAnnuelle(
                        $eleve,
                        $annee
                    )
        );
    }

    private function construireClassement(
        Classe $classe,
        callable $calculMoyenne
    ): array {

        $resultats = $classe->eleves
            ->map(function (Eleve $eleve) use ($calculMoyenne) {
                return [
                    'eleve' => $eleve,
                    'moyenne' => $calculMoyenne($eleve),
                ];
            })
            ->sortByDesc(
                fn($item) => $item['moyenne'] ?? -1
            )
            ->values()
            ->toArray();

        $rang = 1;

        foreach ($resultats as $index => &$resultat) {
            if ($index > 0 && $resultat['moyenne'] === $resultats[$index - 1]['moyenne'])
            {
                $resultat['rang']
                    = $resultats[$index - 1]['rang'];
            } else {
                $resultat['rang'] = $rang;
            }

            $rang++;
        }

        return $resultats;
    }

    public function rangAnnuel(
        Eleve $eleve,
        string $annee
    ): ?int {

        $classement = $this->calculerClassementAnnuel(
            $eleve->classe,
            $annee
        );

        foreach ($classement as $ligne) {
            if ($ligne['eleve']->id === $eleve->id) {
                return $ligne['rang'];
            }
        }

        return null;
    }

    public function rangTrimestriel(
        Eleve $eleve,
        int $trimestre,
        string $annee
    ): ?int {

        $classement =
            $this->calculerClassementTrimestriel(
                $eleve->classe,
                $trimestre,
                $annee
            );

        foreach ($classement as $ligne) {
            if ($ligne['eleve']->id === $eleve->id) {
                return $ligne['rang'];
            }
        }

        return null;
    }
}