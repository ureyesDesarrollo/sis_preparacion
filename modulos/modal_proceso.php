<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();
extract($_POST);

$cadena = mysqli_query($cnx, "SELECT inv_id,mat_id,inv_kg_totales FROM inventario WHERE inv_id='$inv_id'") or die(mysqli_error($cnx) . "Error: en consultar");
$registros = mysqli_fetch_assoc($cadena);
$rows = mysqli_num_rows($cadena);

$cad_material = mysqli_query($cnx, "SELECT * FROM materiales WHERE mat_id ='" . $registros['mat_id'] . "'
  ") or die(mysqli_error($cnx) . "Error: en consultar material");
$reg_mat = mysqli_fetch_assoc($cad_material);
$tot_mat = mysqli_num_rows($cad_material);
?>
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
 -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Enviar material a proceso...</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="inputPassword4">Kilos</label>
                        <input class="form-control" type="text" readonly value="<?php echo $registros['inv_kg_totales'] ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputPassword4">Material</label>
                        <input class="form-control" type="text" readonly value="<?php echo $reg_mat['mat_nombre'] ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="recipient-name" class="col-form-label">Proceso:</label>
                        <select id="cbx_material" class="form-control" name="cbx_material">
                            <option value="">Seleccionar</option>
                            <?php

                            $cadena =  mysqli_query($cnx, "SELECT * from procesos WHERE pro_estatus = '1' ");
                            $registros =  mysqli_fetch_array($cadena);
                            do { ?>
                                <option value="<?php echo $registros['pro_id'] ?>"> <?php echo $registros['pro_id'] ?> </option>
                            <?php   } while ($registros =  mysqli_fetch_array($cadena)); ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
<!-- </div> -->