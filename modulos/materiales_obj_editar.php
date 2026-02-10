<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
						 FROM materiales_tipo_obj 
						 WHERE mto_id = '".$_POST['hdd_id']."'") or die(mysql_error()."Error: en consultar datos");
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

$(document).ready(function()
{
	$("#formE").submit(function(){
		//alert('editar');
		var formData = $(this).serialize();
		$.ajax({
			url: "materiales_obj_actualizar.php",
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
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form name="formE" id="formE">	
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Editar tipo proceso</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  
      <div class="modal-body">
        <div class="col-md-5">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Origen material:</label>
            <select name="cbxTipo" class="form-control" id="cbxTipo" required="required">
              <!--<option value="">Seleccionar Material</option>-->
              <?php
				$cad_cbx =  mysqli_query($cnx, "SELECT * FROM materiales_tipo WHERE mt_est = 'A' and mt_id = '$registros[mt_id]' ORDER BY mt_descripcion") or die(mysql_error()."Error: en consultar el material");
				$reg_cbx =  mysqli_fetch_array($cad_cbx);
				
				do
				{?>
              <option value="<?php echo $reg_cbx['mt_id'] ?>"><?php echo $reg_cbx['mt_descripcion'] ?></option>
              <?php	
				}while($reg_cbx =  mysqli_fetch_array($cad_cbx));
				?>
            </select>
            <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['mto_id'] ?>"/>
        </div>
		  
 		<div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Kilos:</label>
            <input name="txtKilos" type="text" class="form-control" id="txtKilos" maxlength="20" required placeholder="Kilos" value="<?php echo $registros['mto_kilos'] ?>" onkeypress="return isNumberKey(event, this);">
          </div>
		  
		  <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Fecha:</label>
            <input name="txtFecha" type="date" class="form-control" id="txtFecha" maxlength="20" required placeholder="Fecha" value="<?php echo $registros['mto_fecha'] ?>">
      </div>

      <div class="col-md-5">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Proveedor:</label>
            <select name="slc_proveedor" class="form-control" id="slc_proveedor" required="required">
              <option value="">Seleccionar Proveedor</option>
              <?php
              $cad_cbx =  mysqli_query($cnx, "SELECT * FROM proveedores WHERE prv_est = 'A' ORDER BY prv_nombre") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
              $reg_cbx =  mysqli_fetch_array($cad_cbx);

              do {

                if ($reg_cbx['prv_ban']  == '2') {
                  $estilo = "<span style='font-weight:bold'> (Maquila) </span>";
                } else if ($reg_cbx['prv_tipo'] == 'L' && $reg_cbx['prv_ban'] == 1) {
                  $estilo = "<span style='font-weight:bold'> (Especial) </span>";
                } else if ($reg_cbx['prv_tipo'] == 'E') {
                  $estilo = "<span style='font-weight:bold'> (Extranjero) </span>";
                } else {
                  $estilo = "<span style='font-weight:bold'> (Local) </span>";
                }
              ?>

                <?php
                if ($reg_cbx['prv_tipo'] == 'L' && $reg_cbx['prv_ban'] == '1') { ?>
                  <option style="background:#E6E6" value="<?php echo $reg_cbx['prv_id'] ?>" <?php if( $reg_cbx['prv_id'] == $registros['prv_id']){echo "selected = 'selected'";} ?>><?php echo $reg_cbx['prv_nombre'] . $estilo ?></option>
                <?php } elseif ($reg_cbx['prv_tipo'] == 'L') { ?>
                  <option style="background:#FFF" value="<?php echo $reg_cbx['prv_id'] ?>" <?php if( $reg_cbx['prv_id'] == $registros['prv_id']){echo "selected = 'selected'";} ?>><?php echo $reg_cbx['prv_nombre'] . $estilo ?></option>
                <?php }
                if ($reg_cbx['prv_tipo'] == 'E') { ?>
                  <option style="background:#F7FEA0" value="<?php echo $reg_cbx['prv_id'] ?>" <?php if( $reg_cbx['prv_id'] == $registros['prv_id']){echo "selected = 'selected'";} ?>><?php echo $reg_cbx['prv_nombre'] . $estilo ?></option>
              <?php }
              } while ($reg_cbx =  mysqli_fetch_array($cad_cbx));
              ?>
            </select>
          </div>
        
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