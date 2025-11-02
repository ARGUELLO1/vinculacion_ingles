<div>

    <div class="bg-white shadow rounded-lg p-6">
        <h1>INSCRIBIRSE</h1>
        <form action="" method="POST" enctype="multipart/form-data">


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
                wire:model="linea_captura">




            <label for="fe_pago">FECHA DE PAGO</label>
            <input type="date" name="fe_pago" id="fe_pago" wire:model="fecha_pago">

            <label for="fe_entrega">FECHA DE ENTREGA</label>
            <input type="date" name="fe_entrega" id="fe_entrega" wire:model="fecha_entrega">


            <label for="nivel">NIVEL A CURSAR</label>
            <select name="nivel" id="nivel" wire:model="nivel_cursar">
                <option value=''>Selecciona una opción...</option>
                <option value='1'>NIVEL 1</option>
                <option value='2'>NIVEL 2</option>
                <option value='3'>NIVEL 3</option>
                <option value='4'>NIVEL 4</option>
                <option value='5'>NIVEL 5</option>
                <option value='6'>NIVEL 6</option>
            </select>

            <label for="grupo">GRUPO</label>









            <h3>DOCUMENTOS EN FORMATO PDF NO MAYOR A 2MB</h3>



            <div class="form_documentos">
                <label for="soli_aspirante" id="file-label">
                    <span>SOLICITUD DE ASPIRANTE</span></label>
                <input class="file-input" type="file" name="soli_aspirante">


                <label for="lin_captura_d" id="file-label1">
                    <span>LINEA DE CAPTURA</span></label>
                <input class="file-input" type="file" name="lin_captura_d">

                <label for="comp_pago" id="file-label2">
                    <span>COMPROBANTE DE PAGO</span></label>
                <input class="file-input" type="file" name="comp_pago">


                <label for="ine" id="file-label3">
                    <span>INE</span></label>
                <input class="file-input" type="file" name="ine">


                <label for="act_nacimiento" id="file-label4">
                    <span>ACTA DE NACIMIENTO</span></label>
                <input class="file-input" type="file" name="act_nacimiento">


                <label for="comp_estudios" id="file-label5">
                    <span>COMRPOBANTE DE ESTUDIOS</span></label>
                <input class="file-input" type="file" name="comp_estudios">

            </div>
            
            <button>INSCRIBIRSE</button>
        </form>
    </div>
</div>
