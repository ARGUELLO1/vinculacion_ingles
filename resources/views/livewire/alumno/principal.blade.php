<div>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight text-center lg:text-left">
            {{ __('INFORMACIÓN DEL NIVEL') }}
        </h2>
    </x-slot>
    <div class="flex flex-col bg-white font-semibold shadow rounded-lg m-4 lg:p-6 lg:m-3">

        @if ($info_alumno->nombre && $info_alumno->ap_paterno && $info_alumno->ap_materno)
            <h1 class="text-blue-800 text-center text-3xl p-4 lg:text-5xl lg:mb-3">BIENVENIDO</h1>
            <h1 class="text-2xl text-center mb-8 lg:text-5xl ">{{ $info_alumno->nombre }} {{ $info_alumno->ap_paterno }}
                {{ $info_alumno->ap_materno }}</h1>

            @if ($info_alumno->nivel)
                <div class="mb-2 overflow-x-auto">
                    <table
                        class="w-full text-center border border-separate rounded-lg table-auto border-gray-400 bg-gray-100 text-md">
                        <tr>
                            <th class="bg-blue-800 text-white" colspan="7">INSCRITO EN:</th>
                        </tr>
                        <tr>
                            <td class="">GRUPO</th>
                            <td class="">NIVEL</th>
                            <td class="">MODALIDAD</th>
                            <td class="">DOCENTE</th>
                            <td class="">HORARIO</th>
                            <td class="">PERIODO</th>
                            <td class="">AULA</th>
                        </tr>
                        <tr class="text-sm lg:text-lg ">

                            <td class="bg-white border border-white rounded-lg">{{ $info_alumno->nivel->nombre_grupo }}
                            </td>
                            <td class="bg-white border border-white rounded-lg">{{ $info_alumno->nivel->nivel }}</td>
                            <td class="bg-white border border-white rounded-lg">
                                {{ $info_alumno->nivel->modalidad->tipo_modalidad }}</td>
                            <td class="bg-white border border-white rounded-lg">
                                {{ $info_alumno->nivel->profesor->nombre }}
                                {{ $info_alumno->nivel->profesor->ap_materno }}
                            </td>
                            <td class="bg-white border border-white rounded-lg">{{ $info_alumno->nivel->horario }}</td>
                            <td class="bg-white border border-white rounded-lg">
                                {{ $info_alumno->nivel->periodo->periodo }}
                            </td>
                            <td class="bg-white border border-white rounded-lg">
                                {!! strlen($info_alumno->nivel->aula) > 8
                                    ? "<a target='_blank' href='" . $info_alumno->nivel->aula . "' class='text-blue-800 underline'>Enlace</a>"
                                    : e($info_alumno->nivel->aula) !!}
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="mb-2">
                    <table class="w-full text-center border border-separate rounded-lg table-auto border-gray-400 bg-gray-100 text-md">
                        <tr>
                            <th class="bg-blue-800 text-white" colspan="2">DOCUMENTOS SUBIDOS AL SISTEMA</th>
                        </tr>
                        <tr>
                            <td>DOCUMENTO</th>
                            <td>OPCIÓN</th>
                        </tr>
                        @foreach ($documentos->documentosExpedientes as $documento)
                            <tr>
                                <td class="bg-white border border-white rounded-lg">{{ $documento->tipo_doc }}</td>
                                <td class="bg-white border border-white rounded-lg hover:bg-blue-800 hover:text-white"><a href="{{ route('Alumno.documento.ver', [
                                    'nivel' => $info_alumno->nivel_id,
                                    'alumno' => $info_alumno->id_alumno,
                                    'archivo' => $documento->tipo_doc,
                                ]) }}"
                                        target="_blank">VER</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                @if ($info_alumno->notas && $nota->nota_parcial_1 != 0)
                    <div class="mb-2">
                        <table
                            class="w-full text-center border border-separate rounded-lg table-auto border-gray-400 bg-gray-100 text-md">
                            <thead>
                                <tr>
                                    <th class="bg-blue-800 text-white" colspan="4">CALIFICACIONES DEL PARCIAL</th>
                                </tr>
                                <tr>
                                    <td>PARCIAL 1</td>
                                    <td>PARCIAL 2</td>
                                    <td>PARCIAL 3</td>
                                    <td>TOTAL</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="bg-white border border-white rounded-lg">
                                        {{ $nota->nota_parcial_1 == 0 ? 'PENDIENTE' : $nota->nota_parcial_1 }}</td>
                                    <td class="bg-white border border-white rounded-lg">
                                        {{ $nota->nota_parcial_2 == 0 ? 'PENDIENTE' : $nota->nota_parcial_2 }}</td>
                                    <td class="bg-white border border-white rounded-lg">
                                        {{ $nota->nota_parcial_3 == 0 ? 'PENDIENTE' : $nota->nota_parcial_3 }}</td>
                                    <td class="bg-white border border-white rounded-lg">
                                        {{ number_format($nota->promedio, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            @else
                <h1 class="text-lg text-center p-3 lg:m-9 lg:text-3xl">NO ESTAS INSCRITO EN ALGÚN NIVEL</h1>
            @endif
        @else
            <h1>¡ACTUALIZA TUS DATOS!</h1>
        @endif



    </div>
</div>
