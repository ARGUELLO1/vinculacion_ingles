<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRol
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Si $role es un número, úsalo directamente
        if (is_numeric($role)) {
            $requiredRoleId = (int)$role;
        } else {
            // Si es texto, usa el mapeo
            $requiredRoleId = $this->getRoleId($role);
        }

        if ((int)$user->rol_id !== $requiredRoleId) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }

    protected function getRoleId($roleName)
    {
        $roles = [
            'admin' => 1,
            'capturista' => 2,
            'profesor' => 3,
            'alumno' => 4,
        ];

        return $roles[$roleName] ?? null;
    }
}
