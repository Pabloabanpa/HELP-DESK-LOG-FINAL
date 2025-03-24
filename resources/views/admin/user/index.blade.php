<x-layouts.app :title="__('Lista de Usuarios')">
    <div class="flex items-center justify-between mb-6">
        <!-- Título -->
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Lista de Usuarios</h1>

        <!-- Botón para crear usuario -->
        <a href=""
           class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
            + Nuevo Usuario
        </a>
    </div>

    <!-- Componente con Alpine para manejar búsqueda -->
    <div x-data="{ search: '' }" class="space-y-4">
        <!-- Input de búsqueda -->
        <input type="text"
               x-model="search"
               placeholder="Buscar usuario..."
               class="w-full md:w-1/3 px-4 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-800 dark:text-white dark:border-gray-600" />

        <!-- Tabla -->
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
                        <tr x-show="
                            '{{ strtolower($user->name) }}'.includes(search.toLowerCase()) ||
                            '{{ strtolower($user->email) }}'.includes(search.toLowerCase()) ||
                            '{{ strtolower($user->ci) }}'.includes(search.toLowerCase()) ||
                            '{{ strtolower($user->cargo) }}'.includes(search.toLowerCase())"
                            class="hover:bg-gray-50 dark:hover:bg-gray-800 transition"
                        >
                            <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->cargo }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->ci }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->celular }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->oficina }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($user->fecha_nacimiento)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300 space-x-2">
                                <a href=""
                                   class="text-blue-600 hover:underline">Editar</a>

                                <form action="" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline"
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
