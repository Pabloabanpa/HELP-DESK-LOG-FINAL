<x-layouts.app :title="__('Crear Usuario')">
    <div class="max-w-3xl mx-auto p-6 bg-white dark:bg-zinc-800 rounded-xl shadow">

        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">
            Crear Nuevo Usuario
        </h2>

        <!-- Formulario de creación -->
        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre</label>
                <input type="text" name="name" id="name" required
                       class="w-full mt-1 px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white dark:border-zinc-600" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                <input type="email" name="email" id="email" required
                       class="w-full mt-1 px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white dark:border-zinc-600" />
            </div>

            <!-- Cargo -->
            <div>
                <label for="cargo" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Cargo</label>
                <input type="text" name="cargo" id="cargo"
                       class="w-full mt-1 px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white dark:border-zinc-600" />
            </div>

            <!-- CI -->
            <div>
                <label for="ci" class="block text-sm font-medium text-gray-700 dark:text-gray-200">CI</label>
                <input type="text" name="ci" id="ci"
                       class="w-full mt-1 px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white dark:border-zinc-600" />
            </div>

            <!-- Celular -->
            <div>
                <label for="celular" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Celular</label>
                <input type="text" name="celular" id="celular"
                       class="w-full mt-1 px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white dark:border-zinc-600" />
            </div>

            <!-- Oficina -->
            <div>
                <label for="oficina" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Oficina</label>
                <input type="text" name="oficina" id="oficina"
                       class="w-full mt-1 px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white dark:border-zinc-600" />
            </div>

            <!-- Fecha de Nacimiento -->
            <div>
                <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                       class="w-full mt-1 px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white dark:border-zinc-600" />
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Contraseña</label>
                <input type="password" name="password" id="password" required
                       class="w-full mt-1 px-4 py-2 border rounded-md dark:bg-zinc-700 dark:text-white dark:border-zinc-600" />
            </div>

            <!-- Botón -->
            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
