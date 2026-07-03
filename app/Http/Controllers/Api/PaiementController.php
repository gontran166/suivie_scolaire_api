<?php

namespace App\Http\Controllers\Api;

use App\Services\PaiementService;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaiementResource;
use App\DTOs\Paiement\CreatePaiementDto;
use App\DTOs\Paiement\UpdatePaiementDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Paiement\StorePaiementRequest;
use App\Http\Requests\Paiement\UpdatePaiementRequest;


class PaiementController extends Controller
{
    public function __construct(
        private PaiementService $service
    ) {}

    public function index(Request $request)
    {
        return PaiementResource::collection(
            $this->service->filter(
                $request->only([
                    'eleve_id',
                    'per_page'
                ])
            )
        );
    }

    public function store(
        StorePaiementRequest $request
    )
    {
        $dto = CreatePaiementDto::fromArray(
            $request->validated()
        );

        $paiement = $this->service->create($dto);
        if($paiement === null){
            return response()->json([
                'message' => 'le montant depasse les frais de scolarité ou du reste à payer'
            ],400);
        }
 
        return new PaiementResource(
            $paiement
        );
    }

    public function show(int $id)
    {
        return new PaiementResource(
            $this->service->findOrFail($id)
        );
    }

    public function update(UpdatePaiementRequest $request,int $id)
    {
        $dto = UpdatePaiementDto::fromArray(
            $request->validated()
        );

        return new PaiementResource(
            $this->service->update($id, $dto)
        );
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Paiement supprimée avec succès.'
        ]);
    }

    public function summary(Request $request): JsonResponse
    {
        $request->validate([
            'eleve_id' => [
                'required',
                'exists:eleves,id'
            ]
        ]);

        return response()->json(
            $this->service->summary(
                (int) $request->eleve_id
            )
        );
    }
}