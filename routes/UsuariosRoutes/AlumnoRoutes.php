<?php

use Illuminate\Support\Facades\Route;


use App\Livewire\Alumno\Dashboard as AlumnoDashboard;

Route::middleware(['auth', 'role:alumno'])->group(function () {
    Route::get('/Alumno/dashboard', AlumnoDashboard::class)->name('Alumno.dashboard');

    // aquí luego puedes agregar más rutas del alumno, p.ej huevos al arguello xd.
});