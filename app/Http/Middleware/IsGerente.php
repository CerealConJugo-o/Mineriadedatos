<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsGerente
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
// app/Http/Middleware/IsGerente.php

public function handle(Request $request, Closure $next): Response
{
    if ($request->user() && $request->user()->hasRole('gerente')) {
        return $next($request);
    }

    abort(403, 'Acceso denegado. Solo el Gerente administra salarios.');
}
}
