<?php

use Illuminate\Support\Facades\Route;

//Rutas del Coordinador
use App\Livewire\Coordinador\Dashboard as DashboardCoordinador;
use App\Livewire\Coordinador\Permisos\Index as CoordinadorPermisosIndex;
use App\Livewire\Coordinador\Permisos\Create as CoordinadorPermisosCreate;
use App\Livewire\Coordinador\Usuarios\Capturistas\Index as CoordinadorCapturistasIndex;
use App\Livewire\Coordinador\Usuarios\Capturistas\Create as CoordinadorCapturistasCreate;
use App\Livewire\Coordinador\Usuarios\Capturistas\Update as CoordinadorCapturistasUpdate;
use App\Livewire\Coordinador\Usuarios\Profesores\Index as CoordinadorProfesoresIndex;
use App\Livewire\Coordinador\Usuarios\Profesores\Create as CoordinadorProfesoresCreate;
use App\Livewire\Coordinador\Usuarios\Profesores\Update as CoordinadorProfesoresUpdate;
use App\Livewire\Coordinador\Usuarios\Alumnos\Index as CoordinadorAlumnosIndex;
use App\Livewire\Coordinador\Niveles\Index as CoordinadorNivelesIndex;
use App\Livewire\Coordinador\Niveles\Create as CoordinadorNivelesCreate;
use App\Livewire\Coordinador\Niveles\Update as CoordinadorNivelesUpdate;

Route::prefix('coordinador')->middleware(['role:coordinador'])->group(function () {
    //Dashboard principal del Coordinador
    Route::get('/dashboard', DashboardCoordinador::class)->name('coordinador.dashboard');

    //Logica sobre permisos
    Route::get('/permisos', CoordinadorPermisosIndex::class)->name('coordinador.permisos.index');
    Route::get('/permisos/{usuario}/edit', CoordinadorPermisosCreate::class)->name('coordinador.permisos.create');

    //Logica sobre Capturistas
    Route::get('/capturistas', CoordinadorCapturistasIndex::class)->name('coordinador.capturistas.index');
    Route::get('/capturistas/create', CoordinadorCapturistasCreate::class)->name('coordinador.capturistas.create');
    Route::get('/capturistas/{capturista}/edit', CoordinadorCapturistasUpdate::class)->name('coordinador.capturistas.edit');

    //Logica sobre Profesores
    Route::get('/profesores', CoordinadorProfesoresIndex::class)->name('coordinador.profesores.index');
    Route::get('/profesores/create', CoordinadorProfesoresCreate::class)->name('coordinador.profesores.create');
    Route::get('/profesores/{profesor}/edit', CoordinadorProfesoresUpdate::class)->name('coordinador.profesores.edit');

    //Logica sobre Alumnos
    Route::get('/alumnos', CoordinadorAlumnosIndex::class)->name('coordinador.alumnos.index');

    //Logica sobre Niveles
    Route::get('/niveles', CoordinadorNivelesIndex::class)->name('coordinador.niveles.index');
    Route::get('/niveles/create', CoordinadorNivelesCreate::class)->name('coordinador.niveles.create');
    Route::get('/niveles/{nivel}/edit', CoordinadorNivelesUpdate::class)->name('coordinador.niveles.edit');
});