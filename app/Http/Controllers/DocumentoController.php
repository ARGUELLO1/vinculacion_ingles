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
    public function descargar($archivo)
    {

        //Obtener el archivo dentro de la carpeta
        $ruta = base64_decode($archivo);
        switch ($ruta) {
            //Documento de alumno
            case (Storage::exists($ruta)):
                //Obtener la ruta absoluta en storage
                $rutaFisica = Storage::path($ruta);
                // Obtener nombre del archivo para el navegador
                $nombre = basename($rutaFisica);
                // Mostrarlo
                return response()->file($rutaFisica, [
                    'Content-Disposition' => 'inline; filename="' . $nombre . '"'
                ]);
                break;
            //Constancia
            case (Storage::disk('expedientesNiveles')->exists($ruta)):
                $rutaFisica = Storage::disk('expedientesNiveles')->path($ruta);
                // Obtener nombre del archivo para el navegador
                $nombre = basename($rutaFisica);

                // Mostrarlo
                return response()->file($rutaFisica, [
                    'Content-Disposition' => 'inline; filename="' . $nombre . '"'
                ]);
                break;
            //Si no encuentra documento
            default:
                abort(404, "Archivo no encontrado.");
                break;
        }
    }

    public function ver($id_nivel, $id_alumno, $archivo)
    {

        $expediente = Expediente::where('alumno_id', $id_alumno)->where('nivel_id', $id_nivel)->first();
        $ruta = DocumentoExpediente::where('id_expediente', $expediente->id_expediente)->where('tipo_doc', $archivo)->first();
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

    public function ver_constancia($carpeta)
    {

        //Obtener el archivo dentro de la carpeta
        $archivos = Storage::files(base64_decode($carpeta));

        // Validar que existan archivos
        if (empty($archivos)) {
            abort(404, "La carpeta existe pero no contiene archivos.");
        }

        //Como solo hay un documento tomamos el primero
        $archivo = $archivos[0];
        $nombre = basename($archivo);

        // Obtener ruta absoluta
        $ruta = Storage::path($archivo);

        //Mostrar el archivo en el navegador
        return response()->file($ruta, [
            'Content-Disposition' => 'inline; filename="' . $nombre . '"'
        ]);
    }
}
