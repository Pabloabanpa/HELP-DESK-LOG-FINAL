<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function index()
    {
        $solicitudes = Solicitud::with(['solicitanteUsuario', 'tecnicoUsuario'])->latest()->get();
        return view('solicitudes.index', compact('solicitudes'));
    }

    public function create()
    {
        $usuarios = User::all();
        return view('solicitudes.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'solicitante' => 'required|exists:users,id',
            'tecnico' => 'nullable|exists:users,id',
            'descripcion' => 'nullable|string',
            'archivo' => 'nullable|file|max:2048',
            'estado' => 'required|string',
        ]);

        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('solicitudes', 'public');
        }

        Solicitud::create($data);

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud creada correctamente.');
    }

    public function show(Solicitud $solicitud)
    {
        return view('solicitudes.show', compact('solicitud'));
    }

    public function edit(Solicitud $solicitud)
    {
        $usuarios = User::all();
        return view('solicitudes.edit', compact('solicitud', 'usuarios'));
    }

    public function update(Request $request, Solicitud $solicitud)
    {
        $data = $request->validate([
            'tecnico' => 'nullable|exists:users,id',
            'descripcion' => 'nullable|string',
            'estado' => 'required|string',
        ]);

        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('solicitudes', 'public');
        }

        $solicitud->update($data);

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud actualizada.');
    }

    public function destroy(Solicitud $solicitud)
    {
        $solicitud->delete();
        return redirect()->route('solicitudes.index')->with('success', 'Solicitud eliminada.');
    }
}
