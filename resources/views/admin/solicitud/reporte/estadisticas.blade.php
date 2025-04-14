<!DOCTYPE html>
<html>
<head>
    <title>Estadísticas de Solicitudes</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Estadísticas de Solicitudes</h1>
    <p>Desde: <strong>{{ $fechaInicio }}</strong> hasta <strong>{{ $fechaFin }}</strong></p>

    <h2>Por Técnico</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Área</th>
                <th>Solicitudes Atendidas</th>
                <th>Solicitudes Concluidas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estadisticasTecnicos as $tecnico)
                <tr>
                    <td>{{ $tecnico['nombre'] }}</td>
                    <td>{{ $tecnico['area'] }}</td>
                    <td>{{ $tecnico['atendidas'] }}</td>
                    <td>{{ $tecnico['concluidas'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Resumen General por Estado</h2>
    <table>
        <thead>
            <tr>
                <th>Estado</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estadisticasEstados as $estado => $total)
                <tr>
                    <td>{{ ucfirst($estado) }}</td>
                    <td>{{ $total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
