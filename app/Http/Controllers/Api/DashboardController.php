<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\PaiementService;
use App\Http\Resources\EleveResource;
use App\Http\Resources\PaiementResource;
use App\Http\Resources\NoteResource;
use App\Http\Resources\AbsenceResource;

use App\Models\Classe;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $service,
        private PaiementService $paiementService
    ) {}

    public function gestionnaire()
    {
        return response()->json([
            'frais_attendus' => $this->service->totalFraisAttendus(),
            'frais_collectes' => $this->service->totalFraisCollectes(),
            'impayes' => $this->service->montantImpayes(),
            'eleves_en_retard' => EleveResource::collection($this->service->elevesEnRetard()),
        ]);
    }

    public function enseignant()
    {
        $classe = auth()->user()
            ->classes()
            ->first();

        return response()->json([
            'classe' => $classe,
            'effectif' => $classe?->eleves()->count() ?? 0,
        ]);
    }

    public function classementTrimestriel(
        Classe $classe,
        int $trimestre,
        string $annee
    ): JsonResponse
    {
        return response()->json(
            $this->service->classementTrimestrielClasse(
                $classe,
                $trimestre,
                $annee
            )
        );
    }

    public function classementAnnuel(
        Classe $classe,
        string $annee
    ): JsonResponse
    {
        return response()->json(
            $this->service->classementAnnuelClasse(
                $classe,
                $annee
            )
        );
    }

    public function parent(Request $request)
    {
        $parent = auth()->user();

        // Tous les enfants du parent
        $enfants = $parent->eleves()
            ->with('classe')
            ->get();

        if ($enfants->isEmpty()) {
            return response()->json([
                'message' => 'Aucun enfant associé à ce parent.'
            ], 404);
        }

        // Élève demandé dans l'URL
        $eleveId = $request->query('eleve_id');

        if ($eleveId) {

            $eleve = $parent->eleves()
                ->with('classe')
                ->find($eleveId);

            if (! $eleve) {
                return response()->json([
                    'message' => 'Cet élève ne vous appartient pas.'
                ], 403);
            }

        } else {

            // Premier enfant par défaut
            $eleve = $enfants->first();
        }

        return response()->json([

            'enfants' => EleveResource::collection(
                $enfants
            ),

            'eleve' => new EleveResource(
                $eleve
            ),

            'moyennes_trimestrielles' => $this->service
                ->moyennesTrimestrielles(
                    $eleve,
                    $eleve->classe->annee_scolaire
                ),

            'moyenne_annuelle' => $this->service
                ->moyenneAnnuelle(
                    $eleve,
                    $eleve->classe->annee_scolaire
                ),

            'rang_annuel' => $this->service
                ->rangAnnuel(
                    $eleve,
                    $eleve->classe->annee_scolaire
                ),

            'paiements' => PaiementResource::collection(
                $eleve->paiements
            ),
            'paiement_summary' => $this->paiementService->summary($eleve->id),

            'absences' => AbsenceResource::collection(
                $eleve->absences
            ),

            'dernieres_notes' => NoteResource::collection(
                $this->service->dernieresNotes($eleve)
            ),
        ]);
    }
}
