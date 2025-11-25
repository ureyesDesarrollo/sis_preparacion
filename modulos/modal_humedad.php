<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Creado: Octubre-2023*/
include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();
extract($_POST);


$cadena = mysqli_query($cnx, "SELECT inv_id,mat_id,inv_kg_totales,inv_no_ticket,inv_fecha,prv_id, inv_extrac, inv_especial,inv_ban_flor, inv_alcalinidad,inv_calcios,inv_humedad, inv_ce FROM inventario WHERE inv_id='$inv_id'") or die(mysqli_error($cnx) . "Error: en consultar");
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

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Agregar humedad de origen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form name="form_humedad_origen" id="form_humedad_origen">
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="inputPassword4">Ticket</label>
                        <input class="form-control" type="hidden" readonly value="<?php echo $registros['inv_id'] ?>" name="inv_id" id="inv_id">
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
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="inputPassword4">Material</label>
                        <input class="form-control" type="text" readonly value="<?php echo $reg_mat['mat_nombre'] ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputPassword4">Humedad</label>
                        <input onKeyPress="return isNumberKey(event, this);" class="form-control" type="text" name="inv_humedad_origen" id="inv_humedad_origen" value="<?php echo $registros['inv_humedad_origen'] ?>">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><img src="../iconos/close.png" alt="">Cerrar</button>
                <button class="btn btn-primary" type="submit" id="btn" name="btn"><img src="../iconos/guardar.png" alt=""> Guardar</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#form_humedad_origen").submit(function() {
            var formData = $(this).serialize();
            $.ajax({
                url: "inventario_humedad.php",
                type: 'POST',
                data: formData,
                success: function(result) {
                    data = JSON.parse(result);

                    alertas("#alerta-error_dividir", 'Listo!', data["mensaje"], 1, true, 5000);
                    return filtro();
                }
            });
            return false;
        });
    });

</script>