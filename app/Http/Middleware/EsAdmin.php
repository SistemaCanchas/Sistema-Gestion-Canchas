<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->rol !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json(['mensaje' => 'Acceso denegado. Se requiere rol de Recepcionista/Admin.'], 403);
            }
            abort(403, 'Acceso denegado. Solo el personal del lobby puede entrar aquí.');
        }

        return $next($request);
    }
}
