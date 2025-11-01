<div>
    <h1>BIENVENIDO</h1>
    <h1>{{ $info_alumno->nombre }} {{ $info_alumno->ap_paterno }} {{ $info_alumno->ap_materno }}</h1>
    <h5>TU ID ES: {{ $info_alumno->id_alumno }}</h5>
    <h5>EL ID DE TU NIVEL ES: {{ $info_alumno->nivel }}</h5>

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
            <td>{{ $info_alumno->nivel->profesor->nombre }} {{ $info_alumno->nivel->profesor->ap_materno }}</td>
            <td>{{ $info_alumno->nivel->horario }}</td>
            <td>{{ $info_alumno->nivel->periodo->periodo }}</td>
            <td>{{ $info_alumno->nivel->aula }}</td>
        </tr>

    </table>
</div>
