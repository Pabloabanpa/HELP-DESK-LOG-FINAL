<x-layouts.app :title="__('Listado de Solicitudes')">
    <!-- Contenedor Alpine.js para el modal -->
    <div x-data="{ showModal: false, lat: '', lng: '' }" class="relative">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <!-- Encabezado: Título y botón para crear nueva solicitud -->
            <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-4">
                @can('admin.solicitud.create')
                    <a href="{{ route('admin.solicitud.create') }}"
                       class="flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg shadow hover:bg-green-700 transition duration-200">
                        <!-- Ícono de más -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                  clip-rule="evenodd" />
                        </svg>
                        Nueva Solicitud
                    </a>

                    @endcan
                    @can('admin.solicitud.edit')
                    <a href="{{ route('admin.solicitud.index') }}"
                       class="flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg shadow hover:bg-red-700 transition duration-200">
                        <!-- Ícono PDF -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4" />
                        </svg>
                        Generar Reporte PDF
                    </a>
                @endcan

                @can('admin.solicitud.edit')
                <form method="GET" action="{{ route('admin.solicitud.reporte.estadisticas') }}" class="flex items-center gap-2 mb-6">
                    <label for="inicio">Desde:</label>
                    <input type="date" name="inicio" id="inicio" required class="border rounded px-2 py-1">

                    <label for="fin">Hasta:</label>
                    <input type="date" name="fin" id="fin" required class="border rounded px-2 py-1">

                    <button type="submit"
                            class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                        Descargar Estadísticas PDF
                    </button>
                </form>

                @endcan


            </div>


            <!-- Tarjetas de Contadores -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="p-4 bg-white shadow rounded">
                    <span class="text-xl font-bold">{{ $solicitudes->total() }}</span>
                    <span class="text-gray-500">Solicitudes encontradas</span>
                </div>
                @can('admin.solicitud.edit')
                    <div class="p-4 bg-white shadow rounded">
                        <span class="text-xl font-bold">{{ \App\Models\User::count() }}</span>
                        <span class="text-gray-500">Usuarios registrados</span>
                    </div>
                @endcan
            </div>

            <!-- Botón para reenviar solicitudes pendientes con más de 7 días -->
            @can('admin.solicitud.edit')
                <div class="mb-6">
                    <form action="{{ route('admin.solicitud.reenviarPendientes') }}" method="POST"
                          onsubmit="return confirm('¿Deseas reenviar automáticamente las solicitudes pendientes con más de 7 días?')">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-lg shadow hover:from-orange-600 hover:to-orange-700 transition duration-200">
                            Reenviar Pendientes (7+ días)
                        </button>
                    </form>
                </div>
            @endcan

            @php
                // Se obtiene la colección actual del paginador para las gráficas
                $solicitudCollection = $solicitudes->getCollection();
                $estadoCounts = $solicitudCollection->groupBy('estado')->map->count();
                $prioridadCounts = $solicitudCollection->groupBy('prioridad')->map->count();
            @endphp

            <!-- Sección de Gráficas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Gráfico de pastel para Estados -->
                <div class="bg-white p-4 shadow rounded">
                    <h2 class="text-lg font-semibold mb-4">Estado de Solicitudes</h2>
                    <div class="relative h-64">
                        <canvas id="estadoChart" class="w-full h-full"></canvas>
                    </div>
                </div>
                <!-- Gráfico de barras para Prioridades (solo para admin) -->
                @can('admin.solicitud.edit')
                    <div class="bg-white p-4 shadow rounded">
                        <h2 class="text-lg font-semibold mb-4">Prioridad de Solicitudes</h2>
                        <div class="relative h-64">
                            <canvas id="prioridadChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                @endcan
                <!-- Tabla: Solicitudes por Areas -->
                @can('admin.solicitud.edit')
                <div class="bg-white p-4 shadow rounded">
                    <h2 class="text-lg font-semibold mb-4">Solicitudes por Área</h2>
                    <div class="relative h-64">
                        <canvas id="areaChart"></canvas>
                    </div>
                </div>
                @endcan
            </div>

            <!-- Listado General de Solicitudes (con paginación) -->
            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 mb-8">
                <h2 class="text-xl font-bold mb-4">
                    @can('admin.solicitud.edit')
                        Listado de todas las solicitudes enviadas
                    @else
                        Mis Solicitudes enviadas
                    @endcan
                </h2>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left">Solicitante</th>
                            <th class="px-4 py-3 text-left">Técnico</th>
                            <th class="px-4 py-3 text-left">Equipo / Archivo</th>
                            <th class="px-4 py-3 text-left">Descripción</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-left">Prioridad</th>
                            <th class="px-4 py-3 text-left">Tiempo transcurrido</th>
                            <th class="px-4 py-3 text-left">Atenciones</th>
                            <th class="px-4 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($solicitudes as $solicitud)
                            @php
                                // Definir clases según estado
                                switch(strtolower($solicitud->estado)) {
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
                                    case 'rechazada':
                                        $estadoClasses = 'bg-red-100 text-red-800';
                                        break;
                                    default:
                                        $estadoClasses = 'bg-gray-100 text-gray-800';
                                        break;
                                }
                                // Definir clases según prioridad
                                switch(strtolower($solicitud->prioridad)) {
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
                                // Calcular los días enteros transcurridos desde la creación
                                $dias = (int) $solicitud->created_at->diffInDays(now());
                                if ($dias < 3) {
                                    $tiempoBadge = 'bg-green-100 text-green-800';
                                } elseif ($dias < 7) {
                                    $tiempoBadge = 'bg-yellow-100 text-yellow-800';
                                } else {
                                    $tiempoBadge = 'bg-red-100 text-red-800';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-4 py-2">{{ $solicitud->solicitanteUser->name }}</td>
                                <td class="px-4 py-2">
                                    {{ $solicitud->tecnicoUser ? $solicitud->tecnicoUser->name : 'Sin asignar' }}
                                </td>
                                <td class="px-4 py-2">
                                    @if($solicitud->archivo)
                                        <a href="{{ route('archivo.mostrar', ['archivo' => $solicitud->archivo]) }}" target="_blank"
                                           class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                            Ver Archivo
                                        </a>
                                    @elseif($solicitud->equipo_id)
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Código: {{ $solicitud->equipo_id }}</span>
                                    @else
                                        <span class="text-sm text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ Str::limit($solicitud->descripcion, 50) }}</td>
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
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $tiempoBadge }}">
                                        {{ $dias }} día{{ $dias != 1 ? 's' : '' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $solicitud->atenciones->count() }}</td>
                                <td class="px-4 py-2 flex flex-col space-y-1">
                                    <!-- Botón para Editar -->
                                    @can('admin.solicitud.edit')
                                        <a href="{{ route('admin.solicitud.edit', $solicitud) }}"
                                           class="flex items-center px-3 py-1 bg-blue-600 text-white rounded shadow hover:bg-blue-700 transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M17.414 2.586a2 2 0 010 2.828l-1.586 1.586-2.828-2.828 1.586-1.586a2 2 0 012.828 0z" />
                                                <path d="M2 13.5V18h4.5l9.793-9.793-4.5-4.5L2 13.5z" />
                                            </svg>
                                            Editar
                                        </a>
                                    @endcan

                                    <!-- Botón para Eliminar -->
                                    @can('admin.solicitud.destroy')
                                        <form action="{{ route('admin.solicitud.destroy', $solicitud) }}" method="POST" class="flex items-center">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar esta solicitud?')"
                                                    class="flex items-center px-3 py-1 bg-red-600 text-white rounded shadow hover:bg-red-700 transition duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7 4H4a1 1 0 000 2h1v10a2 2 0 002 2h6a2 2 0 002-2V6h1a1 1 0 100-2h-3l-.106-.447A1 1 0 0011 2H9zM7 6v10a1 1 0 001 1h4a1 1 0 001-1V6H7z" clip-rule="evenodd" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    @endcan

                                    <!-- Botón para ver Atenciones -->
                                    @can('admin.solicitud.edit')
                                    <a href="{{ route('admin.solicitud.edit', $solicitud) }}"
                                       class="flex items-center px-3 py-1 bg-purple-600 text-white rounded shadow hover:bg-purple-700 transition duration-200 text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Atenciones
                                    </a>
                                    @endcan

                                    <!-- Botón para reenviar solicitud individual (si no tiene técnico asignado) -->
                                    @if(is_null($solicitud->tecnico))
                                        <form action="{{ route('admin.solicitud.reenviar', $solicitud) }}" method="POST"
                                              onsubmit="return confirm('¿Deseas reenviar esta solicitud?')" class="flex items-center">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded shadow hover:from-orange-600 hover:to-orange-700 transition duration-200 text-xs">
                                                Reenviar Solicitud
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Botón para ver Ubicación en modal (tomando datos del usuario, latitude y longitude) -->
                                    @if($solicitud->solicitanteUser && $solicitud->solicitanteUser->latitude && $solicitud->solicitanteUser->longitude)
                                        <button type="button"
                                                @click="lat='{{ $solicitud->solicitanteUser->latitude }}'; lng='{{ $solicitud->solicitanteUser->longitude }}'; showModal=true"
                                                class="flex items-center px-3 py-1 bg-teal-600 text-white rounded shadow hover:bg-teal-700 transition duration-200 text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M17.657 16.657L13.414 20.9a1.5 1.5 0 01-2.121 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Ubicación
                                        </button>
                                    @endif
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

        <!-- Modal para mostrar la ubicación (usando Alpine.js) -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 z-50" x-transition>
            <div class="bg-white rounded-lg p-6 w-full max-w-lg relative">
                <button @click="showModal=false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-2xl">&times;</button>
                <h2 class="text-xl font-bold mb-4">Ubicación de la Solicitud</h2>
                <div class="w-full h-64">
                    <iframe :src="`https://maps.google.com/maps?q=${lat},${lng}&hl=es;z=14&amp;output=embed`" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        @can('admin.solicitud.edit')
        <div class="mb-8">
            <h2 class="text-xl font-bold mb-4">Seguimiento de Solicitudes</h2>

            @php
                $areaCounts = $solicitudCollection->groupBy(fn($s) => optional($s->solicitanteUser)->area)->map->count();
                $solicitudesPendientes = $solicitudCollection->filter(fn($s) => strtolower($s->estado) == 'pendiente');
                $solicitudesEnProceso = $solicitudCollection->filter(fn($s) => strtolower($s->estado) == 'en proceso');
                // Consideramos rechazadas aquellas con estado 'rechazada' o 'cancelada'
                $solicitudesRechazadas = $solicitudCollection->filter(fn($s) => in_array(strtolower($s->estado), ['rechazada', 'cancelada']));
            @endphp

            <!-- Tabla: Solicitudes Pendientes -->
            @can('admin.solicitud.edit')
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Solicitudes Pendientes ({{ $solicitudesPendientes->count() }})</h3>
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-left">Solicitante</th>
                                <th class="px-4 py-3 text-left">Descripción</th>
                                <th class="px-4 py-3 text-left">Estado</th>
                                <th class="px-4 py-3 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($solicitudesPendientes as $sol)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    <td class="px-4 py-2">{{ $sol->solicitanteUser->name }}</td>
                                    <td class="px-4 py-2">{{ $sol->descripcion }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">
                                            {{ $sol->estado }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <!-- Ejemplo de acción: Ver detalle -->
                                        <a href="{{ route('admin.solicitud.edit', $sol) }}" class="text-blue-600 hover:underline">Ver</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            <!-- Tabla: Solicitudes En Proceso -->
            @can('admin.solicitud.edit')
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Solicitudes En Proceso ({{ $solicitudesEnProceso->count() }})</h3>
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-left">Solicitante</th>
                                <th class="px-4 py-3 text-left">Descripción</th>
                                <th class="px-4 py-3 text-left">Estado</th>
                                <th class="px-4 py-3 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($solicitudesEnProceso as $sol)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    <td class="px-4 py-2">{{ $sol->solicitanteUser->name }}</td>
                                    <td class="px-4 py-2">{{ $sol->descripcion }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                            {{ $sol->estado }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('admin.solicitud.edit', $sol) }}" class="text-blue-600 hover:underline">Ver</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            <!-- Tabla: Solicitudes Rechazadas -->
            @can('admin.solicitud.edit')
            <div>
                <h3 class="text-lg font-semibold mb-2">Solicitudes Rechazadas ({{ $solicitudesRechazadas->count() }})</h3>
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-left">Solicitante</th>
                                <th class="px-4 py-3 text-left">Descripción</th>
                                <th class="px-4 py-3 text-left">Estado</th>
                                <th class="px-4 py-3 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($solicitudesRechazadas as $sol)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    <td class="px-4 py-2">{{ $sol->solicitanteUser->name }}</td>
                                    <td class="px-4 py-2">{{ $sol->descripcion }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                                            {{ $sol->estado }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('admin.solicitud.edit', $sol) }}" class="text-blue-600 hover:underline">Ver</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan


        </div>
        @endcan
    </div>

    <!-- Inclusión de Chart.js desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de pastel para la distribución de Estados
        var ctxEstado = document.getElementById('estadoChart').getContext('2d');
        new Chart(ctxEstado, {
            type: 'pie',
            data: {
                labels: {!! json_encode($estadoCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($estadoCounts->values()) !!},
                    backgroundColor: ['#FBBF24', '#60A5FA', '#34D399', '#F87171', '#A3A3A3']
                }]
            },
            options: { responsive: true }
        });

        // Gráfico de barras para la distribución de Prioridades (solo para admin)
        @can('admin.solicitud.edit')
            var ctxPrioridad = document.getElementById('prioridadChart').getContext('2d');
            new Chart(ctxPrioridad, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($prioridadCounts->keys()) !!},
                    datasets: [{
                        label: 'Solicitudes por Prioridad',
                        data: {!! json_encode($prioridadCounts->values()) !!},
                        backgroundColor: ['#F87171', '#FBBF24', '#34D399', '#60A5FA', '#A3A3A3']
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });


        var ctxArea = document.getElementById('areaChart').getContext('2d');
        new Chart(ctxArea, {
            type: 'bar',
            data: {
                labels: {!! json_encode($areaCounts->keys()) !!},
                datasets: [{
                    label: 'Solicitudes por Área',
                    data: {!! json_encode($areaCounts->values()) !!},
                    backgroundColor: '#6366F1'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        @endcan
    </script>
</x-layouts.app>
