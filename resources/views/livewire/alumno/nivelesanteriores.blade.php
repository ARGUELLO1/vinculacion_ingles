<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight text-center lg:text-left">
            {{ __('NIVELES QUE HAS CURSADO') }}
        </h2>
    </x-slot>

    <div class="bg-white shadow rounded-lg m-4 lg:p-6 lg:m-3 ">

        <h1 class="text-blue-800 my-3 lg:text-xl">*Aqui se muestra el historial de los niveles que has cursado.</h1>

        @if ($tabla)
            <div class="overflow-x-auto sm:text-sm">
                <table
                    class="lg:text-lg w-full text-center border border-separate rounded-lg table-auto border-gray-400 bg-gray-100 font-bold">
                    <tr>
                        <th class="bg-blue-800 text-white" colspan="6">INFORMACIÓN DEL NIVEL</th>
                        <th class="bg-blue-800 text-white" colspan="4">CALIFICACIONES</th>
                    </tr>
                    <tr>
                        <td>NIVEL</td>
                        <td>GRUPO</td>
                        <td>PERIODO</td>
                        <td>MAESTRO</td>
                        <td>DOCUMENTOS</td>
                        <td>CONSTANCIA</td>
                        <td>PARCIAL 1</td>
                        <td>PARCIAL 2</td>
                        <td>PARCIAL 3</td>
                        <td>FINAL</td>

                    </tr>

                    @foreach ($expediente as $exp)
                        @if ($exp->finalizado == 1)
                            <tr wire:key="expediente-{{ $exp->id_expediente }}">
                                <td class="bg-white border border-white rounded-lg">{{ $exp->nivel_texto }}</td>
                                <td class="bg-white border border-white rounded-lg">{{ $exp->grupo_texto }}</td>
                                <td class="bg-white border border-white rounded-lg">{{ $exp->periodo_texto }}</td>
                                <td class="bg-white border border-white rounded-lg">{{ $exp->maestro_texto }}</td>
                                <td class="bg-white border border-white rounded-lg">
                                    <x-primary-button wire:key="expediente-{{ $exp->id_expediente }}"
                                        wire:click="ver({{ $exp->id_expediente }})">VER</x-primary-button>
                                </td>

                                @if (empty($exp->ruta_const))
                                    <td class="bg-white border border-white rounded-lg">X</td>
                                @else
                                    <td class="bg-white border border-white rounded-lg">
                                        <a href="{{ route('Alumno.documento.descargar', [
                                            'archivo' => base64_encode($exp->ruta_const),
                                        ]) }}"
                                            target="_blank"><x-primary-button>DESCARGAR</x-primary-button></a>
                                    </td>
                                @endif

                                <td class="bg-white border border-white rounded-lg">{{ $exp->nota_parcial_1_texto }}
                                </td>
                                <td class="bg-white border border-white rounded-lg">{{ $exp->nota_parcial_2_texto }}
                                </td>
                                <td class="bg-white border border-white rounded-lg">{{ $exp->nota_parcial_3_texto }}
                                </td>
                                <td class="bg-white border border-white rounded-lg">{{ $exp->nota_final_texto }}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
        @else
            <h1 class="text-black font-bold my-3 lg:text-xl">No hay niveles que mostrar</h1>
        @endif
    </div>

    {{-- MODAL --}}
    @if ($open)
        <div class="bg-gray-950 bg-opacity-75 fixed inset-0 flex items-center justify-center">
            <div class="bg-white shadow rounded-lg m-4 lg:p-6 lg:m-3 w-full lg:text-lg font-bold">
                <table
                    class="w-full text-center border border-separate rounded-lg table-auto border-gray-400 bg-gray-100 text-md">
                    <tr>
                        <th class="bg-blue-800 text-white" colspan="2">DOCUMENTOS SUBIDOS AL SISTEMA</th>
                    </tr>
                    <tr>
                        <td>DOCUMENTO</td>
                        <td>OPCIÓN</td>
                    </tr>
                    @foreach ($documentos_nivel as $documento)
                        <tr>
                            <td class="bg-white border border-white rounded-lg">{{ $documento->tipo_doc }}</td>
                            <td class="bg-white border border-white rounded-lg hover:bg-blue-800 hover:text-white">
                                <a href="{{ route('Alumno.documento.descargar', [
                                    'archivo' => base64_encode($documento->ruta_doc),
                                ]) }}"
                                    target="_blank">
                                    VER
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <x-danger-button wire:click="close()" class="my-3 w-full flex justify-center">CERRAR</x-danger-button>
            </div>
        </div>
    @endif

</div>
