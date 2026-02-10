<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

extract($_POST);

$res = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT t.*, c.cal_descripcion FROM rev_tarimas t JOIN rev_calidad c ON t.cal_id = c.cal_id WHERE t.tar_id = '$tar_id'"));
if ($res == null) {
    $res = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT * FROM rev_tarimas WHERE tar_id = '$tar_id'"));
}



// Verificación de variables vacías
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
$tar_coliformes = isset($res['tar_coliformes']) ? $res['tar_coliformes'] : '';
$tar_ecoli = isset($res['tar_ecoli']) ? $res['tar_ecoli'] : '';
$tar_salmonella = isset($res['tar_salmonella']) ? $res['tar_salmonella'] : '';
$tar_saereus = isset($res['tar_saereus']) ? $res['tar_saereus'] : '';

include 'tarimas_validacion.php';

$isFino = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT tar_fino FROM rev_tarimas WHERE tar_id = '$tar_id'"))['tar_fino'];

if ($isFino == 'F') {
    $valores_a_eliminar = ['malla_30', 'malla_45'];
    $parametros_fallidos = array_diff($parametros_fallidos, $valores_a_eliminar);
}
?>

<script type="text/javascript" src="../js/alerta.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Captura de parámetros de calidad: Proceso: <?= $res['pro_id'] . '/ Tarima: ' . $res['tar_folio'] ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="form_tarima_parametros" method="POST">
                <input type="text" name="tar_id" id="tar_id" class="d-none" value="<?= $tar_id ?>">
                <div class="row">
                    <div class="col-md-3">
                        <label for="tar_color" class="form-label">Color</label>
                        <select name="tar_color" id="tar_color" class="form-select <?= in_array('color', $parametros_fallidos) ? 'is-invalid' : '' ?>" required>
                            <option value="">Seleccione</option>
                            <option value="0" <?= $res['tar_color'] === "0.00" ? 'selected' : '' ?>>0 - EXCELENTE</option>
                            <option value="1" <?= $res['tar_color'] === "1.00" ? 'selected' : '' ?>>1 - MUY BIEN</option>
                            <option value="2" <?= $res['tar_color'] === "2.00" ? 'selected' : '' ?>>2 - BIEN</option>
                            <option value="3" <?= $res['tar_color'] === "3.00" ? 'selected' : '' ?>>3 - ACEPTABLE</option>
                            <option value="4" <?= $res['tar_color'] === "4.00" ? 'selected' : '' ?>>4 - MAL</option>
                            <option value="5" <?= $res['tar_color'] === "5.00" ? 'selected' : '' ?>>5 - MUY MAL</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tar_redox" class="form-label">Redox</label>
                        <input type="number" class="form-control <?= in_array('redox', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_redox" id="tar_redox" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_redox'] ?>" required min="0" max="10" step="0.001">
                    </div>
                    <div class="col-md-3">
                        <label for="tar_ph" class="form-label">PH</label>
                        <input type="text" class="form-control <?= in_array('ph', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_ph" id="tar_ph" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_ph'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="tar_trans" class="form-label">Trans</label>
                        <input type="text" class="form-control <?= in_array('trans', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_trans" id="tar_trans" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_trans'] ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="tar_olor" class="form-label">Olor</label>
                        <select name="tar_olor" id="tar_olor" class="form-select <?= in_array('olor', $parametros_fallidos) ? 'is-invalid' : '' ?>" required>
                            <option value="">Seleccione</option>
                            <option value="0" <?= $res['tar_olor'] === "0" ? 'selected' : '' ?>>0 -SIN OLOR</option>
                            <option value="1" <?= $res['tar_olor'] === "1" ? 'selected' : '' ?>>1 - CARACTERÍSTICO</option>
                            <option value="2" <?= $res['tar_olor'] === "2" ? 'selected' : '' ?>>2 -LIGERO</option>
                            <option value="3" <?= $res['tar_olor'] === "3" ? 'selected' : '' ?>>3 -ACENTUADO</option>
                            <option value="4" <?= $res['tar_olor'] === "4" ? 'selected' : '' ?>>4 -MUY ACENTUADO</option>
                            <option value="5" <?= $res['tar_olor'] === "5" ? 'selected' : '' ?>>5 - INTENSO</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="tar_ntu" class="form-label">NTU</label>
                        <input type="text" class="form-control <?= in_array('ntu', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_ntu" id="tar_ntu" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_ntu'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="tar_humedad" class="form-label">Humedad</label>
                        <input type="text" class="form-control <?= in_array('humedad', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_humedad" id="tar_humedad" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_humedad'] ?>" required>
                    </div>
                    <!-- <div class="col-md-3">
                        <label for="tar_cenizas" class="form-label">Cenizas</label>
                        <input type="text" class="form-control <?= in_array('cenizas', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_cenizas" id="tar_cenizas" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_cenizas'] ?>" required>
                    </div> -->
                    <div class="col-md-3">
                        <label for="tar_viscosidad" class="form-label">Viscosidad</label>
                        <input type="text" class="form-control <?= in_array('viscosidad', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_viscosidad" id="tar_viscosidad" onchange="determinarCalidad()" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_viscosidad'] == '0.00' ? '' : $res['tar_viscosidad'] ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="tar_ce" class="form-label">Conduct</label>
                        <input type="text" class="form-control <?= in_array('ce', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_ce" id="tar_ce" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_ce'] ?>" required>
                    </div>
                    <!-- <div class="col-md-3">
                        <label for="tar_fino" class="form-label">Fino</label>
                        <input type="text" class="form-control <?= in_array('fino', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_fino" id="tar_fino" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_fino'] ?>" required>
                    </div> -->
                    <div class="col-md-3">
                        <label for="tar_par_ind" class="form-label">Part. Ind</label>
                        <input type="text" class="form-control <?= in_array('par_ind', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_par_ind" id="tar_par_ind" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_par_ind'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="tar_pe_1kg" class="form-label">P.E en 1 kg</label>
                        <input type="text" class="form-control <?= in_array('pe_1kg', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_pe_1kg" id="tar_pe_1kg" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_pe_1kg'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="tar_par_extr" class="form-label">Part. Extrañas</label>
                        <input type="text" class="form-control <?= in_array('par_extr', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_par_extr" id="tar_par_extr" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_par_extr'] ?>" required>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-3">
                        <label for="tar_hidratacion" class="form-label">Hidratación</label>
                        <select class="form-select <?= in_array('hidratacion', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_hidratacion" id="tar_hidratacion" required>
                            <option value="">Seleccione</option>
                            <option value="MAL" <?= $res['tar_hidratacion'] === "MAL" ? 'selected' : '' ?>>MAL</option>
                            <option value="REG" <?= $res['tar_hidratacion'] === "REG" ? 'selected' : '' ?>>REGULAR</option>
                            <option value="BIEN" <?= $res['tar_hidratacion'] === "BIEN" ? 'selected' : '' ?>>BIEN</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tar_porcentaje_t" class="form-label">%T</label>
                        <input type="text" class="form-control <?= in_array('porcentaje_t', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_porcentaje_t" id="tar_porcentaje_t" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_porcentaje_t'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="malla_30" class="form-label">Malla #30</label>
                        <input type="text" class="form-control <?= in_array('malla_30', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_malla_30" id="tar_malla_30" value="<?= $res['tar_malla_30'] ?>" onkeypress="return isNumberKey(event, this);" required>
                    </div>
                    <div class="col-md-3">
                        <label for="malla_45" class="form-label">Malla #45</label>
                        <input type="text" class="form-control <?= in_array('malla_45', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_malla_45" id="tar_malla_45" value="<?= $res['tar_malla_45'] ?>" required onkeypress="return isNumberKey(event, this);">
                    </div>
                </div>
                <div class="row mt-3">

                    <div class="col-md-3">
                        <label for="rechazado" class="form-label">Cuarentena</label>
                        <input type="text" class="form-control <?= $res['tar_rechazado'] === "C" || $res['tar_rechazado'] === "R" ? 'is-invalid' : '' ?>" name="rechazado" id="rechazado" value="<?= $res['tar_rechazado'] === "C" || $res['tar_rechazado'] === "R" ? 'Si' : 'No' ?>" readonly>
                    </div>

                    <div class="col-md-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <input type="text" class="form-control" value="<?= $isFino == 'F' ? 'Fino'  : 'Normal' ?>" readonly>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <span class="fw-bold fs-3" style="background-color: yellow;width:300px">Después de 18 horas</span>
                    </div>
                    <div class="col-md-3">
                        <label for="tar_bloom" class="form-label">Bloom</label>
                        <input type="text" class="form-control <?= in_array('bloom', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_bloom" id="tar_bloom" onchange="determinarCalidad()" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_bloom'] == '0.00' ? '' : $res['tar_bloom'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="tar_cenizas" class="form-label">Cenizas</label>
                        <input type="text" class="form-control <?= in_array('cenizas', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_cenizas" id="tar_cenizas" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_cenizas'] ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="tar_coliformes" class="form-label">Coliformes</label>
                        <input type="text" class="form-control <?= in_array('coliformes', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_coliformes" id="tar_coliformes" onkeypress="return isNumberKey(event,this)" value="<?= isset($res['tar_coliformes']) ? $res['tar_coliformes'] : '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="tar_ecoli" class="form-label">E.Coli</label>
                        <input type="text" class="form-control <?= in_array('ecoli', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_ecoli" id="tar_ecoli" onkeypress="return isNumberKey(event,this)" value="<?= isset($res['tar_ecoli']) ? $res['tar_ecoli'] : '' ?>">
                    </div>
                    <!-- <div class="col-md-4">
                        <label for="tar_viscosidad" class="form-label">Viscosidad</label>
                        <input type="text" class="form-control <?= in_array('viscosidad', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_viscosidad" id="tar_viscosidad" onchange="determinarCalidad()" onkeypress="return isNumberKey(event, this);" value="<?= $res['tar_viscosidad'] == '0.00' ? '' : $res['tar_viscosidad'] ?>">
                    </div> -->
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="tar_salmonella" class="form-label">Salmonella</label>
                        <input type="text" class="form-control <?= in_array('salmonella', $parametros_fallidos) ? 'is-invalid' : '' ?>" name="tar_salmonella" id="tar_salmonella" onkeypress="return isNumberKey(evente,this)" value="<?= isset($res['tar_salmonella']) ? $res['tar_salmonella'] : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="tar_saereus" class="form-label">S.Aereus</label>
                        <input type="text" class="form-control <?= in_array('saereus', $parametros_fallidos) ? 'is-invalid' : '' ?>"  name="tar_saereus" id="tar_saereus" onkeypress="return isNumberKey(event,this)" value="<?= isset($res['tar_saereus']) ? $res['tar_saereus'] : '' ?>">
                    </div>
                    <div class="col-md-4 d-none">
                        <label for="cal_id" class="form-label">Id Calidad</label>
                        <input type="text" class="form-control" name="cal_id" id="cal_id" onkeypress="return isNumberKey(event, this);" value="<?= isset($res['cal_id']) ? $res['cal_id'] : '' ?>">
                    </div>

                    <div class="col-md-4">
                        <label for="cal_descripcion" class="form-label">Calidad</label>
                        <?php

                        $tarBloom = isset($res['tar_bloom']) ? $res['tar_bloom'] : null;
                        $tarViscosidad = isset($res['tar_viscosidad']) ? $res['tar_viscosidad'] : null;
                        $calDescripcion = isset($res['cal_descripcion']) ? $res['cal_descripcion'] : null;

                        if (
                            ($calDescripcion === null && $tarBloom === '0.00' && $tarViscosidad === '0.00') ||
                            ($calDescripcion === null && $tarBloom === null && $tarViscosidad === null)
                        ) {
                            $cal_descripcion = '';
                        } else if ($calDescripcion !== null) {
                            $cal_descripcion = $calDescripcion;
                        } else {
                            $cal_descripcion = 'Sin determinar';
                        }
                        ?>

                        <input type="text" class="form-control" name="cal_descripcion" id="cal_descripcion" onkeypress="return isNumberKey(event, this);" value="<?= $cal_descripcion ?>" readonly>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="row w-100 align-items-center">
                <div class="col-md-8 mb-3">
                    <div id="alerta-tarima-param" class="alert alert-success m-0 d-none">
                        <strong class="alert-heading"></strong>
                        <span class="alert-body"></span>
                    </div>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <img src="../iconos/close.png" alt=""> Cerrar
                    </button>
                    <button form="form_tarima_parametros" type="submit" class="btn btn-primary ms-2">
                        <img src="../iconos/guardar.png" alt=""> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#form_tarima_parametros').submit(async function(e) {
            e.preventDefault();

            let dataForm = $(this).serialize();
            try {
                let response = await $.ajax({
                    type: 'POST',
                    url: 'funciones/tarimas_insertar_parametros.php',
                    data: dataForm
                });

                let res = JSON.parse(response);
                manejarRespuesta(res);

            } catch (error) {
                console.error('Error en la petición AJAX:', error);
                alertas_v5("#alerta-tarima-param", 'Error en el servidor', error, 3, true, 5000);
            }
        });

        function manejarRespuesta(res) {
            if (res.success) {
                $('#dataTableTarimas').DataTable().ajax.reload();
                alertas_v5("#alerta-tarima-param", 'Listo!', res.success, 1, true, 5000);
            } else {
                alertas_v5("#alerta-tarima-param", 'Error!', res.error, 3, true, 5000);
            }

            $('#form_tarima_parametros .is-invalid').removeClass('is-invalid');

            console.log(res);
            if (res.fallidos.length > 0) {
                $('#rechazado').val('Si').addClass('is-invalid');
                res.fallidos.forEach(param => $('#tar_' + param).addClass('is-invalid'));
                if(res.rechazado === 'R'){
                    manejarQR($('#tar_id').val(), 4,'tarimas_generar_qr_rechazado');// Opcion 4 rechazado
                }else{
                    manejarQR($('#tar_id').val(), 2,'tarimas_generar_qr'); 
                }
            } else {
                $('#rechazado').val('No');
                manejarQR($('#tar_id').val(), 3,'tarimas_generar_qr');
            }
        }

        async function manejarQR(tar_id, opcion, url) {
            let cal_id = $('#cal_id').val();
            console.log(url);
            if (!cal_id || cal_id === '0' || opcion === 4) {
                try {
                    let qrResponse = await generarQR(tar_id, opcion, url);
                    console.log('QR generado:');
                } catch (error) {
                    console.error('Error al generar QR:', error);
                }
            }
        }

        function generarQR(tar_id, opcion, url) {
            return $.ajax({
                    type: 'GET',
                    url: `funciones/${url}.php`,
                    data: {
                        tar_id,
                        opcion
                    },
                    cache: false,
                    headers: {
                        'Cache-Control': 'no-cache',
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    }
                }).then(response => JSON.parse(response))
                .catch(error => {
                    throw new Error(error)
                });
        }

    });
</script>