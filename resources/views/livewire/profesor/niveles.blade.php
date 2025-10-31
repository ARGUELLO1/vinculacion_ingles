<div>
    <div class="min-h-screen bg-no-repeat bg-cover bg-center" style="background-image: url('/images/exp.jpg');">
        <div class="relative z-10 py-12 px-6">
            @if (!$nivelSeleccionado)
            {{-- Vista de niveles --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @for ($i = 1; $i <= 6; $i++)
                    <div class="bg-white/20 backdrop-blur-md rounded-lg shadow-lg p-6 flex flex-col items-center text-center border border-white/30">
                    <h3 class="text-black text-xl font-bold mb-2">Nivel {{ $i }}</h3>
                    <p class="text-black mb-4">Grupos disponibles</p>
                    <button wire:click="verNivel({{ $i }})"
                        class="px-6 py-2 text-black font-semibold rounded-lg border border-green-500 bg-white/90 hover:bg-green-100 transition">
                        Ver
                    </button>
            </div>
            @endfor
        </div>
        @else
        {{-- Vista de grupos del nivel seleccionado --}}
        <div class="space-y-6">
            <button wire:click="regresar"
                class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition">
                ← Volver a niveles
            </button>

            <h2 class="text-2xl font-bold text-black text-center">Grupos del Nivel {{ $nivelSeleccionado }}</h2>

            @if (count($grupos) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($grupos as $grupo)
                <div class="bg-white/90 rounded-lg shadow-lg p-6 text-center border border-green-600">
                    <h3 class="text-black text-xl font-bold mb-2">{{ $grupo->nombre_grupo }}</h3>
                    <p class="text-black mb-2">Aula: {{ $grupo->aula }}</p>
                    <p class="text-black mb-2">Horario: {{ $grupo->horario }}</p>
                    <div class="mt-4">
                        <a href="{{ route('profesor.grupo.vista', $grupo->id_nivel) }}"
                            wire:navigate
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                            Administrar Grupo
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-black font-bold text-center text-lg mt-6">
                No hay grupos registrados para este nivel.
            </p>
            @endif
        </div>
        @endif
    </div>
</div>
</div>