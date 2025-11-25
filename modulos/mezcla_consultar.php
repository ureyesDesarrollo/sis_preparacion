<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
include('../seguridad/user_seguridad.php');

$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT * FROM mezclas 
WHERE mez_id = '" . $_POST['hdd_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar los tipos");
$registros = mysqli_fetch_assoc($cadena);

$cad_fase = mysqli_query($cnx, "SELECT m.mat_nombre
FROM mezclas_materiales as x
INNER JOIN materiales as m on (x.mat_id = m.mat_id)
WHERE x.mez_id = '" . $_POST['hdd_id'] . "'  ") or die(mysqli_error($cnx) . "Error: en consultar las etapas");
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

 	$(document).ready(function() {
 		$("#formC").submit(function() {
 			//alert('editar');
 			var formData = $(this).serialize();
 			$.ajax({
 				url: "mezcla_material_agregar.php",
 				type: 'POST',
 				data: formData,
 				success: function(result) {
 					data = JSON.parse(result);
 					//alert("Guardo el registro");

 					alertas("#alerta-errorProvEditar", 'Listo!', data["mensaje"], 1, true, 5000);
 					//$('#formC').each (function(){this.reset();}); 
 					document.getElementById("cbxMaterial").value = '';
 					var hdd_id = document.getElementById("hdd_id").value;
 					setTimeout(cargar('#main', 'mezcla_consultar_cargar.php?id=' + hdd_id), 23000);

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
 	function cargar(div, desde) {
 		$(div).load(desde);
 	}
 </script>

 <div class="modal-dialog modal-lg" role="document" id="consultaProcesoModal">
 	<div class="modal-content">
 		<form name="formC" id="formC">
 			<div class="modal-header">
 				<h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Consultar mezclas</h5>
 				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
 					<span aria-hidden="true">&times;</span>
 				</button>
 			</div>

 			<div class="modal-body">
 				<div class="row">
 					<div class="col-md-5">
 						<label for="recipient-name" class="col-form-label">Nombre:</label>
 						<input name="txtNombre" type="text" class="form-control" id="txtNombre" placeholder="Nombre" value="<?php echo $registros['mez_nombre'] ?>" readonly="true">
 						<input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['mez_id'] ?>" />
 						<input name="hdd_total" type="hidden" id="hdd_total" value="<?php echo $tot_fase + 1; ?>" />
 					</div>

 					<div class="col-md-3">
 						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Material</label>
 						<select name="cbxMaterial" id="cbxMaterial" class="form-control">
 							<option value="">Seleccionar material</option>
 							<?php
								$cad_cbx =  mysqli_query($cnx, "SELECT * FROM materiales ORDER BY mat_nombre") or die(mysqli_error($cnx) . "Error: en consultar las etapas ");
								$reg_cbx =  mysqli_fetch_array($cad_cbx);

								do { ?>
 								<option value="<?php echo $reg_cbx['mat_id'] ?>"><?php echo $reg_cbx['mat_nombre'] ?></option>
 							<?php
								} while ($reg_cbx =  mysqli_fetch_array($cad_cbx));
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
 										<th width="200">Material</th>
 									</tr>
 								</tbody>
 								<?php do { ?>
 									<tr>
 										<td><?php echo $reg_fase['mat_nombre'] ?></td>
 									</tr>
 								<?php } while ($reg_fase = mysqli_fetch_assoc($cad_fase)); ?>
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
 				<?php if (fnc_permiso($_SESSION['privilegio'], 8, 'upe_editar') == 1) { ?> <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button> <?php } ?>
 			</div>
 		</form>
 	</div>
 </div>