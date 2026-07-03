<?php

namespace App\Services;

use App\Models\Paiement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfReceiptService
{
    public function generate(
        Paiement $paiement
    ): string {

        $paiement->load([
            'eleve.classe'
        ]);

        $eleve = $paiement->eleve;

        $html = view(
            'pdf.recu_paiement',
            [
                'paiement' => $paiement,
                'eleve' => $eleve,
                'classe' => $eleve->classe,
                'total_paye' => $eleve->totalPaye(),
                'reste_a_payer' => $eleve->resteAPayer(),
            ]
        )->render();

        $pdf = Pdf::loadHTML($html);

        $pdf->setPaper(
            'A5',
            'portrait'
        );

        $filename =
            'recus/recu_' .
            $paiement->id .
            '_' .
            now()->format('YmdHis') .
            '.pdf';

        Storage::disk('public')
            ->put(
                $filename,
                $pdf->output()
            );

        return $filename;
    }
}