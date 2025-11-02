<?php

use Illuminate\Support\Facades\Route;

//Rutas del Administrador
use App\Livewire\Admin\Dashboard as DashboardAdmin;
use App\Livewire\Admin\Permisos\Index as AdminPermisosIndex;
use App\Livewire\Admin\Permisos\Create as AdminPermisosCreate;
use App\Livewire\Admin\Usuarios\Capturistas\Index as AdminCapturistasIndex;
use App\Livewire\Admin\Usuarios\Capturistas\Create as AdminCapturistasCreate;
use App\Livewire\Admin\Usuarios\Capturistas\Update as AdminCapturistasUpdate;
use App\Livewire\Admin\Usuarios\Coordinadores\Index as AdminCoordinadoresIndex;
use App\Livewire\Admin\Usuarios\Coordinadores\Create as AdminCoordinadoresCreate;
use App\Livewire\Admin\Usuarios\Coordinadores\Update as AdminCoordinadorUpdate;
use App\Livewire\Admin\Usuarios\Profesores\Index as AdminProfesoresIndex;
use App\Livewire\Admin\Usuarios\Profesores\Create as AdminProfesoresCreate;
use App\Livewire\Admin\Usuarios\Profesores\Update as AdminProfesoresUpdate;
use App\Livewire\Admin\Usuarios\Alumnos\Index as AdminAlumnosIndex;

Route::prefix('admin')->middleware(['role:admin'])->group(function () {
    //Dashboard principal del administrador
    Route::get('/dashboard', DashboardAdmin::class)->name('admin.dashboard');

    //Logica sobre Permisos
    Route::get('/permisos', AdminPermisosIndex::class)->name('admin.permisos.index');
    Route::get('/permisos/{usuario}/edit', AdminPermisosCreate::class)->name('admin.permisos.create');

    //Logica sobre Coordinadores
    Route::get('/coordinadores', AdminCoordinadoresIndex::class)->name('admin.coordinadores.index');
    Route::get('/coordinadores/create', AdminCoordinadoresCreate::class)->name('admin.coordinadores.create');
    Route::get('/coordinadores/{coordinador}/edit', AdminCoordinadorUpdate::class)->name('admin.coordinadores.edit');

    //Logica sobre Capturistas
    Route::get('/capturistas', AdminCapturistasIndex::class)->name('admin.capturistas.index');
    Route::get('/capturistas/create', AdminCapturistasCreate::class)->name('admin.capturistas.create');
    Route::get('/capturistas/{capturista}/edit', AdminCapturistasUpdate::class)->name('admin.capturistas.edit');

    //Logica sobre Profesores
    Route::get('/profesores', AdminProfesoresIndex::class)->name('admin.profesores.index');
    Route::get('/profesores/create', AdminProfesoresCreate::class)->name('admin.profesores.create');
    Route::get('/profesores/{profesor}/edit', AdminProfesoresUpdate::class)->name('admin.profesores.edit');

    //Logica sobre Alumnos
    Route::get('/alumnos', AdminAlumnosIndex::class)->name('admin.alumnos.index');
});
