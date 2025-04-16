<?php

namespace App\Exports;

use App\Models\Solicitud;
use App\Models\User;
use App\Models\TipoProblema;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EstadisticasSolicitudExport implements FromView
{
    protected $inicio;
    protected $fin;

    public function __construct($inicio, $fin)
    {
        $this->inicio = $inicio;
        $this->fin = $fin;
    }

    public function view(): View
    {
        $inicio = $this->inicio;
        $fin = $this->fin;

        $tecnicos = User::role('tecnico')->get();
        $estadisticasTecnicos = [];
        $estadisticasAreas = [];

        foreach ($tecnicos as $tecnico) {
            $atendidas = Solicitud::where('tecnico', $tecnico->id)
                ->whereBetween('created_at', [$inicio, $fin])
                ->count();

            $concluidas = Solicitud::where('tecnico', $tecnico->id)
                ->where('estado', 'finalizada')
                ->whereBetween('created_at', [$inicio, $fin])
                ->count();

            $estadisticasTecnicos[] = [
                'nombre' => $tecnico->name,
                'area' => $tecnico->area,
                'atendidas' => $atendidas,
                'concluidas' => $concluidas,
            ];

            if (!isset($estadisticasAreas[$tecnico->area])) {
                $estadisticasAreas[$tecnico->area] = 0;
            }
            $estadisticasAreas[$tecnico->area] += $atendidas;
        }

        $estadisticasEstados = Solicitud::selectRaw('estado, COUNT(*) as total')
            ->whereBetween('created_at', [$inicio, $fin])
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $tiposProblemas = TipoProblema::withCount(['solicitudes' => function ($query) use ($inicio, $fin) {
            $query->whereBetween('created_at', [$inicio, $fin]);
        }])->orderByDesc('solicitudes_count')->take(10)->get();

        $sinTecnico = Solicitud::whereNull('tecnico')
            ->whereBetween('created_at', [$inicio, $fin])
            ->count();

        $tiempoPromedio = Solicitud::where('estado', 'finalizada')
            ->whereBetween('created_at', [$inicio, $fin])
            ->get()
            ->map(function ($s) {
                return $s->created_at->diffInDays($s->updated_at);
            })->avg();

        return view('admin.solicitud.reporte.estadisticas_excel', [
            'estadisticasTecnicos' => $estadisticasTecnicos,
            'estadisticasEstados' => $estadisticasEstados,
            'estadisticasAreas' => $estadisticasAreas,
            'tiposProblemas' => $tiposProblemas,
            'sinTecnico' => $sinTecnico,
            'tiempoPromedio' => $tiempoPromedio,
            'fechaInicio' => $inicio,
            'fechaFin' => $fin,
        ]);
    }
}
