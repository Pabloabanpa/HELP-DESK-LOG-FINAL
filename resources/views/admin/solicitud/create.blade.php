<x-layouts.app :title="__('Crear Solicitud')">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Contenedor con dos columnas -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Columna izquierda: Formulario -->
            <div class="lg:w-2/3 p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <h1 class="text-3xl font-bold text-center mb-6 text-gray-800 dark:text-white">Crear Solicitud</h1>
                <form action="{{ route('admin.solicitud.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Solicitante (solo lectura, se obtiene de la sesión) -->
                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-300">Solicitante</label>
                        <input type="text" value="{{ auth()->user()->name }}" disabled
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    </div>

                    <!-- Técnico -->
                    <div>
                        <label for="tecnico" class="block font-medium text-gray-700 dark:text-gray-300">Técnico Asignado</label>
                        <select name="tecnico" id="tecnico"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Seleccione un técnico --</option>
                            @foreach($tecnicos as $tecnico)
                                <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Código de Equipo / Archivo -->
                    <div id="equipoSection">
                        <label for="equipo_id" class="block font-medium text-gray-700 dark:text-gray-300">Código de Equipo</label>
                        <input type="text" name="equipo_id" id="equipo_id"
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="uploadCheckbox" name="upload_file" value="1" class="mr-2" onclick="toggleEquipoSection()">
                        <label for="uploadCheckbox" class="text-gray-700 dark:text-gray-300">No tengo código de equipo, subir archivo</label>
                    </div>
                    <div id="fileSection" class="hidden">
                        <label for="archivo" class="block font-medium text-gray-700 dark:text-gray-300">Archivo</label>
                        <input type="file" name="archivo" id="archivo"
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Tipo de Problema -->
                    <div>
                        <label for="tipo_problema" class="block font-medium text-gray-700 dark:text-gray-300">Tipo de Problema</label>
                        <select name="tipo_problema" id="tipo_problema"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Seleccione un tipo de problema --</option>
                            <option value="hardware">Hardware (equipos, componentes, etc.)</option>
                            <option value="software">Software (aplicaciones, sistemas operativos, etc.)</option>
                            <option value="red">Red (conexiones, VPN, WiFi, etc.)</option>
                            <option value="acceso">Acceso y Autenticación (credenciales, permisos, etc.)</option>
                            <option value="impresora">Impresoras y Periféricos</option>
                            <option value="seguridad">Seguridad (antivirus, malware, etc.)</option>
                            <option value="otros">Otros</option>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="descripcion" class="block font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="4"
                                  class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="estado" class="block font-medium text-gray-700 dark:text-gray-300">Estado</label>
                        <select name="estado" id="estado"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="pendiente" selected>Pendiente</option>
                            <option value="en proceso">En Proceso</option>
                            <option value="finalizada">Finalizada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>

                    <!-- Prioridad -->
                    <div>
                        <label for="prioridad" class="block font-medium text-gray-700 dark:text-gray-300">Prioridad</label>
                        <select name="prioridad" id="prioridad"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Seleccione la prioridad --</option>
                            <option value="alta" {{ old('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="media" {{ old('prioridad') == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="baja" {{ old('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                        </select>
                        @error('prioridad')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Botón -->
                    <div class="text-center">
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                            Crear Solicitud
                        </button>
                    </div>
                </form>
            </div>

            <!-- Columna derecha: Información de Técnicos y Solicitudes Asignadas -->
            <div class="lg:w-1/3 p-4 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Técnicos y Solicitudes Asignadas</h2>
                <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($tecnicos as $tecnico)
                        <li class="py-2">
                            <div class="font-semibold text-gray-800 dark:text-gray-100">{{ $tecnico->name }}</div>
                            <ul class="ml-4 mt-1">
                                @foreach($tecnico->solicitudes as $solicitud)
                                    <li class="text-sm text-gray-600 dark:text-gray-300">
                                        #{{ $solicitud->id }}: {{ Str::limit($solicitud->descripcion, 30) }}
                                    </li>
                                @endforeach
                                @if($tecnico->solicitudes->isEmpty())
                                    <li class="text-sm text-gray-600 dark:text-gray-300">Sin solicitudes asignadas</li>
                                @endif
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <script>
        function toggleEquipoSection(){
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
