<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Note\CreateNoteDto;
use App\DTOs\Note\UpdateNoteDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Services\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct(
        private NoteService $service
    ) {}

    public function index(Request $request)
    {
        return NoteResource::collection(
            $this->service->filter(
                $request->only([
                    'eleve_id',
                    'classe_id',
                    'trimestre',
                    'per_page'
                ])
            )
        );
    }

    public function store(StoreNoteRequest $request)
    {
        $dto = CreateNoteDto::fromArray(
            $request->validated()
        );

        $note = $this->service->create(
            $dto,
            auth()->user()
        );

        return new NoteResource($note);
    }

    public function show(int $id)
    {
        return new NoteResource(
            $this->service->findOrFail($id)
        );
    }

    public function update(
        UpdateNoteRequest $request,
        int $id
    )
    {
        $dto = UpdateNoteDto::fromArray(
            $request->validated()
        );

        $note = $this->service->update(
            $id,
            $dto,
            auth()->user()
        );

        return new NoteResource($note);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete(
            $id,
            auth()->user()
        );

        return response()->json([
            'message' => 'Note supprimée avec succès.'
        ]);
    }
}