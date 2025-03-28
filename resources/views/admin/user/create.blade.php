<x-layouts.app :title="__('Crear Usuario')">
    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Crear Usuario</h1>
        <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @csrf

            <div>
                <label for="name" class="block font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label for="email" class="block font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label for="password" class="block font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
                <input type="password" name="password" id="password" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label for="password_confirmation" class="block font-medium text-gray-700 dark:text-gray-300">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label for="cargo" class="block font-medium text-gray-700 dark:text-gray-300">Cargo</label>
                <input type="text" name="cargo" id="cargo" value="{{ old('cargo') }}"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label for="oficina" class="block font-medium text-gray-700 dark:text-gray-300">Oficina</label>
                <input type="text" name="oficina" id="oficina" value="{{ old('oficina') }}"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label for="ci" class="block font-medium text-gray-700 dark:text-gray-300">CI</label>
                <input type="text" name="ci" id="ci" value="{{ old('ci') }}"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label for="celular" class="block font-medium text-gray-700 dark:text-gray-300">Celular</label>
                <input type="text" name="celular" id="celular" value="{{ old('celular') }}"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label for="fecha_nacimiento" class="block font-medium text-gray-700 dark:text-gray-300">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
