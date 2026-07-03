<?php

namespace App\Http\Controllers\Api;

use App\Services\AnnonceService;
use App\Http\Controllers\Controller;
use App\Http\Resources\AnnonceResource;
use App\DTOs\Annonce\CreateAnnonceDto;
use App\DTOs\Annonce\UpdateAnnonceDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Annonce\StoreAnnonceRequest;
use App\Http\Requests\Annonce\UpdateAnnonceRequest;


class AnnonceController extends Controller
{
    public function __construct(
        private AnnonceService $service
    ) {}

    public function index(Request $request)
    {
        return AnnonceResource::collection(
            $this->service->filter(
                $request->only([
                    'classe_id',
                    'active_only',
                    'per_page'
                ])
            )
        );
    }

    public function store(
        StoreAnnonceRequest $request
    )
    {
        $dto = CreateAnnonceDto::fromArray(
            $request->validated()
        );

        return new AnnonceResource(
            $this->service->create($dto)
        );
    }

    public function show(int $id)
    {
        return new AnnonceResource(
            $this->service->findOrFail($id)
        );
    }

    public function update(UpdateAnnonceRequest $request,int $id)
    {
        $dto = UpdateAnnonceDto::fromArray(
            $request->validated()
        );

        return new AnnonceResource(
            $this->service->update($id, $dto)
        );
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Annonce supprimée avec succès.'
        ]);
    }
}