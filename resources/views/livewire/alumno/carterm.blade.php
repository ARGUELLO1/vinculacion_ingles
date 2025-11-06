<div>
    <div class="bg-white shadow rounded-lg p-6">
        @if (!$carta_documento->ruta_doc)
            <h1>AQUI SE MOSTRARAN LAS CARTAS DE TERMINO CUANDO SE SUBAN AL SISTEMA</h1>
        @else
            <h1>CARTAS DISPONIBLES</h1>
            <table>
                <tr>
                    <th>NOMBRE DEL DOCUMENTO</th>
                    <th colspan="2">Acciones</th>
                </tr>

                @foreach ($archivos as $archivo)
                    <tr>
                        <td>
                            <p>{{ basename($archivo) }}</p>
                        </td>
                        <td>
                            <a href="{{ route('Alumno.documento.descargar', [
                                'id_grupo' => $datos_alumno->nivel->id_nivel,
                                'grupo' => $datos_alumno->nivel->nombre_grupo,
                                'archivo' => basename($archivo),
                            ]) }}"
                                target="_blank">
                                VER
                            </a>
                        </td>
                    </tr>
                @endforeach

            </table>
    </div>
    @endif
</div>
