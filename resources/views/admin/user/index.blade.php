<x-layouts.app :title="__('Lista de Usuarios')">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Encabezado con título, buscador y botones de acción -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <!-- Icono de usuarios -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m7-1a4 4 0 10-8 0 4 4 0 008 0zm4-3a4 4 0 10-8 0v1h8v-1z" />
                </svg>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Lista de Usuarios</h1>
            </div>
            <!-- Buscador -->
            <div class="mt-4 md:mt-0 flex items-center space-x-2">
                <form id="searchForm" class="relative" method="GET" action="{{ route('admin.user.index') }}">
                    <input type="text" id="userSearchInput" name="search" placeholder="Buscar usuarios..."
                           value="{{ request('search') }}"
                           class="pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1016.65 2.5a7.5 7.5 0 000 14.15z" />
                        </svg>
                    </div>
                </form>
                <button type="submit" form="searchForm" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Buscar
                </button>
            </div>
        </div>

        <!-- Botones de acciones -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex space-x-2">
                <!-- Botón para sincronizar usuarios -->
                <a href="{{ route('sync.users') }}"
                   class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                    <!-- Icono de sincronizar -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                        <path d="M4 10a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4z" />
                    </svg>
                    Sincronizar Usuarios
                </a>
                <!-- Botón para agregar un nuevo usuario -->
                <a href="{{ route('admin.user.create') }}"
                   class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                    <!-- Icono de agregar -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    + Nuevo Usuario
                </a>
            </div>
        </div>

        <!-- Mensajes de feedback (éxito o error) -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabla de usuarios -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
                <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Cargo</th>
                        <th class="px-4 py-3">CI</th>
                        <th class="px-4 py-3">Celular</th>
                        <th class="px-4 py-3">Oficina/Secretaría</th>
                        <th class="px-4 py-3">Área</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Fecha Nac.</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $user->email }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $user->cargo }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $user->ci }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $user->celular }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $user->oficina }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $user->area }}</td>
                            <td class="px-4 py-2">
                                @if($user->estado == 'activo')
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">
                                @if($user->fecha_nacimiento)
                                    {{ \Carbon\Carbon::parse($user->fecha_nacimiento)->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-2 flex space-x-2">
                                <!-- Botón Editar -->
                                <a href="{{ route('admin.user.edit', $user) }}"
                                   class="flex items-center text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 010 2.828l-1.586 1.586-2.828-2.828 1.586-1.586a2 2 0 012.828 0z" />
                                        <path d="M2 13.5V18h4.5l9.793-9.793-4.5-4.5L2 13.5z" />
                                    </svg>
                                    Editar
                                </a>
                                <!-- Botón Eliminar -->
                                <form action="{{ route('admin.user.destroy', $user) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este usuario?')"
                                            class="flex items-center text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7 4H4a1 1 0 000 2h1v10a2 2 0 002 2h6a2 2 0 002-2V6h1a1 1 0 100-2h-3l-.106-.447A1 1 0 0011 2H9zM7 6v10a1 1 0 001 1h4a1 1 0 001-1V6H7z" clip-rule="evenodd" />
                                        </svg>
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
    </div>
</x-layouts.app>
