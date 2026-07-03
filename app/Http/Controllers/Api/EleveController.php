<?php

namespace App\Http\Controllers\Api;

use App\Services\EleveService;
use App\Http\Controllers\Controller;
use App\Http\Resources\EleveResource;
use App\DTOs\Eleve\CreateEleveDto;
use App\DTOs\Eleve\UpdateEleveDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Eleve\StoreEleveRequest;
use App\Http\Requests\Eleve\UpdateEleveRequest;


class EleveController extends Controller
{
    public function __construct(
        private EleveService $service
    ) {}

    public function index(Request $request)
    {
        return EleveResource::collection(
            $this->service->filter(
                $request->only([
                    'classe_id',
                    'per_page'
                ])
            )
        );
    }

    public function store(StoreEleveRequest $request)
    {
        $data = $request->validated();
        $data['photo'] = $request->file('photo');
        $dto = CreateEleveDto::fromArray($data);

        return new EleveResource(
            $this->service->create($dto)
        );
    }

    public function show(int $id)
    {
        return new EleveResource(
            $this->service->findOrFail($id)
        );
    }

    public function update(UpdateEleveRequest $request, int $id)
    {
        $data = $request->validated();
        $data['photo'] = $request->file('photo');
        $dto = UpdateEleveDto::fromArray($data);

        return new EleveResource(
            $this->service->update($id, $dto)
        );
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Elève supprimé avec succès.'
        ]);
    }
}