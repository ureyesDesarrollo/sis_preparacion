<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
require_once('../conexion/conexion.php');
$cnx = Conectarse();
?>
<div class="modal-dialog modal-lg" role="document" style="height: 200px">
  <div class="modal-content">
    <form id="formMat">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Alta materiales</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="col-md-4">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Origen material:</label>
          <select name="cbxTipo" type="email" class="form-control" id="cbxTipo" placeholder="tipo" required>
            <option value="">Seleccionar</option>
            <?php
            $query =  mysqli_query($cnx, "SELECT mt_id, mt_descripcion FROM materiales_tipo WHERE mt_est = 'A'");
            while ($row = mysqli_fetch_array($query)) { ?>
              <option value="<?php echo mb_convert_encoding($row['mt_id'], "UTF-8");  ?>"><?php echo mb_convert_encoding($row['mt_descripcion'], "UTF-8");  ?></option>
            <?php }
            ?>
          </select>
        </div>
        <div class="col-md-4">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Material:</label>
          <input name="txtMaterial" type="text" class="form-control" id="txtMaterial" maxlength="60" required placeholder="Nombre">
        </div>
        <div class="col-md-4">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Unidad medida:</label>
          <select name="cbxMedida" type="email" class="form-control" id="cbxMedida" placeholder="Email" required>
            <!-- <option value="">Seleccionar</option>-->
            <?php
            $query =  mysqli_query($cnx, "SELECT um_id, um_descripcion FROM unidades_medida");
            while ($row = mysqli_fetch_array($query)) { ?>
              <option value="<?php echo mb_convert_encoding($row['um_id'], "UTF-8");  ?>"><?php echo mb_convert_encoding($row['um_descripcion'], "UTF-8");  ?></option>
            <?php }
            ?>
          </select>
        </div>
        <!--  <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Costo:</label>
            <input name="txtCosto" type="text" class="form-control" id="txtCosto" onkeypress="return isNumberKey(event, this);" required placeholder="costo" value="0">
          </div> -->
        <div class="col-md-2">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Stock min:</label>
          <input name="txtSMin" type="text" class="form-control" id="txtSMin" onkeypress="return isNumberKey(event, this);" required placeholder="Stock max">
        </div>
        <div class="col-md-2">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Stock max:</label>
          <input name="txtSMax" type="text" class="form-control" id="txtSMax" onkeypress="return isNumberKey(event, this);" required placeholder="Stock max">
        </div>
        <div class="col-md-2">
          <label for="recipient-name" class="col-form-label">Existencia:</label>
          <input name="txtExistencia" type="text" class="form-control" id="txtExistencia" value="0" readonly="">
        </div>
        <div class="col-md-2">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Estatus:</label>
          <select name="txtEstatus" type="select" class="form-control" id="txtEstatus" required>
            <option value="A">Activo</option>
            <option value="B">Baja</option>
          </select>
        </div>
        <div class="col-md-4">
          <div class="form-check form-switch p-0">
            <div class="d-flex flex-column-reverse gap-1">
              <input class="form-check-input ms-0" type="checkbox" id="chk_ingreso" name="chk_ingreso" />
              <label class="form-check-label" for="chk_ingreso" id="chk_ingreso_label">Rendimiento kilos iniciales</label>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <label for="recipient-name" class="col-form-label">Comentarios:</label>
          <textarea name="txaNotas" type="text" class="form-control" id="txaNotas" placeholder="Comentarios..."></textarea>
        </div>

        <div class="modal-footer" style="margin-top: 25%;">
          <!--mensajes-->
          <div class="alert alert-info hide" id="alerta-errorMatAlta" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
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