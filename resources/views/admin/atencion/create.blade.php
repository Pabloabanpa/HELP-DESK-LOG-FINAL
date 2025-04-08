<x-layouts.app :title="__('Nueva Atención')">
    <div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
        <h1 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-white">Crear Atención</h1>
        <form action="{{ route('admin.atencion.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Seleccionar Solicitud -->
            <div>
                <label for="solicitud_id" class="block font-medium text-gray-700 dark:text-gray-300">Solicitud</label>
                <select name="solicitud_id" id="solicitud_id"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Seleccione una solicitud --</option>
                    @foreach ($solicitudes as $solicitud)
                        <option value="{{ $solicitud->id }}">
                            {{ $solicitud->id }} - {{ Str::limit($solicitud->descripcion, 30) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Descripción de la Atención -->
            <div>
                <label for="descripcion" class="block font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="4"
                          class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                          placeholder="Detalle la atención..."></textarea>
            </div>

            <!-- Fecha de Inicio (opcional) -->
            <div>
                <label for="fecha_inicio" class="block font-medium text-gray-700 dark:text-gray-300">Fecha de Inicio</label>
                <input type="datetime-local" name="fecha_inicio" id="fecha_inicio"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Fecha de Fin (opcional) -->
            <div>
                <label for="fecha_fin" class="block font-medium text-gray-700 dark:text-gray-300">Fecha de Fin</label>
                <input type="datetime-local" name="fecha_fin" id="fecha_fin"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Botón de Envío -->
            <div class="text-center">
                <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                    Crear Atención
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
