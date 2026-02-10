<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
?>
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <form id="form">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Alta proveedores</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Nombre:</label>
            <input name="txtNombre" type="text" class="form-control" id="txtNombre" maxlength="25" required placeholder="Nombre">
          </div>
          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Nombre comercial:</label>
            <input name="txtNombreC" type="text" class="form-control" id="txtNombreC" maxlength="25" required placeholder="Nombre comercial">
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Tipo de proveedor:</label>
            <select name="cbxTipo" class="form-control" id="cbxTipo" required>
              <option value="">Seleccionar tipo</option>
              <option value="L">Local</option>
              <option value="E">Extranjero</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Categoría de Proveedor:</label>
            <select name="cbxCategoriaProveedor" class="form-control" id="cbxCategoriaProveedor" required>
              <option value="">Seleccionar Categoría</option>
              <option value="S">Maquila</option>
              <option value="N" selected>Materia Prima</option>
              <option value="C">Maquila y Materia Prima</option>
            </select>
          </div>
          <!-- <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Tipo proveedor-maquila:</label>
            <select name="cbxIndica" class="form-control" id="cbxIndica" required>
              <option value="">Seleccionar</option>
              <option value="0" selected>Normal</option>
              <option value="2">Maquila</option>
              <option value="1">Especial</option>
            </select>
          </div> -->
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> RFC:</label>
            <input name="txtRfc" type="text" class="form-control" id="txtRfc" maxlength="15" required placeholder="RFC" value="XAXX010101000">
          </div>
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Teléfono:</label>
            <input name="txtTelefono" type="tel" class="form-control" id="txtTelefono" onkeypress="return isNumberKey(event, this);" required placeholder="Telefono">
          </div>
          <div class="col-md-5">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Correo:</label>
            <input name="txtEmail" type="email" class="form-control" id="txtEmail" placeholder="Email" required value="NA@gmail.com">
          </div>
          <div class="col-md-5">
            <label for="recipient-name" class="col-form-label">Contacto:</label>
            <input name="txtContacto" type="text" class="form-control" id="txtContacto" maxlength="35" placeholder="Contacto">
          </div>
          <div class="col-md-4">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Calle:</label>
            <input name="txtCalle" type="text" class="form-control" id="txtCalle" maxlength="30" placeholder="Calle" required>
          </div>
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> No:</label>
            <input name="txtNo" type="text" class="form-control" id="txtNo" maxlength="10" placeholder="Numero" required>
          </div>
          <div class="col-md-2">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> CP:</label>
            <input name="txtCodPos" type="text" class="form-control" id="txtCodPos" placeholder="Codigo Postal" onKeyPress="return isNumberKey(event, this);" required maxlength="6">
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Colonia:</label>
            <input name="txtColonia" type="text" class="form-control" id="txtColonia" maxlength="30" placeholder="Colonia" required>
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Estado:</label>
            <select name="cbxEstado" class="form-control" id="cbxEstado" required>
              <option value="">Seleccionar Estado</option>
              <?php
              $cad_cbx =  mysqli_query($cnx, "SELECT * FROM estados ORDER BY est_descripcion") or die(mysqli_error($cnx) . "Error: en consultar el estado");
              $reg_cbx =  mysqli_fetch_array($cad_cbx);

              do { ?>
                <option value="<?php echo $reg_cbx['est_id'] ?>"><?php echo $reg_cbx['est_descripcion'] ?></option>
              <?php
              } while ($reg_cbx =  mysqli_fetch_array($cad_cbx));
              ?>
            </select>
          </div>
          <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Ciudad:</label>
            <select name="cbxCiudad" class="form-control" id="cbxCiudad" required></select>
          </div>
          <!-- <div class="col-md-12">
            <label for="message-text" class="col-form-label">Comentarios:</label>
            <textarea name="txaComentarios" class="form-control" id="message-text"></textarea>
          </div>-->
        </div>
      </div>
      <div class="modal-footer">
        <!--mensajes-->
        <div class="alert alert-info hide" id="alerta-errorProvAlta" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
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