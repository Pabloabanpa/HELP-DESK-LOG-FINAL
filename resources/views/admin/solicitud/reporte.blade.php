<x-layouts.app :title="__('Listado de Solicitudes')">
    
    <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Listado de Solicitudes</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Solicitante</th>
                        <th class="px-4 py-3 text-left">Descripción</th>
                        <th class="px-4 py-3 text-left">Atenciones</th>
                        <th class="px-4 py-3 text-left">Anotaciones</th>
                        <th class="px-4 py-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100">
                    @foreach($solicitudes as $solicitud)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-2">{{ $solicitud->id }}</td>
                            <td class="px-4 py-2">{{ $solicitud->solicitanteUser->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ Str::limit($solicitud->descripcion, 50) }}</td>
                            <td class="px-4 py-2">
                                {{ isset($solicitud->atenciones_count) ? $solicitud->atenciones_count : $solicitud->atenciones->count() }}
                            </td>
                            <td class="px-4 py-2">
                                {{ isset($solicitud->anotaciones_count) ? $solicitud->anotaciones_count : $solicitud->anotaciones->count() }}
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.solicitud.show', $solicitud) }}" class="text-blue-600 hover:text-blue-800">Ver</a>
                                <!-- Puedes agregar más acciones aquí -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Paginación -->
        <div class="mt-6">
            {{ $solicitudes->links() }}
        </div>
    </div>
</x-layouts.app>
