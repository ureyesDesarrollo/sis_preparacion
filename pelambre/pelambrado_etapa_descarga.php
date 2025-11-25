<?php
include "../conexion/conexion.php";
$cnx =  Conectarse();

extract($_GET);
?>
<form id="form_descarga">
    <div class="row renglones" id="titulos">
        <input type="hidden" name="hdd_id_pelambre" value="<?php echo $reg_pelambre['ip_id'] ?>">
        <input type="hidden" name="hdd_id_inventario" value="<?php echo $reg_pelambre['inv_id'] ?>">
        <input type="hidden" name="hdd_id_equipo" value="<?php echo $id_e ?>">
        <div class="col-md-2">
            <?php $type = "date" ?>
            <?php if ($reg_pelambre['ip_fe_descarga'] == null) {
                $ip_fe_descarga = '';
                $readonly = '';
            } else {
                $ip_fe_descarga = substr($reg_pelambre['ip_fe_descarga'], 0, 10);
                $readonly = 'readonly';
                $type = "text";
            } ?>
            <label for=" formFile" class="form-label">Fecha descarga en patio</label>
            <input type="<?php echo $type ?>" required class="form-control" id="inputPassword2" name="txt_fecha_descargo" value="<?php echo $ip_fe_descarga ?>" <?php echo $readonly ?>>
        </div>
        <div class="col-md-2">
            <?php if ($reg_pelambre['ip_kg_finales'] == null) {
                $ip_kg_finales = '';
                $readonly = '';
            } else {
                $ip_kg_finales  = $reg_pelambre['ip_kg_finales'];
                $readonly = 'readonly';
            } ?>
            <label for="formFile" class="form-label">Kilos finales</label>
            <input type="hidden" id="txt_kilos_enviados" name="txt_kilos_enviados" class="form-control" value="<?php echo  $inventario['inv_kilos'] ?>">
            <input type="text" required class="form-control" onkeypress="return isNumberKey(event, this);" autocomplete="off" id="txt_kg_totales" name="txt_kg_totales" value="<?php echo $ip_kg_finales ?> " <?php echo $readonly ?> onchange="valida_kilos();">
        </div>
        <div class="col-md-3">
            <?php if ($reg_pelambre['ip_observaciones'] == '') {
                $ip_observaciones = '';
                $readonly = '';
                $input = '<textarea  rows="1" required class="form-control" name="txt_observaciones" id="" value="<?php echo $ip_observaciones ?> "></textarea>';
            } else {
                $ip_observaciones = $reg_pelambre['ip_observaciones'];
                $readonly = 'readonly';
                $input = '<input type="text" required class="form-control" name="txt_observaciones" id="" value="' . $ip_observaciones  . '"' . $readonly . ">";
            } ?>
            <label for="formFile" class="form-label">Observaciones</label>
            <?php echo $input ?>
        </div>
        <!--<div class="col-md-2">
            <label for="recipient-name" class="form-label">Ubicación:</label>

            <select name="cbxUbicacion" class="form-select" id="cbxUbicacion" required="required">
                <option value="">Seleccionar ubicación</option>
                <?php /*
                $cad_cbx =  mysqli_query($cnx, "SELECT * FROM almacen_cajones WHERE ac_estatus = 'A' AND ac_ban = 'M' ORDER BY ac_descripcion");
                $reg_cbx =  mysqli_fetch_array($cad_cbx);

                do {
                ?>
                    <option value="<?php echo $reg_cbx['ac_id'] ?>">Cajón <?php echo $reg_cbx['ac_descripcion']; ?></option>
                <?php
                } while ($reg_cbx =  mysqli_fetch_array($cad_cbx));*/
                ?>
            </select>
        </div>-->
        <!--mensajes-->
        <div class="col p-3">
            <div id="alerta-accion_descarga" class="alert d-none">
                <strong class="alert-heading">¡Error!</strong>
                <span class="alert-body"></span>
            </div>
        </div>
        <?php if ($_SESSION['privilegio'] == 7) { ?>
            <div class="col" id="boton">
                <button id="btnGuardar_descarga" style="margin-top:2rem;" type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xl" style="color: #000;"></i> Guardar</button>
            </div>
        <?php } ?>
    </div>
</form>
<script>
    function valida_kilos() {
        var kilos_enviados = parseFloat(document.getElementById('txt_kilos_enviados').value);
        var kilos_totales = parseFloat(document.getElementById('txt_kg_totales').value);

        if (kilos_enviados >= kilos_totales) {
            alert('La cantidad debe ser mayor a ' + kilos_enviados + ' kg que se enviaron para pelambre');
            document.getElementById('txt_kg_totales').value = '';

        }
    }
</script>