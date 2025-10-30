<?php

use Illuminate\Support\Facades\Route;


Route::view('/', 'index');

Route::middleware('auth')->group(function () {
    // Ruta Dashboard Principal (Redirige según rol)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $mainRole = $user->getRoleNames()->first();

        return match (strtolower($mainRole)) {
            'admin' => redirect()->route('admin.dashboard'),
            'coordinador' => redirect()->route('coordinador.dashboard'),
            'capturista' => redirect()->route('capturista.dashboard'),
        };
    })->name('dashboard');

    // Rutas de Perfil (Comunes para todos los roles)
    Route::view('profile', 'profile')->name('profile');

    //ADMIN
    require __DIR__ . '/UsuariosRoutes/AdminRoutes.php';

    //Coordinador
    require __DIR__ . '/UsuariosRoutes/CoordinadorRoutes.php';

    //Capturista
    require __DIR__ . '/UsuariosRoutes/CapturistaRoutes.php';
});

require __DIR__ . '/auth.php';