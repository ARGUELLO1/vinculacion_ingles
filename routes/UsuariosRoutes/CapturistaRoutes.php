<?php

use Illuminate\Support\Facades\Route;

//Rutas del Capturista
use App\Livewire\Capturista\Dashboard as DashboardCapturista;
use App\Livewire\Capturista\Niveles\Index as CapturistaNivelesIndex;
use App\Livewire\Capturista\Niveles\Create as CapturistaNivelesCreate;
use App\Livewire\Capturista\Niveles\Update as CapturistaNivelesUpdate;

Route::prefix('capturista')->middleware(['role:capturista'])->group(function () {
    //Dashboard principal del capturista
    Route::get('/dashboard', DashboardCapturista::class)->name('capturista.dashboard');

    //Logica sobre Niveles
    Route::get('/niveles', CapturistaNivelesIndex::class)->name('capturista.niveles.index');
    Route::get('/niveles/create', CapturistaNivelesCreate::class)->name('capturista.niveles.create');
    Route::get('/niveles/{nivel}/edit', CapturistaNivelesUpdate::class)->name('capturista.niveles.edit');
});