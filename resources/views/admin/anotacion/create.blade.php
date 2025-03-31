<x-layouts.app :title="__('Nueva Anotación')">
    <div class="max-w-3xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Nueva Anotación</h1>
        <form action="{{ route('admin.anotacion.store') }}" method="POST" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            @csrf

            <!-- Atención (selección) -->
            <div class="mb-4">
                <label for="atencion_id" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Atención</label>
                <select name="atencion_id" id="atencion_id"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Seleccione una atención</option>
                    @foreach($atenciones as $id => $descripcion)
                        <option value="{{ $id }}" {{ old('atencion_id') == $id ? 'selected' : '' }}>
                            {{ $id }} - {{ $descripcion }}
                        </option>
                    @endforeach
                </select>
                @error('atencion_id')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="mb-4">
                <label for="descripcion" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="4"
                          class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Material Usado -->
            <div class="mb-4">
                <label for="material_usado" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Material Usado</label>
                <textarea name="material_usado" id="material_usado" rows="3"
                          class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('material_usado') }}</textarea>
                @error('material_usado')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end">
                <a href="{{ route('admin.anotacion.index') }}" class="mr-4 text-gray-600 dark:text-gray-300 hover:underline">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
