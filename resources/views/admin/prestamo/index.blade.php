<x-layouts.app :title="__('Listado de Préstamos')">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Préstamos</h1>
            <a href="{{ route('admin.prestamo.create') }}"
               class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Nuevo Préstamo
            </a>
        </div>

        <!-- Mensajes de éxito -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabla de Préstamos -->
        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Solicitante</th>
                        <th class="px-4 py-2 text-left">Descripción</th>
                        <th class="px-4 py-2 text-left">Estado</th>
                        <th class="px-4 py-2 text-left">Fecha Creación</th>
                        <th class="px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($prestamos as $prestamo)
                        <tr>
                            <td class="px-4 py-2">{{ $prestamo->id }}</td>
                            <td class="px-4 py-2">
                                {{ $prestamo->solicitanteUser->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2">{{ $prestamo->descripcion ?? 'Sin descripción' }}</td>
                            <td class="px-4 py-2">{{ $prestamo->estado ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $prestamo->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 flex space-x-2">
                                <a href="{{ route('admin.prestamo.show', $prestamo) }}"
                                   class="px-2 py-1 text-blue-600 hover:underline">Ver</a>
                                <a href="{{ route('admin.prestamo.edit', $prestamo) }}"
                                   class="px-2 py-1 text-yellow-600 hover:underline">Editar</a>
                                <form action="{{ route('admin.prestamo.destroy', $prestamo) }}" method="POST"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este préstamo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 text-red-600 hover:underline">
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
            {{ $prestamos->links() }}
        </div>
    </div>
</x-layouts.app>
