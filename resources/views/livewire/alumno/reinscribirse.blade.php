<div>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight text-center lg:text-left">
            {{ __('REINSCRIBIRSE A UN NIVEL') }}
        </h2>
    </x-slot>

    <div class="flex lg:flex-col bg-white shadow rounded-lg  p-6 m-3">
        @if (!$info_formulario->info_alumno->nivel_id || $constancias_termino->isEmpty())
            <h1>INSCRIBETE A UN NIVEL / ESPERA A QUE EL NIVEL TERMINE</h1>
        @else
            <form wire:submit="save" enctype="multipart/form-data">
                @vite(['resources/js/lineacapturaformato.js'])

                <h1 class="text-blue-800 my-3 lg:text-xl">*Llena los siguientes campos y sube los documentos solicitados
                    para poder reinscribirte a un nivel</h1>

                <div class="mb-2">
                    <label class="lg:text-xl font-bold" for="lin_captura">LINEA DE CAPTURA</label>
                    <input class="w-full" type="text" name="lin_captura" id="lin_captura"
                        placeholder="XXXXXX(6) XXXXXX XXXXXX XXXXXX XXX" maxlength="31" oninput="formatInput(event)"
                        wire:model.live="info_formulario.linea_captura">
                    <x-input-error-rule for="info_formulario.linea_captura" />
                </div>

                <div class="mb-2">
                    <label class="lg:text-xl font-bold" for="fe_pago">FECHA DE PAGO</label>
                    <input class="w-full" type="date" name="fe_pago" id="fe_pago"
                        wire:model="info_formulario.fecha_pago">
                    <x-input-error-rule for="info_formulario.fecha_pago" />
                </div>

                <div class="mb-2">
                    <label class="lg:text-xl font-bold" for="fe_entrega">FECHA DE ENTREGA</label>
                    <input class="w-full" type="date" name="fe_entrega" id="fe_entrega"
                        wire:model="info_formulario.fecha_entrega">
                    <x-input-error-rule for="info_formulario.fecha_entrega" />
                </div>

                <div class="mb-2">
                    <label class="lg:text-xl font-bold" for="nivel">NIVEL A CURSAR</label>
                    <select class="w-full" name="nivel" wire:model.live="info_formulario.nivel_cursar"
                        @if ($bloquear_nivel) disabled @endif>
                        <option value='' selected disabled>Selecciona una opción...</option>
                        @if ($info_formulario->info_alumno->nivel->nivel + 1 == 7)
                            <option value=''>YA CURSASTE TODOS LOS NIVELES</option>
                        @else
                            <option value='{{ $info_formulario->info_alumno->nivel->nivel + 1 }}'>NIVEL
                                {{ $info_formulario->info_alumno->nivel->nivel + 1 }}
                            </option>
                        @endif
                    </select>
                    <x-input-error-rule for="info_formulario.nivel_cursar" />
                </div>

                <div class="mb-2">
                    <label class="lg:text-xl font-bold" for="selec_grupo">GRUPO</label>
                    <select class="w-full" name="selec_grupo" wire:model.live="info_formulario.grupo_cursar">

                        @forelse ($info_formulario->grupos as $grupo)
                            @switch($grupo)
                                @case($grupo->cantidad_alumnos >= $grupo->cupo_max)
                                {{--NIVEL LLENO--}}
                                   {{--<option disabled value="{{ $grupo->id_nivel }}">
                                    Grupo: {{ $grupo->nombre_grupo }} -
                                    Aula: {{ $grupo->aula }} -
                                    Horario: {{ $grupo->horario }} -
                                    Profesor: {{ $grupo->profesor->nombre }} {{ $grupo->profesor->ap_paterno }} -
                                    Modalidad: {{ $grupo->modalidad->tipo_modalidad }} -
                                    Cupo: {{ $grupo->cantidad_alumnos }}/{{ $grupo->cupo_max }}</option>--}}
                                @break

                                @case($grupo->nivel_concluido == 1)
                                {{--NIVEL CONCLUIDO --}}
                                  {{-- <option disabled value="{{ $grupo->id_nivel }}">
                                    Grupo: {{ $grupo->nombre_grupo }} -
                                    Aula: {{ $grupo->aula }} -
                                    Horario: {{ $grupo->horario }} -
                                    Profesor: {{ $grupo->profesor->nombre }} {{ $grupo->profesor->ap_paterno }} -
                                    Modalidad: {{ $grupo->modalidad->tipo_modalidad }} -
                                    NIVEL FINALIZADO</option>--}}
                                @break

                                @default
                                    <option value="{{ $grupo->id_nivel }}">
                                        Grupo: {{ $grupo->nombre_grupo }} -
                                        Aula: {{ $grupo->aula }} -
                                        Horario: {{ $grupo->horario }} -
                                        Profesor: {{ $grupo->profesor->nombre }} {{ $grupo->profesor->ap_paterno }} -
                                        Modalidad: {{ $grupo->modalidad->tipo_modalidad }}
                                    </option>
                            @endswitch


                            @empty
                                <option value='' disabled>No hay grupos disponibles para este nivel</option>
                            @endforelse
                            <option value="" disabled selected>Selecciona un grupo...</option>
                        </select>
                        <x-input-error-rule for="info_formulario.grupo_cursar" />
                    </div>

                    <div class="mb-2">
                        <h3 class=" bg-red-600 my-4 text-white w-full font-bold text-sm lg:text-xl text-center">DOCUMENTOS
                            EN FORMATO PDF NO MAYOR A 500KB</h3>
                    </div>
                    <fieldset wire:loading.attr="disabled"
                        wire:target="info_formulario.documentos.constancia_nivel_anterior_doc,
                    info_formulario.documentos.comprobante_pago_doc,
                    info_formulario.documentos.linea_captura_doc">

                        <div class="text-blue-700 font-bold my-2 hidden" wire:loading.class.remove="hidden"
                            wire:target="info_formulario.documentos.constancia_nivel_anterior_doc,
                     info_formulario.documentos.comprobante_pago_doc,
                     info_formulario.documentos.linea_captura_doc">
                            Subiendo documento...
                        </div>

                        <div class="mb-2">
                            <label class="lg:text-xl font-bold" for="soli_aspirante" id="file-label">
                                <span>CONSTANCIA DE NIVEL ANTERIOR</span></label>
                            <input
                                class="file:py-2 file:border-none focus:outline-none file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 focus:ring-blue-500 bg-gray-200 text-gray-700 border border-gray-300 cursor-pointer w-full file-input"
                                type="file" name="soli_aspirante"
                                wire:model="info_formulario.documentos.constancia_nivel_anterior_doc">
                            <x-input-error-rule for="info_formulario.documentos.constancia_nivel_anterior_doc" />
                        </div>

                        <div class="mb-2">
                            <label class="lg:text-xl font-bold" for="comp_pago" id="file-label2">
                                <span>COMPROBANTE DE PAGO</span></label>
                            <input
                                class="file:py-2 file:border-none focus:outline-none file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 focus:ring-blue-500 bg-gray-200 text-gray-700 border border-gray-300 cursor-pointer w-full file-input"
                                type="file" name="comp_pago"
                                wire:model="info_formulario.documentos.comprobante_pago_doc">
                            <x-input-error-rule for="info_formulario.documentos.comprobante_pago_doc" />
                        </div>

                        <div class="mb-2">
                            <label class="lg:text-xl font-bold" for="comp_estudios" id="file-label5">
                                <span>LINEA DE CAPTURA</span></label>
                            <input
                                class="file:py-2 file:border-none focus:outline-none file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 focus:ring-blue-500 bg-gray-200 text-gray-700 border border-gray-300 cursor-pointer w-full file-input"
                                type="file" name="comp_estudios"
                                wire:model="info_formulario.documentos.linea_captura_doc">
                            <x-input-error-rule for="info_formulario.documentos.linea_captura_doc" />
                        </div>
                    </fieldset>
                    <button
                        class="border-2 text-blue-800 border-blue-800 hover:bg-blue-800 hover:text-white rounded-lg mt-5 lg:text-xl w-full lg:text-center ">REINSCRIBIRSE</button>
                </form>
            @endif

        </div>
    </div>
