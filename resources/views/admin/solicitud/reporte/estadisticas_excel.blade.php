<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 20px;
        }

        h2 {
            margin-top: 30px;
            font-size: 16px;
            color: #1f2937;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #e5e7eb;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .highlight {
            font-weight: bold;
            font-size: 16px;
            color: #111827;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .period {
            text-align: center;
            font-size: 14px;
            margin-bottom: 30px;
        }

        footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 40px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="title">📊 Estadísticas de Solicitudes</div>
    <div class="period">Desde: <strong>{{ $fechaInicio }}</strong> hasta <strong>{{ $fechaFin }}</strong></div>

    <h2>📋 Por Técnico</h2>
    <table>
        <thead>
            <tr><th>Nombre</th><th>Área</th><th>Atendidas</th><th>Concluidas</th></tr>
        </thead>
        <tbody>
            @foreach($estadisticasTecnicos as $item)
                <tr>
                    <td>{{ $item['nombre'] }}</td>
                    <td>{{ $item['area'] }}</td>
                    <td>{{ $item['atendidas'] }}</td>
                    <td>{{ $item['concluidas'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>🏢 Atenciones por Área</h2>
    <table>
        <thead><tr><th>Área</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($atencionesPorArea as $area => $total)
                <tr><td>{{ $area }}</td><td>{{ $total }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2>📌 Resumen por Estado</h2>
    <table>
        <thead><tr><th>Estado</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($estadisticasEstados as $estado => $total)
                <tr><td>{{ ucfirst($estado) }}</td><td>{{ $total }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2>⚠️ Tipos de Problemas con Mayor Incidencia</h2>
    <table>
        <thead><tr><th>Tipo</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($tiposProblemas as $tipo)
                <tr><td>{{ $tipo->nombre }}</td><td>{{ $tipo->solicitudes_count }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2>⏳ Solicitudes sin Técnico Asignado</h2>
    <p class="highlight">Total: {{ $sinTecnico }}</p>

    <h2>⏱ Tiempo Promedio de Atención</h2>
    <p class="highlight">{{ round($tiempoPromedio, 2) ?? 0 }} días</p>

    <footer>
        Reporte generado automáticamente por el sistema Help-Desk
    </footer>

</body>
</html>
