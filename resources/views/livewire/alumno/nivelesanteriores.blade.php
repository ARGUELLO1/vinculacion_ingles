<div>

    <div class="bg-white shadow rounded-lg p-6">
        @if ($expediente->isEmpty())
            <h1>TODAVÍA NO HAY NIVELES PARA MOSTRAR</h1>
        @else
            <table>
                <tr>
                    <th colspan="4">INFORMACIÓN DEL NIVEL</th>
                    <th colspan="4">CALIFICACIONES</th>
                </tr>
                <tr>
                    <td>NIVEL</td>
                    <td>GRUPO</td>
                    <td>PERIODO</td>
                    <td>MAESTRO</td>
                    <td>PARCIAL 1</td>
                    <td>PARCIAL 2</td>
                    <td>PARCIAL 3</td>
                    <td>FINAL</td>
                </tr>

                @foreach ($expediente as $exp)
                    <tr>
                        <td>{{ $exp->nivel }}</td>
                        <td>{{ $exp->grupo }}</td>
                        <td>{{ $exp->periodo }}</td>
                        <td>{{ $exp->maestro }}</td>
                        <td>{{ $exp->nota_parcial_1 }}</td>
                        <td>{{ $exp->nota_parcial_2 }}</td>
                        <td>{{ $exp->nota_parcial_3 }}</td>
                        <td>{{ $exp->nota_final }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>

    {{--
    COSAS POR HACER
    ->Programar la restricción para que no se puedan duplicar las lineas de captura
    ->Darle diseño a todo XD
    --}}
</div>
