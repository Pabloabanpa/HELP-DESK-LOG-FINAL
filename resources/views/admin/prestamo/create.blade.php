<x-layouts.app :title="__('Crear Préstamo')">
    <div class="max-w-3xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Crear Préstamo</h1>

        <!-- Muestra errores de validación -->
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.prestamo.store') }}" method="POST">
            @csrf
            <!-- La descripción es opcional -->
            <div class="mb-4">
                <label for="descripcion" class="block font-medium">Mateial Solicitado</label>
                <textarea name="descripcion" id="descripcion" rows="3" class="w-full border rounded p-2">{{ old('descripcion') }}</textarea>
            </div>

            <!-- El estado es opcional -->
            @can('admin.prestamo.edit')
            <div class="mb-4">
                <label for="estado" class="block font-medium">Estado</label>
                <input type="text" name="estado" id="estado" value="{{ old('estado') }}"
                       class="w-full border rounded p-2" placeholder="Por ejemplo: Pendiente, Aprobado, etc.">
            </div>
            @endcan

            <div class="flex space-x-4">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Guardar
                </button>
                <a href="{{ route('admin.prestamo.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
