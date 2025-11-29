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
            <h1 class="text-red-500 my-3 lg:text-xl">*Se muestran las constancias que no han sido asignadas a su alumno
                correspondiente por lo que no se guardaran a largo plazo.</h1>
            <div class="overflow-x-auto">
                <table
                    class="overflow-x-auto w-full text-center border border-separate rounded-lg table-auto border-gray-400 bg-gray-100 text-md">
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
                                    'archivo' => base64_encode($archivo),
                                ]) }}"
                                    target="_blank">
                                    VER
                                </a>
                            </td>
                        </tr>
                    @endforeach

                </table>
            </div>
    </div>
    @endif
</div>
