<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../seguridad/user_seguridad.php";
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();
extract($_POST);
$consulta =  mysqli_query($cnx, "select * from  almacen_cajones where ac_id = '$hdd_cajon'");
$reg_consulta =  mysqli_fetch_array($consulta);

?>
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> -->
<div class="modal-dialog">
    <form id="form_cajones_e">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edición de cajón <?php echo  $reg_consulta['ac_descripcion'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <label for="exampleInputEmail1" class="form-label">Patio</label>
                        <select type="text" class="form-select" id="cbx_patio_e" name="cbx_patio_e" required>
                            <?php
                            if ($reg_consulta['ac_ban'] == 'M') {
                                $var_est = "Materia prima";
                            }

                            if ($reg_consulta['ac_ban'] == 'P') {
                                $var_est = "Molinos";
                            }
                            ?>

                            <option value="<?php echo $reg_consulta['ac_ban']; ?>"><?php echo mb_convert_encoding($var_est, "UTF-8") ?></option>
                            <?php
                            if ($reg_consulta['ac_ban'] == 'M') {
                                echo '<option value="P">Molinos</option>';
                            }
                            if ($reg_consulta['ac_ban'] == 'P') {
                                echo '<option value="M">Materia prima</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="exampleInputEmail1" class="form-label">Cajón</label>
                        <input type="hidden" name="hdd_cajon" value="<?php echo $hdd_cajon ?>">
                        <input onchange="valida_edicion()" onkeypress="return isNumberKey(event, this);" autocomplete="off" type="text" class="form-control" id="txt_cajon_e" name="txt_cajon_e" value="<?php echo $reg_consulta['ac_descripcion'] ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="exampleInputEmail1" class="form-label">Estatus</label>
                        <select type="text" class="form-select" id="cbx_estatus_e" name="cbx_estatus_e" required>
                            <?php
                            if ($reg_consulta['ac_estatus'] == 'B') {
                                $var_est = "Baja";
                            }

                            if ($reg_consulta['ac_estatus'] == 'A') {
                                $var_est = "Activo";
                            }
                            ?>

                            <option value="<?php echo $reg_consulta['ac_estatus']; ?>"><?php echo mb_convert_encoding($var_est, "UTF-8") ?></option>
                            <?php
                            if ($reg_consulta['ac_estatus'] == 'A') {
                                echo '<option value="B">Baja</option>';
                            }
                            if ($reg_consulta['ac_estatus'] == 'B') {
                                echo '<option value="A">Activo</option>';
                            }
                            ?>
                        </select>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <!--mensajes-->
                <div class="col-md-6">
                    <div id="alerta-edicion-cajon" class="alert d-none">
                        <strong class="alert-heading">¡Error!</strong>
                        <span class="alert-body"></span>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="location.reload()"><i class="fa-solid fa-rectangle-xmark"></i> Cerrar</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
            </div>
    </form>
</div>
</div>
<!-- </div> -->
<script>
    $(document).ready(function() {
        $("#form_cajones_e").submit(function() {
            //alert('editar');
            var formData = $(this).serialize();
            $.ajax({
                url: "cajones_edit.php",
                type: 'POST',
                data: formData,
                success: function(result) {
                    data = JSON.parse(result);
                    if (data["mensaje"] == "Registro actualizado") {
                        alertas_v5("#alerta-edicion-cajon", '', data["mensaje"], 1, true, 5000);
                        setTimeout("location.reload()", 1000);
                    } else {
                        alertas_v5("#alerta-edicion-cajon", '', data["mensaje"], 3, true, 5000);
                    }
                }
            });
            return false;
        });
    });

    function valida_edicion() {
        var cajon = document.getElementById('txt_cajon_e').value;
        var patio = document.getElementById('cbx_patio_e').value;

        $.ajax({
            type: 'post',
            url: 'get_cajones.php',
            data: {
                "cajon": cajon,
                "patio": patio,
            },
            success: function(result) {
                data = JSON.parse(result);
                if (data["mensaje"] == "El registro ya existe") {
                    document.getElementById('txt_cajon_e').value = '';
                    alertas_v5("#alerta-edicion-cajon", '', data["mensaje"], 3, true, 5000);

                }
            }
        });
        return false;
    }
</script>