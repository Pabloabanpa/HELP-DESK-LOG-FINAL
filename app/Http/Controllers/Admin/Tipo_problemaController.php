<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoProblema;
use Illuminate\Http\Request;

class Tipo_problemaController extends Controller
{
    // Mostrar todos los registros
    public function index()
    {
        // Recupera los registros, en este caso con paginación (puedes ajustar el número de registros por página)
        $tipoProblemas = TipoProblema::paginate(10);

        // Retorna la vista, asegurándote de enviar la variable $tipoProblemas
        return view('admin.tipo_problema.index', compact('tipoProblemas'));
    }

    public function create()
    {
        return view('admin.tipo_problema.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'         => 'required|string|max:255',
            'descripcion'    => 'nullable|string',
            'area_solucion'  => 'nullable|string|max:255',
        ]);

        TipoProblema::create($data);

        return redirect()->route('admin.tipo_problema.index')
                         ->with('success', 'Tipo de problema creado correctamente');
    }

    public function edit(TipoProblema $tipoProblema)
    {
        return view('admin.tipo_problema.edit', compact('tipoProblema'));
    }

    public function update(Request $request, TipoProblema $tipoProblema)
    {
        $data = $request->validate([
            'nombre'         => 'required|string|max:255',
            'descripcion'    => 'nullable|string',
            'area_solucion'  => 'nullable|string|max:255',
        ]);

        $tipoProblema->update($data);

        return redirect()->route('admin.tipo_problema.index')
                         ->with('success', 'Tipo de problema actualizado correctamente');
    }

    public function destroy(TipoProblema $tipoProblema)
    {
        $tipoProblema->delete();

        return redirect()->route('admin.tipo_problema.index')
                         ->with('success', 'Tipo de problema eliminado correctamente');
    }
}
