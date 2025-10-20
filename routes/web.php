<?php
// routes/web.php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    switch ($user->rol_id) {
        case 1:
            return view('admin.dashboard');
        case 2:
            return view('capturista.dashboard');
        case 3:
            return view('profesor.dashboard');
        case 4:
            return view('alumno.dashboard');
        default:
            return view('auth.login');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas públicas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// RUTAS POR ROLES usando el middleware de roles

// Solo Admin (rol 1)
Route::middleware(['auth', 'rol:1'])->prefix('admin')->group(function () {
    Route::get('/panel', function () {
        return view('admin.dashboard');
    })->name('admin.panel');
});


// Solo Capturistas (rol 2)
Route::middleware(['auth', 'rol:2'])->prefix('capturista')->group(function () {
    Route::get('/panel', function () {
        return view('capturista.dashboard');
    })->name('capturista.panel');
});

// Solo Profesores (rol 3)
Route::middleware(['auth', 'rol:3'])->prefix('profesor')->group(function () {
    Route::get('/panel', function () {
        return view('profesor.dashboard');
    })->name('profesor.panel');
});

// Solo Alumnos (rol 4)
Route::middleware(['auth', 'rol:4'])->prefix('alumno')->group(function () {
    Route::get('/panel', function () {
        return view('alumno.dashboard');
    })->name('alumno.panel');
});

// Múltiples roles (Admin y Capturista)
/*Route::middleware(['auth', 'role:1,2'])->prefix('staff')->group(function () {
    Route::get('/panel', function () {
        return view('staff.dashboard');
    })->name('staff.panel');
});*/

require __DIR__ . '/auth.php';
