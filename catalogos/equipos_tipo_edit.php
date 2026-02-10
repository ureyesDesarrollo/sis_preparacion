<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
             FROM equipos_tipos  
             WHERE et_id = '" . $_POST['hdd_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el tipo de material");
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

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edición tipo de equipos</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="" id="form_tipo_equipos_e">
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descripción tipo de equipo:</label>
                        <input value="<?php echo $registros['et_descripcion'] ?>" onchange="valida_tipo_equipo()" name="txt_descripcion_tipo_e" type="text" class="form-control" id="txt_descripcion_tipo_e" maxlength="25" required placeholder=" Descripción tipo de equipo">
                        <input value="<?php echo $registros['et_id'] ?>" type="hidden" name="hdd_id_tipo" id="hdd_id_tipo">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Sigla:</label>
                        <input value="<?php echo $registros['et_tipo'] ?>" onchange="valida_sigla_e()" name="txt_sigla_e" type="text" class="form-control" id="txt_sigla_e" maxlength="1" required placeholder=" Sigla">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Orden en tablero:</label>
                        <input value="<?php echo $registros['et_orden'] ?>" onkeypress="return isNumberKey(event, this);" onchange="valida_orden_e()" name="txt_orden_e" type="text" class="form-control" id="txt_orden_e" maxlength="2" required placeholder="Orden en tablero">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Almacena</label>
                        <select name="slc_almacen_e" type="email" class="form-control" id="slc_almacen_e" required>
                            <?php
                            $list_estaus =  mysqli_query($cnx, "SELECT distinct(ban_almacena) as ban_almacena FROM equipos_tipos ORDER BY ban_almacena");
                            while ($reg_ban_almacena =  mysqli_fetch_assoc($list_estaus)) {
                            ?>
                                <option value="<?php echo mb_convert_encoding($reg_ban_almacena['ban_almacena'], "UTF-8");  ?>" <?php
                                                                                                                                if (mb_convert_encoding($reg_ban_almacena['ban_almacena'], "UTF-8") == $registros['ban_almacena']) {
                                                                                                                                ?> selected="selected" <?php } ?>>

                                    <?php
                                    if ($reg_ban_almacena['ban_almacena'] == 'S') {
                                        echo 'Si';
                                    }
                                    if ($reg_ban_almacena['ban_almacena'] == 'N') {
                                        echo 'No';
                                    }
                                    ?>
                                </option>

                            <?php } ?>

                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Estatus</label>
                        <select name="slc_estatus_e" type="email" class="form-control" id="slc_estatus_e" required>
                            <?php
                            $list_estaus =  mysqli_query($cnx, "SELECT distinct(et_estatus) as et_estatus FROM equipos_tipos ORDER BY et_estatus");
                            $tot_est = mysqli_num_rows($list_estaus);
                            while ($reg_estatus =  mysqli_fetch_assoc($list_estaus)) {
                                $estatus = '';
                            ?>
                                <option  value="<?php echo mb_convert_encoding($reg_estatus['et_estatus'], "UTF-8");  ?>" <?php
                                                                                                                            if (mb_convert_encoding($reg_estatus['et_estatus'], "UTF-8") == $registros['et_estatus']) {
                                                                                                                            ?> selected="selected" <?php } ?>>

                                    <?php
                                    if ($reg_estatus['et_estatus'] == 'A') {
                                        echo 'Activo';
                                    } else {
                                        echo 'Baja';
                                    }

                                    ?>
                                </option>

                            <?php

                            }

                            if ($reg_estatus['et_estatus'] == 'A') { ?>
                                <option value="B">Baja</option>
                            <?php } else { ?>
                                <option value="A">Activo</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-8">
                        <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Imagen de equipo</label>
                        <input value="<?php echo $registros['et_descripcion'] ?>" name="txt_file_e" type="file" class="form-control" id="txt_file_e">
                    </div>
                    <div class="form-group col-md-4">
                        <img id="imagen-seleccionada" style="width: 40%;margin-top:2rem;display:none" src="" alt="Imagen seleccionada">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-1 col-lg-1"></div>
                    <!--mensajes-->
                    <div class="col-sm-6 col-lg-7">
                        <div class="alert alert-info" id="alerta-tipo_equipos" style="height: 40px;display:none;position:fixed">
                            <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                            <strong>Titulo</strong> &nbsp;&nbsp;
                            <span> Mensaje </span>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-7">
                        <div class="alert alert-danger" id="alerta-tipo_equipos_valida" style=" height: 40px;display:none;">
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
    function valida_orden_e() {
        var orden = document.getElementById("txt_orden_e").value;
        var hdd_id = document.getElementById("hdd_id_tipo").value;

        $.ajax({
            url: "get_orden_quipo_tipo.php",
            type: 'POST',
            data: {
                "orden": orden,
                "hdd_id": hdd_id,
            },
            success: function(result) {
                //data = JSON.parse(result);
                // Mostrar el resultado
                if (result != '') {
                    data = JSON.parse(result);
                    document.getElementById('txt_orden_e').value = '';
                    alertas("#alerta-tipo_equipos_valida", '', data["mensaje"], 4, true, 5000);

                    document.getElementById('alerta-tipo_equipos_valida').style.display = 'block';
                    //document.getElementById('imagen-seleccionada').style.display = 'none';
                }
            }
        });
        return false;
    }

    function valida_sigla_e() {
        var orden = document.getElementById("txt_sigla_e").value;
        var hdd_id = document.getElementById("hdd_id_tipo").value;

        $.ajax({
            url: "get_sigla_equipo_tipo.php",
            type: 'POST',
            data: {
                "orden": orden,
                "hdd_id": hdd_id,
            },
            success: function(result) {
                //data = JSON.parse(result);
                // Mostrar el resultado
                if (result != '') {
                    data = JSON.parse(result);
                    alertas("#alerta-tipo_equipos_valida", '', data["mensaje"], 4, true, 5000);
                    document.getElementById('txt_sigla_e').value = '';
                    document.getElementById('alerta-tipo_equipos_valida').style.display = 'block';
                }
            }
        });
        return false;
    }


    $(document).ready(function() {
        $("#form_tipo_equipos_e").submit(function() {
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: "equipos_tipo_editar.php",
                type: 'POST',
                data: formData,
                contentType: "application/json",
                success: function(result) {
                    data = JSON.parse(result);
                    alertas("#alerta-tipo_equipos", 'Listo!', data["mensaje"], 1, true, 5000);
                    document.getElementById('alerta-tipo_equipos').style.display = 'block';
                    document.getElementById('imagen-seleccionada').style.display = 'none';

                    $('#form_tipo_equipos').each(function() {
                        this.reset();
                    });

                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false;
        });

    });
</script>