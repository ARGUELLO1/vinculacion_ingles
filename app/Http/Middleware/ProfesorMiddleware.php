<?php
// app/Http/Middleware/AlumnoMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfesorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role_id == 3) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Acceso solo para profesores');
    }
}