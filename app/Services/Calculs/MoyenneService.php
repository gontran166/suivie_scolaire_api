<?php

namespace App\Services\Calculs;

use App\Models\Eleve;

class MoyenneService
{
    public function calculerMoyenneTrimestrielle(
        Eleve $eleve,
        int $trimestre,
        string $annee
    ): ?float {

        $notes = $eleve->notes()
            ->with('matiere')
            ->where('trimestre', $trimestre)
            ->where('annee_scolaire', $annee)
            ->get();

        if ($notes->isEmpty()) {
            return null;
        }

        $totalNotes = $notes->sum(
            fn($note) => $note->note
        );

        $totalCoefficients = $notes->sum(
            fn($note) => $note->matiere->coefficient
        );

        return $totalCoefficients > 0
            ? round($totalNotes / $totalCoefficients, 2)
            : null;
    }

    public function calculerMoyenneAnnuelle(
        Eleve $eleve,
        string $annee
    ): ?float {

        $m1 = $this->calculerMoyenneTrimestrielle($eleve,1,$annee);
        $m2 = $this->calculerMoyenneTrimestrielle($eleve,2,$annee);
        $m3 = $this->calculerMoyenneTrimestrielle($eleve,3,$annee);

        if ($m1 === null || $m2 === null || $m3 === null)
        {
            return null;
        }
        

        return round(
            ($m1 + $m2 + $m3) / 3,2);
    }
}