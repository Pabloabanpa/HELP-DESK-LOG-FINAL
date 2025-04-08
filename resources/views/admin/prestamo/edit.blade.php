<x-layouts.app :title="__('Editar Préstamo')">
    <div class="max-w-3xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Editar Préstamo</h1>

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

        <form action="{{ route('admin.prestamo.update', $prestamo) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="descripcion" class="block font-medium">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3" class="w-full border rounded p-2">{{ old('descripcion', $prestamo->descripcion) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="estado" class="block font-medium">Estado</label>
                <select name="estado" id="estado" class="w-full border rounded p-2">
                    <option value="">-- Seleccione un estado --</option>
                    <option value="pendiente" {{ old('estado', $prestamo->estado)=='pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="aprobado" {{ old('estado', $prestamo->estado)=='aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="rechazado" {{ old('estado', $prestamo->estado)=='rechazado' ? 'selected' : '' }}>Rechazado</option>
                    <!-- Agrega las opciones que necesites -->
                </select>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Actualizar
                </button>
                <a href="{{ route('admin.prestamo.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Cancelar
                </a>
            </div>
            
        </form>
    </div>
</x-layouts.app>
