<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;

class SolicitudController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('secretaria')) {
            // Admin y secretaria ven todas las solicitudes
            $solicitudes = Solicitud::with(['solicitanteUser', 'tecnicoUser', 'atenciones'])
                ->latest()
                ->paginate(10);
        } elseif ($user->hasRole('tecnico')) {
            // El técnico ve solo las solicitudes asignadas a él
            $solicitudes = Solicitud::with(['solicitanteUser', 'tecnicoUser', 'atenciones'])
                ->where('tecnico', $user->id)
                ->latest()
                ->paginate(10);
        } elseif ($user->hasRole('solicitante')) {
            // El solicitante ve solo las solicitudes que él registró
            $solicitudes = Solicitud::with(['solicitanteUser', 'tecnicoUser', 'atenciones'])
                ->where('solicitante', $user->id)
                ->latest()
                ->paginate(10);
        } else {
            // Para otros roles, se puede retornar un listado vacío o implementar otra lógica
            $solicitudes = collect([]);
        }

        return view('admin.solicitud.index', compact('solicitudes'));
    }

    public function create()
    {
        // Obtener todos los usuarios para asignar como técnicos
        $tecnicos = User::role('tecnico')->get();
        return view('admin.solicitud.create', compact('tecnicos'));
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
        return view('admin.solicitud.edit', compact('solicitud', 'tecnicos'));
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

        return response()->file($path);
    }

    public function rechazar(Solicitud $solicitud, Request $request)
    {
        // Solo permite que el técnico asignado rechace la solicitud
        if ($solicitud->tecnico != auth()->user()->id) {
            abort(403, 'No autorizado');
        }

        // Actualizamos el estado y removemos el técnico asignado
        $solicitud->update([
            'estado'   => 'pendiente reasignacion',
            'tecnico'  => null,
        ]);

        return redirect()->back()->with('info', 'La solicitud ha sido rechazada. La secretaría podrá asignar un nuevo técnico.');
    }


}
