<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si l'utilisateur n'est pas connecté, Laravel gère via le middleware 'auth'
        // Ici on vérifie uniquement le rôle
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'Non authentifié.'
            ], 401);
        }

        if ($user->role !== $role) {
            return response()->json([
                'message' => 'Accès refusé.'
            ], 403);
        }

        return $next($request);
    }
}
