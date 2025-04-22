<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Solicitudes Filtradas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 40px; color: #333; }
        header { text-align: center; margin-bottom: 20px; }
        header img { height: 50px; margin-bottom: 5px; }
        header h1 { font-size: 22px; margin: 0; }
        .filtros { font-size: 12px; color: #555; margin-top: 5px; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:6px 8px; text-align:left; font-size:12px; }
        th { background:#eee; }
        tr:nth-child(even) { background:#f9f9f9; }
    </style>
</head>
<body>

<header>
    <img src="{{ public_path('images/logo-tarija.png') }}" alt="Logo">
    <h1>Solicitudes Filtradas</h1>
    <div class="filtros">
        Desde <strong>{{ $fechaInicio }}</strong> hasta <strong>{{ $fechaFin }}</strong>
        @if($filtroTecnico)
            · Técnico: <strong>{{ \App\Models\User::find($filtroTecnico)->name }}</strong>
        @endif
        @if($filtroEstado)
            · Estado: <strong>{{ ucfirst($filtroEstado) }}</strong>
        @endif
        @if($filtroPrioridad)
            · Prioridad: <strong>{{ ucfirst($filtroPrioridad) }}</strong>
        @endif
    </div>
</header>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Solicitante</th>
            <th>Técnico</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Prioridad</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @forelse($solicitudes as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->solicitanteUser->name }}</td>
                <td>{{ optional($s->tecnicoUser)->name ?? '—' }}</td>
                <td>{{ Str::limit($s->descripcion, 40) }}</td>
                <td>{{ ucfirst($s->estado) }}</td>
                <td>{{ ucfirst($s->prioridad) }}</td>
                <td>{{ $s->created_at->format('Y-m-d') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align:center;">No se encontraron solicitudes con esos filtros.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<footer style="text-align:center; margin-top:30px; font-size:11px; color:#666;">
    Reporte generado por Help‑Desk · {{ now()->format('Y‑m‑d H:i') }}
</footer>

</body>
</html>
