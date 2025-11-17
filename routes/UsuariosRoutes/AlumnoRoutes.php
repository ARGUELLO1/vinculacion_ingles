<?php

use App\Http\Controllers\DocumentoController;
use App\Livewire\Alumno\Calificaciones;
use App\Livewire\Alumno\Carterm;
use Illuminate\Support\Facades\Route;


use App\Livewire\Alumno\Dashboard as AlumnoDashboard;
use App\Livewire\Alumno\Infoalumno;
use App\Livewire\Alumno\Inscribirse;
use App\Livewire\Alumno\NivelesAnteriores;
use App\Livewire\Alumno\Principal;
use App\Livewire\Alumno\Reinscribirse;

Route::prefix('alumno')->name('Alumno.')->middleware(['auth', 'role:alumno'])->group(function () {
    Route::get('/Alumno/dashboard', AlumnoDashboard::class)->name('dashboard');
    Route::get('/principal', Principal::class)->name('principal');
    Route::get('/inscribirse', Inscribirse::class)->name('inscribirse');
    Route::get('/reinscribirse', Reinscribirse::class)->name('reinscribirse');
    Route::get('/cartas_de_termino', Carterm::class)->name('carterm');
    Route::get('/nivelesanteriores', NivelesAnteriores::class)->name('nivelesanteriores');
    Route::get('/descargar/{archivo}', [DocumentoController::class, 'descargar'])->name('documento.descargar');
    Route::get('/ver/{nivel}/{alumno}/{archivo}', [DocumentoController::class, 'ver'])->name('documento.ver');
    Route::get('/ver/{carpeta}', [DocumentoController::class, 'ver_constancia'])->name('constancia.ver');



    // aquí luego puedes agregar más rutas del alumno, p.ej huevos al arguello xd.
});
