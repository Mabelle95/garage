<?php

// app/Http/Middleware/EnsureUserHasRole.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\UserRole;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $userRole = $user->role;

        // Convertir les rôles en valeurs d'enum si nécessaire
        $allowedRoles = array_map(function($role) {
            return $role instanceof UserRole ? $role->value : $role;
        }, $roles);

        if (!in_array($userRole instanceof UserRole ? $userRole->value : $userRole, $allowedRoles)) {
            abort(403, 'Accès non autorisé');
        }

        return $next($request);
    }
}
