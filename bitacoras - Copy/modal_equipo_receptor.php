<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
require_once('../conexion/conexion.php');
include('../funciones/funciones.php');
$cnx = Conectarse();

$cad_pa =  mysqli_query($cnx, "SELECT pa_id from procesos_agrupados where pro_id  = '" . $_POST['pro_id'] . "' ");
$reg_pa =  mysqli_fetch_array($cad_pa);

$cad_procesos =  mysqli_query($cnx, "SELECT pro_id from procesos_agrupados where pa_id  = '" . $reg_pa['pa_id'] . "' ");
$reg_procesos =  mysqli_fetch_array($cad_procesos);

$procesos = '';
$tot_kilos_modal = 0;

do {

    $cad_g_procesos = mysqli_query($cnx, "SELECT * FROM procesos
    WHERE pro_id  = '" . $reg_procesos['pro_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el proceso");
    $reg_g_procesos = mysqli_fetch_assoc($cad_g_procesos);

    $tot_kilos_modal += $reg_g_procesos['pro_total_kg'];

    $procesos .= $reg_procesos['pro_id'] . ', ';
} while ($reg_procesos =  mysqli_fetch_array($cad_procesos));

$new_process = substr(rtrim($procesos), 0, -1);
?>

<!--<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">-->
<div class="modal-dialog modal-md" role="document" style="width: 800px;">
    <div class="modal-content">
        <form id="formModalP" name="formModalP">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Enviar procesos a "Receptores"</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="recipient-name" class="col-form-label">Proceso:</label>
                        <input name="txtPro_x" type="text" id="txtPro_x" value="<?php echo $new_process ?>" readonly="true" class="form-control" />
                        <input name="txtPro" type="hidden" id="txtPro" value="<?php echo $_POST['pro_id'] ?>" readonly="true" class="form-control" />
                    </div>
                    <div class="col-md-4">
                        <label for="recipient-name" class="col-form-label">Equipo actual:</label>
                        <?php
                        $cad_eq =  mysqli_query($cnx, "SELECT ep_id, ep_descripcion,ep_descripcion from equipos_preparacion where ep_id = '" . $_POST['equipo'] . "' ");
                        $reg_eq =  mysqli_fetch_array($cad_eq);

                        ?>
                        <input type="text" class="form-control" id="txtDescripcion" name="txtDescripcion" readonly="true" value="<?php echo $reg_eq['ep_descripcion'] ?>">
                        <input type="hidden" class="form-control" id="txtEquipo" name="txtEquipo" readonly="true" value="<?php echo $reg_eq['ep_id'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="recipient-name" class="col-form-label">Kg totales en <?php echo $reg_eq['ep_descripcion'] ?>:</label>
                        <input id="txt_eq_kg_tot_ant" class="form-control" name="txt_eq_kg_tot_ant" value="<?php echo $tot_kilos_modal ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="recipient-name" class="col-form-label">Equipo nuevo:</label>
                        <select id="cbxEquipo" class="form-control" name="cbxEquipo" onchange="obtener_capacidad();" required>
                            <option value="">Seleccionar</option>
                            <?php

                            //$cadena =  mysqli_query($cnx,"SELECT * from preparacion_paletos where le_id IN (2,1) and pp_id > 2");//Invalidador en correo de Andrea "Detalle con el boton.."
                            //$cadena =  mysqli_query($cnx,"SELECT * from preparacion_paletos where le_id IN (2,1)");
                            $cadena =  mysqli_query($cnx, "SELECT e.ep_id,e.ep_descripcion,e.le_id from equipos_preparacion as e
                            inner join equipos_tipos as t on(e.ep_tipo = t.et_tipo)
                            where e.le_id IN (9,11) and e.estatus = 'A' and ban_almacena = 'S' order by e.ep_descripcion asc ");
                            $registros =  mysqli_fetch_array($cadena);
                            $tot_reg =  mysqli_num_rows($cadena);


                            if ($tot_reg > 0) {
                                do { ?>
                                    <option value="<?php echo $registros['ep_id'] ?>" <?php if ($registros['le_id'] == 11) { ?>style="background:#F7FEA0" <?php } ?>><?php echo $registros['ep_descripcion'] ?></option>
                            <?php } while ($registros =  mysqli_fetch_array($cadena));
                            }
                            //mysqli_free_result($registros);

                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="recipient-name" class="col-form-label">Kg totales en equipo</label>
                        <input id="txt_eq_kg_tot" class="form-control" name="txt_eq_kg_tot" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="recipient-name" class="col-form-label">Capacidad máxima equipo:</label>
                        <input id="txt_capacidad_nuevo" class="form-control" name="txt_capacidad_nuevo" readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="recipient-name" class="col-form-label">Lote:</label>
                        <input name="txt_lote" id="txt_lote" type="text" value="<?php echo fnc_lote_anio(date("Y")) ?>" readonly="true" class="form-control is-valid" required />
                    </div>
                    <div class="col-md-2">
                        <label for="recipient-name" class="col-form-label">Turno:</label>

                        <input type="text" name="txt_turno" id="txt_turno" class="form-control" readonly value="<?php echo fnc_obtener_momento_dia(); ?>">
                    </div>


                </div>
                <div class="modal-footer" style="margin-top: 8%;">
                    <!--mensajes-->
                    <div class="alert alert-info hide" id="alerta-errorAgregarR" style="height: 40px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
                        <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                        <strong>Titulo</strong> &nbsp;&nbsp;
                        <span> Mensaje </span>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();"><img src="../iconos/close.png" alt="">Cerrar</button>
                    <button class="btn btn-primary" type="submit" name="btn_enviar_equipo" id="btn_enviar_equipo"><img src="../iconos/guardar.png" alt=""> Guardar</button>
                </div>
            </div>
        </form>

    </div>
</div>
<!--</div>-->
<script>
    $(document).ready(function() {
        function hayConexion() {
            if (navigator.onLine) {
                return true; // Está conectado a Internet
            } else {
                return false; // No está conectado a Internet
            }
        }
        $("#formModalP").submit(function() {
            //alert('editar');
            var formData = $(this).serialize();
            $.ajax({
                url: "modal_equipo_receptor_agregar.php",
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    if (!hayConexion()) {
                        document.getElementById("alerta-errorAgregarR").style.display = "block";
                        alertas("#alerta-errorAgregarR", 'Error!', 'de conexión. Por favor, comprueba tu conexión a Internet y vuelve a intentarlo.', 4, true, '600000');
                        return false;
                    }
                    formModalP.btn_enviar_equipo.disabled = true;
                    formModalP.btn_enviar_equipo.value = "Enviando...";
                },
                success: function(result) {
                    data = JSON.parse(result);
                    //alert("Guardo el registro");
                    document.getElementById("alerta-errorAgregarR").style.display = "block";
                    alertas("#alerta-errorAgregarR", 'Listo!', data["mensaje"], 1, true, '5000');
                    $('#formModalP').each(function() {
                        this.reset();
                    });

                    setTimeout(function() {
                        location.reload();
                    }, 5000);
                }
            });
            //confirmEnviar3();
            return false;
        });

    });

    //obtener capacidad equipo nuevo
    function obtener_capacidad() {
        var datos = {
            "eq_nuevo": $("#cbxEquipo").val()
        }
        $.ajax({
            type: 'post',
            url: 'get_capacidad_max.php',
            data: datos,
            //data: {nombre:n},
            success: function(d) {
                //var capacidad_enterior = parseFloat(document.getElementById('txt_capacidad_anterior').value);
                var capacidad_nuevo = parseFloat(document.getElementById('txt_capacidad_nuevo').value = d);

                $.ajax({
                    type: 'post',
                    url: 'get_equipo_kg_total.php',
                    data: datos,
                    //data: {nombre:n},
                    success: function(d) {
                        var tot_kg_eq_anterior = parseFloat(document.getElementById('txt_eq_kg_tot_ant').value);
                        var tot_kg_eq_nuevo = parseFloat(document.getElementById('txt_eq_kg_tot').value = d);

                        var res = capacidad_nuevo - tot_kg_eq_nuevo;
                        var diferencia = res;

                        //obtener nombre de equipo anterior
                        var equipo_anterior = document.getElementById('txtDescripcion').value;

                        // Obtener el nombre del equipo nuevo
                        var val_eq_nuevo = document.getElementById("cbxEquipo");
                        var selecciona_nombre = val_eq_nuevo.options[val_eq_nuevo.selectedIndex];
                        var equipo_nuevo = selecciona_nombre.text;

                        if (tot_kg_eq_anterior > diferencia) {
                            document.getElementById("alerta-errorAgregarR").style.display = "block";
                            alertas("#alerta-errorAgregarR", 'Advertencia!', 'El total de kg de ' + equipo_anterior + ' supero la carga máxima del ' + equipo_nuevo, 3, true, '60000');
                            document.getElementById('btn_enviar_equipo').disabled = true;
                        } else {
                            document.getElementById("alerta-errorAgregarR").style.display = "none";
                            alertas("#alerta-errorAgregarR", 'Advertencia!', 'El total de kg de ' + equipo_anterior + ' supero la carga máxima del ' + equipo_nuevo, 3, true, '1000');
                            document.getElementById('btn_enviar_equipo').disabled = false;
                        }

                    }
                });

            }
        });

        return false;
    }
</script>