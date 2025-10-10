<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCasseIsApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si l'utilisateur est une casse et n'est pas approuvé
        if (!$user->approved) {
            // Déconnexion de l'utilisateur
            auth()->logout();

            // Invalider la session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirection avec message
            return redirect()->route('auth.pending-approval')
                ->with('error', 'Votre compte est en attente d\'approbation par un administrateur. Vous recevrez un email une fois votre compte validé.');
        }

        return $next($request);
    }
}
