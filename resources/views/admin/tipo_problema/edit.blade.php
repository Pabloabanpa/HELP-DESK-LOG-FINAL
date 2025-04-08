<x-layouts.app :title="__('Editar Tipo de Problema')">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Editar Tipo de Problema</h1>
        <form action="{{ route('admin.tipo_problema.update', $tipoProblema) }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 dark:text-gray-300">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $tipoProblema->nombre) }}" class="w-full border border-gray-300 rounded p-2" required>
            </div>
            <div class="mb-4">
                <label for="descripcion" class="block text-gray-700 dark:text-gray-300">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="w-full border border-gray-300 rounded p-2" rows="3">{{ old('descripcion', $tipoProblema->descripcion) }}</textarea>
            </div>
            <div class="mb-4">
                <label for="area_solucion" class="block text-gray-700 dark:text-gray-300">Área de Solución</label>
                <input type="text" name="area_solucion" id="area_solucion" value="{{ old('area_solucion', $tipoProblema->area_solucion) }}" class="w-full border border-gray-300 rounded p-2">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Actualizar</button>
            </div>
        </form>
    </div>
</x-layouts.app>
