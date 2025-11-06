<?php

namespace App\Http\Controllers;

use App\Models\DocumentoNivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function descargar($id_grupo, $grupo, $archivo)
    {
        $ruta = 'documentos_nivel/' . $id_grupo . '_' . $grupo . '/' . $archivo;

        if (!Storage::disk('local')->exists($ruta)) {
            abort(404, 'El documento no existe.');
        }

        // Obtenemos el archivo desde storage/app/private (disk local)
        $file = Storage::disk('local')->get($ruta);
        $mime = Storage::mimeType($ruta);

        return response($file, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . basename($ruta) . '"');
    }


    public function descargarold($id)
    {

        $documento = DocumentoNivel::findOrFail($id);

        // Verificamos si existe el archivo
        if (!Storage::disk('local')->exists($documento->ruta_doc)) {
            abort(404, 'El documento no existe.');
        }

        // Obtenemos el archivo desde storage/app/private (disk local)
        $file = Storage::disk('local')->get($documento->ruta_doc);
        $mime = Storage::mimeType($documento->ruta_doc);


        return response($file, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . basename($documento->ruta_doc) . '"');
    }
}
