<div>

    <div class="">
        @if ($info_alumno->nivel_id)
            <h1>Ya estas inscrito a un nivel</h1>
        @else
            <h1>INSCRIBIRSE</h1>
            <form wire:submit="save" enctype="multipart/form-data">


                <script>
                    function formatInput(event) {
                        let input = event.target;
                        let value = input.value.replace(/\s+/g, ''); // Elimina todos los espacios
                        let formattedValue = '';

                        for (let i = 0; i < value.length; i += 6) {
                            if (i > 0) {
                                formattedValue += ' ';
                            }
                            formattedValue += value.substring(i, i + 6);
                        }

                        input.value = formattedValue;
                    }
                </script>

                <label for="lin_captura">LINEA DE CAPTURA</label>
                <input type="text" name="lin_captura" id="lin_captura" placeholder="XXXXXX(6) XXXXXX XXXXXX XXXXXX XXX"
                    maxlength="31" oninput="formatInput(event)" pattern="[0-9\s]+" title="SOLO SE ADMITEN NÚMEROS"
                    wire:model="info_formulario.linea_captura">




                <label for="fe_pago">FECHA DE PAGO</label>
                <input type="date" name="fe_pago" id="fe_pago" wire:model="info_formulario.fecha_pago">

                <label for="fe_entrega">FECHA DE ENTREGA</label>
                <input type="date" name="fe_entrega" id="fe_entrega" wire:model="info_formulario.fecha_entrega">

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

                <label for="selec_grupo">GRUPO</label>
                <select name="selec_grupo" wire:model.live="info_formulario.grupo_cursar" wire:loading.attr="disabled">


                    @forelse ($grupos as $grupo)
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









                <h3>DOCUMENTOS EN FORMATO PDF NO MAYOR A 2MB</h3>



                <div class="form_documentos">
                    <label for="soli_aspirante" id="file-label">
                        <span>SOLICITUD DE ASPIRANTE</span></label>
                    <input class="file-input" type="file" name="soli_aspirante"
                        wire:model="documentos_formulario.solicitud_aspirante_doc">


                    <label for="lin_captura_d" id="file-label1">
                        <span>LINEA DE CAPTURA</span></label>
                    <input class="file-input" type="file" name="lin_captura_d"
                        wire:model="documentos_formulario.linea_captura_doc">

                    <label for="comp_pago" id="file-label2">
                        <span>COMPROBANTE DE PAGO</span></label>
                    <input class="file-input" type="file" name="comp_pago"
                        wire:model="documentos_formulario.comprobante_pago_doc">


                    <label for="ine" id="file-label3">
                        <span>INE</span></label>
                    <input class="file-input" type="file" name="ine" wire:model="documentos_formulario.ine_doc">


                    <label for="act_nacimiento" id="file-label4">
                        <span>ACTA DE NACIMIENTO</span></label>
                    <input class="file-input" type="file" name="act_nacimiento"
                        wire:model="documentos_formulario.acta_nacimiento_doc">


                    <label for="comp_estudios" id="file-label5">
                        <span>COMRPOBANTE DE ESTUDIOS</span></label>
                    <input class="file-input" type="file" name="comp_estudios"
                        wire:model="documentos_formulario.comprobante_estudio_doc">

                </div>

                <button>INSCRIBIRSE</button>
            </form>
        @endif

    </div>
</div>
