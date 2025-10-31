<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

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
            'profesor' => redirect()->route('profesor.dashboard'),
            'alumno' => redirect()->route('Alumno.dashboard'),
        };
    })->name('dashboard');

    //excel de profesor
    Route::get('/grupo/{grupo}/exportar-asistencias/{parcial}', [ExportController::class, 'exportarAsistencias'])
        ->name('exportar.asistencias')
        ->where('parcial', '[1-3]'); // Asegura que el parcial solo sea 1, 2 o 3

    //pdf profesor
    Route::get('/grupo/{grupo}/reporte-pdf/{parcial?}', [ExportController::class, 'exportarReportePDF'])
    ->name('exportar.reporte')
    ->where('parcial', '[1-3]');

    // Rutas de Perfil (Comunes para todos los roles)
    Route::view('profile', 'profile')->name('profile');

    //ADMIN
    require __DIR__ . '/UsuariosRoutes/AdminRoutes.php';

    //Coordinador
    require __DIR__ . '/UsuariosRoutes/CoordinadorRoutes.php';

    //Capturista
    require __DIR__ . '/UsuariosRoutes/CapturistaRoutes.php';

    //Profesor
    require __DIR__ . '/UsuariosRoutes/ProfesorRoutes.php';

    //Alumno
    require __DIR__ . '/UsuariosRoutes/AlumnoRoutes.php';
});

require __DIR__ . '/auth.php';