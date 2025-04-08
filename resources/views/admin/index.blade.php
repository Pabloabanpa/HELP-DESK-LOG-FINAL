<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-col items-center justify-center h-[80vh] text-center">
        <!-- Ícono o imagen de bienvenida -->
        <div class="mb-6">
            <x-heroicon-o-user-circle class="w-24 h-24 text-indigo-500 dark:text-indigo-300" />
        </div>

        <!-- Título de bienvenida -->
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
            ¡Bienvenido, {{ auth()->user()->name }}!
        </h1>

        <!-- Subtítulo -->
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
            Este es tu panel principal del sistema Help Desk.
        </p>


    </div>
</x-layouts.app>
        