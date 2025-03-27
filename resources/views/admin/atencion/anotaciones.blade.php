<x-layouts.app :title="'Detalle de Atención #'.$atencion->id">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Detalles de la atención -->
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Detalle de Atención #{{ $atencion->id }}</h1>
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
            <p><strong>Solicitud:</strong>
                @if($atencion->solicitud)
                    {{ $atencion->solicitud->id }} - {{ Str::limit($atencion->solicitud->descripcion, 30) }}
                @else
                    N/A
                @endif
            </p>
            <p><strong>Descripción:</strong> {{ $atencion->descripcion }}</p>
            <p><strong>Estado:</strong> {{ $atencion->estado }}</p>
            <p>
                <strong>Fecha Inicio:</strong>
                {{ $atencion->fecha_inicio ? \Carbon\Carbon::parse($atencion->fecha_inicio)->format('d/m/Y H:i') : 'N/A' }}
            </p>
            <p>
                <strong>Fecha Fin:</strong>
                {{ $atencion->fecha_fin ? \Carbon\Carbon::parse($atencion->fecha_fin)->format('d/m/Y H:i') : 'N/A' }}
            </p>
        </div>

        <!-- Sección de Anotaciones -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Anotaciones</h2>
            @if($atencion->anotaciones->isEmpty())
                <p class="text-gray-600 dark:text-gray-300">No hay anotaciones para esta atención.</p>
            @else
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-left">Descripción</th>
                                <th class="px-4 py-3 text-left">Material Usado</th>
                                <th class="px-4 py-3 text-left">Fecha Registro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($atencion->anotaciones as $anotacion)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    <td class="px-4 py-2">{{ Str::limit($anotacion->descripcion, 50) }}</td>
                                    <td class="px-4 py-2">{{ Str::limit($anotacion->material_usado, 50) }}</td>
                                    <td class="px-4 py-2">{{ $anotacion->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Sección para agregar nueva anotación -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Agregar Nueva Anotación</h2>
            <form action="{{ route('admin.anotacion.store') }}" method="POST" class="bg-white dark:bg-gray-800 shadow rounded p-6">
                @csrf
                <!-- Se envía el ID de la atención de forma oculta -->
                <input type="hidden" name="atencion_id" value="{{ $atencion->id }}">
                <div class="mb-4">
                    <label for="descripcion" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    @error('descripcion')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="material_usado" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Material Usado</label>
                    <textarea name="material_usado" id="material_usado" rows="2" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    @error('material_usado')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Agregar Anotación</button>
                </div>
            </form>
        </div>

        <!-- Botón para regresar a la lista de atenciones -->
        <div class="mt-6">
            <a href="{{ route('admin.atencion.index') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                Volver a Atenciones
            </a>
        </div>
    </div>
</x-layouts.app>
