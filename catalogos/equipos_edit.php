<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
             FROM equipos_preparacion 
             WHERE ep_id = '" . $_POST['hdd_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el tipo de material");
$registros = mysqli_fetch_assoc($cadena);

?>
<script>
    $(document).ready(function() {
        $("#form_equipos_e").submit(function() {
            //alert('editar');
            var formData = $(this).serialize();
            $.ajax({
                url: "equipos_editar.php",
                type: 'POST',
                data: formData,
                success: function(result) {
                    data = JSON.parse(result);
                    //alert("Guardo el registro");
                    alertas("#alerta-equipos_e", 'Listo!', data["mensaje"], 1, true, 5000);
                    document.getElementById('alerta-equipos_e').style.display = 'block';
                }
            });
            return false;
        });
    });
</script>


<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Modificación de equipos</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="" id="form_equipos_e">
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descripción equipo:</label>
                        <input onchange="valida_nombre_equipo_e()" name="txt_descripcion_e" type="text" class="form-control" id="txt_descripcion_e" maxlength="60" required placeholder=" Descripción equipo" value="<?php echo $registros['ep_descripcion'] ?>">
                        <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['ep_id'] ?>" />

                    </div>
                    <div class="form-group col-md-6">
                        <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Tipo equipo:</label>
                        <select name="cbx_tipo_e" type="email" class="form-control" id="cbx_tipo_e" required>
                            <option value="">Seleccionar</option>
                            <?php
                            $cad_tipo =  mysqli_query($cnx, "SELECT * FROM equipos_tipos ORDER BY et_descripcion");

                            while ($reg_tipo =  mysqli_fetch_assoc($cad_tipo)) { ?>
                                <option value="<?php echo $reg_tipo['et_tipo'];  ?>" <?php if ($reg_tipo['et_tipo'] == $registros['ep_tipo']) { ?> selected="selected" <?php } ?>>
                                    <?php echo mb_convert_encoding($reg_tipo['et_descripcion'], "UTF-8");  ?>
                                </option>
                            <?php } ?>
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Capacidad minima:</label>
                        <input onkeypress="return isNumberKey(event, this);" name="txt_capacidad_min" type="text" class="form-control" id="txt_capacidad_min" maxlength="8" required placeholder=" Capacidad minima" value="<?php echo $registros['ep_carga_min'] ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Capacidad maxima:</label>
                        <input onkeypress="return isNumberKey(event, this);" name="txt_capacidad_max" type="text" class="form-control" id="txt_capacidad_max" maxlength="8" required placeholder=" Capacidad maxima" value="<?php echo $registros['ep_carga_max'] ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Habilitado:</label>
                        <select name="cbx_estatus" type="email" class="form-control" id="cbx_estatus" required>
                            <?php
                            $list_estaus =  mysqli_query($cnx, "SELECT distinct(estatus) as estatus FROM equipos_preparacion ORDER BY estatus");
                            while ($reg_estatus =  mysqli_fetch_assoc($list_estaus)) {
                            ?>
                                <option value="<?php echo mb_convert_encoding($reg_estatus['estatus'], "UTF-8");  ?>" <?php
                                                                                                                        if (mb_convert_encoding($reg_estatus['estatus'], "UTF-8") == $registros['estatus']) {
                                                                                                                        ?> selected="selected" <?php } ?>>

                                    <?php
                                    if ($reg_estatus['estatus'] == 'A') {
                                        echo 'Si';
                                    } else {
                                        echo 'No';
                                    }
                                    ?>
                                </option>

                            <?php } ?>

                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <!--mensajes-->
                    <div class="col-sm-6 col-lg-7">
                        <div class="alert alert-info" id="alerta-equipos_e" style="height: 40px;display:none;position:fixed">
                            <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                            <strong>Titulo</strong> &nbsp;&nbsp;
                            <span> Mensaje </span>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-7">
                        <div class="alert alert-danger" id="alerta-equipo_nombre_valida_e" style="height: 40px;display:none;text-align:left">
                            <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                            <strong>Titulo</strong> &nbsp;&nbsp;
                            <span> Mensaje </span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-lg-2">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><i class="fa-solid fa-xmark"></i> Cerrar</button>
                    </div>
                    <div class="col-sm-3 col-lg-2">
                        <button class="btn btn-primary" type="submit"><i class="fa-regular fa-floppy-disk"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function valida_nombre_equipo_e() {
        var dato = document.getElementById("txt_descripcion_e").value;
        var hdd_id = document.getElementById("hdd_id").value;

        $.ajax({
            url: "get_equipo_nombre.php",
            type: 'POST',
            data: {
                "dato": dato,
                "hdd_id": hdd_id
            },
            success: function(result) {
                if (result != '') {
                    data = JSON.parse(result);
                    alertas("#alerta-equipo_nombre_valida_e", '', data["mensaje"], 4, true, 5000);
                    document.getElementById('txt_descripcion_e').value = '';
                    document.getElementById('alerta-equipo_nombre_valida_e').style.display = 'block';

                }
            }
        });
        return false;
    }
</script>