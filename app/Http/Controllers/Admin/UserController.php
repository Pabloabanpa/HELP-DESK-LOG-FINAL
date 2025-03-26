<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Si se envía un parámetro de búsqueda, filtra por nombre o email
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Ordena por id descendente y pagina (10 por página, por ejemplo)
        $users = $query->orderBy('id', 'desc')->paginate(10);

        // Mantiene los parámetros de búsqueda en la paginación
        $users->appends($request->all());

        return view('admin.user.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Guarda un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|unique:users,name',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|confirmed|min:6',
            'cargo'             => 'nullable|string',
            'oficina'           => 'nullable|string',
            'ci'                => 'nullable|string',
            'celular'           => 'nullable|string',
            'fecha_nacimiento'  => 'nullable|date',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.user.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Muestra los detalles de un usuario.
     */
    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    /**
     * Muestra el formulario para editar un usuario.
     */
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Actualiza los datos de un usuario en la base de datos.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'              => 'required|string|unique:users,name,' . $user->id,
            'email'             => 'required|email|unique:users,email,' . $user->id,
            'cargo'             => 'nullable|string',
            'oficina'           => 'nullable|string',
            'ci'                => 'nullable|string',
            'celular'           => 'nullable|string',
            'fecha_nacimiento'  => 'nullable|date',
        ]);

        // Actualiza la contraseña solo si se envía
        if ($request->filled('password')) {
            $request->validate(['password' => 'confirmed|min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina un usuario.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
