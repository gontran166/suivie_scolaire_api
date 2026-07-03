<?php

namespace App\Http\Controllers\Api;

use App\Services\MatiereService;
use App\Http\Controllers\Controller;
use App\Http\Resources\MatiereResource;
use App\DTOs\Matiere\CreateMatiereDto;
use App\DTOs\Matiere\UpdateMatiereDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Matiere\StoreMatiereRequest;
use App\Http\Requests\Matiere\UpdateMatiereRequest;


class MatiereController extends Controller
{
    public function __construct(
        private MatiereService $service
    ) {}

    public function index(Request $request)
    {
        return MatiereResource::collection(
            $this->service->filter(
                $request->only([
                    'classe_id',
                    'per_page'
                ])
            )
        );
    }

    public function store(
        StoreMatiereRequest $request
    )
    {
        $dto = CreateMatiereDto::fromArray(
            $request->validated()
        );

        return new MatiereResource(
            $this->service->create($dto)
        );
    }

    public function show(int $id)
    {
        return new MatiereResource(
            $this->service->findOrFail($id)
        );
    }

    public function update(UpdateMatiereRequest $request,int $id)
    {
        $dto = UpdateMatiereDto::fromArray(
            $request->validated()
        );

        return new MatiereResource(
            $this->service->update($id, $dto)
        );
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Matière supprimée avec succès.'
        ]);
    }
}