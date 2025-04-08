<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    /**
     * Constructor.
     *
     * Se puede agregar middleware de permisos si es necesario, por ejemplo:
     * $this->middleware('can:admin.prestamo.index')->only(['index']);
     */
    public function __construct()
    {
        // Puedes agregar middleware para restringir accesos según permisos.
    }

    /**
     * Muestra un listado paginado de préstamos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Se obtienen los préstamos ordenados por id descendente (más recientes primero)
        $prestamos = Prestamo::orderBy('id', 'desc')->paginate(10);
        return view('admin.prestamo.index', compact('prestamos'));
    }

    /**
     * Muestra el formulario para crear un nuevo préstamo.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.prestamo.create');
    }

    /**
     * Almacena un nuevo préstamo en la base de datos.
     *
     * Se asigna automáticamente el usuario autenticado como solicitante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validación: La descripción y estado son campos opcionales.
        $data = $request->validate([
            'descripcion' => 'nullable|string',
            'estado'      => 'nullable|string',
        ]);

        // Se asigna el usuario autenticado como solicitante
        $data['solicitante'] = auth()->user()->id;

        // Se crea el préstamo
        Prestamo::create($data);

        return redirect()->route('admin.prestamo.index')
            ->with('success', 'Préstamo creado exitosamente.');
    }

    /**
     * Muestra los detalles de un préstamo específico.
     *
     * @param  \App\Models\Prestamo  $prestamo
     * @return \Illuminate\View\View
     */
    public function show(Prestamo $prestamo)
    {
        return view('admin.prestamo.show', compact('prestamo'));
    }

    /**
     * Muestra el formulario de edición de un préstamo.
     *
     * @param  \App\Models\Prestamo  $prestamo
     * @return \Illuminate\View\View
     */
    public function edit(Prestamo $prestamo)
    {
        return view('admin.prestamo.edit', compact('prestamo'));
    }

    /**
     * Actualiza los datos de un préstamo existente en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prestamo  $prestamo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Prestamo $prestamo)
    {
        $data = $request->validate([
            'descripcion' => 'nullable|string',
            'estado'      => 'nullable|string',
        ]);

        // Si deseas que el solicitante se actualice (lo cual generalmente no cambia), se puede omitir.
        $data['solicitante'] = auth()->user()->id;

        $prestamo->update($data);

        return redirect()->route('admin.prestamo.index')
            ->with('success', 'Préstamo actualizado correctamente.');
    }

    /**
     * Elimina un préstamo de la base de datos.
     *
     * @param  \App\Models\Prestamo  $prestamo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Prestamo $prestamo)
    {
        $prestamo->delete();
        return redirect()->route('admin.prestamo.index')
            ->with('success', 'Préstamo eliminado correctamente.');
    }
}
