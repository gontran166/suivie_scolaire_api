<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Absence\CreateAbsenceDto;
use App\DTOs\Absence\UpdateAbsenceDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Absence\StoreAbsenceRequest;
use App\Http\Requests\Absence\UpdateAbsenceRequest;
use App\Http\Resources\AbsenceResource;
use App\Services\AbsenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    public function __construct(
        private AbsenceService $service
    ) {}

    public function index(Request $request)
    {
        return AbsenceResource::collection(
            $this->service->filter(
                $request->only([
                    'eleve_id',
                    'classe_id',
                    'per_page'
                ])
            )
        );
    }

    public function store(StoreAbsenceRequest $request)
    {
        $dto = CreateAbsenceDto::fromArray(
            $request->validated()
        );

        $absence = $this->service->create(
            $dto,
            auth()->user()
        );

        return new AbsenceResource($absence);
    }

    public function show(int $id)
    {
        return new AbsenceResource(
            $this->service->findOrFail($id)
        );
    }

    public function update(
        UpdateAbsenceRequest $request,
        int $id
    )
    {
        $dto = UpdateAbsenceDto::fromArray(
            $request->validated()
        );

        $absence = $this->service->update(
            $id,
            $dto,
            auth()->user()
        );

        return new AbsenceResource($absence);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete(
            $id,
            auth()->user()
        );

        return response()->json([
            'message' => 'Absence supprimée avec succès.'
        ]);
    }
}