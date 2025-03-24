<?php

namespace App\Http\Controllers;

use App\Models\Atencion;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class AtencionController extends Controller
{
    public function index()
    {
        $atenciones = Atencion::with('solicitud')->latest()->get();
        return view('atenciones.index', compact('atenciones'));
    }

    public function create()
    {
        $solicitudes = Solicitud::all();
        return view('atenciones.create', compact('solicitudes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'solicitud_id' => 'required|exists:solicitudes,id',
            'descripcion' => 'nullable|string',
            'estado' => 'required|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        Atencion::create($data);
        return redirect()->route('atenciones.index')->with('success', 'Atención registrada.');
    }

    public function show(Atencion $atencion)
    {
        return view('atenciones.show', compact('atencion'));
    }

    public function edit(Atencion $atencion)
    {
        $solicitudes = Solicitud::all();
        return view('atenciones.edit', compact('atencion', 'solicitudes'));
    }

    public function update(Request $request, Atencion $atencion)
    {
        $data = $request->validate([
            'descripcion' => 'nullable|string',
            'estado' => 'required|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $atencion->update($data);
        return redirect()->route('atenciones.index')->with('success', 'Atención actualizada.');
    }

    public function destroy(Atencion $atencion)
    {
        $atencion->delete();
        return redirect()->route('atenciones.index')->with('success', 'Atención eliminada.');
    }
}
