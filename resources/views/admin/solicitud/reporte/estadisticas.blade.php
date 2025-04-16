<!DOCTYPE html>
<html>
<head>
    <title>Estadísticas de Solicitudes</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { margin-top: 30px; }
    </style>
</head>
<body>
    <h1>Estadísticas de Solicitudes</h1>
    <p>Desde: <strong>{{ $fechaInicio }}</strong> hasta <strong>{{ $fechaFin }}</strong></p>

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
    <p><strong>Total:</strong> {{ $sinTecnico }}</p>

    <h2>⏱ Tiempo Promedio de Atención</h2>
    <p><strong>{{ round($tiempoPromedio, 2) ?? 0 }} días</strong></p>
</body>
</html>
