<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Creado: Octubre-2023*/
include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();
extract($_POST);


$cadena = mysqli_query($cnx, "SELECT inv_id,mat_id,inv_kg_totales,inv_no_ticket,inv_fecha,prv_id, inv_extrac, inv_especial,inv_ban_flor, inv_alcalinidad,inv_calcios,inv_humedad, inv_ce, inv_humedad_origen, inv_solidos, inv_ph, inv_rendimiento, inv_riesgo FROM inventario WHERE inv_id='$inv_id'") or die(mysqli_error($cnx) . "Error: en consultar");
$registros = mysqli_fetch_assoc($cadena);
$rows = mysqli_num_rows($cadena);

$cad_material = mysqli_query($cnx, "SELECT * FROM materiales WHERE mat_id ='" . $registros['mat_id'] . "'
  ") or die(mysqli_error($cnx) . "Error: en consultar material");
$reg_mat = mysqli_fetch_assoc($cad_material);

$tot_mat = mysqli_num_rows($cad_material);

$cad_prov = mysqli_query($cnx, "SELECT * FROM proveedores WHERE prv_id ='" . $registros['prv_id'] . "'
  ") or die(mysqli_error($cnx) . "Error: en consultar proveedor");
$reg_prov = mysqli_fetch_assoc($cad_prov);

?>
<script>
    $(document).ready(function() {
        $("#form_completar").submit(function() {
            var formData = $(this).serialize();
            $.ajax({
                url: "inventario_completar.php",
                type: 'POST',
                data: formData,
                success: function(result) {
                    data = JSON.parse(result);

                    alertas("#alerta-error_dividir", 'Listo!', data["mensaje"], 1, true, 5000);
                    /*                     cargar_inventario('#listadohistorial', 'inventario_historial.php');
                     */
                    return filtro();
                }
            });
            return false;
        });

        let rendimientoTimer;

        $('#txt_rendimiento').on('input', function() {
            clearTimeout(rendimientoTimer);

            const input = $(this);

            rendimientoTimer = setTimeout(function() {
                const rendimiento = parseFloat(input.val());

                let riesgo = '';
                let className = '';

                if (!Number.isNaN(rendimiento)) {

                    if (rendimiento >= 38) {
                        riesgo = 'ALTO - Sobre-tratado';
                        className = 'riesgo-danger';

                    } else if (rendimiento >= 34) {
                        riesgo = 'MEDIO - Reactivo';
                        className = 'riesgo-warning';

                    } else if (rendimiento >= 26) {
                        riesgo = 'BAJO - Normal';
                        className = 'riesgo-success';

                    } else if (rendimiento >= 22) {
                        riesgo = 'MEDIO - Limite bajo';
                        className = 'riesgo-warning';

                    } else {
                        riesgo = 'ALTO - Bajo rendimiento';
                        className = 'riesgo-danger';
                    }
                }

                $('#txt_riesgo')
                    .val(riesgo)
                    .removeClass('riesgo-danger riesgo-warning riesgo-success')
                    .addClass(className);

            }, 300);
        });
    });

    //carga opciones de menu sin importación
    /*  function cargar_inventario(div, desde) {
         $(div).load(desde);
     } */
</script>
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
 -->

<style>
    .riesgo-danger {
        background-color: #dc3545 !important;
        color: white !important;
    }

    .riesgo-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }

    .riesgo-success {
        background-color: #28a745 !important;
        color: white !important;
    }
</style>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Agregar exctractibilidad a material</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form name="form_completar" id="form_completar">
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="inputPassword4">Ticket</label>
                        <input class="form-control" type="hidden" readonly value="<?php echo $registros['inv_id'] ?>" name="hdd_id" id="hdd_id">
                        <input class="form-control" type="text" readonly value="<?php echo $registros['inv_no_ticket'] ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputPassword4">Fecha</label>
                        <input class="form-control" type="text" readonly value="<?php echo $registros['inv_fecha'] ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputPassword4">Kg totales</label>
                        <input class="form-control" type="text" readonly value="<?php echo $registros['inv_kg_totales'] ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputPassword4">Proveedor</label>
                        <input class="form-control" type="text" readonly value="<?php echo $reg_prov['prv_nombre'] ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputPassword4">Material</label>
                        <input class="form-control" type="text" readonly value="<?php echo $reg_mat['mat_nombre'] ?>">
                    </div>
                    <!--<div class="form-group col-md-2 d-none">
                        <label for="inputPassword4">Alcalinidad total</label>
                        <input onKeyPress="return isNumberKey(event, this);" class="form-control" type="text" name="txt_alcalinidad" id="txt_alcalinidad" value="<?php echo $registros['inv_alcalinidad'] ?>">
                    </div>
                    <div class="form-group col-md-2 d-none">
                        <label for="inputPassword4">Calcios</label>
                        <input onKeyPress="return isNumberKey(event, this);" class="form-control" type="text" name="txt_calcios" id="txt_calcios" value="<?php echo $registros['inv_calcios'] ?>">
                    </div>-->
                    <div class="form-group col-md-2">
                        <label for="inputPassword4">Solidos</label>
                        <input onKeyPress="return isNumberKey(event, this);" class="form-control" type="text" name="txt_solidos" id="txt_solidos" value="<?php echo $registros['inv_solidos'] ?>">
                    </div>
                    <?php if ($reg_mat['mt_id'] == 14 || $reg_mat['mat_id'] == 12 || $reg_mat['mat_id'] == 7): ?>
                        <div class="form-group col-md-2">
                            <label for="inputPassword4">Humedad Origen</label>
                            <input onKeyPress="return isNumberKey(event, this);" class="form-control" type="text" value="<?php echo $registros['inv_humedad_origen'] ?>" readonly>
                        </div>
                    <?php endif; ?>
                    <div class="form-group col-md-2">
                        <label for="inputPassword4">Humedad</label>
                        <input onKeyPress="return isNumberKey(event, this);" class="form-control" type="text" name="txt_humedad" id="txt_humedad" value="<?php echo $registros['inv_humedad'] ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputPassword4">Extractibilidad</label>
                        <input onKeyPress="return isNumberKey(event, this);" class="form-control" type="text" name="txt_ext" id="txt_ext" value="<?php echo $registros['inv_extrac'] ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputPassword4">Ce</label>
                        <input onKeyPress="return isNumberKey(event, this);" class="form-control" type="text" name="txt_ce" id="txt_ce" value="<?php echo $registros['inv_ce'] ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label>PH</label>
                        <input onKeyPress="return isNumberKey(event, this);" class="form-control" type="text" name="txt_ph" id="txt_ph" value="<?php echo $registros['inv_ph'] ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label>Rendimiento</label>
                        <input onKeyPress="return isNumberKey(event, this);" class="form-control" type="text" name="txt_rendimiento" id="txt_rendimiento" value="<?php echo $registros['inv_rendimiento'] ?>">
                    </div>
                    <div class="form-group col-md-7">
                        <label>Riesgo</label>
                        <input class="form-control" type="text" name="txt_riesgo" id="txt_riesgo" value="<?php echo $registros['inv_riesgo'] ?>" readonly>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputPassword4">Seguimiento</label>
                        <input type="checkbox" name="chk_seg" id="chk_seg" value="1" <?php if ($registros['inv_especial'] == 1) {
                                                                                            echo "checked";
                                                                                        } ?>>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="inputPassword4">Venta de flor</label>
                        <input type="checkbox" name="chk_flor" id="chk_flor" value="1" <?= $registros['inv_ban_flor'] == 1 ? 'checked' : '' ?>>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--mensajes-->
                <div class="alert alert-info hide" id="alerta-error_dividir" style="height: 40px;width: 250px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
                    <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                    <strong>Titulo</strong> &nbsp;&nbsp;
                    <span> Mensaje </span>
                </div>
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt="">Cerrar</button> -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><img src="../iconos/close.png" alt="">Cerrar</button>
                <button class="btn btn-primary" type="submit" id="btn" name="btn"><img src="../iconos/guardar.png" alt=""> Guardar</button>
            </div>
        </form>
    </div>
</div>
<!-- </div> -->