<x-layouts.app :title="__('Listado de Solicitudes')"> <div class="max-w-7xl mx-auto px-4 py-6"> <!-- Encabezado --> <div class="flex flex-col md:flex-row items-center justify-between mb-6"> <div class="flex items-center space-x-3"> <!-- Ícono de Solicitudes --> <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m2 8a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v8a2 2 0 002 2h8z" /> </svg> <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Solicitudes</h1> </div> <div class="mt-4 md:mt-0"> <a href="{{ route('admin.solicitud.create') }}" class="flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition"> <!-- Ícono de agregar --> <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20"> <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /> </svg> Nueva Solicitud </a> </div> </div>

        <!-- Tabla de Solicitudes -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3 text-left">Solicitante</th>
                        <th class="px-4 py-3 text-left">Técnico</th>
                        <th class="px-4 py-3 text-left">Equipo / Archivo</th>
                        <th class="px-4 py-3 text-left">Descripción</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-left">Prioridad</th>
                        <th class="px-4 py-3 text-left">Atenciones</th>
                        <th class="px-4 py-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @if(auth()->user()->hasRole('admin'))
                        @foreach ($solicitudes as $solicitud)
                            @php
                                // Definir clases según estado
                                switch($solicitud->estado) {
                                    case 'pendiente':
                                        $estadoClasses = 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'en proceso':
                                        $estadoClasses = 'bg-blue-100 text-blue-800';
                                        break;
                                    case 'finalizada':
                                        $estadoClasses = 'bg-green-100 text-green-800';
                                        break;
                                    case 'cancelada':
                                        $estadoClasses = 'bg-red-100 text-red-800';
                                        break;
                                    default:
                                        $estadoClasses = 'bg-gray-100 text-gray-800';
                                        break;
                                }
                                // Definir clases según prioridad
                                switch($solicitud->prioridad) {
                                    case 'alta':
                                        $prioridadClasses = 'bg-red-100 text-red-800';
                                        break;
                                    case 'media':
                                        $prioridadClasses = 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'baja':
                                        $prioridadClasses = 'bg-green-100 text-green-800';
                                        break;
                                    default:
                                        $prioridadClasses = 'bg-gray-100 text-gray-800';
                                        break;
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $solicitud->solicitanteUser->name }}</td>
                                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">
                                    {{ $solicitud->tecnicoUser ? $solicitud->tecnicoUser->name : 'Sin asignar' }}
                                </td>
                                <td class="px-4 py-2">
                                    @if($solicitud->archivo)
                                        <a href="{{ route('archivo.mostrar', ['archivo' => $solicitud->archivo]) }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                            Ver Archivo
                                        </a>
                                    @elseif($solicitud->equipo_id)
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Código: {{ $solicitud->equipo_id }}</span>
                                    @else
                                        <span class="text-sm text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $solicitud->descripcion }}</td>
                                <td class="px-4 py-2">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $estadoClasses }}">
                                        {{ $solicitud->estado }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $prioridadClasses }}">
                                        {{ $solicitud->prioridad ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    @if($solicitud->atenciones->isNotEmpty())
                                        <ul class="list-disc list-inside">
                                            @foreach($solicitud->atenciones as $atencion)
                                                <li class="truncate">
                                                    <a href="{{ route('admin.atencion.anotaciones', $atencion) }}" target="_blank" class="text-blue-600 hover:underline">
                                                        {{ $atencion->id }} - {{ Str::limit($atencion->descripcion, 30) }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-sm text-gray-500">Sin atenciones</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 flex space-x-2">
                                    <a href="{{ route('admin.solicitud.edit', $solicitud) }}" class="flex items-center text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M17.414 2.586a2 2 0 010 2.828l-1.586 1.586-2.828-2.828 1.586-1.586a2 2 0 012.828 0z" />
                                            <path d="M2 13.5V18h4.5l9.793-9.793-4.5-4.5L2 13.5z" />
                                        </svg>
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.solicitud.destroy', $solicitud) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Estás seguro de eliminar esta solicitud?')" class="flex items-center text-red-600 hover:text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7 4H4a1 1 0 000 2h1v10a2 2 0 002 2h6a2 2 0 002-2V6h1a1 1 0 100-2h-3l-.106-.447A1 1 0 0011 2H9zM7 6v10a1 1 0 001 1h4a1 1 0 001-1V6H7z" clip-rule="evenodd" />
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @elseif(auth()->user()->hasRole('tecnico'))
                        @foreach ($solicitudes as $solicitud)
                            @if($solicitud->tecnico == auth()->user()->id)
                                @php
                                    switch($solicitud->estado) {
                                        case 'pendiente':
                                            $estadoClasses = 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'en proceso':
                                            $estadoClasses = 'bg-blue-100 text-blue-800';
                                            break;
                                        case 'finalizada':
                                            $estadoClasses = 'bg-green-100 text-green-800';
                                            break;
                                        case 'cancelada':
                                            $estadoClasses = 'bg-red-100 text-red-800';
                                            break;
                                        default:
                                            $estadoClasses = 'bg-gray-100 text-gray-800';
                                            break;
                                    }
                                    switch($solicitud->prioridad) {
                                        case 'alta':
                                            $prioridadClasses = 'bg-red-100 text-red-800';
                                            break;
                                        case 'media':
                                            $prioridadClasses = 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'baja':
                                            $prioridadClasses = 'bg-green-100 text-green-800';
                                            break;
                                        default:
                                            $prioridadClasses = 'bg-gray-100 text-gray-800';
                                            break;
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $solicitud->solicitanteUser->name }}</td>
                                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100">
                                        {{ $solicitud->tecnicoUser ? $solicitud->tecnicoUser->name : 'Sin asignar' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($solicitud->archivo)
                                            <a href="{{ route('archivo.mostrar', ['archivo' => $solicitud->archivo]) }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                                Ver Archivo
                                            </a>
                                        @elseif($solicitud->equipo_id)
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Código: {{ $solicitud->equipo_id }}</span>
                                        @else
                                            <span class="text-sm text-gray-500">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $solicitud->descripcion }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $estadoClasses }}">
                                            {{ $solicitud->estado }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $prioridadClasses }}">
                                            {{ $solicitud->prioridad ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($solicitud->atenciones->isNotEmpty())
                                            <ul class="list-disc list-inside">
                                                @foreach($solicitud->atenciones as $atencion)
                                                    <li class="truncate">
                                                        <a href="{{ route('admin.atencion.anotaciones', $atencion) }}" target="_blank" class="text-blue-600 hover:underline">
                                                            {{ $atencion->id }} - {{ Str::limit($atencion->descripcion, 30) }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-sm text-gray-500">Sin atenciones</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 flex space-x-2">
                                        <a href="{{ route('admin.solicitud.edit', $solicitud) }}" class="flex items-center text-blue-600 hover:text-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M17.414 2.586a2 2 0 010 2.828l-1.586 1.586-2.828-2.828 1.586-1.586a2 2 0 012.828 0z" />
                                                <path d="M2 13.5V18h4.5l9.793-9.793-4.5-4.5L2 13.5z" />
                                            </svg>
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.solicitud.destroy', $solicitud) }}" method="POST" class="flex items-center">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar esta solicitud?')" class="flex items-center text-red-600 hover:text-red-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7 4H4a1 1 0 000 2h1v10a2 2 0 002 2h6a2 2 0 002-2V6h1a1 1 0 100-2h-3l-.106-.447A1 1 0 0011 2H9zM7 6v10a1 1 0 001 1h4a1 1 0 001-1V6H7z" clip-rule="evenodd" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $solicitudes->links() }}
        </div>
    </div>
    </x-layouts.app>
