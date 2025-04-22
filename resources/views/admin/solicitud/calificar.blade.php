<x-layouts.app :title="__('Calificar Solicitud')" >
    <div class="max-w-md mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Calificar Solicitud #{{ $solicitud->id }}</h1>

        <form action="{{ route('admin.solicitud.storeCalificacion', $solicitud) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1 text-gray-700 dark:text-gray-300">Puntuación</label>
                <div class="flex space-x-1">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio"
                               name="puntuacion"
                               id="star{{ $i }}"
                               value="{{ $i }}"
                               class="hidden peer"
                               {{ old('puntuacion', $solicitud->puntuacion) == $i ? 'checked' : '' }}
                               required>
                        <label for="star{{ $i }}"
                               class="cursor-pointer text-3xl text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 transition-colors">
                            &#9733;
                        </label>
                    @endfor
                </div>
                @error('puntuacion')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label class="block mb-1 text-gray-700 dark:text-gray-300">Comentario <span class="text-sm text-gray-500">(opcional)</span></label>
                <textarea name="comentario"
                          rows="4"
                          placeholder="Deja tu comentario para seguir mejorando..."
                          class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-100">{{ old('comentario', $solicitud->comentario) }}</textarea>
                @error('comentario')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">
                Guardar Feedback
            </button>
        </form>
    </div>
</x-layouts.app>
