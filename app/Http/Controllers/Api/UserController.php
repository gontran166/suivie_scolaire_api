<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\DTOs\User\CreateUserDto;
use App\DTOs\User\UpdateUserDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct(
        private UserService $service
    ) {}

    public function index(Request $request)
    {
        return UserResource::collection(
            $this->service->filter(
                $request->only('role')
            )
        );
    }

    public function store(StoreUserRequest $request): UserResource 
    {
        $dto = CreateUserDto::fromArray(
            $request->validated()
        );

        $user = $this->service->create($dto);
        return new UserResource($user);
    }

    public function show(int $id): UserResource 
    {
        return new UserResource(
            $this->service->findOrFail($id)
        );
    }

    public function update(UpdateUserRequest $request,int $id): UserResource 
    {
        $dto = UpdateUserDto::fromArray(
            $request->validated()
        );

        $user = $this->service->update($id, $dto);
        return new UserResource($user);
    }

    public function destroy(int $id): JsonResponse 
    {
        $this->service->delete($id);
        return response()->json([
            'message' => 'Utilisateur supprimé.'
        ]);
    }
}