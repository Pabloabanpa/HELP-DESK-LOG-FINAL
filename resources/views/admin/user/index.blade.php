<x-layouts.app :title="__('Lista de Usuarios')">
    <!-- Encabezado con título y buscador -->
    <div class="flex flex-col md:flex-row items-center justify-between mb-6 space-y-4 md:space-y-0">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Lista de Usuarios</h1>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="{{ route('admin.user.index') }}" class="flex items-center space-x-2">
            <input type="text" name="search" placeholder="Buscar usuarios..."
                   value="{{ request('search') }}"
                   class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Buscar
            </button>
        </form>
    </div>

    <!-- Botones de acciones -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex space-x-2">
            <a href="{{ route('sync.users') }}"
               class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                Sincronizar Usuarios
            </a>
            <a href="{{ route('admin.user.create') }}"
               class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                + Nuevo Usuario
            </a>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Cargo</th>
                    <th class="px-4 py-3">CI</th>
                    <th class="px-4 py-3">Celular</th>
                    <th class="px-4 py-3">Oficina</th>
                    <th class="px-4 py-3">Fecha Nac.</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->cargo }}</td>
                        <td class="px-4 py-2">{{ $user->ci }}</td>
                        <td class="px-4 py-2">{{ $user->celular }}</td>
                        <td class="px-4 py-2">{{ $user->oficina }}</td>
                        <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($user->fecha_nacimiento)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('admin.user.edit', $user) }}"
                               class="text-blue-600 hover:underline">Editar</a>
                            <form action="{{ route('admin.user.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline"
                                        onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-layouts.app>
