<?php
/*Desarrollado por: Ca & Ce Technologies */
/*21 - Abril - 2024*/
include "../seguridad/user_seguridad.php";
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();
extract($_POST);
?>
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> -->
<div class="modal-dialog">
    <form id="form_cajones">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Movimiento de material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row g-3 align-items-center">
                    <div class="col-md-2">
                        <label for="txt_folio" class="col-form-label">Folio</label>
                    </div>
                    <div class="col-auto">
                        <input type="hidden" id="hdd_inv" name="hdd_inv" class="form-control" aria-describedby="passwordHelpInline" value="<?php echo $hdd_inv ?>">
                        <input type="text" id="txt_folio" class="form-control" aria-describedby="passwordHelpInline" value="<?php echo $hdd_folio ?>" disabled>
                    </div>
                </div>
                <div class=" row g-3 align-items-center">
                    <div class="col-md-2">
                        <label for="txt_fecha" class="col-form-label">Fecha</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="txt_fecha" class="form-control" aria-describedby="passwordHelpInline" value="<?php echo $hdd_fecha ?>" disabled>
                    </div>
                </div>
                <div class=" row g-3 align-items-center">
                    <div class="col-md-2">
                        <label for="txt_fecha" class="col-form-label">Material</label>
                    </div>
                    <div class="col-auto">

                        <?php
                        $consulta =  mysqli_query($cnx, "select * from materiales where mat_id = '$hdd_mat'");
                        $reg = mysqli_fetch_assoc($consulta);
                        ?>
                        <input type="text" id="txt_fecha" class="form-control" aria-describedby="passwordHelpInline" value="<?php echo $reg['mat_nombre'] ?>" disabled>
                    </div>
                </div>
                <div class=" row g-3 align-items-center">
                    <div class="col-md-2">
                        <label for="txt_kg" class="col-form-label">Kilos</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="txt_kg" class="form-control" aria-describedby="passwordHelpInline" value="<?php echo $hdd_kg ?>" disabled>
                    </div>
                </div>
                <div class=" row g-3 align-items-center">
                    <?php
                    $proveedor =  mysqli_query($cnx, "select * from proveedores order by prv_nombre");
                    $reg =  mysqli_fetch_array($proveedor) ?>
                    <div class="col-md-2">
                        <label for="txt_proveedor" class="col-form-label">Proveedor</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="txt_proveedor" class="form-control" aria-describedby="passwordHelpInline" value="<?php echo $reg['prv_id'] ?>" disabled>
                    </div>
                </div>


                <div class=" row g-3 align-items-center">
                    <div class="col-md-2">
                        <label for="slc_enviar" class="col-form-label">Enviar a</label>
                    </div>
                    <div class="col-auto">
                        <select type="text" class="form-select" id="slc_enviar" name="slc_enviar" style="width:220px" required>
                            <option value="">Selecciona</option>

                            <?php
                            /* si es supervisor habilidar combo con listado de cajones en molinos */
                            if ($_SESSION['privilegio'] == 17) {
                                $consulta =  mysqli_query($cnx, "select * from almacen_cajones where ac_ban = 'P'AND ac_estatus = 'A' order by ac_descripcion");
                                $reg_num_cajon = mysqli_fetch_assoc($consulta);
                            } else {
                                $consulta =  mysqli_query($cnx, "select * from almacen_cajones where ac_ban = 'M'AND ac_estatus = 'A' order by ac_descripcion");
                                $reg_num_cajon = mysqli_fetch_assoc($consulta);
                            }


                            do {
                            ?>
                                <option value="<?php echo $reg_num_cajon['ac_id'] ?>">
                                    <?php echo "Cajón " . $reg_num_cajon['ac_descripcion']; ?>
                                </option>
                            <?php  } while ($reg_num_cajon = mysqli_fetch_assoc($consulta));
                            ?>
                        </select>
                    </div>
                    <!--  <div class="col-auto">
                        <div class="form-check">
                            <input onclick="molinos_chk()" class="form-check-input" type="checkbox" value="" id="check_molinos">
                            <label class="form-check-label" for="flexCheckDefault">
                                Molinos
                            </label>
                        </div>
                    </div> -->

                </div>
                <!--   <div class="mb-3">
                    <label for="recipient-name" class="col-form-label">Proveedor:</label>
                    <select type="text" class="form-select" id="recipient-name">
                        <?php
                        $modificar =  mysqli_query($cnx, "select * from proveedores order by prv_nombre");
                        while ($registroCiu =  mysqli_fetch_array($modificar)) {
                            if ($registroCiu['prv_id'] == $hdd_prov) {
                                $var = ' selected="selected" ';
                            } else {
                                $var = '';
                            }
                            echo '<option value="' . mb_convert_encoding($registroCiu['prv_id'], "UTF-8") . '"' . $var . '>';
                            echo '' . mb_convert_encoding($registroCiu['prv_nombre'], "UTF-8") . '';
                            echo '</option>';
                        }
                        ?>
                    </select>
                </div> -->
            </div>
            <div class="modal-footer">
                <!--mensajes-->
                <div class="col-md-6">
                    <div class="alert alert-info" id="alerta-errorMovimientoCajon" role="alert" style="display:none">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Titulo</strong> &nbsp;&nbsp;
                        <span> Mensaje </span>
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
        $("#form_cajones").submit(function() {
            //alert('editar');
            var formData = $(this).serialize();
            $.ajax({
                url: "movimiento_patio.php",
                type: 'POST',
                data: formData,
                success: function(result) {
                    data = JSON.parse(result);
                    //alert("Guardo el registro");
                    alertas("#alerta-errorMovimientoCajon", '', data["mensaje"], 1, true, 5000);
                    document.getElementById('alerta-errorMovimientoCajon').style.display = 'block';
                    //$('#form').each (function(){this.reset();});  
                }
            });
            return false;
        });
    });


    function molinos_chk() {
        var check_molinos = document.getElementById('check_molinos');
        if (check_molinos.checked != false) {
            var datos = {
                "check_molinos": 'P'
            };
        }
        if (check_molinos.checked != true) {
            var datos = {
                "check_molinos": 'M'
            };
        }
        $.ajax({
            type: 'post',
            url: 'get_tipo_almacen.php',
            data: datos,
            success: function(d) {
                $("#slc_enviar").html(d);
            },
            complete: function() {
                return false; // Retorna false después de que la solicitud AJAX se complete
            }
        });
    }
</script>