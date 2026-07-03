<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse 
    {
        $user = User::where('email',$request->email)->first();

        if (! $user || ! Hash::check(
                $request->password,
                $user->password
            )
        ) {
            return response()->json([
                'message' => 'Identifiants invalides.'
            ], 401);
        }

        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie.',
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function me(): JsonResponse
    {
        return response()->json(
            auth()->user()
        );
    }

    public function logout(): JsonResponse
    {
        auth()
            ->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'message' => 'Déconnexion réussie.'
        ]);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse 
    {
        $user = auth()->user();

        if (! Hash::check(
            $request->current_password,
            $user->password
        )) {

            return response()->json([
                'message' => 'Mot de passe actuel incorrect.'
            ], 422);
        }

        $user->update([
            'password' => $request->password,
            'password_changed' => true,
        ]);

        return response()->json([
            'message' => 'Mot de passe modifié avec succès.'
        ]);
    }
}