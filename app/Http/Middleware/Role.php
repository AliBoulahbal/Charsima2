<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'error' => 'Non authentifié.'], 401);
        }

        $userRole = Auth::user()->role;

        if (!in_array($userRole, $roles)) {
            return response()->json([
                'success' => false, 
                'error' => 'Accès non autorisé. Rôles requis: ' . implode(', ', $roles)
            ], 403);
        }

        return $next($request);
    }
}