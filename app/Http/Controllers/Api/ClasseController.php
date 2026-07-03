<?php

namespace App\Http\Controllers\Api;

use App\Services\ClasseService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClasseResource;
use App\DTOs\Classe\CreateClasseDto;
use App\DTOs\Classe\UpdateClasseDto;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Classe\StoreClasseRequest;
use App\Http\Requests\Classe\UpdateClasseRequest;

class ClasseController extends Controller
{
    public function __construct(
        private ClasseService $service
    ) {}

    public function index()
    {
        return ClasseResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreClasseRequest $request
    )
    {
        $dto = CreateClasseDto::fromArray(
            $request->validated()
        );

        return new ClasseResource(
            $this->service->create($dto)
        );
    }

    public function show(int $id)
    {
        return new ClasseResource(
            $this->service->findOrFail($id)
        );
    }

    public function update(
        UpdateClasseRequest $request,
        int $id
    )
    {
        $dto = UpdateClasseDto::fromArray(
            $request->validated()
        );

        return new ClasseResource(
            $this->service->update($id, $dto)
        );
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Classe supprimée avec succès.'
        ]);
    }
}