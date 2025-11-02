<?php

use App\Livewire\Admin\Dashboard as DashboardAdmin;
use App\Livewire\Admin\Usuarios\Capturistas\Index as CapturistasIndex;
use App\Livewire\Admin\Usuarios\Capturistas\Create as CapturistasCreate;
use App\Livewire\Admin\Usuarios\Capturistas\Update as CapturistasUpdate;
use App\Livewire\Admin\Usuarios\Coordinadores\Index as CoordinadoresIndex;
use App\Livewire\Admin\Usuarios\Coordinadores\Create as CoordinadoresCreate;
use App\Livewire\Admin\Usuarios\Coordinadores\Update as CoordinadorUpdate;
use App\Livewire\Admin\Usuarios\Profesores\Index as ProfesoresIndex;
use App\Livewire\Admin\Usuarios\Profesores\Create as ProfesoresCreate;
use App\Livewire\Admin\Usuarios\Profesores\Update as ProfesoresUpdate;
use App\Livewire\Admin\Usuarios\Alumnos\Index as AlumnosIndex;
use App\Livewire\Alumno\Carterm;
use App\Livewire\Alumno\Infoalumno;
use App\Livewire\Alumno\Inscribirse;
use App\Livewire\Alumno\Principal;
use App\Livewire\Alumno\Reinscribirse;
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

        //Logica sobre Alumnos
        Route::get('/alomnos', AlumnosIndex::class)->name('admin.alumnos.index');


    Route::prefix('alumno')->name('alumno.')->middleware(['role:alumno'])->group(function () {
        Route::get('/principal', Principal::class)->name('principal');
        Route::get('/inscribirse', Inscribirse::class)->name('inscribirse');
        Route::get('/reinscribirse', Reinscribirse::class)->name('reinscribirse');
        Route::get('/cartas_de_termino', Carterm::class)->name('carterm');
        Route::get('/informacion_del_alumno', Infoalumno::class)->name('infoalumno');
    });
    //Alumno
    require __DIR__ . '/UsuariosRoutes/AlumnoRoutes.php';
});

require __DIR__ . '/auth.php';