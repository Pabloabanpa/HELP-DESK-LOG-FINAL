<div>
    <!-- Barra de búsqueda en tiempo real -->
    <input type="text" wire:model.debounce.300ms="search" placeholder="Buscar usuarios..."
           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" />

    <!-- Tabla de usuarios -->
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 mt-4">
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
                @forelse ($users as $user)
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
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-2 text-center">No se encontraron usuarios</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
