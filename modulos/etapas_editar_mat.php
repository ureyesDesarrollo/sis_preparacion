<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT * FROM preparacion_etapas_param 
WHERE pep_id = '".$_POST['hdd_id']."'") or die(mysql_error()."Error: en consultar los tipos");
$registros = mysqli_fetch_assoc($cadena);

$cad_fase = mysqli_query($cnx, "SELECT x.mez_nombre
FROM mezclas as x
INNER JOIN preparacion_etapas_mezclas as m on (x.mez_id = m.mez_id)
WHERE m.pep_id = '".$_POST['hdd_id']."'  ") or die(mysql_error()."Error: en consultar las etapas");
$reg_fase = mysqli_fetch_assoc($cad_fase);
$tot_fase = mysqli_num_rows($cad_fase);
?> 
<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

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
	$("#formC").submit(function(){
		//alert('editar');
		var formData = $(this).serialize();
		$.ajax({
			url: "etapas_editar_mez_agregar.php",
			type: 'POST',
			data: formData,
			success: function(result) {
				data = JSON.parse(result);
			  //alert("Guardo el registro");

			  alertas("#alerta-errorProvEditar", 'Listo!', data["mensaje"], 1, true, 5000);
			  $('#formC').each (function(){this.reset();}); 
			  //document.getElementById("cbxMaterial").value = '';
			  var hdd_id =  document.getElementById("hdd_id").value; 
			  setTimeout(cargar('#main','mezcla_consultar_cargar_mez.php?id='+hdd_id), 5000); 

			}
		});
		return false;
	});
});


/*
function fnc_baja_fase(id){
	var respuesta = confirm("¿Deseas dar de baja este registro?");
	//alert(id);
	if (respuesta){
		$.ajax({
			url: 'tipo_proceso_baja_fase.php',
			data: 'id=' + id, 
			type: 'post',
			success: function(result){
				data = JSON.parse(result);
				alertas("#alerta-errorProvEditar", 'Listo!', data["mensaje"], 1, true, 5000); 
		
		var hdd_id =  document.getElementById("hdd_id").value; 
	    setTimeout(cargar('#main','tipo_proceso_consultar_cargar.php?id='+hdd_id), 23000);
	}
});
		//return false;
	}
}

function fnc_sube_fase(id){
	$.ajax({
		url: 'tipo_proceso_sube_fase.php',
		data: 'id=' + id,
		type: 'post',
		success: function(result){
			data = JSON.parse(result);
			alertas("#alerta-errorProvEditar", 'Listo!', data["mensaje"], 1, true, 5000); 
			
			var hdd_id =  document.getElementById("hdd_id").value; 
			setTimeout(cargar('#main','tipo_proceso_consultar_cargar.php?id='+hdd_id), 23000);
		}
	});
	//return false;
}

function fnc_bajar_fase(id){
	$.ajax({
		url: 'tipo_proceso_bajar_fase.php',
		data: 'id=' + id,
		type: 'post',
		success: function(result){
			data = JSON.parse(result);
			alertas("#alerta-errorProvEditar", 'Listo!', data["mensaje"], 1, true, 5000); 
			
			var hdd_id =  document.getElementById("hdd_id").value; 
			  setTimeout(cargar('#main','tipo_proceso_consultar_cargar.php?id='+hdd_id), 23000);

		}
	});
	//return false;
}*/
</script>

<!-- Acción sobre el botó con id=boton y actualizamos el div con id=capa -->
<script type="text/javascript">
function cargar(div, desde)
		{
			$(div).load(desde);
		}


</script>

<div class="modal-dialog modal-lg" role="document" id="consultaProcesoModal">
	<div class="modal-content" style="height: 100%">
		<form name="formC" id="formC">	
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Asignar mezclas</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-5">
						<label for="recipient-name" class="col-form-label">Etapa:</label>
						<input name="txtNombre" type="text" class="form-control" id="txtNombre" placeholder="Nombre" value="<?php echo $registros['pep_nombre'] ?>" readonly="true">
						<input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['pep_id'] ?>" />
						<!--<input name="hdd_total" type="hidden" id="hdd_total" value="<?php echo $tot_fase + 1; ?>" />-->
					</div>
					
					<div class="col-md-3">
						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Mezcla</label>
						<select name="cbxMezcla" id="cbxMezcla" class="form-control"> 
							<option value="">Seleccionar mezcla</option>
							<?php
							$cad_cbx =  mysqli_query($cnx, "SELECT * FROM mezclas ORDER BY mez_nombre") or die(mysql_error()."Error: en consultar las etapas ");
							$reg_cbx =  mysqli_fetch_array($cad_cbx);

							do
							{?>
								<option value="<?php echo $reg_cbx['mez_id'] ?>"><?php echo $reg_cbx['mez_nombre'] ?></option>
								<?php	
							}while($reg_cbx =  mysqli_fetch_array($cad_cbx));
							?>
						</select>
					</div>

					
				</div>
				<br />
				<div class="row" id="divFase">

					<div id="main">
						<div class="col-md-5">
							<table border="1">
								<tbody>
									<tr bgcolor="#4AB5B9" style="color:#FFFFFF; font-weight:bold;" height="30">
										<th width="200">Mezclas</th>
									</tr>
								</tbody>
								<?php do{?>
									<tr>
										<td><?php echo $reg_fase['mez_nombre'] ?></td>
									</tr>
								<?php }while($reg_fase = mysqli_fetch_assoc($cad_fase));?>
							</table>
						</div>
					</div>
					<br>
				</div>

			</div>
			<div class="modal-footer">
				<!--mensajes-->
				<div class="alert alert-info hide" id="alerta-errorProvEditar" style="height: 40px;width: 300px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
					<button type="button" class="close" id="cerrar_alerta" aria-laWel="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt=""> Cerrar</button>
				<button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt="" > Guardar</button>
			</div>
		</form>
	</div>
</div>