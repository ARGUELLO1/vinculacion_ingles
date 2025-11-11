<div>
    {{-- <div>
         <h1>DOCUMENTOS OBTENIDOS DEL NIVEL</h1>
       Si esto se cumple, es por que el nivel ya terminó, ya que existen constancias de nivel 
        <H5>{{ $prueba }}</H5>
        
    </div> --}}

    <div class="bg-white shadow rounded-lg p-6">
        @if (!$info_alumno->nivel_id || $constancias_termino->isEmpty())
            <h1>INSCRIBETE A UN NIVEL / ESPERA A QUE EL NIVEL TERMINE</h1>
        @else
            <h1>REINSCRIBIRSE</h1>
            <form wire:submit="save" enctype="multipart/form-data">
                @vite(['resources/js/lineacapturaformato.js'])
                
                <label for="lin_captura">LINEA DE CAPTURA</label>
                <input type="text" name="lin_captura" id="lin_captura" placeholder="XXXXXX(6) XXXXXX XXXXXX XXXXXX XXX"
                    maxlength="31" oninput="formatInput(event)" pattern="[0-9\s]+" title="SOLO SE ADMITEN NÚMEROS"
                    wire:model="info_formulario.linea_captura">




                <label for="fe_pago">FECHA DE PAGO</label>
                <input type="date" name="fe_pago" id="fe_pago" wire:model="info_formulario.fecha_pago">

                <label for="fe_entrega">FECHA DE ENTREGA</label>
                <input type="date" name="fe_entrega" id="fe_entrega" wire:model="info_formulario.fecha_entrega">

                <label for="nivel">NIVEL A CURSAR</label>
                <select name="nivel" wire:model.live="info_formulario.nivel_cursar"
                    @if ($bloquear_nivel) disabled @endif>
                    <option value='' selected disabled>Selecciona una opción...</option>
                    @if ($info_alumno->nivel->nivel + 1 == 7)
                        <option value=''>YA CURSASTE TODOS LOS NIVELES</option>
                    @else
                        <option value='{{ $info_alumno->nivel->nivel + 1 }}'>NIVEL {{ $info_alumno->nivel->nivel + 1 }}
                        </option>
                    @endif

                </select>

                <label for="selec_grupo">GRUPO</label>
                <select name="selec_grupo" wire:model.live="info_formulario.grupo_cursar">



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
                                Modalidad: {{ $grupo->modalidad->tipo_modalidad }}
                            </option>
                        @endif
                    @empty
                        <option value='' disabled>No hay grupos disponibles para este nivel</option>
                    @endforelse
                    <option value="" disabled selected>Selecciona un grupo...</option>

                </select>








                <h3>DOCUMENTOS EN FORMATO PDF NO MAYOR A 2MB</h3>



                <div class="form_documentos">
                    <label for="soli_aspirante" id="file-label">
                        <span>CONSTANCIA DE NIVEL ANTERIOR</span></label>
                    <input class="file-input" type="file" name="soli_aspirante"
                        wire:model="documentos_formulario.constancia_nivel_anterior_doc">

                    <label for="comp_pago" id="file-label2">
                        <span>COMPROBANTE DE PAGO</span></label>
                    <input class="file-input" type="file" name="comp_pago"
                        wire:model="documentos_formulario.comprobante_pago_doc">

                    <label for="comp_estudios" id="file-label5">
                        <span>LINEA DE CAPTURA</span></label>
                    <input class="file-input" type="file" name="comp_estudios"
                        wire:model="documentos_formulario.linea_captura_doc">

                </div>

                <button>REINSCRIBIRSE</button>
            </form>
        @endif

    </div>



</div>
