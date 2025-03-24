<?php

namespace App\Http\Controllers;

use App\Models\Anotacion;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Http\Request;

class AnotacionController extends Controller
{
    public function index()
    {
        $anotaciones = Anotacion::with(['solicitud', 'tecnico'])->latest()->get();
        return view('anotaciones.index', compact('anotaciones'));
    }

    public function create()
    {
        $solicitudes = Solicitud::all();
        $tecnicos = User::all();
        return view('anotaciones.create', compact('solicitudes', 'tecnicos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'solicitud_id' => 'required|exists:solicitudes,id',
            'tecnico_id' => 'required|exists:users,id',
            'descripcion' => 'nullable|string',
            'material_usado' => 'nullable|string',
        ]);

        Anotacion::create($data);
        return redirect()->route('anotaciones.index')->with('success', 'Anotación guardada.');
    }

    public function show(Anotacion $anotacion)
    {
        return view('anotaciones.show', compact('anotacion'));
    }

    public function edit(Anotacion $anotacion)
    {
        $solicitudes = Solicitud::all();
        $tecnicos = User::all();
        return view('anotaciones.edit', compact('anotacion', 'solicitudes', 'tecnicos'));
    }

    public function update(Request $request, Anotacion $anotacion)
    {
        $data = $request->validate([
            'descripcion' => 'nullable|string',
            'material_usado' => 'nullable|string',
        ]);

        $anotacion->update($data);
        return redirect()->route('anotaciones.index')->with('success', 'Anotación actualizada.');
    }

    public function destroy(Anotacion $anotacion)
    {
        $anotacion->delete();
        return redirect()->route('anotaciones.index')->with('success', 'Anotación eliminada.');
    }
}
