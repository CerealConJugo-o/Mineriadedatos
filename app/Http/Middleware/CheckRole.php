<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles  <-- Aquí recibimos la lista de roles permitidos
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Usamos la función del modelo User para ver si tiene alguno de los roles requeridos
        // Nota: El Gerente tiene acceso maestro, así que siempre verificamos si es gerente O el rol solicitado.
        // Pero para ser estrictos con tu función, pasaremos la lista completa en la ruta.
        
        // Verificamos si el rol del usuario coincide con alguno de los permitidos
        if (in_array($user->role->nombre, $roles)) {
            return $next($request);
        }

        // Si no tiene permiso, error 403
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}