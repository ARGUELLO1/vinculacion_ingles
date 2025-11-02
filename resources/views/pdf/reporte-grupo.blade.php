<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reporte de Grupo</title>
    <style>
        /* Estilos CSS para el PDF. dompdf funciona mejor con estilos en línea o en <style> */
        body {

            font-family: 'Helvetica', 'Arial', sans-serif;

            font-size: 14px;

            color: #333;

            line-height: 1.6;
        }

        .container {

            width: 95%;

            margin: 0 auto;

        }

        .header {

            text-align: center;

            border-bottom: 2px solid #1b396b;

            /* Color de tu nav */

            padding-bottom: 10px;

        }

        .header h1 {

            color: #1b396b;

            margin: 0;

            padding: 0;

        }

        .content {

            margin-top: 20px;

        }

        table {

            width: 100%;

            border-collapse: collapse;

            margin-bottom: 20px;
        }

        th,

        td {

            border: 1px solid #ddd;

            padding: 8px;

            text-align: left;
        }

        th {

            background-color: #f2f2f2;
            font-weight: bold;
            color: #1b396b;
            width: 30%;
        }

        .analysis {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .chart-container {
            text-align: center;
            margin-top: 25px;
        }

        .chart-container img {
            max-width: 450px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Reporte de Desempeño del Grupo</h1>
    </div>
    <div class="container content">
        <h3>Información General del Grupo</h3>
        <table>
            <tr>
                <th>Nombre del Grupo:</th>
                <td>{{ $grupo->nombre_grupo }}</td>
            </tr>
            <tr>
                <th>Profesor a Cargo:</th>
                <td>{{ $profesor->nombre_completo ?? 'No asignado' }}</td>
            </tr>
            <tr>
                <th>Horario:</th>
                <td>{{ $grupo->horario }}</td>
            </tr>
            <tr>
                <th>Aula:</th>
                <td>{{ $grupo->aula }}</td>
            </tr>
            <tr>
                <th>Parcial Evaluado:</th>
                <td>{{ $parcialNumero }}</td>
            </tr>
        </table>
        <h3>Análisis de Desempeño</h3>
        <div class="analysis">
            <p>
                El grupo <strong>{{ $grupo->nombre_grupo }}</strong>, compuesto por <strong>{{ $totalAlumnos }}</strong> alumno(s),
                presenta los siguientes resultados para el Parcial {{ $parcialNumero }}:
            </p>
            <ul>
                <li><strong>Total de Alumnos Aprobados:</strong> {{ $totalAlumnos - $totalReprobados }} ({{ number_format($porcentajeAprobados, 2) }}%)</li>
                <li><strong>Total de Alumnos Reprobados (N/A):</strong> {{ $totalReprobados }} ({{ number_format($porcentajeReprobados, 2) }}%)</li>
            </ul>
        </div>
        <div class="chart-container">
            <h4>Distribución de Aprobados y Reprobados</h4>
            @if ($chartBase64)
            <img src="data:image/png;base64,{{ $chartBase64 }}" alt="Gráfica de Pastel">
            @else
            <p style="color:red;">No se pudo generar la gráfica.</p>
            @endif
        </div>
    </div>
</body>

</html>