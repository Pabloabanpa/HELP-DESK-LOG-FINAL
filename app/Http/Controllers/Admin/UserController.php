<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
            'cargo' => 'nullable|string',
            'oficina' => 'nullable|string',
            'ci' => 'nullable|string',
            'celular' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:users,name,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cargo' => 'nullable|string',
            'oficina' => 'nullable|string',
            'ci' => 'nullable|string',
            'celular' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        // Actualiza la contraseña solo si se envía
        if ($request->filled('password')) {
            $request->validate(['password' => 'confirmed|min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
    }
}
