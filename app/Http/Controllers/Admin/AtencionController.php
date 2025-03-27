<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Atencion;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class AtencionController extends Controller
{
    // Muestra las atenciones de las solicitudes asignadas al técnico autenticado
    public function index()
    {
        $userId = auth()->user()->id;

        $atenciones = Atencion::with('solicitud')
            ->whereHas('solicitud', function ($query) use ($userId) {
                $query->where('tecnico', $userId);
            })
            ->latest()
            ->paginate(10);

        return view('admin.atencion.index', compact('atenciones'));
    }

    // Muestra el formulario para crear una nueva atención
    public function create()
    {
        // Se muestran las solicitudes que están asignadas al técnico actual o sin asignar
        $solicitudes = Solicitud::where(function ($query) {
            $query->where('tecnico', auth()->user()->id)
                  ->orWhereNull('tecnico');
        })->get();

        return view('admin.atencion.create', compact('solicitudes'));
    }

    // Almacena una nueva atención
    public function store(Request $request)
    {
        $request->validate([
            'solicitud_id' => 'required|exists:solicitudes,id',
            'descripcion'  => 'required|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $data = $request->all();
        // Por defecto, se asigna el estado "en proceso"
        $data['estado'] = 'en proceso';

        Atencion::create($data);

        return redirect()->route('admin.atencion.index')->with('success', 'Atención creada exitosamente.');
    }

    // Muestra una atención en detalle
    public function show(Atencion $atencion)
    {
        // Se verifica que la solicitud asociada a la atención pertenezca al técnico actual
        if ($atencion->solicitud->tecnico != auth()->user()->id) {
            abort(403, 'No autorizado');
        }
        return view('admin.atencion.show', compact('atencion'));
    }

    // Muestra el formulario para editar una atención
    public function edit(Atencion $atencion)
    {
        if ($atencion->solicitud->tecnico != auth()->user()->id) {
            abort(403, 'No autorizado');
        }
        return view('admin.atencion.edit', compact('atencion'));
    }

    // Actualiza la atención
    public function update(Request $request, Atencion $atencion)
    {
        if ($atencion->solicitud->tecnico != auth()->user()->id) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'descripcion'  => 'required|string',
            'estado'       => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $atencion->update($request->all());

        return redirect()->route('admin.atencion.index')->with('success', 'Atención actualizada exitosamente.');
    }

    // Elimina la atención
    public function destroy(Atencion $atencion)
    {
        if ($atencion->solicitud->tecnico != auth()->user()->id) {
            abort(403, 'No autorizado');
        }

        $atencion->delete();

        return redirect()->route('admin.atencion.index')->with('success', 'Atención eliminada exitosamente.');
    }

    // Nuevo método: Muestra todas las anotaciones correspondientes a una atención
    public function anotaciones(Atencion $atencion)
    {
        // Se verifica que la solicitud asociada a la atención pertenezca al técnico actual
        if ($atencion->solicitud->tecnico != auth()->user()->id) {
            abort(403, 'No autorizado');
        }

        // Se obtiene el listado de anotaciones asociadas a la atención
        $anotaciones = $atencion->anotaciones; // Asegúrate de tener la relación "anotaciones" en el modelo Atencion

        return view('admin.atencion.anotaciones', compact('atencion', 'anotaciones'));
    }
}
