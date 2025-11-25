<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *  FROM proveedores 
						   WHERE prv_id = '" . $_POST['hdd_id'] . "'");
$registros = mysqli_fetch_assoc($cadena);

?>
<script>
  /*$(document).ready(function(){

$("#cbxEstadoE").change(function () {
  $("#cbxEstadoE option:selected").each(function () {
	est_id = $(this).val();

	$.post("extras/getCiudad.php", { est_id: est_id }, function(data){

	  $("#cbxCiudadE").html(data);
	});          
  });
})
});*/

  $(document).ready(function() {
    $("#formE").submit(function() {
      //alert('editar');
      var formData = $(this).serialize();
      $.ajax({
        url: "proveedores_actualizar.php",
        type: 'POST',
        data: formData,
        success: function(result) {
          data = JSON.parse(result);
          //alert("Guardo el registro");
          alertas("#alerta-errorProvEditar", 'Listo!', data["mensaje"], 1, true, 5000);
          //$('#form').each (function(){this.reset();});  
        }
      });
      return false;
    });
  });
</script>

<!--cargar combos-->
<script language="javascript">
  $(document).ready(function() {
    $("#cbxEstadoE").change(function() {
      $("#cbxEstadoE option:selected").each(function() {
        est_id = $(this).val();

        $.post("Extras/getCiudad.php", {
          est_id: est_id
        }, function(data) {

          $("#cbxCiudadE").html(data);
        });
      });
    })
  });
</script>

<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <form name="formE" id="formE">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Editar proveedores</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="col-md-4">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Nombre:</label>
          <input name="txtNombre" type="text" class="form-control" id="txtNombre" value="<?php echo $registros['prv_nombre'] ?>" maxlength="25" required placeholder="Nombre">
          <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['prv_id'] ?>" />
        </div>
        <div class="col-md-4">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Nombre Comercial:</label>
          <input name="txtNombreC" type="text" class="form-control" id="txtNombreC" value="<?php echo $registros['prv_nom_comercial'] ?>" maxlength="25" required placeholder="Nombre">
        </div>
        <div class="col-md-3">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Tipo de proveedor:</label>
          <select name="cbxTipo" type="email" class="form-control" id="cbxTipo">
            <option value="<?php echo $registros['prv_tipo']; ?>">
              <?php
              if ($registros['prv_tipo'] == 'E') {
                echo "Extranjero";
              } else {
                echo "Local";
              }
              ?>
            </option>
            <?php
            if ($registros['prv_tipo'] == 'E') { ?>
              <option value="L">Local</option>
            <?php } else { ?>
              <option value="E">Extranjero</option>
            <?php } ?>
          </select>
        </div>
        <!-- <div class="col-md-3">
           <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Tipo proveedor-maquila:</label>
           <select name="cbxIndica" type="email" class="form-control" id="cbxIndica">
             <option value="<?php echo $registros['prv_ban']; ?>">
               <?php
                if ($registros['prv_ban'] == '0') {
                  echo "Normal";
                } else if ($registros['prv_ban'] == '1') {
                  echo "Especial";
                } else {
                  echo "Maquila";
                }
                ?>
             </option>
             <?php
              if ($registros['prv_ban'] == '1') { ?>
               <option value="0">Normal</option>
               <option value="2">Maquila</option>
             <?php } else if ($registros['prv_ban'] == '0') { ?>
               <option value="1">Especial</option>
               <option value="2">Maquila</option>
             <?php } else { ?>
               <option value="1">Especial</option>
               <option value="0">Normal</option>
             <?php } ?>

           </select>
         </div> -->
        <div class="col-md-3">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Categoría de Proveedor:</label>
          <select name="cbxCategoriaProveedor" class="form-control" id="cbxCategoriaProveedor" required>
            <option value="">Seleccionar Categoría</option>
            <option value="S" <?= $registros['prv_mql'] == 'S' ? 'selected' : '' ?>>Maquila</option>
            <option value="N" <?= $registros['prv_mql'] == 'N' ? 'selected' : '' ?>>Materia Prima</option>
            <option value="C" <?= $registros['prv_mql'] == 'C' ? 'selected' : '' ?>>Maquila y Materia Prima</option>
          </select>
        </div>
        <div class="col-md-2">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> RFC:</label>
          <input name="txtRfc" type="text" class="form-control" id="txtRfc" required placeholder="RFC" maxlength="15" value="<?php echo $registros['prv_rfc'] ?>">
        </div>
        <div class="col-md-2">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Teléfono:</label>
          <input name="txtTelefono" type="text" class="form-control" id="txtTelefono" onkeypress="return isNumberKey(event, this);" required placeholder="Telefono" value="<?php echo $registros['prv_telefono'] ?>">
        </div>
        <div class="col-md-5">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Correo:</label>
          <input name="txtEmail" type="email" class="form-control" id="txtEmail" placeholder="Email" value="<?php echo $registros['prv_email'] ?>" required>
        </div>
        <div class="col-md-5">
          <label for="recipient-name" class="col-form-label">Contacto:</label>
          <input name="txtContacto" type="text" class="form-control" id="txtContacto" value="<?php echo $registros['prv_contacto'] ?>" maxlength="35" placeholder="Contacto">
        </div>
        <div class="col-md-4">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Calle:</label>
          <input name="txtCalle" type="text" class="form-control" id="txtCalle" value="<?php echo $registros['prv_calle'] ?>" maxlength="30" placeholder="Calle" required>
        </div>
        <div class="col-md-2">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> No:</label>
          <input name="txtNo" type="text" class="form-control" id="txtNo" value="<?php echo $registros['prv_numero'] ?>" maxlength="10" placeholder="Numero" required>
        </div>
        <div class="col-md-2">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> CP:</label>
          <input name="txtCodPos" type="text" class="form-control" id="txtCodPos" placeholder="Codigo Postal" maxlength="6" value="<?php echo $registros['prv_cp'] ?>" onKeyPress="return isNumberKey(event, this);">
        </div>
        <div class="col-md-3">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Colonia:</label>
          <input name="txtColonia" type="text" class="form-control" id="txtColonia" value="<?php echo $registros['prv_colonia'] ?>" maxlength="30" placeholder="Colonia">
        </div>
        <div class="col-md-3">
          <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Estado:</label>
          <select name="cbxEstadoE" class="form-control" id="cbxEstadoE" required>
            <option value="">Seleccionar Estado</option>
            <?php
            $query =  mysqli_query($cnx, "SELECT est_id, est_descripcion FROM estados ORDER BY est_descripcion");
            while ($row = mysqli_fetch_array($query)) {
              if ($row['est_id'] == $registros['est_id']) {
                $var = ' selected="selected" ';
              } else {
                $var = '';
              }

              echo '<option value="' . mb_convert_encoding($row['est_id'], "UTF-8") . '"' . $var . '>';
              echo '' . mb_convert_encoding($row['est_descripcion'], "UTF-8") . '';
              echo '</option>';
            }
            ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="recipient-name" class="col-form-label">Ciudad:</label>
          <select name="cbxCiudadE" class="form-control" id="cbxCiudadE" required>

            <?php
            $modificar =  mysqli_query($cnx, "select * from ciudades where est_id = '$registros[est_id]'");
            while ($registroCiu =  mysqli_fetch_array($modificar)) {
              if ($registroCiu['ciu_id'] == $registros['ciu_id']) {
                $var = ' selected="selected" ';
              } else {
                $var = '';
              }
              echo '<option value="' . mb_convert_encoding($registroCiu['ciu_id'], "UTF-8") . '"' . $var . '>';
              echo '' . mb_convert_encoding($registroCiu['ciu_descripcion'], "UTF-8") . '';
              echo '</option>';
            }
            ?>

          </select>
        </div>
        <!-- <div class="col-md-12">
            <label for="message-text" class="col-form-label">Comentarios:</label>
            <textarea name="txaComentarios" class="form-control" id="message-text"></textarea>
          </div>-->

      </div>
      <div class="modal-footer" style="margin-top: 30%;">
        <!--mensajes-->
        <div class="alert alert-info hide" id="alerta-errorProvEditar" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
          <button type="button" class="close" id="cerrar_alerta" aria-laWel="Close">&times;</button>
          <strong>Titulo</strong> &nbsp;&nbsp;
          <span> Mensaje </span>
        </div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt=""> Cerrar</button>
        <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button>
      </div>
    </form>
  </div>
</div>