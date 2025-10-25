<?php
// app/Http/Middleware/CasseMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CasseMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isCasse()) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Accès non autorisé.');
    }
}
