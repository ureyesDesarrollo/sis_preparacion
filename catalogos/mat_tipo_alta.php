<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
require_once('../conexion/conexion.php');
$cnx = Conectarse();
?>
  <div class="modal-dialog modal-lg" role="document" style="height: 200px">
    <div class="modal-content">
      <form id="formTipoMat">	
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Origen material</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Origen material:</label>
            <input name="txtTipo" type="text" class="form-control" id="txtTipo" maxlength="17" required placeholder="Descripción">
          </div>
           <div class="col-md-4">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Estatus:</label>
            <select name="cbxEstatus" type="text" class="form-control" id="cbxEstatus" required>
              <option value="A">Activo</option>
              <option value="B">Baja</option>
            </select>
          </div>
      </div>
      <div class="modal-footer" style="margin-top: 8%;">
          <!--mensajes-->
         <div class="alert alert-info hide" id="alerta-errorTipoAlta" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
          <button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
          <strong>Titulo</strong> &nbsp;&nbsp;
          <span> Mensaje </span>
        </div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt="">Cerrar</button>
        <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button>
      </div>
	  </form>
  </div>
</div>