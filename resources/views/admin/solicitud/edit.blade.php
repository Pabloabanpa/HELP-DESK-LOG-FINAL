<x-layouts.app :title="__('Editar Solicitud')">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Columna izquierda: Formulario de Edición -->
            <div class="lg:w-2/3 p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <h1 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-white">Editar Solicitud</h1>
                <form action="{{ route('admin.solicitud.update', $solicitud) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Solicitante (solo lectura) -->
                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-300">Solicitante</label>
                        <input type="text" value="{{ $solicitud->solicitanteUser->name }}" disabled
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    </div>

                    <!-- Técnico -->
                    <div>
                        <label for="tecnico" class="block font-medium text-gray-700 dark:text-gray-300">Técnico Asignado</label>
                        <select name="tecnico" id="tecnico"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Seleccione un técnico --</option>
                            @foreach($tecnicos as $tecnico)
                                @if($tecnico->hasRole('tecnico'))
                                    <option value="{{ $tecnico->id }}" {{ $solicitud->tecnico == $tecnico->id ? 'selected' : '' }}>
                                        {{ $tecnico->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Código de Equipo / Archivo -->
                    <div id="equipoSection" class="{{ $solicitud->equipo_id ? '' : 'hidden' }}">
                        <label for="equipo_id" class="block font-medium text-gray-700 dark:text-gray-300">Código de Equipo</label>
                        <input type="text" name="equipo_id" id="equipo_id" value="{{ old('equipo_id', $solicitud->equipo_id) }}"
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="uploadCheckbox" name="upload_file" value="1" class="mr-2" onclick="toggleEquipoSectionEdit()"
                        {{ $solicitud->archivo ? 'checked' : '' }}>
                        <label for="uploadCheckbox" class="text-gray-700 dark:text-gray-300">No tengo código de equipo, subir archivo</label>
                    </div>
                    <div id="fileSection" class="{{ $solicitud->archivo ? '' : 'hidden' }}">
                        <label for="archivo" class="block font-medium text-gray-700 dark:text-gray-300">Archivo Actual</label>
                        @if($solicitud->archivo)
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Archivo cargado: {{ $solicitud->archivo }}</p>
                        @endif
                        <label for="archivo" class="block font-medium text-gray-700 dark:text-gray-300">Cambiar Archivo</label>
                        <input type="file" name="archivo" id="archivo"
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Tipo de Problema (dinámico desde la tabla) -->
                    <div>
                        <label for="tipo_problema" class="block font-medium text-gray-700 dark:text-gray-300">Tipo de Problema</label>
                        <select name="tipo_problema" id="tipo_problema"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Seleccione un tipo de problema --</option>
                            @foreach($tipoProblemas as $tipo)
                                <option value="{{ $tipo->id }}" {{ old('tipo_problema', $solicitud->tipo_problema) == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }} - {{ $tipo->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="descripcion" class="block font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="4"
                                  class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('descripcion', $solicitud->descripcion) }}</textarea>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="estado" class="block font-medium text-gray-700 dark:text-gray-300">Estado</label>
                        <select name="estado" id="estado"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="pendiente" {{ old('estado', $solicitud->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en proceso" {{ old('estado', $solicitud->estado) == 'en proceso' ? 'selected' : '' }}>En Proceso</option>
                            <option value="finalizada" {{ old('estado', $solicitud->estado) == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                            <option value="cancelada" {{ old('estado', $solicitud->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>

                    <!-- Prioridad -->
                    <div>
                        <label for="prioridad" class="block font-medium text-gray-700 dark:text-gray-300">Prioridad</label>
                        <select name="prioridad" id="prioridad"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Seleccione la prioridad --</option>
                            <option value="alta" {{ old('prioridad', $solicitud->prioridad) == 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="media" {{ old('prioridad', $solicitud->prioridad) == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="baja" {{ old('prioridad', $solicitud->prioridad) == 'baja' ? 'selected' : '' }}>Baja</option>
                        </select>
                        @error('prioridad')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Botón -->
                    <div class="text-center">
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                            Actualizar Solicitud
                        </button>
                    </div>
                </form>
            </div>

            <!-- Columna derecha: Información de Técnicos y Solicitudes Asignadas agrupados por Área -->
            <div class="lg:w-1/3 p-4 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Técnicos por Área</h2>
                @php
                    $tecnicosPorArea = $tecnicos->groupBy('area');
                @endphp
                @foreach($tecnicosPorArea as $area => $tecnicosDelArea)
                    <h3 class="text-md font-bold text-gray-700 dark:text-gray-300 mt-4">{{ $area }}</h3>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($tecnicosDelArea as $tecnico)
                            @if($tecnico->hasRole('tecnico'))
                                <li class="py-2">
                                    <div class="font-semibold text-gray-800 dark:text-gray-100">{{ $tecnico->name }}</div>
                                    <ul class="ml-4 mt-1">
                                        @foreach($tecnico->solicitudes as $solicitudAsignada)
                                            <li class="text-sm text-gray-600 dark:text-gray-300">
                                                #{{ $solicitudAsignada->id }}: {{ Str::limit($solicitudAsignada->descripcion, 30) }}
                                            </li>
                                        @endforeach
                                        @if($tecnico->solicitudes->isEmpty())
                                            <li class="text-sm text-gray-600 dark:text-gray-300">Sin solicitudes asignadas</li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function toggleEquipoSectionEdit(){
            var checkbox = document.getElementById('uploadCheckbox');
            var fileSection = document.getElementById('fileSection');
            var equipoSection = document.getElementById('equipoSection');
            if(checkbox.checked){
                fileSection.classList.remove('hidden');
                equipoSection.classList.add('hidden');
            } else {
                fileSection.classList.add('hidden');
                equipoSection.classList.remove('hidden');
            }
        }
    </script>
</x-layouts.app>
