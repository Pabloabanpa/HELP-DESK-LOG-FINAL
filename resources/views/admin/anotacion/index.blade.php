<x-layouts.app :title="__('Mis Anotaciones')">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Encabezado -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Mis Anotaciones</h1>
            @can('admin.anotacion.create')
            <a href="{{ route('admin.anotacion.create') }}" class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <!-- Ícono de agregar -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nueva Anotación
            </a>
            @endcan
        </div>

        <!-- Contenedor de la tabla -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3 text-left">Atención</th>
                        <th class="px-4 py-3 text-left">Descripción</th>
                        <th class="px-4 py-3 text-left">Material Usado</th>
                        <th class="px-4 py-3 text-left">Fecha Registro</th>
                        <th class="px-4 py-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($anotaciones as $anotacion)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">
                                @if($anotacion->atencion)
                                    {{ $anotacion->atencion->id }} - {{ $anotacion->atencion->descripcion ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ Str::limit($anotacion->descripcion, 50) }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ Str::limit($anotacion->material_usado, 50) }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $anotacion->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 flex space-x-2">

                                @can('admin.anotacion.edit')
                                <a href="{{ route('admin.anotacion.edit', $anotacion) }}" class="flex items-center text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M17.414 2.586a2 2 0 010 2.828l-1.586 1.586-2.828-2.828 1.586-1.586a2 2 0 012.828 0z" />
                                        <path d="M2 13.5V18h4.5l9.793-9.793-4.5-4.5L2 13.5z" />
                                    </svg>
                                    Editar
                                </a>
                                @endcan

                                @can('admin.anotacion.destroy')
                                <form action="{{ route('admin.anotacion.destroy', $anotacion) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Estás seguro de eliminar esta anotación?')" class="flex items-center text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7 4H4a1 1 0 000 2h1v10a2 2 0 002 2h6a2 2 0 002-2V6h1a1 1 0 100-2h-3l-.106-.447A1 1 0 0011 2H9zM7 6v10a1 1 0 001 1h4a1 1 0 001-1V6H7z" clip-rule="evenodd" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $anotaciones->links() }}
        </div>
    </div>
</x-layouts.app>
