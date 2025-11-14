<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Models\DocumentoNivel;
use App\Models\DocumentoProfesor;

//Rutas del Administrador
use App\Livewire\Admin\Dashboard as DashboardAdmin;
use App\Livewire\Admin\Permisos\Index as AdminPermisosIndex;
use App\Livewire\Admin\Permisos\Create as AdminPermisosCreate;
use App\Livewire\Admin\Niveles\Index as AdminNivelesIndex;
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
use App\Livewire\Admin\Documentos\Alumnos as AdminDocumentosAlumnos;
use App\Livewire\Admin\Documentos\Profesor as AdminDocumentosProfesor;
use App\Livewire\Admin\Documentos\Constacias as AdminDocumentosCosntancias;
use App\Livewire\Admin\Documentos\Actas as AdminDocmuentosActas;

Route::prefix('admin')->middleware(['role:admin'])->group(function () {
    //Dashboard principal del administrador
    Route::get('/dashboard', DashboardAdmin::class)->name('admin.dashboard');

    //Logica sobre Permisos
    Route::get('/permisos', AdminPermisosIndex::class)->name('admin.permisos.index');
    Route::get('/permisos/{usuario}/edit', AdminPermisosCreate::class)->name('admin.permisos.create');

    //Logica sobre Niveles
    Route::get('/niveles', AdminNivelesIndex::class)->name('admin.niveles.index');

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

    //logica sobre Documentos
    Route::get('/documentos/alumno', AdminDocumentosAlumnos::class)->name('admin.documentos.alumnos');
    Route::get('/documentos/profesor', AdminDocumentosProfesor::class)->name('admin.documentos.profesor');

    //Logica sobre Constancias y Actas
    Route::get('/constancias/{nivelId}', AdminDocumentosCosntancias::class)->name('admin.documentos.constancias');
    Route::get('/actas/{nivelId}', AdminDocmuentosActas::class)->name('admin.documentos.actas');
});

// NUEVA RUTA para documentos de PROFESORES
Route::get('/documentos-profesor/{documento}', function ($documentoId) {
    $documento = DocumentoProfesor::findOrFail($documentoId);

    // Verificar permisos (solo admin y coordinador pueden ver)
    if (!auth()->check() || !(auth()->user()->hasRole('admin') || auth()->user()->hasRole('coordinador'))) {
        abort(403);
    }

    // Verificar que el archivo existe en el disco de expedientes
    if (!Storage::disk('expedientesProfesores')->exists($documento->ruta_doc)) {
        abort(404);
    }

    // Obtener la ruta física del archivo
    $path = Storage::disk('expedientesProfesores')->path($documento->ruta_doc);

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="planeacion.pdf"'
    ]);
})->name('documentos.profesor.ver')->middleware(['auth', 'role:admin']);

// RUTA UNIFICADA para documentos de nivel (constancias y actas)
Route::get('/documentos-nivel/{documento}', function ($documentoId) {
    $documento = DocumentoNivel::findOrFail($documentoId);

    // Verificar permisos
    if (!auth()->check() || !(auth()->user()->hasRole('admin'))) {
        abort(403);
    }

    // Verificar que el tipo de documento es válido
    if (!in_array($documento->tipo_doc, ['constancia', 'acta'])) {
        abort(404, 'Tipo de documento no válido');
    }

    // Verificar que el archivo existe
    if (!Storage::disk('expedientesNiveles')->exists($documento->ruta_doc)) {
        abort(404);
    }

    // Obtener la ruta física del archivo
    $path = Storage::disk('expedientesNiveles')->path($documento->ruta_doc);

    // Generar nombre del archivo según el tipo
    if ($documento->tipo_doc === 'constancia') {
        $fileName = $documento->nombre_original ?? 'constancia_' . $documento->id_documento . '.pdf';
    } else {
        $fileName = $documento->nombre_original ?? 'acta_' . $documento->id_documento . '.pdf';
    }

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $fileName . '"'
    ]);
})->name('documentos.alumno.ver')->middleware(['auth', 'role:admin|coordinador']);