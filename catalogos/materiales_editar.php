<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
	 FROM materiales 
	 WHERE mat_id = '" . $_POST['hdd_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el material");
$registros = mysqli_fetch_assoc($cadena);
?>
<script>
  $(document).ready(function() {
    $("#formMaterialesEditar").submit(function() {
      //alert('editar');
      var formData = $(this).serialize();
      $.ajax({
        url: "materiales_actualizar.php",
        type: 'POST',
        data: formData,
        success: function(result) {
          data = JSON.parse(result);
          //alert("Guardo el registro");
          alertas("#alerta-errorMatEditar", 'Listo!', data["mensaje"], 1, true, 5000);
          //$('#form').each (function(){this.reset();});  
        }
      });
      return false;
    });
  });
</script>


<div class="modal-dialog modal-lg" role="document" style="height: 200px">
  <div class="modal-content">
    <form id="formMaterialesEditar">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Editar materiales</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="col-md-4">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Origen material:</label>
          <select name="cbxTipo" type="email" class="form-control" id="cbxTipo" placeholder="Email" required>
            <option value="">Seleccionar</option>
            <?php
            $list_tipo = mysqli_query($cnx, "select * from materiales_tipo WHERE mt_est = 'A'");
            while ($reg_tipo =  mysqli_fetch_assoc($list_tipo)) { ?>
              <option value="<?php echo mb_convert_encoding($reg_tipo['mt_id'], "UTF-8");  ?>" <?php if (mb_convert_encoding($reg_tipo['mt_id'], "UTF-8") == $registros['mt_id']) { ?>selected="selected" <?php } ?>><?php echo mb_convert_encoding($reg_tipo['mt_descripcion'], "UTF-8");  ?></option>
            <?php } ?>
          </select>
          <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['mat_id'] ?>" />
        </div>
        <div class="col-md-4">
          <label for="recipient-name" class="col-form-label">Material:</label>
          <input name="txtMaterial" type="text" class="form-control" id="txtMaterial" value="<?php echo $registros['mat_nombre'] ?>" maxlength="60" required placeholder="Nombre">
        </div>
        <div class="col-md-4">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Unidad medida:</label>
          <select name="cbxMedida" type="email" class="form-control" id="cbxMedida" placeholder="Email" required>
            <option value="">Seleccionar</option>
            <?php
            $list_unidades = mysqli_query($cnx, "select * from unidades_medida");
            while ($reg_unidades =  mysqli_fetch_assoc($list_unidades)) { ?>
              <option value="<?php echo mb_convert_encoding($reg_unidades['um_id'], "UTF-8");  ?>" <?php if (mb_convert_encoding($reg_unidades['um_id'], "UTF-8") == $registros['um_id']) { ?>selected="selected" <?php } ?>><?php echo mb_convert_encoding($reg_unidades['um_descripcion'], "UTF-8");  ?></option>
            <?php } ?>
          </select>
        </div>
        <!--  <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Costo:</label>
            <input name="txtCosto" type="text" class="form-control" id="txtCosto" onkeypress="return isNumberKey(event, this);" required value="<?php echo $registros['mat_costo'] ?>">
          </div> -->
        <div class="col-md-2">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Stock min:</label>
          <input name="txtSMin" type="text" class="form-control" id="txtSMin" onkeypress="return isNumberKey(event, this);" required value="<?php echo $registros['mat_stock_min'] ?>">
        </div>
        <div class="col-md-2">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Stock max:</label>
          <input name="txtSMax" type="text" class="form-control" id="txtSMax" onkeypress="return isNumberKey(event, this);" required value="<?php echo $registros['mat_stock_max'] ?>">
        </div>
        <div class="col-md-2">
          <label for="recipient-name" class="col-form-label">Existencia:</label>
          <input name="txtExistencia" type="text" class="form-control" id="txtExistencia" required value="<?php echo $registros['mat_existencia'] ?>" readonly>
        </div>
        <div class="col-md-2">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Estatus:</label>
          <select name="txtEstatus" type="email" class="form-control" id="txtEstatus" required>
            <?php
            if ($registros['mat_est'] == 'B') {
              $var_est = "Baja";
            }

            if ($registros['mat_est'] == 'A') {
              $var_est = "Activo";
            }

            ?>
            <option value="<?php echo $registros['mat_est']; ?>"><?php echo mb_convert_encoding($var_est, "UTF-8") ?></option>
            <?php
            if ($registros['mat_est'] == 'A') {
              echo '<option value="B">Baja</option>';
            }
            if ($registros['mat_est'] == 'B') {
              echo '<option value="A">Activo</option>';
            }
            ?>

          </select>
        </div>
        <div class="col-md-4">
          <div class="form-check form-switch p-0">
            <div class="d-flex flex-column-reverse gap-1">
              <input class="form-check-input ms-0" type="checkbox" id="chk_ingreso" name="chk_ingreso" <?= ($registros['mat_ingreso'] == 'S') ? 'checked' : ''; ?> />
              <label class="form-check-label" for="chk_ingreso" id="chk_ingreso_label">Rendimiento kilos iniciales</label>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <label for="recipient-name" class="col-form-label">Comentarios:</label>
          <textarea name="txaNotas" type="text" class="form-control" id="txaNotas"><?php echo $registros['mat_comentarios'] ?></textarea>
        </div>

        <div class="modal-footer" style="margin-top: 25%;">
          <!--mensajes-->
          <div class="alert alert-info hide" id="alerta-errorMatEditar" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
            <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
            <strong>Titulo</strong> &nbsp;&nbsp;
            <span> Mensaje </span>
          </div>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt="">Cerrar</button>
          <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button>
        </div>
      </div>
    </form>

  </div>
</div>