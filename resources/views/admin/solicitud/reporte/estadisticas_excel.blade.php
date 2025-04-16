<table>
    <thead><tr><th colspan="4">📋 Por Técnico</th></tr></thead>
    <thead><tr><th>Nombre</th><th>Área</th><th>Atendidas</th><th>Concluidas</th></tr></thead>
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

<table>
    <thead><tr><th colspan="2">🏢 Atenciones por Área</th></tr></thead>
    <thead><tr><th>Área</th><th>Total</th></tr></thead>
    <tbody>
        @foreach($atencionesPorArea as $area => $total)
            <tr><td>{{ $area }}</td><td>{{ $total }}</td></tr>
        @endforeach
    </tbody>
</table>

<table>
    <thead><tr><th colspan="2">📌 Resumen por Estado</th></tr></thead>
    <thead><tr><th>Estado</th><th>Total</th></tr></thead>
    <tbody>
        @foreach($estadisticasEstados as $estado => $total)
            <tr><td>{{ ucfirst($estado) }}</td><td>{{ $total }}</td></tr>
        @endforeach
    </tbody>
</table>

<table>
    <thead><tr><th colspan="2">⚠️ Tipos de Problemas con Mayor Incidencia</th></tr></thead>
    <thead><tr><th>Tipo</th><th>Total</th></tr></thead>
    <tbody>
        @foreach($tiposProblemas as $tipo)
            <tr><td>{{ $tipo->nombre }}</td><td>{{ $tipo->solicitudes_count }}</td></tr>
        @endforeach
    </tbody>
</table>

<table>
    <thead><tr><th colspan="2">⏳ Solicitudes sin Técnico</th></tr></thead>
    <tbody>
        <tr><td>Total</td><td>{{ $sinTecnico }}</td></tr>
    </tbody>
</table>

<table>
    <thead><tr><th colspan="2">⏱ Tiempo Promedio de Atención</th></tr></thead>
    <tbody>
        <tr><td>Días</td><td>{{ round($tiempoPromedio, 2) ?? 0 }}</td></tr>
    </tbody>
</table>
