<x-layouts.app :title="__('Editar Usuario')">
    <div class="max-w-3xl mx-auto py-8">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">
                Editar Usuario
            </h1>
            <form action="{{ route('admin.user.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-lg font-medium text-gray-700 dark:text-gray-300">
                        Nombre
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-lg font-medium text-gray-700 dark:text-gray-300">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Cargo -->
                <div>
                    <label for="cargo" class="block text-lg font-medium text-gray-700 dark:text-gray-300">
                        Cargo
                    </label>
                    <input type="text" name="cargo" id="cargo" value="{{ old('cargo', $user->cargo) }}"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Oficina -->
                <div>
                    <label for="oficina" class="block text-lg font-medium text-gray-700 dark:text-gray-300">
                        Oficina
                    </label>
                    <input type="text" name="oficina" id="oficina" value="{{ old('oficina', $user->oficina) }}"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Área -->
                <div>
                    <label for="area" class="block font-medium text-gray-700 dark:text-gray-300">Área</label>
                    <select name="area" id="area"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Seleccione un área --</option>
                        <option value="soporte" {{ old('area', $user->area) == 'soporte' ? 'selected' : '' }}>Soporte</option>
                        <option value="redes" {{ old('area', $user->area) == 'redes' ? 'selected' : '' }}>Redes</option>
                        <option value="desarrollo" {{ old('area', $user->area) == 'desarrollo' ? 'selected' : '' }}>Desarrollo</option>
                    </select>
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-lg font-medium text-gray-700 dark:text-gray-300">
                        Estado
                    </label>
                    <select name="estado" id="estado"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="activo" {{ old('estado', $user->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $user->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <!-- CI -->
                <div>
                    <label for="ci" class="block text-lg font-medium text-gray-700 dark:text-gray-300">
                        CI
                    </label>
                    <input type="text" name="ci" id="ci" value="{{ old('ci', $user->ci) }}"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Celular -->
                <div>
                    <label for="celular" class="block text-lg font-medium text-gray-700 dark:text-gray-300">
                        Celular
                    </label>
                    <input type="text" name="celular" id="celular" value="{{ old('celular', $user->celular) }}"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Fecha de Nacimiento -->
                <div>
                    <label for="fecha_nacimiento" class="block text-lg font-medium text-gray-700 dark:text-gray-300">
                        Fecha de Nacimiento
                    </label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                           value="{{ old('fecha_nacimiento', $user->fecha_nacimiento ? $user->fecha_nacimiento->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Nueva Contraseña (opcional) -->
                <div>
                    <label for="password" class="block text-lg font-medium text-gray-700 dark:text-gray-300">
                        Nueva Contraseña (opcional)
                    </label>
                    <input type="password" name="password" id="password"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Confirmar Nueva Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-lg font-medium text-gray-700 dark:text-gray-300">
                        Confirmar Nueva Contraseña
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Roles -->
                <div class="mb-4">
                    <label class="block text-lg font-medium text-gray-700 dark:text-gray-300">
                        Roles
                    </label>
                    <div class="mt-2">
                        @foreach($roles as $role)
                            <label class="inline-flex items-center mr-4">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                       {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                       class="form-checkbox h-5 w-5 text-indigo-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Botón -->
                <div class="text-center">
                    <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
