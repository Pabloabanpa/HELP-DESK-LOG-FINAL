<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anotacion;
use App\Models\Atencion;
use Illuminate\Http\Request;

class AnotacionController extends Controller
{
    /**
     * Muestra la lista de anotaciones.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Anotacion::query();

        // Si el usuario NO es admin ni secretaria, se muestran solo las anotaciones que realizó
        if (!$user->hasRole('admin') && !$user->hasRole('secretaria')) {
            $query->where('tecnico_id', $user->id);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('descripcion', 'like', '%' . $search . '%')
                  ->orWhere('material_usado', 'like', '%' . $search . '%');
            });
        }

        $anotaciones = $query->orderBy('id', 'desc')->paginate(10);
        $anotaciones->appends($request->all());

        return view('admin.anotacion.index', compact('anotaciones'));
    }

    /**
     * Muestra el formulario para crear una nueva anotación.
     */
    public function create()
    {
        // Obtener las atenciones cuyo técnico asignado (en la solicitud) es el usuario logueado
        $atenciones = Atencion::whereHas('solicitud', function ($query) {
            $query->where('tecnico', auth()->user()->id);
        })->pluck('descripcion', 'id');

        return view('admin.anotacion.create', compact('atenciones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'atencion_id'    => 'required|exists:atenciones,id',
            'descripcion'    => 'required|string',
            'material_usado' => 'nullable|string',
        ]);

        $atencion = Atencion::findOrFail($data['atencion_id']);

        // Verificar que, a través de la solicitud, el técnico asignado es el usuario logueado
        if (!$atencion->solicitud || $atencion->solicitud->tecnico != auth()->user()->id) {
            return redirect()->back()->with('error', 'No tienes permiso para registrar anotaciones en esta atención.');
        }

        $data['tecnico_id'] = auth()->user()->id;

        Anotacion::create($data);

        return redirect()->route('admin.anotacion.index')
            ->with('success', 'Anotación registrada exitosamente.');
    }


    public function show(Anotacion $anotacion)
    {
        $user = auth()->user();
        // Permitir ver la anotación solo si:
        // - El usuario es admin o secretaria, o
        // - El usuario es el técnico que realizó la anotación.
        if (!$user->hasRole('admin') && !$user->hasRole('secretaria') && $anotacion->tecnico_id != $user->id) {
            abort(403, 'No tienes permiso para ver esta anotación.');
        }
        return view('admin.anotacion.show', compact('anotacion'));
    }


    public function edit(Anotacion $anotacion)
    {
        // Solo se permite editar si, a través de la solicitud, el técnico asignado es el usuario logueado.
        if (!$anotacion->atencion->solicitud || $anotacion->atencion->solicitud->tecnico != auth()->user()->id) {
            return redirect()->back()->with('error', 'No tienes permiso para editar esta anotación.');
        }

        $atenciones = Atencion::whereHas('solicitud', function ($query) {
            $query->where('tecnico', auth()->user()->id);
        })->pluck('descripcion', 'id');

        return view('admin.anotacion.edit', compact('anotacion', 'atenciones'));
    }

    /**
     * Actualiza los datos de una anotación en la base de datos.
     */
    public function update(Request $request, Anotacion $anotacion)
    {
        // Verificar permiso usando la relación con la solicitud
        if (!$anotacion->atencion->solicitud || $anotacion->atencion->solicitud->tecnico != auth()->user()->id) {
            return redirect()->back()->with('error', 'No tienes permiso para actualizar esta anotación.');
        }

        $data = $request->validate([
            'atencion_id'    => 'required|exists:atenciones,id',
            'descripcion'    => 'required|string',
            'material_usado' => 'nullable|string',
        ]);

        $atencion = Atencion::findOrFail($data['atencion_id']);
        if (!$atencion->solicitud || $atencion->solicitud->tecnico != auth()->user()->id) {
            return redirect()->back()->with('error', 'No tienes permiso para actualizar esta anotación en esta atención.');
        }

        $anotacion->update($data);

        return redirect()->route('admin.anotacion.index')
            ->with('success', 'Anotación actualizada correctamente.');
    }

    /**
     * Elimina una anotación.
     */
    public function destroy(Anotacion $anotacion)
    {
        if (!$anotacion->atencion->solicitud || $anotacion->atencion->solicitud->tecnico != auth()->user()->id) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar esta anotación.');
        }

        $anotacion->delete();

        return redirect()->route('admin.anotacion.index')
            ->with('success', 'Anotación eliminada correctamente.');
    }
}
