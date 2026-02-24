<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Asegúrate de importar esto

class AdminMiddleware
{
    public function handle(Request $request, Closure $next) {
        // Auth::user() es más fácil de rastrear para el editor
        if (Auth::check() && Auth::user()->admin) {
            return $next($request);
        }

        return response()->json(['message' => 'Acceso denegado.'], 403);
    }
}