<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Atencion;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class AtencionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.atencion.index')->only(['index']);
        $this->middleware('can:admin.atencion.create')->only(['create', 'store']);
        $this->middleware('can:admin.atencion.edit')->only(['edit', 'update']);
        $this->middleware('can:admin.atencion.destroy')->only(['destroy']);
    }

    // Muestra el listado de atenciones
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('secretaria')) {
            // El administrador y la secretaria ven TODAS las atenciones
            $atenciones = Atencion::with('solicitud')->latest()->paginate(10);
        } elseif ($user->hasRole('tecnico')) {
            // El técnico ve solo las atenciones de las solicitudes asignadas a él
            $atenciones = Atencion::with('solicitud')
                ->whereHas('solicitud', function ($query) use ($user) {
                    $query->where('tecnico', $user->id);
                })->latest()->paginate(10);
        } else {
            $atenciones = collect([]);
        }

        return view('admin.atencion.index', compact('atenciones'));
    }

    // Muestra el formulario para crear una nueva atención


     

    // Almacena una nueva atención
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('tecnico') || $user->hasRole('admin')) {
            $request->validate([
                'solicitud_id' => 'required|exists:solicitudes,id',
                'descripcion'  => 'required|string',
                'fecha_inicio' => 'nullable|date',
                'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
            ]);

            $data = $request->all();
            // Se asigna el estado por defecto "en proceso"
            $data['estado'] = 'en proceso';
            Atencion::create($data);

            return redirect()->route('admin.atencion.index')
                ->with('success', 'Atención creada exitosamente.');
        }

        abort(403, 'No autorizado');
    }

    // Muestra una atención en detalle
    public function show(Atencion $atencion)
    {
        $user = auth()->user();

        // El admin y la secretaria pueden ver cualquier atención;
        // el técnico solo puede ver las atenciones asignadas a él.
        if ($user->hasRole('admin') || $user->hasRole('secretaria') ||
            ($user->hasRole('tecnico') && $atencion->solicitud->tecnico == $user->id)) {
            return view('admin.atencion.show', compact('atencion'));
        }

        abort(403, 'No autorizado');
    }

    // Muestra el formulario para editar una atención
    public function edit(Atencion $atencion)
    {
        $user = auth()->user();

        // El admin puede editar cualquier atención.
        // El técnico solo si es el asignado.
        // La secretaria podrá acceder al formulario para cambiar el estado a "finalizada".
        if ($user->hasRole('admin') ||
            ($user->hasRole('tecnico') && $atencion->solicitud->tecnico == $user->id) ||
            $user->hasRole('secretaria')) {
            return view('admin.atencion.edit', compact('atencion'));
        }

        abort(403, 'No autorizado');
    }

    // Actualiza la atención
    public function update(Request $request, Atencion $atencion)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // El administrador puede actualizar todos los campos
            $request->validate([
                'descripcion'  => 'required|string',
                'estado'       => 'required|string',
                'fecha_inicio' => 'nullable|date',
                'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
            ]);

            $atencion->update($request->all());
            return redirect()->route('admin.atencion.index')
                ->with('success', 'Atención actualizada exitosamente.');
        } elseif ($user->hasRole('tecnico') && $atencion->solicitud->tecnico == $user->id) {
            // El técnico puede actualizar todos los campos de su atención
            $request->validate([
                'descripcion'  => 'required|string',
                'estado'       => 'required|string',
                'fecha_inicio' => 'nullable|date',
                'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
            ]);

            $atencion->update($request->all());
            return redirect()->route('admin.atencion.index')
                ->with('success', 'Atención actualizada exitosamente.');
        } elseif ($user->hasRole('secretaria')) {
            // La secretaria solo puede cambiar el estado a "finalizada"
            $request->validate([
                'estado' => 'required|string|in:finalizada',
            ]);

            $atencion->update(['estado' => $request->estado]);
            return redirect()->route('admin.atencion.index')
                ->with('success', 'Atención marcada como finalizada.');
        }

        abort(403, 'No autorizado');
    }

    // Elimina una atención
    public function destroy(Atencion $atencion)
    {
        $user = auth()->user();

        // Solo el admin o el técnico asignado pueden eliminar la atención
        if ($user->hasRole('admin') ||
            ($user->hasRole('tecnico') && $atencion->solicitud->tecnico == $user->id)) {
            $atencion->delete();
            return redirect()->route('admin.atencion.index')
                ->with('success', 'Atención eliminada exitosamente.');
        }

        abort(403, 'No autorizado');
    }

    // Muestra las anotaciones correspondientes a una atención
    public function anotaciones(Atencion $atencion)
    {
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('secretaria') ||
            ($user->hasRole('tecnico') && $atencion->solicitud->tecnico == $user->id)) {
            $anotaciones = $atencion->anotaciones;
            return view('admin.atencion.anotaciones', compact('atencion', 'anotaciones'));
        }

        abort(403, 'No autorizado');
    }
}
