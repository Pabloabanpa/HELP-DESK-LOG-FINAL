<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EstadisticasSolicitudExport;
use App\Models\TipoProblema;


class SolicitudController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.solicitud.index')->only(['index']);
        $this->middleware('can:admin.solicitud.create')->only(['create', 'store']);
        $this->middleware('can:admin.solicitud.edit')->only(['edit', 'update']);
        $this->middleware('can:admin.solicitud.destroy')->only(['destroy']);
    }
    public function index()
    {
        // Verifica que el usuario esté autenticado; si no, redirige al login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $query = null;

        // Filtrado según rol:
        if ($user->hasRole('admin') || $user->hasRole('secretaria')) {
            // Admin y secretaria ven todas las solicitudes
            $query = \App\Models\Solicitud::with(['solicitanteUser', 'tecnicoUser', 'atenciones.anotaciones']);
        } elseif ($user->hasRole('tecnico')) {
            // El técnico ve solo las solicitudes asignadas a él
            $query = \App\Models\Solicitud::with(['solicitanteUser', 'tecnicoUser', 'atenciones.anotaciones'])
                        ->where('tecnico', $user->id);
        } elseif ($user->hasRole('solicitante')) {
            // El solicitante ve solo las solicitudes que él registró
            $query = \App\Models\Solicitud::with(['solicitanteUser', 'tecnicoUser', 'atenciones.anotaciones'])
                        ->where('solicitante', $user->id);
        } else {
            // Para otros roles, se asigna un listado vacío
            $query = \App\Models\Solicitud::query()->whereRaw('1 = 0');
        }

        // Listado general paginado (10 elementos por página)
        $solicitudes = $query->latest()->paginate(10);

        // Listados paginados por categoría, cada uno con 10 elementos y un nombre de paginador distinto:
        $pendientes = $query->where('estado', 'pendiente')->latest()->paginate(10, ['*'], 'pendientes');
        $enProceso = $query->where('estado', 'en proceso')->latest()->paginate(10, ['*'], 'enproceso');
        $rechazadas = $query->whereIn('estado', ['rechazada', 'cancelada'])->latest()->paginate(10, ['*'], 'rechazadas');

        return view('admin.solicitud.index', compact('solicitudes', 'pendientes', 'enProceso', 'rechazadas'));
    }


    public function create()
    {
        // Obtener todos los técnicos
        $tecnicos = User::role('tecnico')->get();
        // Obtener todos los tipos de problema
        $tipoProblemas = \App\Models\TipoProblema::all();
        return view('admin.solicitud.create', compact('tecnicos', 'tipoProblemas'));
    }
    public function store(Request $request)
    {
        // Validación, agregando la prioridad como campo opcional de tipo string
        $request->validate([
            'tecnico'       => 'nullable|exists:users,id',
            'equipo_id'     => 'nullable|string',
            'archivo'       => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'descripcion'   => 'nullable|string',
            'prioridad'     => 'nullable|string', // Validación para prioridad (puedes ajustar la regla según tus requerimientos)
        ]);

        $data = $request->all();
        // Asigna el solicitante del registro al usuario autenticado
        $data['solicitante'] = auth()->user()->id;

        // Procesa la carga del archivo si se marcó el checkbox
        if ($request->has('upload_file')) {
            if ($request->hasFile('archivo')) {
                $path = $request->file('archivo')->store('Solicitudes', 'public');
                $data['archivo'] = $path;
            }
            // Si se sube archivo, se ignora el código de equipo
            $data['equipo_id'] = null;
        } else {
            // Si no se sube archivo, se limpia el campo archivo
            $data['archivo'] = null;
        }

        Solicitud::create($data);

        return redirect()->route('admin.solicitud.index')->with('success', 'Solicitud creada exitosamente.');
    }


    public function show(Solicitud $solicitud)
    {
        return view('admin.solicitud.show', compact('solicitud'));
    }

    public function edit(Solicitud $solicitud)
    {
        $tecnicos = User::all();
        $tipoProblemas = \App\Models\TipoProblema::all();
        return view('admin.solicitud.edit', compact('solicitud', 'tecnicos', 'tipoProblemas'));
    }

    public function update(Request $request, Solicitud $solicitud)
    {
        $request->validate([
            'tecnico'       => 'nullable|exists:users,id',
            'equipo_id'     => 'nullable|string',
            'archivo'       => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'descripcion'   => 'nullable|string',
            'prioridad'     => 'nullable|string', // Validación para prioridad
        ]);

        $data = $request->all();

        // Procesa la carga del archivo si se marcó el checkbox para subir archivo
        if ($request->has('upload_file')) {
            if ($request->hasFile('archivo')) {
                $path = $request->file('archivo')->store('Solicitudes', 'public');
                $data['archivo'] = $path;
            }
            $data['equipo_id'] = null;
        } else {
            // Si no se sube archivo, se conserva el archivo actual
            $data['archivo'] = $solicitud->archivo;
        }

        $solicitud->update($data);

        return redirect()->route('admin.solicitud.index')->with('success', 'Solicitud actualizada exitosamente.');
    }


    public function destroy(Solicitud $solicitud)
    {
        $solicitud->delete();
        return redirect()->route('admin.solicitud.index')->with('success', 'Solicitud eliminada.');
    }


    public function mostrar($archivo)
    {
        $path = storage_path('app/public/' . $archivo);

        if (!File::exists($path)) {
            abort(404, 'Archivo no encontrado.');
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'Cache-Control' => 'public, must-revalidate, max-age=0',
            'X-Content-Type-Options' => 'nosniff'
        ];

        return response()->stream(function () use ($path) {
            readfile($path);
        }, 200, $headers);
    }


    public function rechazar(Solicitud $solicitud, Request $request)
    {
        // Valida que se envíe el motivo del rechazo
        $data = $request->validate([
            'motivo_rechazo' => 'required|string',
        ]);

        // Solo permite que el técnico asignado rechace la solicitud
        if ($solicitud->tecnico != auth()->user()->id) {
            abort(403, 'No autorizado');
        }

        // Actualiza el estado a "rechazada" y remueve el técnico, además guarda el motivo del rechazo
        $solicitud->update([
            'estado'          => 'rechazada',
            'tecnico'         => null,
            'motivo_rechazo'  => $data['motivo_rechazo'],
        ]);

        return redirect()->back()->with('info', 'La solicitud ha sido rechazada. La secretaría podrá asignar un nuevo técnico.');
    }


    public function report()
    {
        // Consultamos todas las solicitudes con sus relaciones y contamos atenciones y anotaciones
        $solicitudes = Solicitud::with(['solicitanteUser', 'tecnicoUser'])
                        ->withCount(['atenciones', 'anotaciones'])
                        ->latest()
                        ->get();

        // Cargamos la vista 'reports.solicitudes' pasando la variable $solicitudes
        $pdf = PDF::loadView('reports.solicitudes', compact('solicitudes'));

        // Puedes ajustar opciones como tamaño de papel u orientación
        // $pdf->setPaper('A4', 'landscape');

        // Puedes retornar el PDF para ser visualizado en el navegador o para descarga
        return $pdf->stream('reporte-solicitudes.pdf');
        // Para forzar la descarga usa: return $pdf->download('reporte-solicitudes.pdf');
    }

    public function dashboard()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // Consultar todas las solicitudes para el admin y contar usuarios si es necesario
            $solicitudes = Solicitud::with(['solicitanteUser', 'atenciones'])->latest()->get();
            $totalUsuarios = \App\Models\User::count();
        } else {
            // Para usuarios (técnico o solicitante), se asume que se filtran las solicitudes correspondientes
            $solicitudes = Solicitud::where('solicitante', $user->id)->with(['solicitanteUser', 'atenciones'])->latest()->get();
        }

        return view('admin.solicitud.dashboard', compact('solicitudes', 'totalUsuarios'));
    }

    public function finalizar(Request $request, Solicitud $solicitud)
    {
        // Actualizamos la solicitud estableciendo su estado a "finalizada"
        $solicitud->update(['estado' => 'finalizada']);

        // Redirige al listado con un mensaje de éxito
        return redirect()->route('admin.solicitud.index')
            ->with('success', 'La solicitud ha sido finalizada exitosamente.');
    }

    public function pendientes(Request $request)
    {

        // Recupera las solicitudes con relaciones necesarias
        $solicitudes = Solicitud::with(['solicitanteUser', 'tecnicoUser', 'atenciones'])
                        ->where('estado', 'pendiente')
                        ->orderBy('id', 'desc')
                        ->paginate(10);

        return view('admin.solicitud.pendiente', compact('solicitudes'));
    }
    public function reenviarPendientes()
    {
        // Se buscan solicitudes en estado "pendiente" creadas hace 7 días o más.
        $pendientes = Solicitud::where('estado', 'pendiente')
            ->where('created_at', '<=', now()->subDays(7))
            ->get();

        foreach ($pendientes as $solicitud) {
            // Obtener los datos de la solicitud a clonar
            $data = $solicitud->toArray();
            // Eliminar los campos que deben generarse de forma nueva
            unset($data['id'], $data['created_at'], $data['updated_at']);
            // Puedes resetear el estado o mantenerlo; en este caso lo dejamos en "pendiente"
            $data['estado'] = 'pendiente';
            // Se crean las nuevas marcas de tiempo
            $data['created_at'] = now();
            $data['updated_at'] = now();

            // Crear la nueva solicitud con los mismos datos
            Solicitud::create($data);
        }

        return redirect()->back()->with('success', 'Se han reenviado las solicitudes pendientes por más de 7 días.');
    }
    public function reenviarSolicitud(Solicitud $solicitud)
    {
        // Permite reenviar la solicitud solo si no tiene técnico asignado.
        if (!is_null($solicitud->tecnico)) {
            return redirect()->back()->with('error', 'Esta solicitud ya tiene un técnico asignado, no se puede reenviar individualmente.');
        }

        // Convertir la solicitud a array y eliminar campos que se generan automáticamente.
        $data = $solicitud->toArray();
        unset($data['id'], $data['created_at'], $data['updated_at']);

        // Puedes reiniciar el estado o dejarlo como "pendiente".
        $data['estado'] = 'pendiente';
        $data['created_at'] = now();
        $data['updated_at'] = now();

        Solicitud::create($data);

        return redirect()->back()->with('success', 'La solicitud ha sido reenviada exitosamente.');
    }

    public function generarEstadisticas(Request $request)
    {
        $request->validate([
            'inicio' => 'required|date',
            'fin' => 'required|date|after_or_equal:inicio',
            'formato' => 'required|in:pdf,excel',
        ]);

        $inicio = $request->input('inicio');
        $fin = $request->input('fin');

        // Técnicos
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

        // Resumen por estado
        $estadisticasEstados = Solicitud::selectRaw('estado, COUNT(*) as total')
            ->whereBetween('created_at', [$inicio, $fin])
            ->groupBy('estado')
            ->pluck('total', 'estado');

        // Tipos de problema con mayor incidencia
        $problemas = TipoProblema::withCount(['solicitudes' => function ($q) use ($inicio, $fin) {
            $q->whereBetween('created_at', [$inicio, $fin]);
        }])->orderByDesc('solicitudes_count')->take(10)->get();

        // Solicitudes sin técnico
        $sinTecnico = Solicitud::whereNull('tecnico')
            ->whereBetween('created_at', [$inicio, $fin])
            ->count();

        // Tiempo promedio de atención
        $tiempoPromedio = Solicitud::where('estado', 'finalizada')
            ->whereBetween('created_at', [$inicio, $fin])
            ->get()
            ->map(function ($s) {
                return $s->created_at->diffInDays($s->updated_at);
            })->avg();

        $data = [
            'fechaInicio' => $inicio,
            'fechaFin' => $fin,
            'estadisticasTecnicos' => $estadisticasTecnicos,
            'estadisticasEstados' => $estadisticasEstados,
            'atencionesPorArea' => $estadisticasAreas,
            'tiposProblemas' => $problemas,
            'sinTecnico' => $sinTecnico,
            'tiempoPromedio' => $tiempoPromedio,
        ];

        if ($request->input('formato') === 'excel') {
            // Generar HTML de la vista Excel
            $html = view('admin.solicitud.reporte.estadisticas_excel', $data)->render();

            // Guardar temporalmente como archivo .xls
            $filePath = storage_path('app/public/estadisticas_solicitudes.xls');
            file_put_contents($filePath, $html);

            // Forzar descarga
            return response()->download($filePath, 'estadisticas_solicitudes.xls')->deleteFileAfterSend(true);
        } else {
            $pdf = \PDF::loadView('admin.solicitud.reporte.estadisticas', $data);
            return $pdf->stream('estadisticas_solicitudes.pdf');
        }
    }


}
