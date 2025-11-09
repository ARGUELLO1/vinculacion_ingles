<div>

    <div class="bg-white shadow rounded-lg p-6">
        @if ($info_formulario->info_alumno->nivel_id)
            <h1>YA ESTAS INSCRITO A UN NIVEL</h1>
        @else
            <h1>INSCRIBIRSE</h1>
            <form wire:submit="save" enctype="multipart/form-data">
                @vite(['resources/js/lineacapturaformato.js'])

                <label for="lin_captura">LINEA DE CAPTURA</label>
                <input type="text" name="lin_captura" id="lin_captura" placeholder="XXXXXX(6) XXXXXX XXXXXX XXXXXX XXX"
                    maxlength="31" oninput="formatInput(event)" pattern="[0-9\s]+" title="SOLO SE ADMITEN NÚMEROS"
                    wire:model="info_formulario.linea_captura">
                <x-input-error-rule for="info_formulario.linea_captura" />




                <label for="fe_pago">FECHA DE PAGO</label>
                <input type="date" name="fe_pago" id="fe_pago" wire:model="info_formulario.fecha_pago">
                <x-input-error-rule for="info_formulario.fecha_pago" />

                <label for="fe_entrega">FECHA DE ENTREGA</label>
                <input type="date" name="fe_entrega" id="fe_entrega" wire:model="info_formulario.fecha_entrega">
                <x-input-error-rule for="info_formulario.fecha_entrega" />

                <label for="nivel">NIVEL A CURSAR</label>
                <select name="nivel" wire:model.live="info_formulario.nivel_cursar" wire:loading.attr="disabled"
                    @if ($bloquear_nivel) disabled @endif>
                    <option value='' selected disabled>Selecciona una opción...</option>
                    <option value='1'>NIVEL 1</option>
                    <option value='2'>NIVEL 2</option>
                    <option value='3'>NIVEL 3</option>
                    <option value='4'>NIVEL 4</option>
                    <option value='5'>NIVEL 5</option>
                    <option value='6'>NIVEL 6</option>
                </select>
                <x-input-error-rule for="info_formulario.nivel_cursar" />

                <label for="selec_grupo">GRUPO</label>
                <select name="selec_grupo" wire:model.live="info_formulario.grupo_cursar" wire:loading.attr="disabled">


                    @forelse ($info_formulario->grupos as $grupo)
                        @if ($grupo->cantidad_alumnos >= $grupo->cupo_max)
                            <option disabled value="{{ $grupo->id_nivel }}">
                                Grupo: {{ $grupo->nombre_grupo }} -
                                Aula: {{ $grupo->aula }} -
                                Horario: {{ $grupo->horario }} -
                                Profesor: {{ $grupo->profesor->nombre }} {{ $grupo->profesor->ap_paterno }} -
                                Modalidad: {{ $grupo->modalidad->tipo_modalidad }} -
                                Cupo: {{ $grupo->cantidad_alumnos }}/{{ $grupo->cupo_max }}</option>
                        @else
                            <option value="{{ $grupo->id_nivel }}">
                                Grupo: {{ $grupo->nombre_grupo }} -
                                Aula: {{ $grupo->aula }} -
                                Horario: {{ $grupo->horario }} -
                                Profesor: {{ $grupo->profesor->nombre }} {{ $grupo->profesor->ap_paterno }} -
                                Modalidad: {{ $grupo->modalidad->tipo_modalidad }}</option>
                        @endif
                    @empty
                        <option disabled value=''>No hay grupos disponibles para este nivel</option>
                    @endforelse
                    <option value="" disabled selected>Selecciona un grupo...</option>
                </select>
                <x-input-error-rule for="info_formulario.grupo_cursar" />

                <h3>DOCUMENTOS EN FORMATO PDF NO MAYOR A 2MB</h3>

                <div class="form_documentos">
                    <label for="soli_aspirante" id="file-label">
                        <span>SOLICITUD DE ASPIRANTE</span></label>
                    <input class="file-input" type="file" name="soli_aspirante"
                        wire:model="info_formulario.documentos.solicitud_aspirante_doc">
                    <x-input-error-rule for="info_formulario.documentos.solicitud_aspirante_doc" />

                    <label for="lin_captura_d" id="file-label1">
                        <span>LINEA DE CAPTURA</span></label>
                    <input class="file-input" type="file" name="lin_captura_d"
                        wire:model="info_formulario.documentos.linea_captura_doc">
                    <x-input-error-rule for="info_formulario.documentos.linea_captura_doc" />

                    <label for="comp_pago" id="file-label2">
                        <span>COMPROBANTE DE PAGO</span></label>
                    <input class="file-input" type="file" name="comp_pago"
                        wire:model="info_formulario.documentos.comprobante_pago_doc">
                    <x-input-error-rule for="info_formulario.documentos.comprobante_pago_doc" />


                    <label for="ine" id="file-label3">
                        <span>INE</span></label>
                    <input class="file-input" type="file" name="ine"
                        wire:model="info_formulario.documentos.ine_doc">
                    <x-input-error-rule for="info_formulario.documentos.ine_doc" />


                    <label for="act_nacimiento" id="file-label4">
                        <span>ACTA DE NACIMIENTO</span></label>
                    <input class="file-input" type="file" name="act_nacimiento"
                        wire:model="info_formulario.documentos.acta_nacimiento_doc">
                    <x-input-error-rule for="info_formulario.documentos.acta_nacimiento_doc" />


                    <label for="comp_estudios" id="file-label5">
                        <span>COMRPOBANTE DE ESTUDIOS</span></label>
                    <input class="file-input" type="file" name="comp_estudios"
                        wire:model="info_formulario.documentos.comprobante_estudio_doc">
                    <x-input-error-rule for="info_formulario.documentos.comprobante_estudio_doc" />

                </div>

                <button>INSCRIBIRSE</button>
            </form>
        @endif

    </div>
</div>
