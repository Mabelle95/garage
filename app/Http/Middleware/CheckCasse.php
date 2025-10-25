<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckCasse
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isCasse()) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Accès réservé aux casses automobiles.');
    }
}
