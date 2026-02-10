<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

extract($_POST);

$sql_hist = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT COUNT(mez_id) as count 
FROM rev_tarimas_hist WHERE mez_id = '$mez_id';"));
if ($sql_hist['count'] != '0') {
    $res = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT t.*,c.cal_descripcion
    FROM rev_tarimas t
    JOIN rev_mezclas_tarimas rt ON t.tar_id = rt.tar_id
    JOIN rev_mezcla m ON rt.mez_id = m.mez_id
    LEFT JOIN rev_calidad c ON c.cal_id = m.cal_id 
    WHERE t.tar_estatus = 1 AND m.mez_id = '$mez_id' LIMIT 1"));
} else {
    $res = [];
}


$tar_bloom = (isset($res['tar_bloom']) && $res['tar_bloom'] != '0.00') ? $res['tar_bloom'] : '';
$tar_viscosidad = (isset($res['tar_viscosidad']) && $res['tar_viscosidad'] != '0.00') ? $res['tar_viscosidad'] : '';
$tar_ph = isset($res['tar_ph']) ? $res['tar_ph'] : '';
$tar_trans = isset($res['tar_trans']) ? $res['tar_trans'] : '';
$tar_ntu = isset($res['tar_ntu']) ? $res['tar_ntu'] : '';
$tar_humedad = isset($res['tar_humedad']) ? $res['tar_humedad'] : '';
$tar_cenizas = isset($res['tar_cenizas']) ? $res['tar_cenizas'] : '';
$tar_ce = isset($res['tar_ce']) ? $res['tar_ce'] : '';
$tar_redox = isset($res['tar_redox']) ? $res['tar_redox'] : '';
$tar_color = isset($res['tar_color']) ? $res['tar_color'] : '';
/* $tar_fino = isset($res['tar_fino']) ? $res['tar_fino'] : ''; */
$tar_olor = isset($res['tar_olor']) ? $res['tar_olor'] : '';
$tar_pe_1kg = isset($res['tar_pe_1kg']) ? $res['tar_pe_1kg'] : '';
$tar_par_extr = isset($res['tar_par_extr']) ? $res['tar_par_extr'] : '';
$tar_par_ind = isset($res['tar_par_ind']) ? $res['tar_par_ind'] : '';
$tar_hidratacion = isset($res['tar_hidratacion']) ? $res['tar_hidratacion'] : '';
$tar_porcentaje_t = isset($res['tar_porcentaje_t']) ? $res['tar_porcentaje_t'] : '';
$tar_malla_30 = isset($res['tar_malla_30']) ? $res['tar_malla_30'] : '';
$tar_malla_45 = isset($res['tar_malla_45']) ? $res['tar_malla_45'] : '';


include 'tarimas_validacion.php';
?>

<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Captura de parámetros de calidad: Mezcla: <?= $mez_folio ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_mezcla_parametros" method="POST">
                <input type="text" name="mez_id" id="mez_id" class="d-none" value="<?= $mez_id ?>">
                <div class="row">
                    <div class="col-md-3">
                        <label for="mez_color" class="form-label">Color</label>
                        <select name="mez_color" id="mez_color" class="form-select <?= in_array('color', $parametros_fallidos) ? 'is-invalid' : '' ?>" required>
                            <option value="">Seleccione</option>
                            <option value="0" <?= isset($res['tar_color']) && $res['tar_color'] === "0.00" ? 'selected' : '' ?>>0 - EXCELENTE</option>
                            <option value="1" <?= isset($res['tar_color']) && $res['tar_color'] === "1.00" ? 'selected' : '' ?>>1 - MUY BIEN</option>
                            <option value="2" <?= isset($res['tar_color']) && $res['tar_color'] === "2.00" ? 'selected' : '' ?>>2 - BIEN</option>
                            <option value="3" <?= isset($res['tar_color']) && $res['tar_color'] === "3.00" ? 'selected' : '' ?>>3 - ACEPTABLE</option>
                            <option value="4" <?= isset($res['tar_color']) && $res['tar_color'] === "4.00" ? 'selected' : '' ?>>4 - MAL</option>
                            <option value="5" <?= isset($res['tar_color']) && $res['tar_color'] === "5.00" ? 'selected' : '' ?>>5 - MUY MAL</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="mez_redox" class="form-label">Redox</label>
                        <input type="text" class="form-control <?= in_array('redox', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_redox" id="mez_redox" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_redox']) ? $res['tar_redox'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="mez_ph" class="form-label">PH</label>
                        <input type="text" class="form-control  <?= in_array('ph', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_ph" id="mez_ph" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_ph']) ? $res['tar_ph'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="mez_trans" class="form-label">Trans</label>
                        <input type="text" class="form-control <?= in_array('trans', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_trans" id="mez_trans" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_trans']) ? $res['tar_trans'] : '' ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="mez_olor" class="form-label">Olor</label>
                        <select name="mez_olor" id="mez_olor" class="form-select <?= in_array('olor', $parametros_fallidos) ? 'is-invalid' : '' ?>" required>
                            <option value="">Seleccione</option>
                            <option value="0" <?= isset($res['tar_olor']) && $res['tar_olor'] === "0" ? 'selected' : '' ?>>0 -SIN OLOR</option>
                            <option value="1" <?= isset($res['tar_olor']) && $res['tar_olor'] === "1" ? 'selected' : '' ?>>1 - CARACTERÍSTICO</option>
                            <option value="2" <?= isset($res['tar_olor']) && $res['tar_olor'] === "2" ? 'selected' : '' ?>>2 -LIGERO</option>
                            <option value="3" <?= isset($res['tar_olor']) && $res['tar_olor'] === "3" ? 'selected' : '' ?>>3 -ACENTUADO</option>
                            <option value="4" <?= isset($res['tar_olor']) && $res['tar_olor'] === "4" ? 'selected' : '' ?>>4 -MUY ACENTUADO</option>
                            <option value="5" <?= isset($res['tar_olor']) && $res['tar_olor'] === "5" ? 'selected' : '' ?>>5 - INTENSO</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="mez_ntu" class="form-label">NTU</label>
                        <input type="text" class="form-control <?= in_array('ntu', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_ntu" id="mez_ntu" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_ntu']) ? $res['tar_ntu'] : '' ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label for="mez_humedad" class="form-label">Humedad</label>
                        <input type="text" class="form-control <?= in_array('humedad', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_humedad" id="mez_humedad" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_humedad']) ? $res['tar_humedad'] : '' ?>" required>
                    </div>

                    <!-- <div class="col-md-3">
                        <label for="mez_cenizas" class="form-label">Cenizas</label>
                        <input type="text" class="form-control <?= in_array('cenizas', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_cenizas" id="mez_cenizas" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_cenizas']) ? $res['tar_cenizas'] : '' ?>" required>
                    </div> -->
                    <div class="col-md-3">
                        <label for="mez_viscosidad" class="form-label">Viscosidad</label>
                        <input type="text" class="form-control <?= in_array('viscosidad', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_viscosidad" id="mez_viscosidad" onchange="determinarCalidad()" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_viscosidad']) ? ($res['tar_viscosidad'] == '0.00' ? '' : $res['tar_viscosidad']) : '' ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="mez_ce" class="form-label">Conduct</label>
                        <input type="text" class="form-control <?= in_array('ce', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_ce" id="mez_ce" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_ce']) ? $res['tar_ce'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="mez_par_ind" class="form-label">Part. Ind</label>
                        <input type="text" class="form-control <?= in_array('par_ind', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_par_ind" id="mez_par_ind" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_par_ind']) ? $res['tar_par_ind'] : ''  ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="mez_pe_1kg" class="form-label">P.E en 1 kg</label>
                        <input type="text" class="form-control <?= in_array('pe_1kg', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_pe_1kg" id="mez_pe_1kg" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_pe_1kg']) ? $res['tar_pe_1kg'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="mez_par_extr" class="form-label">Part. Extrañas</label>
                        <input type="text" class="form-control <?= in_array('par_extr', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_par_extr" id="mez_par_extr" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_par_extr']) ? $res['tar_par_extr'] : '' ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="mez_hidratacion" class="form-label">Hidratación</label>
                        <select class="form-select <?= in_array('hidratacion', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_hidratacion" id="mez_hidratacion" required>
                            <option value="">Seleccione</option>
                            <option value="MAL" <?= isset($res['tar_hidratacion']) && $res['tar_hidratacion'] === "MAL" ? 'selected' : '' ?>>MAL</option>
                            <option value="REG" <?= isset($res['tar_hidratacion']) && $res['tar_hidratacion'] === "REG" ? 'selected' : '' ?>>REGULAR</option>
                            <option value="BIEN" <?= isset($res['tar_hidratacion']) && $res['tar_hidratacion'] === "BIEN" ? 'selected' : '' ?>>BIEN</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="mez_porcentaje_t" class="form-label">%T</label>
                        <input type="text" class="form-control <?= in_array('porcentaje_t', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_porcentaje_t" id="mez_porcentaje_t" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_porcentaje_t']) ? $res['tar_porcentaje_t'] : '' ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label for="mez_malla_30" class="form-label">Malla 30</label>
                        <input type="text" class="form-control <?= in_array('malla_30', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_malla_30" id="mez_malla_30" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_malla_30']) ? $res['tar_malla_30'] : '' ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="mez_malla_45" class="form-label">Malla 45</label>
                        <input type="text" class="form-control <?= in_array('malla_45', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_malla_45" id="mez_malla_45" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_malla_45']) ? $res['tar_malla_45'] : '' ?>" required>
                    </div>

                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label for="rechazado" class="form-label">Cuarentena</label>
                        <input type="text" class="form-control <?= isset($res['tar_rechazado']) ? ($res['tar_rechazado'] === "C" ? 'is-invalid' : '') : '' ?>" name="rechazado" id="rechazado" value="<?= isset($res['tar_rechazado']) ? ($res['tar_rechazado'] === "C" ? 'Si' : 'No') : '' ?>" readonly>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <span class="fw-bold fs-3" style="background-color: yellow;width:300px">Después de 18 horas</span>
                    </div>
                    <div class="col-md-4">
                        <label for="mez_bloom" class="form-label">Bloom</label>
                        <input type="text" class="form-control <?= in_array('bloom', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_bloom" id="mez_bloom" onchange="determinarCalidad()" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_bloom']) ? ($res['tar_bloom'] == '0.00' ? '' : $res['tar_bloom']) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="mez_cenizas" class="form-label">Cenizas</label>
                        <input type="text" class="form-control <?= in_array('cenizas', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_cenizas" id="mez_cenizas" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_cenizas']) ? $res['tar_cenizas'] : '' ?>">
                    </div>
                    <!-- <div class="col-md-4">
                        <label for="mez_viscosidad" class="form-label">Viscosidad</label>
                        <input type="text" class="form-control <?= in_array('viscosidad', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="mez_viscosidad" id="mez_viscosidad" onchange="determinarCalidad()" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['tar_viscosidad']) ? ($res['tar_viscosidad'] == '0.00' ? '' : $res['tar_viscosidad']) : '' ?>">
                    </div> -->
                    <div class="col-md-4 d-none">
                        <label for="cal_id" class="form-label">Id Calidad</label>
                        <input type="text" class="form-control" name="cal_id" id="cal_id" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['cal_id']) ? $res['cal_id'] : '' ?>">
                    </div>

                    <div class="col-md-4">
                        <label for="cal_descripcion" class="form-label">Calidad</label>
                        <?php
                        $cal_descripcion = '';

                        // Verifica si las claves están definidas en $res
                        $cal_descripcion_null = isset($res['cal_descripcion']) ? $res['cal_descripcion'] === null : false;
                        $tar_bloom_null = isset($res['tar_bloom']) ? $res['tar_bloom'] === '0.00' : false;
                        $tar_viscosidad_null = isset($res['tar_viscosidad']) ? $res['tar_viscosidad'] === '0.00' : false;

                        if (
                            ($cal_descripcion_null && $tar_bloom_null && $tar_viscosidad_null) ||
                            ($cal_descripcion_null && !isset($res['tar_bloom']) && !isset($res['tar_viscosidad']))
                        ) {
                            $cal_descripcion = '';
                        } else if (isset($res['cal_descripcion'])) {
                            $cal_descripcion = $res['cal_descripcion'];
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
                    <div id="alerta-mezcla-param" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_mezcla_parametros" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#form_mezcla_parametros').submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            console.log(dataForm);
            $.ajax({
                type: 'POST',
                url: 'funciones/mezclas_insertar_parametros.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    if (res.success) {
                        $('#dataTableMezclas').DataTable().ajax.reload();
                        alertas_v5("#alerta-mezcla-param", 'Listo!', res.success, 1, true, 5000);
                    } else {
                        alertas_v5("#alerta-mezcla-param", 'Error!', res.error, 3, true, 5000);
                    }

                    console.log(res.fallidos);
                    $('#form_mezcla_parametros .is-invalid').removeClass('is-invalid');
                    if (res.fallidos.length > 0) {
                        $('#rechazado').val('Si');
                        $('#rechazado').addClass('is-invalid');
                        res.fallidos.forEach(function(param) {
                            $('#mez_' + param).addClass('is-invalid');
                        });
                    } else {
                        $('#form_revoltura_parametros .is-invalid').removeClass('is-invalid');
                        $('#rechazado').val('No');
                    }
                }
            });
        });
    });

    function determinarCalidad() {
        let mez_bloom = $('#mez_bloom').val();
        let mez_viscosidad = $('#mez_viscosidad').val();

        let dataForm = {
            'tar_bloom': mez_bloom,
            'tar_viscosidad': mez_viscosidad
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