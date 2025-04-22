<x-layouts.app :title="__('Editar Solicitud')">
    <div class="max-w-3xl mx-auto py-6">
        {{-- Si la solicitud ya está finalizada, mostramos solo los campos de feedback --}}
        @if($solicitud->estado === 'finalizada')
            <form action="{{ route('admin.solicitud.update', $solicitud) }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
                @csrf @method('PUT')

                <h2 class="text-xl font-semibold mb-4">Calificar Solicitud #{{ $solicitud->id }}</h2>

                <div>
                    <label for="puntuacion" class="block font-medium text-gray-700 dark:text-gray-300">Puntuación (1–5)</label>
                    <input type="number" name="puntuacion" id="puntuacion" min="1" max="5"
                           value="{{ old('puntuacion', $solicitud->puntuacion) }}"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                </div>

                <div>
                    <label for="comentario" class="block font-medium text-gray-700 dark:text-gray-300">Comentario</label>
                    <textarea name="comentario" id="comentario" rows="3"
                              class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ old('comentario', $solicitud->comentario) }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">
                    Guardar Feedback
                </button>
            </form>
        @else
            {{-- Formulario completo de edición --}}
            <form action="{{ route('admin.solicitud.update', $solicitud) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded shadow">
                @csrf @method('PUT')

                <!-- Solicitante (solo lectura) -->
                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300">Solicitante</label>
                    <input type="text" value="{{ $solicitud->solicitanteUser->name }}" disabled
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                </div>

                <!-- Técnico -->
                <div>
                    <label for="tecnico" class="block font-medium text-gray-700 dark:text-gray-300">Técnico Asignado</label>
                    <select name="tecnico" id="tecnico" class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Seleccione un técnico --</option>
                        @foreach($tecnicos as $tecnico)
                            @if($tecnico->hasRole('tecnico'))
                                <option value="{{ $tecnico->id }}" {{ $solicitud->tecnico == $tecnico->id ? 'selected' : '' }}>{{ $tecnico->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                @php $useArchivo = empty($solicitud->equipo_id); @endphp
                <!-- Equipo / Archivo -->
                <div id="equipoSection" class="{{ $useArchivo ? 'hidden' : '' }}">
                    <label for="equipo_id" class="block font-medium text-gray-700 dark:text-gray-300">Código de Equipo</label>
                    <input type="text" name="equipo_id" id="equipo_id" value="{{ old('equipo_id', $solicitud->equipo_id) }}"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="uploadCheckbox" name="upload_file" value="1" class="mr-2" onclick="toggleEquipoSectionEdit()" {{ $useArchivo ? 'checked' : '' }}>
                    <label for="uploadCheckbox" class="text-gray-700 dark:text-gray-300">No tengo código de equipo, subir archivo</label>
                </div>
                <div id="fileSection" class="{{ $useArchivo ? '' : 'hidden' }}">
                    <label for="archivo" class="block font-medium text-gray-700 dark:text-gray-300">
                        @if($solicitud->archivo) Archivo Actual @else Subir Archivo @endif
                    </label>
                    @if($solicitud->archivo && !$useArchivo)
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Archivo: {{ $solicitud->archivo }}</p>
                    @endif
                    <input type="file" name="archivo" id="archivo" class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Tipo Problema -->
                <div>
                    <label for="tipo_problema" class="block font-medium text-gray-700 dark:text-gray-300">Tipo de Problema</label>
                    <select name="tipo_problema" id="tipo_problema" class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Seleccione un tipo --</option>
                        @foreach($tipoProblemas as $tipo)
                            <option value="{{ $tipo->id }}" {{ old('tipo_problema', $solicitud->tipo_problema) == $tipo->id ? 'selected' : '' }}>{{ $tipo->nombre }} - {{ $tipo->descripcion }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="block font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4" class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ old('descripcion', $solicitud->descripcion) }}</textarea>
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block font-medium text-gray-700 dark:text-gray-300">Estado</label>
                    <select name="estado" id="estado" class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="pendiente" {{ old('estado', $solicitud->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en proceso" {{ old('estado', $solicitud->estado) == 'en proceso' ? 'selected' : '' }}>En Proceso</option>
                        <option value="finalizada" {{ old('estado', $solicitud->estado) == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                        <option value="cancelada" {{ old('estado', $solicitud->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>

                <!-- Prioridad -->
                <div>
                    <label for="prioridad" class="block font-medium text-gray-700 dark:text-gray-300">Prioridad</label>
                    <select name="prioridad" id="prioridad" class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Seleccione --</option>
                        <option value="alta" {{ old('prioridad', $solicitud->prioridad) == 'alta' ? 'selected' : '' }}>Alta</option>
                        <option value="media" {{ old('prioridad', $solicitud->prioridad) == 'media' ? 'selected' : '' }}>Media</option>
                        <option value="baja" {{ old('prioridad', $solicitud->prioridad) == 'baja' ? 'selected' : '' }}>Baja</option>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        Actualizar Solicitud
                    </button>
                </div>
            </form>
        @endif
    </div>

    <script>
        function toggleEquipoSectionEdit() {
            const chk = document.getElementById('uploadCheckbox');
            document.getElementById('fileSection').classList.toggle('hidden', !chk.checked);
            document.getElementById('equipoSection').classList.toggle('hidden', chk.checked);
        }
    </script>
</x-layouts.app>
