<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Profesor\Niveles;
use App\Livewire\Profesor\GrupoVista;
use App\Livewire\Profesor\Dashboard as ProfesorDashboard;

Route::middleware(['auth', 'role:profesor'])->group(function () {
    Route::get('/profesor/dashboard', ProfesorDashboard::class)->name('profesor.dashboard');
    Route::get('/profesor/niveles', Niveles::class)->name('niveles');
    Route::get('/grupo/{grupo}', GrupoVista::class)->name('profesor.grupo.vista');
    // aquí luego puedes agregar más rutas del profesor, p.ej. grupos, calificaciones, etc.
});