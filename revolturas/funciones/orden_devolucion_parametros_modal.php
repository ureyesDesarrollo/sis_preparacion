<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

$odd_id = isset($_POST['odd_id']) ? (int)$_POST['odd_id'] : 0;

$res = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT odd.*, c.cal_descripcion FROM orden_devolucion_analisis odd JOIN rev_calidad c ON odd.cal_id = c.cal_id WHERE odd.odd_id = '$odd_id'"));
if ($res == null) {
    $res = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT * FROM orden_devolucion_analisis WHERE odd_id = '$odd_id'"));
}

if (!isset($res) || !is_array($res)) {
    $res = [];
}



// Verificación de variables vacías
$bloom = (isset($res['bloom']) && $res['bloom'] != '0.00') ? $res['bloom'] : '';
$viscosidad = (isset($res['viscosidad']) && $res['viscosidad'] != '0.00') ? $res['viscosidad'] : '';
$ph = isset($res['ph']) ? $res['ph'] : '';
$trans = isset($res['trans']) ? $res['trans'] : '';
$ntu = isset($res['ntu']) ? $res['ntu'] : '';
$humedad = isset($res['humedad']) ? $res['humedad'] : '';
$cenizas = isset($res['cenizas']) ? $res['cenizas'] : '';
$ce = isset($res['ce']) ? $res['ce'] : '';
$redox = isset($res['redox']) ? $res['redox'] : '';
$color = isset($res['color']) ? $res['color'] : '';
$olor = isset($res['olor']) ? $res['olor'] : '';
$pe_1kg = isset($res['pe_1kg']) ? $res['pe_1kg'] : '';
$par_extr = isset($res['par_extr']) ? $res['par_extr'] : '';
$par_ind = isset($res['par_ind']) ? $res['par_ind'] : '';
$hidratacion = isset($res['hidratacion']) ? $res['hidratacion'] : '';
$porcentaje_t = isset($res['porcentaje_t']) ? $res['porcentaje_t'] : '';
$malla_30 = isset($res['malla_30']) ? $res['malla_30'] : '';
$malla_45 = isset($res['malla_45']) ? $res['malla_45'] : '';
$malla_60 = isset($res['malla_60']) ? $res['malla_60'] : '';
$malla_100 = isset($res['malla_100']) ? $res['malla_100'] : '';
$malla_200 = isset($res['malla_200']) ? $res['malla_200'] : '';
$malla_base = isset($res['malla_base']) ? $res['malla_base'] : '';
$coliformes = isset($res['coliformes']) ? $res['coliformes'] : '';
$ecoli = isset($res['ecoli']) ? $res['ecoli'] : '';
$salmonella = isset($res['salmonella']) ? $res['salmonella'] : '';
$saereus = isset($res['saereus']) ? $res['saereus'] : '';

include 'orden_devolucion_validacion.php';

?>

<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Captura de parámetros</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_devolucion_parametros" method="POST">
                <input type="text" name="odd_id" id="odd_id" class="d-none" value="<?= $odd_id ?>">
                <input type="text" name="oda_id" id="oda_id" class="d-none" value="<?= isset($res['oda_id']) ? $res['oda_id'] : '' ?>">
                <div class="row">
                    <div class="col-md-3">
                        <label for="color" class="form-label">Color</label>
                        <select name="color" id="color" class="form-select <?= in_array('color', $parametros_fallidos) ? 'is-invalid' : '' ?>" required>
                            <option value="">Seleccione</option>
                            <option value="0" <?= isset($res['color']) && $res['color'] === "0" ? 'selected' : '' ?>>0 - EXCELENTE</option>
                            <option value="1" <?= isset($res['color']) && $res['color'] === "1" ? 'selected' : '' ?>>1 - MUY BIEN</option>
                            <option value="2" <?= isset($res['color']) && $res['color'] === "2" ? 'selected' : '' ?>>2 - BIEN</option>
                            <option value="3" <?= isset($res['color']) && $res['color'] === "3" ? 'selected' : '' ?>>3 - ACEPTABLE</option>
                            <option value="4" <?= isset($res['color']) && $res['color'] === "4" ? 'selected' : '' ?>>4 - MAL</option>
                            <option value="5" <?= isset($res['color']) && $res['color'] === "5" ? 'selected' : '' ?>>5 - MUY MAL</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="redox" class="form-label">Redox</label>
                        <input type="text" class="form-control <?= in_array('redox', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="redox" id="redox" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['redox']) ? $res['redox'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="ph" class="form-label">PH</label>
                        <input type="text" class="form-control <?= in_array('ph', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="ph" id="ph" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['ph']) ? $res['ph'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="trans" class="form-label">Trans</label>
                        <input type="text" class="form-control <?= in_array('trans', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="trans" id="trans" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['trans']) ? $res['trans'] : '' ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="olor" class="form-label">Olor</label>
                        <select name="olor" id="olor" class="form-select <?= in_array('olor', $parametros_fallidos) ? 'is-invalid' : '' ?>" required>
                            <option value="">Seleccione</option>
                            <option value="0" <?= isset($res['olor']) && $res['olor'] === "0" ? 'selected' : '' ?>>0 -SIN OLOR</option>
                            <option value="1" <?= isset($res['olor']) && $res['olor'] === "1" ? 'selected' : '' ?>>1 - CARACTERÍSTICO</option>
                            <option value="2" <?= isset($res['olor']) && $res['olor'] === "2" ? 'selected' : '' ?>>2 -LIGERO</option>
                            <option value="3" <?= isset($res['olor']) && $res['olor'] === "3" ? 'selected' : '' ?>>3 -ACENTUADO</option>
                            <option value="4" <?= isset($res['olor']) && $res['olor'] === "4" ? 'selected' : '' ?>>4 -MUY ACENTUADO</option>
                            <option value="5" <?= isset($res['olor']) && $res['olor'] === "5" ? 'selected' : '' ?>>5 - INTENSO</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="ntu" class="form-label">NTU</label>
                        <input type="text" class="form-control <?= in_array('ntu', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="ntu" id="ntu" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['ntu']) ? $res['ntu'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="humedad" class="form-label">Humedad</label>
                        <input type="text" class="form-control <?= in_array('humedad', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="humedad" id="humedad" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['humedad']) ? $res['humedad'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="viscosidad" class="form-label">Viscosidad</label>
                        <input type="text" class="form-control <?= in_array('viscosidad', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="viscosidad" id="viscosidad" onchange="determinarCalidad()" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['viscosidad']) && $res['viscosidad'] == '0.00' ? '' : ($res['viscosidad'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="ce" class="form-label">Conduct</label>
                        <input type="text" class="form-control <?= in_array('ce', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="ce" id="ce" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['ce']) ? $res['ce'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="par_ind" class="form-label">Part. Ind</label>
                        <input type="text" class="form-control <?= in_array('par_ind', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="par_ind" id="par_ind" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['par_ind']) ? $res['par_ind'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="pe_1kg" class="form-label">P.E en 1 kg</label>
                        <input type="text" class="form-control <?= in_array('pe_1kg', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="pe_1kg" id="pe_1kg" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['pe_1kg']) ? $res['pe_1kg'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="par_extr" class="form-label">Part. Extrañas</label>
                        <input type="text" class="form-control <?= in_array('par_extr', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="par_extr" id="par_extr" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['par_extr']) ? $res['par_extr'] : '' ?>" required>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-3">
                        <label for="hidratacion" class="form-label">Hidratación</label>
                        <select class="form-select <?= in_array('hidratacion', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="hidratacion" id="hidratacion" required>
                            <option value="">Seleccione</option>
                            <option value="MAL" <?= isset($res['hidratacion']) && $res['hidratacion']  === "MAL" ? 'selected' : '' ?>>MAL</option>
                            <option value="REG" <?= isset($res['hidratacion']) && $res['hidratacion']  === "REG" ? 'selected' : '' ?>>REGULAR</option>
                            <option value="BIEN" <?= isset($res['hidratacion']) && $res['hidratacion']  === "BIEN" ? 'selected' : '' ?>>BIEN</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="porcentaje_t" class="form-label">%T</label>
                        <input type="text" class="form-control <?= in_array('porcentaje_t', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="porcentaje_t" id="porcentaje_t" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['porcentaje_t']) ? $res['porcentaje_t'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="malla_30" class="form-label">Malla #30</label>
                        <input type="text" class="form-control <?= in_array('malla_30', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="malla_30" id="malla_30" value="<?= isset($res['malla_30']) ? $res['malla_30'] : '' ?>" onkeypress="return isNumberKey(event, this);" required>
                    </div>
                    <div class="col-md-3">
                        <label for="malla_45" class="form-label">Malla #45</label>
                        <input type="text" class="form-control <?= in_array('malla_45', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="malla_45" id="malla_45" value="<?= isset($res['malla_45']) ? $res['malla_45'] : '' ?>" required onkeypress="return isNumberKey(event, this);">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="malla_100" class="form-label">Malla #60</label>
                        <input type="text" class="form-control <?= in_array('malla_60', $parametros_fallidos) ? 'is-invalid' : '' ?>"" name=" malla_60" id="malla_60" value="<?= isset($res['malla_60']) ? $res['malla_60'] : '' ?>" onkeypress="return isNumberKey(event, this);">
                    </div>
                    <div class="col-md-3">
                        <label for="malla_100" class="form-label">Malla #100</label>
                        <input type="text" class="form-control <?= in_array('malla_100', $parametros_fallidos) ? 'is-invalid' : '' ?>"" name=" malla_100" id="malla_100" value="<?= isset($res['malla_100']) ? $res['malla_100'] : '' ?>" onkeypress="return isNumberKey(event, this);">
                    </div>
                    <div class="col-md-3">
                        <label for="malla_200" class="form-label">Malla #200</label>
                        <input type="text" class="form-control <?= in_array('malla_200', $parametros_fallidos) ? 'is-invalid' : '' ?>"" name=" malla_200" id="malla_200" value="<?= isset($res['malla_200']) ? $res['malla_200'] : '' ?>" onkeypress="return isNumberKey(event, this);">
                    </div>
                    <div class="col-md-3">
                        <label for="malla_base" class="form-label">Malla Base</label>
                        <input type="text" class="form-control <?= in_array('malla_base', $parametros_fallidos) ? 'is-invalid' : '' ?>"" name=" malla_base" id="malla_base" value="<?= isset($res['malla_base']) ? $res['malla_base'] : '' ?>" onkeypress="return isNumberKey(event, this);">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <?php
                        $rechazado = (isset($res['rechazado']) && ($res['rechazado'] === "C" || $res['rechazado'] === "R")) ? 'Si' : 'No';
                        $rechazado_val = $res['rechazado'] ?? null;

                        $rechazado = (in_array($rechazado_val, ['C', 'R'])) ? 'Si' : 'No';

                        $labels = [
                            'C' => 'Cuarentena',
                            'R' => 'Rechazo'
                        ];
                        $rechazado_label = $labels[$rechazado_val] ?? '';

                        ?>
                        <label for="rechazado" class="form-label"><?= $rechazado_label ?></label>
                        <input type="text" class="form-control <?= $rechazado === 'Si' ? 'is-invalid' : '' ?>" name="rechazado" id="rechazado" value="<?= $rechazado ?>" readonly>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <span class="fw-bold fs-3" style="background-color: yellow;width:300px">Después de 18 horas</span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <?php
                        $bloom = isset($res['bloom']) && $res['bloom'] == '0.00' ? '' : ($res['bloom'] ?? '');
                        ?>
                        <label for="bloom" class="form-label">Bloom</label>
                        <input type="text" class="form-control <?= in_array('bloom', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="bloom" id="bloom" onchange="determinarCalidad()" onkeypress="return isNumberKey(event, this);" value="<?= $bloom ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="cenizas" class="form-label">Cenizas</label>
                        <input type="text" class="form-control <?= in_array('cenizas', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="cenizas" id="cenizas" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['cenizas']) ? $res['cenizas'] : '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="coliformes" class="form-label">Coliformes</label>
                        <input type="text" class="form-control <?= in_array('coliformes', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="coliformes" id="coliformes" onkeypress="return isNumberKey(event,this)" value="<?= isset($res['coliformes']) ? $res['coliformes'] : '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="ecoli" class="form-label">E.Coli</label>
                        <input type="text" class="form-control <?= in_array('ecoli', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="ecoli" id="ecoli" onkeypress="return isNumberKey(event,this)" value="<?= isset($res['ecoli']) ? $res['ecoli'] : '' ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="salmonella" class="form-label">Salmonella</label>
                        <input type="text" class="form-control <?= in_array('salmonella', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="salmonella" id="salmonella" onkeypress="return isNumberKey(event,this)" value="<?= isset($res['salmonella']) ? $res['salmonella'] : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="saereus" class="form-label">S.Aereus</label>
                        <input type="text" class="form-control <?= in_array('saereus', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="saereus" id="saereus" onkeypress="return isNumberKey(event,this)" value="<?= isset($res['saereus']) ? $res['saereus'] : '' ?>">
                    </div>
                    <div class="col-md-4 d-none">
                        <label for="cal_id" class="form-label">Id Calidad</label>
                        <input type="text" class="form-control" name="cal_id" id="cal_id" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['cal_id']) ? $res['cal_id'] : '' ?>">
                    </div>

                    <div class="col-md-4">
                        <label for="cal_descripcion" class="form-label">Calidad</label>
                        <?php

                        $revBloom = isset($res['bloom']) ? $res['bloom'] : null;
                        $revViscosidad = isset($res['viscosidad']) ? $res['viscosidad'] : null;
                        $calDescripcion = isset($res['cal_descripcion']) ? $res['cal_descripcion'] : null;

                        if (
                            ($calDescripcion === null && $revBloom === '0.00' && $revViscosidad === '0.00') ||
                            ($calDescripcion === null && $revBloom === null && $revViscosidad === null)
                        ) {
                            $cal_descripcion = '';
                        } else if ($calDescripcion !== null) {
                            $cal_descripcion = $calDescripcion;
                        } else {
                            $cal_descripcion = 'Sin determinar';
                        }
                        ?>

                        <input type="text" class="form-control" name="cal_descripcion" id="cal_descripcion" onkeypress="return isNumberKey(event, this);" value="<?= htmlspecialchars($cal_descripcion) ?>" readonly>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-devolucion-param" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_devolucion_parametros" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        function obtenerDatosIniciales() {
            return {
                odd_id: $('#odd_id').val(),
                viscosidad: $('#viscosidad').val(),
                ph: $('#ph').val(),
                trans: $('#trans').val(),
                ntu: $('#ntu').val(),
                humedad: $('#humedad').val(),
                ce: $('#ce').val(),
                redox: $('#redox').val(),
                color: $('#color').val(),
                olor: $('#olor').val(),
                pe_1kg: $('#pe_1kg').val(),
                par_extr: $('#par_extr').val(),
                par_ind: $('#par_ind').val(),
                hidratacion: $('#hidratacion').val(),
                porcentaje_t: $('#porcentaje_t').val(),
                malla_30: $('#malla_30').val(),
                malla_45: $('#malla_45').val(),
                malla_60: $('#malla_60').val(),
                malla_100: $('#malla_100').val(),
                malla_200: $('#malla_200').val(),
                malla_base: $('#malla_base').val()
            };
        }

        function obtenerDatosCompletos() {
            const datos = obtenerDatosIniciales();
            datos.oda_id = $('#oda_id').val();
            datos.bloom = $('#bloom').val();
            datos.cenizas = $('#cenizas').val();
            datos.coliformes = $('#coliformes').val();
            datos.ecoli = $('#ecoli').val();
            datos.salmonella = $('#salmonella').val();
            datos.saereus = $('#saereus').val();
            datos.cal_id = $('#cal_id').val();
            return datos;
        }

        function enviarDatos(url, datos) {
            $.ajax({
                url: url,
                method: 'POST',
                data: datos,
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        $('#dataTableDevolucion').DataTable().ajax.reload();
                        alertas_v5("#alerta-devolucion-param", 'Listo!', res.message, 1, true, 5000);
                    } else {
                        alertas_v5("#alerta-devolucion-param", 'Error!', res.message, 3, true, 5000);
                    }

                    $('#form_devolucion_parametros .is-invalid').removeClass('is-invalid');
                    if (res.fallidos.length > 0) {
                        $('#rechazado').val('Si');
                        $('#rechazado').addClass('is-invalid');
                        res.fallidos.forEach(function(param) {
                            $('#' + param).addClass('is-invalid');
                        });
                    } else {
                        $('#form_devolucion_parametros .is-invalid').removeClass('is-invalid');
                        $('#rechazado').val('No');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', error);
                    alert('Ocurrió un error en el servidor.');
                }
            });
        }

        $('#form_devolucion_parametros').submit(function(e) {
            e.preventDefault();
            let data;
            let url = '';
            if ($('#oda_id').val() === '') {
                data = obtenerDatosIniciales();
                url = 'funciones/orden_devolucion_insertar_parametros.php';
            } else {
                data = obtenerDatosCompletos();
                url = 'funciones/orden_devolucion_actualizar_parametros.php';
            }

            data = JSON.stringify(data);
            enviarDatos(url, data);

        });
    });

    function determinarCalidad() {
        let bloom = $('#bloom').val();
        let viscosidad = $('#viscosidad').val();

        let dataForm = {
            'tar_bloom': bloom,
            'tar_viscosidad': viscosidad
        }

        $.ajax({
            type: 'POST',
            url: 'funciones/tarimas_determinar_calidad.php',
            data: dataForm,
            success: function(response) {
                let res = JSON.parse(response);

                $('#cal_descripcion').val(res.calidad);
                $('#cal_id').val(res.cal_id);
            }
        });
    }
</script>