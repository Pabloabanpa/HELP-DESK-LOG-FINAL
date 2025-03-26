<x-layouts.app :title="__('Editar Atención')">
    <div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-800 shadow-lg rounded-lg">
        <h1 class="text-3xl font-bold text-center mb-6">Editar Atención</h1>
        <form action="{{ route('admin.atencion.update', $atencion) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Mostrar la Solicitud asociada (solo lectura) -->
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Solicitud</label>
                <input type="text" value="{{ $atencion->solicitud ? $atencion->solicitud->id.' - '.Str::limit($atencion->solicitud->descripcion, 30) : 'N/A' }}" disabled
                       class="mt-1 block w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-gray-300">
            </div>

            <!-- Descripción -->
            <div>
                <label for="descripcion" class="block font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="4" class="mt-1 block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('descripcion', $atencion->descripcion) }}</textarea>
            </div>

            <!-- Estado -->
            <div>
                <label for="estado" class="block font-medium text-gray-700 dark:text-gray-300">Estado</label>
                <select name="estado" id="estado" class="mt-1 block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="en proceso" {{ old('estado', $atencion->estado) == 'en proceso' ? 'selected' : '' }}>En Proceso</option>
                    <option value="finalizada" {{ old('estado', $atencion->estado) == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                    <option value="cancelada" {{ old('estado', $atencion->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            <!-- Fecha de Inicio -->
            <div>
                <label for="fecha_inicio" class="block font-medium text-gray-700 dark:text-gray-300">Fecha de Inicio</label>
                <input type="datetime-local" name="fecha_inicio" id="fecha_inicio"
                       value="{{ old('fecha_inicio', $atencion->fecha_inicio ? \Carbon\Carbon::parse($atencion->fecha_inicio)->format('Y-m-d\TH:i') : '') }}"
                       class="mt-1 block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Fecha de Fin -->
            <div>
                <label for="fecha_fin" class="block font-medium text-gray-700 dark:text-gray-300">Fecha de Fin</label>
                <input type="datetime-local" name="fecha_fin" id="fecha_fin"
                       value="{{ old('fecha_fin', $atencion->fecha_fin ? \Carbon\Carbon::parse($atencion->fecha_fin)->format('Y-m-d\TH:i') : '') }}"
                       class="mt-1 block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="text-center">
                <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                    Actualizar Atención
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
