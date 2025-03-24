<x-layouts.app :title="__('Dashboard')">
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Cargo</th>
                    <th class="px-4 py-3">CI</th>
                    <th class="px-4 py-3">Celular</th>
                    <th class="px-4 py-3">Oficina</th>
                    <th class="px-4 py-3">Fecha Nac.</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->cargo }}</td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->ci }}</td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->celular }}</td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $user->oficina }}</td>
                        <td class="px-4 py-2 text-gray-700 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($user->fecha_nacimiento)->format('d/m/Y') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</x-layouts.app>