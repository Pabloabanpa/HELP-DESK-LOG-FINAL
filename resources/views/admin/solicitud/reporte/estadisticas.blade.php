<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estadísticas de Solicitudes</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 40px;
            color: #333;
        }

        header {
            text-align: center;
            margin-bottom: 40px;
        }

        header img {
            height: 60px;
            margin-bottom: 10px;
        }

        header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .periodo {
            font-size: 14px;
            color: #666;
        }

        h2 {
            margin-top: 30px;
            font-size: 18px;
            color: #1f2937;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
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
    </style>
</head>
<body>

    <header>
        {{-- IMPORTANTE: Asegúrate que esta imagen exista en public/images/logo-tarija.png --}}
        <img src="{{ public_path('images/logo-tarija.png') }}" alt="Logo">
        <h1>Estadísticas de Solicitudes</h1>
        <div class="periodo">Desde: <strong>{{ $fechaInicio }}</strong> hasta <strong>{{ $fechaFin }}</strong></div>
    </header>

    <h2>Por Técnico</h2>
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

    <h2>Atenciones por Área</h2>
    <table>
        <thead><tr><th>Área</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($atencionesPorArea as $area => $total)
                <tr><td>{{ $area }}</td><td>{{ $total }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2>Resumen por Estado</h2>
    <table>
        <thead><tr><th>Estado</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($estadisticasEstados as $estado => $total)
                <tr><td>{{ ucfirst($estado) }}</td><td>{{ $total }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2>Tipos de Problemas con Mayor Incidencia</h2>
    <table>
        <thead><tr><th>Tipo</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($tiposProblemas as $tipo)
                <tr><td>{{ $tipo->nombre }}</td><td>{{ $tipo->solicitudes_count }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2>Solicitudes sin Técnico Asignado</h2>
    <p class="highlight">Total: {{ $sinTecnico }}</p>

    <h2>Tiempo Promedio de Atención</h2>
    <p class="highlight">{{ round($tiempoPromedio, 2) ?? 0 }} días</p>
    
    <footer>
        Reporte generado automáticamente por el sistema Help-Desk
    </footer>
</body>
</html>
