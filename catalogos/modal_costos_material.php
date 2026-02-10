<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();
extract($_POST);


$cad_mat = mysqli_query($cnx, "SELECT mat_nombre FROM materiales 
WHERE mat_id ='$mat_id'") or die(mysqli_error($cnx) . "Error: en consultar");
$reg_mat = mysqli_fetch_assoc($cad_mat);
$rows = mysqli_num_rows($cad_mat);

$cadena = mysqli_query($cnx, "SELECT m.mc_costo,ma.mat_nombre, p.prv_nombre,m.mc_fe_alta, m.mc_year
FROM materiales_costos as m
inner join materiales as ma on(m.mat_id = ma.mat_id)
inner join proveedores as p on(m.prv_id = p.prv_id) 
WHERE m.mat_id ='$mat_id'") or die(mysqli_error($cnx) . "Error: en consultar");
$registros = mysqli_fetch_assoc($cadena);
$rows = mysqli_num_rows($cadena);

?>
<script>
    $(document).ready(function() {
        $("#form_costos").submit(function() {
            var formData = $(this).serialize();
            $.ajax({
                url: "materiales_costos.php",
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $("#boton_guardar").prop("disabled", true);
                },
                success: function(result) {
                    data = JSON.parse(result);
                    //alertas("#alerta-costos", 'Listo!', data["mensaje"], 1, true, 5000);
                    if (data["mensaje"] === "Exito") {
                        alertas("#alerta-costos", 'Listo!', 'Registro guardado', 1, true, 5000);
                        //window.location.hash = '#catalogos/productos_add.php';
                    } else {
                        alertas("#alerta-costos", 'Error!', 'Costo ya registrado', 4, true, 5000);
                    }

                    var hdd_id = $("#hdd_id").val();

                    cargar_costos('#carga_costos', 'listado_materiales_costos.php?hdd_id=' + hdd_id);
                    $('#form_costos').each(function() {
                        this.reset();
                    });

                    $("#boton_guardar").prop("disabled", false);

                }
            });
            return false;
        });
    });

    function cargar_costos(div, desde) {
        $(div).load(desde);
    }
</script>
<script type="text/javascript" src="../js/alerta.js"></script>
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
 -->
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Costos de material por proveedor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form name="form_costos" id="form_costos">
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="inputPassword4"><span style="color: #FF0000;font-weight: bold;">*</span>Costo</label>
                        <input onKeyPress="return isNumberKeyFloat(event, this);" onblur="valida_cero(this)" class="form-control" type="text" value="" name="txt_costo" id="txt_costo" placeholder="Costo" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputPassword4"><span style="color: #FF0000;font-weight: bold;">*</span>Material</label>
                        <input class="form-control" type="text" readonly name="txt_material" id="txt_material" value="<?php echo $reg_mat['mat_nombre'] ?>">
                        <input class="form-control" type="hidden" readonly name="hdd_id" id="hdd_id" value="<?php echo $mat_id ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="recipient-name" class="col-form-label"><span style="color: #FF0000;font-weight: bold;">*</span> Proveedor:</label>
                        <select name="cbx_proveedor" class="form-control" id="cbx_proveedor" required="required">
                            <option value="">Seleccionar proveedor</option>
                            <?php
                            $cad_cbx =  mysqli_query($cnx, "SELECT * FROM proveedores  
                                WHERE prv_est = 'A' ORDER BY prv_nombre") or die(mysqli_error($cnx) . "Error: en consultar el material");
                            $reg_cbx =  mysqli_fetch_array($cad_cbx);

                            do {

                                if ($reg_cbx['prv_tipo'] == 'L') {
                                    $color_proveedor = '';
                                    $tipo_proveedor = 'Local';
                                }
                                if ($reg_cbx['prv_tipo'] == 'E') {
                                    $color_proveedor = 'style="background-color:#F7FEA0"';

                                    $tipo_proveedor = 'Extranjero';
                                }

                                if ($reg_cbx['prv_ban'] == '1') {
                                    $color_proveedor = 'style="background-color:#e6e6"';

                                    $tipo = '- Especial';
                                } else if ($reg_cbx['prv_ban'] == '2') {
                                    $tipo = ' - Maquila';
                                } else {
                                    $tipo = '';
                                }

                            ?>
                                <option <?php echo $color_proveedor ?> value="<?php echo $reg_cbx['prv_id'] ?>"><?php echo $reg_cbx['prv_nombre'] . ' ('  . $tipo_proveedor . $tipo . ')' ?></option>
                            <?php
                            } while ($reg_cbx =  mysqli_fetch_array($cad_cbx));
                            ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="inputPassword4"><span style="color: #FF0000;font-weight: bold;">*</span>Año</label>
                        <select name="cbx_year" class="form-control" id="cbx_year" required="required">
                            <option value="">Seleccionar año</option>
                            <?php for ($i = 2024; $i <= 2030; $i++) { ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary" type="submit" id="boton_guardar" name="boton_guardar" style="margin-top: 2rem;"><img src="../iconos/guardar.png" alt=""> Guardar</button>
                    </div>
                </div>

                <div class="row" id="carga_costos">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td>Costo</td>
                                    <td>Material</td>
                                    <td>Proveedor</td>
                                    <td>Fecha Alta</td>
                                    <td>Año</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($rows > 0) {
                                    do {

                                ?>
                                        <tr>
                                            <td><?php echo $registros['mc_costo'] ?></td>
                                            <td><?php echo $registros['mat_nombre'] ?></td>
                                            <td><?php echo $registros['prv_nombre'] ?></td>
                                            <td><?php echo $registros['mc_fe_alta'] ?></td>
                                            <td><?php echo $registros['mc_year'] ?></td>
                                        </tr>
                                    <?php } while ($registros = mysqli_fetch_assoc($cadena));
                                } else { ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center;">Sin registros</td>
                                    </tr>
                                <?php }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--mensajes-->
                <div class="alert alert-info hide" id="alerta-costos" style="height: 40px;width: 250px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
                    <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
                    <strong>Titulo</strong> &nbsp;&nbsp;
                    <span> Mensaje </span>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt="">Cerrar</button>

            </div>
        </form>
    </div>
</div>
<!-- </div> -->