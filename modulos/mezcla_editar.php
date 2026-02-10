<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
						 FROM mezclas 
						 WHERE mez_id = '".$_POST['hdd_id']."'") or die(mysql_error()."Error: en consultar el proveedor");
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
			url: "mezcla_actualizar.php",
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
        <h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Editar Mezcla</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  
      <div class="modal-body">
        <div class="col-md-5">
            <label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span>Nombre:</label>
            <input name="txtNombre" type="text" class="form-control" id="txtNombre" value="<?php echo $registros['mez_nombre'] ?>" maxlength="35" required placeholder="Nombre">
            <input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['mez_id'] ?>"/>
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