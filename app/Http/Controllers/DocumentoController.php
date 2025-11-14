<?php

namespace App\Http\Controllers;

use App\Models\DocumentoExpediente;
use App\Models\DocumentoNivel;
use App\Models\Expediente;
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

    public function ver($id_nivel, $id_alumno, $archivo)
    {

        $expediente = Expediente::where('alumno_id', $id_alumno)->where('nivel_id', $id_nivel)->first();
        $ruta = DocumentoExpediente::where('id_expediente',$expediente->id_expediente)->where('tipo_doc',$archivo)->first();
        if (!Storage::disk('local')->exists($ruta->ruta_doc)) {
            abort(404, 'El documento no existe.');
        }

        // Obtenemos el archivo desde storage/app/private (disk local)
        $file = Storage::disk('local')->get($ruta->ruta_doc);
        $mime = Storage::mimeType($ruta->ruta_doc);

        return response($file, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . basename($ruta->ruta_doc) . '"');
    }
}
