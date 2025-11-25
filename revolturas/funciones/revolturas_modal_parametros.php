<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Agosto-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

extract($_POST);

$res = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT r.*, c.cal_descripcion FROM rev_revolturas r JOIN rev_calidad c ON r.cal_id = c.cal_id WHERE r.rev_id = '$rev_id'"));
if ($res == null) {
    $res = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT * FROM rev_revolturas WHERE rev_id = '$rev_id'"));
}



// Verificación de variables vacías
$rev_bloom = (isset($res['rev_bloom']) && $res['rev_bloom'] != '0.00') ? $res['rev_bloom'] : '';
$rev_viscosidad = (isset($res['rev_viscosidad']) && $res['rev_viscosidad'] != '0.00') ? $res['rev_viscosidad'] : '';
$rev_ph = isset($res['rev_ph']) ? $res['rev_ph'] : '';
$rev_trans = isset($res['rev_trans']) ? $res['rev_trans'] : '';
$rev_ntu = isset($res['rev_ntu']) ? $res['rev_ntu'] : '';
$rev_humedad = isset($res['rev_humedad']) ? $res['rev_humedad'] : '';
$rev_cenizas = isset($res['rev_cenizas']) ? $res['rev_cenizas'] : '';
$rev_ce = isset($res['rev_ce']) ? $res['rev_ce'] : '';
$rev_redox = isset($res['rev_redox']) ? $res['rev_redox'] : '';
$rev_color = isset($res['rev_color']) ? $res['rev_color'] : '';
/* $rev_fino = isset($res['rev_fino']) ? $res['rev_fino'] : ''; */
$rev_olor = isset($res['rev_olor']) ? $res['rev_olor'] : '';
$rev_pe_1kg = isset($res['rev_pe_1kg']) ? $res['rev_pe_1kg'] : '';
$rev_par_extr = isset($res['rev_par_extr']) ? $res['rev_par_extr'] : '';
$rev_par_ind = isset($res['rev_par_ind']) ? $res['rev_par_ind'] : '';
$rev_hidratacion = isset($res['rev_hidratacion']) ? $res['rev_hidratacion'] : '';
$rev_porcentaje_t = isset($res['rev_porcentaje_t']) ? $res['rev_porcentaje_t'] : '';
$rev_malla_30 = isset($res['rev_malla_30']) ? $res['rev_malla_30'] : '';
$rev_malla_45 = isset($res['rev_malla_45']) ? $res['rev_malla_45'] : '';
$rev_malla_60 = isset($res['rev_malla_60']) ? $res['rev_malla_60'] : '';
$rev_malla_100 = isset($res['rev_malla_100']) ? $res['rev_malla_100'] : '';
$rev_malla_200 = isset($res['rev_malla_200']) ? $res['rev_malla_200'] : '';
$rev_malla_base = isset($res['rev_malla_base']) ? $res['rev_malla_base'] : '';

include 'revolturas_validacion.php';

?>

<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Captura de parámetros de calidad: Revoltura: <?= $res['rev_folio'] ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_revoltura_parametros" method="POST">
                <input type="text" name="rev_id" id="rev_id" class="d-none" value="<?= $rev_id ?>">
                <div class="row">
                    <div class="col-md-3">
                        <label for="rev_color" class="form-label">Color</label>
                        <select name="rev_color" id="rev_color" class="form-select <?= in_array('color', $parametros_fallidos) ? 'is-invalid' : '' ?>" required>
                            <option value="">Seleccione</option>
                            <option value="0" <?= $res['rev_color'] === "0.00" ? 'selected' : '' ?>>0 - EXCELENTE</option>
                            <option value="1" <?= $res['rev_color'] === "1.00" ? 'selected' : '' ?>>1 - MUY BIEN</option>
                            <option value="2" <?= $res['rev_color'] === "2.00" ? 'selected' : '' ?>>2 - BIEN</option>
                            <option value="3" <?= $res['rev_color'] === "3.00" ? 'selected' : '' ?>>3 - ACEPTABLE</option>
                            <option value="4" <?= $res['rev_color'] === "4.00" ? 'selected' : '' ?>>4 - MAL</option>
                            <option value="5" <?= $res['rev_color'] === "5.00" ? 'selected' : '' ?>>5 - MUY MAL</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="rev_redox" class="form-label">Redox</label>
                        <input type="text" class="form-control <?= in_array('redox', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_redox" id="rev_redox" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_redox'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="rev_ph" class="form-label">PH</label>
                        <input type="text" class="form-control <?= in_array('ph', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_ph" id="rev_ph" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_ph'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="rev_trans" class="form-label">Trans</label>
                        <input type="text" class="form-control <?= in_array('trans', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_trans" id="rev_trans" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_trans'] ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="rev_olor" class="form-label">Olor</label>
                        <select name="rev_olor" id="rev_olor" class="form-select <?= in_array('olor', $parametros_fallidos) ? 'is-invalid' : '' ?>" required>
                            <option value="">Seleccione</option>
                            <option value="0" <?= $res['rev_olor'] === "0" ? 'selected' : '' ?>>0 -SIN OLOR</option>
                            <option value="1" <?= $res['rev_olor'] === "1" ? 'selected' : '' ?>>1 - CARACTERÍSTICO</option>
                            <option value="2" <?= $res['rev_olor'] === "2" ? 'selected' : '' ?>>2 -LIGERO</option>
                            <option value="3" <?= $res['rev_olor'] === "3" ? 'selected' : '' ?>>3 -ACENTUADO</option>
                            <option value="4" <?= $res['rev_olor'] === "4" ? 'selected' : '' ?>>4 -MUY ACENTUADO</option>
                            <option value="5" <?= $res['rev_olor'] === "5" ? 'selected' : '' ?>>5 - INTENSO</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="rev_ntu" class="form-label">NTU</label>
                        <input type="text" class="form-control <?= in_array('ntu', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_ntu" id="rev_ntu" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_ntu'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="rev_humedad" class="form-label">Humedad</label>
                        <input type="text" class="form-control <?= in_array('humedad', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_humedad" id="rev_humedad" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_humedad'] ?>" required>
                    </div>
                    <!-- <div class="col-md-3">
                        <label for="rev_cenizas" class="form-label">Cenizas</label>
                        <input type="text" class="form-control <?= in_array('cenizas', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_cenizas" id="rev_cenizas" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_cenizas'] ?>" required>
                    </div> -->
                    <div class="col-md-3">
                        <label for="rev_viscosidad" class="form-label">Viscosidad</label>
                        <input type="text" class="form-control <?= in_array('viscosidad', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_viscosidad" id="rev_viscosidad" onchange="determinarCalidad()" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_viscosidad'] == '0.00' ? '' : $res['rev_viscosidad'] ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="rev_ce" class="form-label">Conduct</label>
                        <input type="text" class="form-control <?= in_array('ce', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_ce" id="rev_ce" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_ce'] ?>" required>
                    </div>
                    <!-- <div class="col-md-3">
                        <label for="rev_fino" class="form-label">Fino</label>
                        <input type="text" class="form-control <?= in_array('fino', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_fino" id="rev_fino" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_fino'] ?>" required>
                    </div> -->
                    <div class="col-md-3">
                        <label for="rev_par_ind" class="form-label">Part. Ind</label>
                        <input type="text" class="form-control <?= in_array('par_ind', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_par_ind" id="rev_par_ind" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_par_ind'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="rev_pe_1kg" class="form-label">P.E en 1 kg</label>
                        <input type="text" class="form-control <?= in_array('pe_1kg', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_pe_1kg" id="rev_pe_1kg" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_pe_1kg'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="rev_par_extr" class="form-label">Part. Extrañas</label>
                        <input type="text" class="form-control <?= in_array('par_extr', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_par_extr" id="rev_par_extr" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_par_extr'] ?>" required>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-3">
                        <label for="rev_hidratacion" class="form-label">Hidratación</label>
                        <select class="form-select <?= in_array('hidratacion', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_hidratacion" id="rev_hidratacion" required>
                            <option value="">Seleccione</option>
                            <option value="MAL" <?= $res['rev_hidratacion'] === "MAL" ? 'selected' : '' ?>>MAL</option>
                            <option value="REG" <?= $res['rev_hidratacion'] === "REG" ? 'selected' : '' ?>>REGULAR</option>
                            <option value="BIEN" <?= $res['rev_hidratacion'] === "BIEN" ? 'selected' : '' ?>>BIEN</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="rev_porcentaje_t" class="form-label">%T</label>
                        <input type="text" class="form-control <?= in_array('porcentaje_t', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_porcentaje_t" id="rev_porcentaje_t" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_porcentaje_t'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="malla_30" class="form-label">Malla #30</label>
                        <input type="text" class="form-control <?= in_array('malla_30', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_malla_30" id="rev_malla_30" value="<?= $res['rev_malla_30'] ?>" onkeypress="return isNumberKey(event, this);" required>
                    </div>
                    <div class="col-md-3">
                        <label for="malla_45" class="form-label">Malla #45</label>
                        <input type="text" class="form-control <?= in_array('malla_45', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_malla_45" id="rev_malla_45" value="<?= $res['rev_malla_45'] ?>" required onkeypress="return isNumberKey(event, this);">
                    </div>
                </div>
                <div class="row">
                <div class="col-md-3">
                        <label for="malla_100" class="form-label">Malla #60</label>
                        <input type="text" class="form-control <?= in_array('malla_60', $parametros_fallidos) ? 'is-invalid' : '' ?>"" name="rev_malla_60" id="rev_malla_60" value="<?= $res['rev_malla_60'] ?>" onkeypress="return isNumberKey(event, this);">
                    </div>
                    <div class="col-md-3">
                        <label for="malla_100" class="form-label">Malla #100</label>
                        <input type="text" class="form-control <?= in_array('malla_100', $parametros_fallidos) ? 'is-invalid' : '' ?>"" name=" rev_malla_100" id="rev_malla_100" value="<?= $res['rev_malla_100'] ?>" onkeypress="return isNumberKey(event, this);">
                    </div>
                    <div class="col-md-3">
                        <label for="malla_200" class="form-label">Malla #200</label>
                        <input type="text" class="form-control <?= in_array('malla_200', $parametros_fallidos) ? 'is-invalid' : '' ?>"" name=" rev_malla_200" id="rev_malla_200" value="<?= $res['rev_malla_200'] ?>" onkeypress="return isNumberKey(event, this);">
                    </div>
                    <div class="col-md-3">
                        <label for="malla_base" class="form-label">Malla Base</label>
                        <input type="text" class="form-control <?= in_array('malla_base', $parametros_fallidos) ? 'is-invalid' : '' ?>"" name=" rev_malla_base" id="rev_malla_base" value="<?= $res['rev_malla_base'] ?>" onkeypress="return isNumberKey(event, this);">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label for="rechazado" class="form-label">Rechazado</label>
                        <input type="text" class="form-control <?= $res['rev_rechazado'] === "R" ? 'is-invalid' : '' ?>" name="rechazado" id="rechazado" value="<?= $res['rev_rechazado'] === "R" ? 'Si' : 'No' ?>" readonly>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <span class="fw-bold fs-3" style="background-color: yellow;width:300px">Después de 18 horas</span>
                    </div>
                    <div class="col-md-4">
                        <label for="rev_bloom" class="form-label">Bloom</label>
                        <input type="text" class="form-control <?= in_array('bloom', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_bloom" id="rev_bloom" onchange="determinarCalidad()" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_bloom'] == '0.00' ? '' : $res['rev_bloom'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="rev_cenizas" class="form-label">Cenizas</label>
                        <input type="text" class="form-control <?= in_array('cenizas', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_cenizas" id="rev_cenizas" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_cenizas'] ?>">
                    </div>
                    <!-- <div class="col-md-4">
                        <label for="rev_viscosidad" class="form-label">Viscosidad</label>
                        <input type="text" class="form-control <?= in_array('viscosidad', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="rev_viscosidad" id="rev_viscosidad" onchange="determinarCalidad()" onkeypress="return isNumberKey(event, this);" value="<?= $res['rev_viscosidad'] == '0.00' ? '' : $res['rev_viscosidad'] ?>">
                    </div> -->
                    <div class="col-md-4 d-none">
                        <label for="cal_id" class="form-label">Id Calidad</label>
                        <input type="text" class="form-control" name="cal_id" id="cal_id" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['cal_id']) ? $res['cal_id'] : '' ?>">
                    </div>

                    <div class="col-md-4">
                        <label for="cal_descripcion" class="form-label">Calidad</label>
                        <?php

                        $revBloom = isset($res['rev_bloom']) ? $res['rev_bloom'] : null;
                        $revViscosidad = isset($res['rev_viscosidad']) ? $res['rev_viscosidad'] : null;
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
                    <div id="alerta-revoltura-param" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_revoltura_parametros" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#form_revoltura_parametros').submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            console.log(dataForm);
            $.ajax({
                type: 'POST',
                url: 'funciones/revolturas_insertar_parametros.php',
                data: dataForm,
                success: function(result) {
                    let res = JSON.parse(result);
                    if (res.success) {
                        $('#dataTableRevolturas').DataTable().ajax.reload();
                        alertas_v5("#alerta-revoltura-param", 'Listo!', res.success, 1, true, 5000);
                    } else {
                        alertas_v5("#alerta-revoltura-param", 'Error!', res.error, 3, true, 5000);
                    }

                    console.log(res.fallidos);
                    $('#form_revoltura_parametros .is-invalid').removeClass('is-invalid');
                    if (res.fallidos.length > 0) {
                        $('#rechazado').val('Si');
                        $('#rechazado').addClass('is-invalid');
                        res.fallidos.forEach(function(param) {
                            $('#rev_' + param).addClass('is-invalid');
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
        let rev_bloom = $('#rev_bloom').val();
        let rev_viscosidad = $('#rev_viscosidad').val();

        let dataForm = {
            'tar_bloom': rev_bloom,
            'tar_viscosidad': rev_viscosidad
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