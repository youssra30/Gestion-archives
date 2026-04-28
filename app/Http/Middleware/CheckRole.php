<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user(); 
        
        if (!$user) {
            return response()->json([
                'message' => 'Non authentifié'
            ], 401);
        }

        // SUPER_ADMIN has global access to role-protected endpoints.
        if ($user->role === 'SUPER_ADMIN') {
            return $next($request);
        }

        if (!in_array($user->role, $roles, true)) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        return $next($request);
    }
}