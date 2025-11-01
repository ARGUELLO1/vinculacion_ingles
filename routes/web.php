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

Route::view('/', 'welcome');

Route::middleware('auth')->group(function () {
    // Ruta Dashboard Principal (Redirige según rol)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $mainRole = $user->getRoleNames()->first();

        return match (strtolower($mainRole)) {
            'admin' => view('livewire.admin.dashboard'),
            'alumno' => view('livewire.alumno.dashboard'),
        };
    })->name('dashboard');

    // Rutas de Perfil (Comunes para todos los roles)
    Route::view('profile', 'profile')->name('profile');

    //ADMIN
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        //Dashboard principal del administrador
        Route::get('/dashboard', DashboardAdmin::class)->name('admin.dashboard');

        //Logica sobre Coordinadores
        Route::get('/coordinadores', CoordinadoresIndex::class)->name('admin.coordinadores.index');
        Route::get('/coordinadores/create', CoordinadoresCreate::class)->name('admin.coordinadores.create');
        Route::get('/coordinadores/{coordinador}/edit', CoordinadorUpdate::class)->name('admin.coordinadores.edit');

        //Logica sobre Capturistas
        Route::get('/capturistas', CapturistasIndex::class)->name('admin.capturistas.index');
        Route::get('/capturistas/create', CapturistasCreate::class)->name('admin.capturistas.create');
        Route::get('/capturistas/{capturista}/edit', CapturistasUpdate::class)->name('admin.capturistas.edit');

        //Logica sobre Profesores
        Route::get('/profesores', ProfesoresIndex::class)->name('admin.profesores.index');
        Route::get('/profesores/cresate', ProfesoresCreate::class)->name('admin.profesores.create');
        Route::get('/profesores/{profesor}/edit', ProfesoresUpdate::class)->name('admin.profesores.edit');

        //Logica sobre Alumnos
        Route::get('/alomnos', AlumnosIndex::class)->name('admin.alumnos.index');
    });

    Route::prefix('alumno')->name('alumno.')->middleware(['role:alumno'])->group(function () {
        Route::get('/principal', Principal::class)->name('principal');
        Route::get('/inscribirse', Inscribirse::class)->name('inscribirse');
        Route::get('/reinscribirse', Reinscribirse::class)->name('reinscribirse');
        Route::get('/cartas_de_termino', Carterm::class)->name('carterm');
        Route::get('/informacion_del_alumno', Infoalumno::class)->name('infoalumno');
    });
});

require __DIR__ . '/auth.php';
