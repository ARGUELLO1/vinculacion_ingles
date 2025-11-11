<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight text-center lg:text-left">
            {{ __('CONSTANCIAS') }}
        </h2>
    </x-slot>
    <div class="bg-white shadow rounded-lg p-6 m-3">
        @if (!$carta_documento)
            <h1>AQUI SE MOSTRARAN LAS CARTAS DE TERMINO CUANDO SE SUBAN AL SISTEMA</h1>
        @else
            <table
                class="w-full text-center border border-separate rounded-lg table-auto border-gray-400 bg-gray-100 text-md">
                <tr>
                    <th class="bg-blue-800 text-white" colspan="2">CONSTANCIAS DISPONIBLES</th>
                </tr>
                <tr>
                    <td>NOMBRE DEL DOCUMENTO</th>
                    <td>OPCIÓN</th>
                </tr>

                @foreach ($archivos as $archivo)
                    <tr>
                        <td class="bg-white border border-white rounded-lg">
                            <p>{{ basename($archivo) }}</p>
                        </td>
                        <td class="bg-white border border-white rounded-lg hover:bg-blue-800 hover:text-white">
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
