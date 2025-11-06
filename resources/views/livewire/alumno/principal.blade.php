<div>

    <div class="bg-white shadow rounded-lg p-6">

        <h1>BIENVENIDO</h1>
        <h1>{{ $info_alumno->nombre }} {{ $info_alumno->ap_paterno }} {{ $info_alumno->ap_materno }}</h1>
        <h5>TU ID ES: {{ $info_alumno->id_alumno }}</h5>
        <h5>EL ID DE TU NIVEL ES: {{ $info_alumno->nivel }}</h5>


        @if ($info_alumno->nivel)
            <table>
                <tr>
                    <th>GRUPO</th>
                    <th>NIVEL</th>
                    <th>MODALIDAD</th>
                    <th>DOCENTE</th>
                    <th>HORARIO</th>
                    <th>PERIODO</th>
                    <th>AULA</th>
                </tr>
                <tr>

                    <td>{{ $info_alumno->nivel->nombre_grupo }}</td>
                    <td>{{ $info_alumno->nivel->nivel }}</td>
                    <td>{{ $info_alumno->nivel->modalidad->tipo_modalidad }}</td>
                    <td>{{ $info_alumno->nivel->profesor->nombre }} {{ $info_alumno->nivel->profesor->ap_materno }}</td>
                    <td>{{ $info_alumno->nivel->horario }}</td>
                    <td>{{ $info_alumno->nivel->periodo->periodo }}</td>
                    <td>{{ $info_alumno->nivel->aula }}</td>
                </tr>
            </table>
            @if ($info_alumno->notas)
                <table>
                    <tr>
                        <th colspan="4">CALIFICACIONES DEL PARCIAL</th>
                    </tr>
                    <tr>
                        <td>PARCIAL 1</td>
                        <td>PARCIAL 2</td>
                        <td>PARCIAL 3</td>
                        <td>TOTAL</td>
                    </tr>
                    <tr>
                        <td>{{ $nota->nota_parcial_1 }}</td>
                        <td>{{ $nota->nota_parcial_2 }}</td>
                        <td>{{ $nota->nota_parcial_3 }}</td>
                        <td>{{ number_format($nota->promedio, 2) }}</td>
                    </tr>
                </table>
            @endif
        @else
            <h1>NO ESTAS INSCRITO EN ALGÚN NIVEL</h1>
        @endif




    </div>
</div>
