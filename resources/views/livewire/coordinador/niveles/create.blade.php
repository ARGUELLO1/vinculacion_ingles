<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Nivel Information') }}
                    </h2>
                </header>

                <section>
                    <form wire:submit='save' class="mt-6 space-y-6">
                        <div>
                            <x-input-label for="nivel" :value="__('Nivel')" />
                            <x-text-input wire:model="form.nivel" id="nivel" name="nivel" type="text"
                                class="mt-1 block w-full" required autofocus autocomplete="nivel" />
                            <x-input-error class="mt-2" :messages="$errors->get('form.nivel')" />
                        </div>

                        <div>
                            <x-input-label for="grupo" :value="__('Grupo')" />
                            <x-text-input wire:model="form.grupo" id="grupo" name="grupo" type="text"
                                class="mt-1 block w-full" required autofocus autocomplete="grupo" />
                            <x-input-error class="mt-2" :messages="$errors->get('form.grupo')" />
                        </div>

                        <div>
                            <x-input-label for="aula" :value="__('Aula')" />
                            <x-text-input wire:model="form.aula" id="aula" name="aula" type="text"
                                class="mt-1 block w-full" required autofocus autocomplete="aula" />
                            <x-input-error class="mt-2" :messages="$errors->get('form.aula')" />
                        </div>

                        <div>
                            <x-input-label for="cupo_max" :value="__('Cupo Maximo')" />
                            <x-text-input wire:model="form.cupo_max" name="cupo_max" id="cupo_max"
                                class="mt-1 block w-full" type="number" min="1" max="100" required
                                autofocus autocomplete="cupo_max" />
                            <x-input-error class="mt-2" :messages="$errors->get('form.estatus')" />
                        </div>

                        <div>
                            <x-input-label for="horario" :value="__('Horario')" />
                            <div class="mt-1 flex items-center space-x-4">
                                <div class="flex-1">
                                    <x-input-label for="hora_entrada" :value="__('Entrada')" />
                                    <x-text-input wire:model="form.hora_entrada" id="hora_entrada" name="hora_entrada"
                                        type="time" class="mt-1 block w-full" required autofocus
                                        autocomplete="hora_entrada" />
                                </div>
                                <div class="flex items-center pt-5">
                                    <span class="text-gray-500">-</span>
                                </div>
                                <div class="flex-1">
                                    <x-input-label for="hora_salida" :value="__('Salida')" />
                                    <x-text-input wire:model="form.hora_salida" id="hora_salida" name="hora_salida"
                                        type="time" class="mt-1 block w-full" required autofocus
                                        autocomplete="hora_salida" />
                                </div>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('form.hora_entrada')" />
                            <x-input-error class="mt-2" :messages="$errors->get('form.hora_salida')" />
                        </div>

                        <div>
                            <x-input-label for="profesor" :value="__('Profesor')" />
                            <x-select wire:model="form.profesor" id="profesor" name="profesor"
                                class="mt-1 block w-full" required autofocus autocomplete="profesor">
                                @if (request()->routeIs('coordinador.niveles.create'))
                                    <option value="" disabled selected>-- Seleccione un profesor --</option>
                                @endif

                                @foreach ($profesores as $profesor)
                                    <option value="{{ $profesor->id_profesor }}">
                                        {{ $profesor->nombre_completo }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('form.profesor')" />
                        </div>

                        <div>
                            <x-input-label for="periodo" :value="__('Periodo')" />
                            <x-select wire:model="form.periodo" id="periodo" name="periodo" class="mt-1 block w-full"
                                required autofocus autocomplete="periodo">
                                @if (request()->routeIs('coordinador.niveles.create'))
                                    <option value="" disabled selected>-- Seleccione un periodo --</option>
                                @endif

                                @foreach ($periodos as $periodo)
                                    <option value="{{ $periodo->id_periodo }}">
                                        {{ $periodo->periodo }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('form.periodo')" />
                        </div>

                        <div>
                            <x-input-label for="modalidad" :value="__('Modalidad')" />
                            <x-select wire:model="form.modalidad" id="modalidad" name="modalidad"
                                class="mt-1 block w-full" required autofocus autocomplete="modalidad">
                                @if (request()->routeIs('coordinador.niveles.create'))
                                    <option value="" disabled selected>-- Seleccione un modalidad --</option>
                                @endif

                                @foreach ($modalidades as $modalidad)
                                    <option value="{{ $modalidad->id_modalidad }}">
                                        {{ $modalidad->tipo_modalidad }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('form.modalidad')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ request()->routeIs('coordinador.niveles.create') ? 'Crear' : 'Actualizar' }}</x-primary-button>
                            <x-danger-button :href="route('coordinador.niveles.index')" wire:navigate>{{ __('Cancelar') }}</x-danger-button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>
