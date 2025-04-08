<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Constructor que asigna los middleware para los permisos.
     */
    public function __construct()
    {
        $this->middleware('can:admin.user.index')->only(['index']);
        $this->middleware('can:admin.user.create')->only(['create', 'store']);
        $this->middleware('can:admin.user.edit')->only(['edit', 'update']);
        $this->middleware('can:admin.user.destroy')->only(['destroy']);
    }

    /**
     * Muestra el listado de usuarios.
     *
     * Permite buscar por nombre o email y realiza la paginación.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Si se envía un parámetro de búsqueda, filtra por nombre o email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
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
            'area'              => 'nullable|string',
            'fecha_nacimiento'  => 'nullable|date',
        ]);

        $data['password'] = Hash::make($data['password']);

        // Crear el usuario
        $user = User::create($data);

        // Asignar el rol "solicitante" al nuevo usuario
        $user->assignRole('solicitante');

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
        $roles = Role::all();
        return view('admin.user.edit', compact('user', 'roles'));
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
            'area'              => 'nullable|string',
            'fecha_nacimiento'  => 'nullable|date',
        ]);

        // Sincroniza los roles asignados al usuario
        $user->roles()->sync($request->roles);

        // Actualiza la contraseña solo si se envía un nuevo valor
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

    /**
     * Sincroniza los usuarios con un servicio externo.
     *
     * Se reciben datos con los siguientes campos:
     *   - idpersona: Identificador externo.
     *   - personanombre: Nombre del usuario.
     *   - personaap: Apellido del usuario.
     *   - idcargo: Identificador del cargo.
     *   - cargo: Cargo (nombre del cargo).
     *   - idoficina: Identificador de la oficina.
     *   - oficina: Nombre de la oficina.
     *   - latitude: Coordenada de latitud.
     *   - longitude: Coordenada de longitud.
     *
     * Las reglas para la sincronización son:
     *   1. El campo 'name' se construye concatenando personanombre y personaap.
     *   2. El email se construye concatenando el apellido y el nombre (sin espacios y en minúsculas) seguido de "@gmail.com".
     *   3. El password se genera a partir del personanombre (sin espacios y en minúsculas) concatenado con "1234" y luego se hashea.
     *
     * Se asume que la tabla 'users' dispone de las columnas 'idpersona', 'latitude' y 'longitude'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sync(Request $request)
    {
        // Simulación de datos externos; en una implementación real se obtendrían mediante un cliente HTTP
        $externalUsers = [
            [
                "idpersona"      => 29,
                "personanombre"  => "IVAN JAVIER",
                "personaap"      => "CASTRO CHOQUE",
                "idcargo"        => 5,
                "cargo"          => "AUXILIAR",
                "idoficina"      => 1,
                "oficina"        => "DESPACHO DEL ALCALDE MUNICIPAL",
                "latitude"       => "-21.534231237189875",
                "longitude"      => "-64.73472213644303"
            ],
            // Aquí puedes agregar más registros externos según sea necesario.
        ];

        foreach ($externalUsers as $extUser) {
            // Se busca un usuario basado en el campo 'idpersona'.
            // Asegúrate de que la columna 'idpersona' existe en la tabla 'users'.
            $user = User::firstOrNew(['idpersona' => $extUser['idpersona']]);

            // Asigna el campo 'name' concatenando personanombre y personaap.
            $user->name = trim($extUser['personanombre'] . ' ' . $extUser['personaap']);

            // Construye el email tomando el apellido y el nombre, sin espacios y en minúsculas, y luego agrega "@gmail.com".
            // Ejemplo: "CASTRO CHOQUE" y "IVAN JAVIER" → "castrochoqueivanjavier@gmail.com"
            $emailLocalPart = strtolower(str_replace(' ', '', $extUser['personaap'] . $extUser['personanombre']));
            $user->email = $emailLocalPart . '@gmail.com';

            // Asigna los demás campos del usuario con los datos externos.
            $user->cargo = $extUser['cargo'];
            $user->oficina = $extUser['oficina'];
            $user->latitude = $extUser['latitude'];
            $user->longitude = $extUser['longitude'];

            // Genera el password a partir del personanombre (sin espacios, en minúsculas) concatenado con "1234".
            // Ejemplo: "IVAN JAVIER" → "ivanjavier1234"
            $passwordBase = strtolower(str_replace(' ', '', $extUser['personanombre']));
            $user->password = Hash::make($passwordBase . '1234');

            // Si el usuario es nuevo (no existe en la base de datos), asigna el rol 'solicitante'.
            if (!$user->exists) {
                $user->assignRole('solicitante');
            }

            // Guarda o actualiza el usuario.
            $user->save();
        }

        return redirect()->route('admin.user.index')->with('success', 'Sincronización completada.');
    }
}
