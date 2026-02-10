<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
include('../seguridad/user_seguridad.php');
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
FROM preparacion_tipo 
WHERE pt_id = '" . $_POST['hdd_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar los tipos");
$registros = mysqli_fetch_assoc($cadena);

$cad_fase = mysqli_query($cnx, "SELECT p.*, e.pe_descripcion, e.pe_hr_maxima 
FROM preparacion_tipo_etapas as p
INNER JOIN preparacion_etapas as e on(p.pe_id = e.pe_id)
WHERE p.pt_id = '" . $_POST['hdd_id'] . "' ORDER BY p.pte_orden asc ") or die(mysqli_error($cnx) . "Error: en consultar las etapas");
$reg_fase = mysqli_fetch_assoc($cad_fase);
$tot_fase = mysqli_num_rows($cad_fase);
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
 		$("#formC").submit(function() {
 			//alert('editar');
 			var formData = $(this).serialize();
 			$.ajax({
 				url: "tipo_proceso_agregar.php",
 				type: 'POST',
 				data: formData,
 				success: function(result) {
 					data = JSON.parse(result);
 					//alert("Guardo el registro");

 					alertas("#alerta-errorProvEditar", 'Listo!', data["mensaje"], 1, true, 5000);
 					//$('#formC').each (function(){this.reset();}); 
 					document.getElementById("cbxFase").value = '';
 					var hdd_id = document.getElementById("hdd_id").value;
 					setTimeout(cargar('#main', 'tipo_proceso_consultar_cargar.php?id=' + hdd_id), 23000);

 				}
 			});
 			return false;
 		});
 	});



 	function fnc_baja_fase(id) {
 		var respuesta = confirm("¿Deseas dar de baja este registro?");
 		//alert(id);
 		if (respuesta) {
 			$.ajax({
 				url: 'tipo_proceso_baja_fase.php',
 				data: 'id=' + id,
 				type: 'post',
 				success: function(result) {
 					data = JSON.parse(result);
 					alertas("#alerta-errorProvEditar", 'Listo!', data["mensaje"], 1, true, 5000);

 					var hdd_id = document.getElementById("hdd_id").value;
 					setTimeout(cargar('#main', 'tipo_proceso_consultar_cargar.php?id=' + hdd_id), 23000);
 				}
 			});
 			//return false;
 		}
 	}

 	function fnc_sube_fase(id) {
 		$.ajax({
 			url: 'tipo_proceso_sube_fase.php',
 			data: 'id=' + id,
 			type: 'post',
 			success: function(result) {
 				data = JSON.parse(result);
 				alertas("#alerta-errorProvEditar", 'Listo!', data["mensaje"], 1, true, 5000);

 				var hdd_id = document.getElementById("hdd_id").value;
 				setTimeout(cargar('#main', 'tipo_proceso_consultar_cargar.php?id=' + hdd_id), 23000);
 			}
 		});
 		//return false;
 	}

 	function fnc_bajar_fase(id) {
 		$.ajax({
 			url: 'tipo_proceso_bajar_fase.php',
 			data: 'id=' + id,
 			type: 'post',
 			success: function(result) {
 				data = JSON.parse(result);
 				alertas("#alerta-errorProvEditar", 'Listo!', data["mensaje"], 1, true, 5000);

 				var hdd_id = document.getElementById("hdd_id").value;
 				setTimeout(cargar('#main', 'tipo_proceso_consultar_cargar.php?id=' + hdd_id), 23000);

 			}
 		});
 		//return false;
 	}
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
 				<h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Consultar tipo proceso</h5>
 				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
 					<span aria-hidden="true">&times;</span>
 				</button>
 			</div>

 			<div class="modal-body">
 				<div class="row">
 					<div class="col-md-5">
 						<label for="recipient-name" class="col-form-label">Nombre:</label>
 						<input name="txtNombre" type="text" class="form-control" id="txtNombre" placeholder="Nombre" value="<?php echo $registros['pt_descripcion'] ?>" readonly="true">
 						<input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['pt_id'] ?>" />
 						<input name="hdd_total" type="hidden" id="hdd_total" value="<?php echo $tot_fase + 1; ?>" />
 					</div>

 					<div class="col-md-3">
 						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Revisión:</label>
 						<input name="txtRevision" type="text" class="form-control" id="txtRevision" placeholder="Revision" value="<?php echo $registros['pt_revision'] ?>" readonly="true">
 					</div>

 					<div class="col-md-3">
 						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Fase</label>
 						<select name="cbxFase" id="cbxFase" class="form-control">
 							<option value="">Seleccionar Fase</option>
 							<?php
								$cad_cbx =  mysqli_query($cnx, "SELECT * FROM preparacion_etapas ORDER BY pe_descripcion") or die(mysqli_error($cnx) . "Error: en consultar las etapas ");
								$reg_cbx =  mysqli_fetch_array($cad_cbx);

								do { ?>
 								<option value="<?php echo $reg_cbx['pe_id'] ?>"><?php echo $reg_cbx['pe_descripcion'] ?></option>
 							<?php
								} while ($reg_cbx =  mysqli_fetch_array($cad_cbx));
								?>
 						</select>
 					</div>
 				</div>
 				<br>
 				<div class="row" id="divFase">

 					<div id="main">
 						<div class="col-md-5">
 							<table border="1">
 								<tbody>
 									<tr bgcolor="#4AB5B9" style="color:#FFFFFF; font-weight:bold;" height="30">
 										<th width="200">Fase</th>
 										<th width="60">Orden</th>
 										<th>Horas</th>
 										<th width="60">Quitar</th>
 										<th width="60">Arriba</th>
 										<th width="60">Abajo</th>
 									</tr>
 								</tbody>
 								<?php
									$flt_hras = 0;
									do {
										if (isset($reg_fase['pe_id'])) { ?>

 										<tr>
 											<td><?php echo $reg_fase['pe_descripcion'] ?></td>
 											<td align="center"><?php echo $reg_fase['pte_orden'] ?></td>
 											<td align="center"><?php echo $reg_fase['pe_hr_maxima'] ?></td>
 											<td align="center"> <?php if (fnc_permiso($_SESSION['privilegio'], 9, 'upe_editar') == 1) { ?>
 													<a href="javascript:fnc_baja_fase(<?= $reg_fase['pte_id'] ?>);"><img src="../iconos/quitar.png" alt="Quitar" /> <?php } ?></a>
 											</td>
 											<td align="center"> <?php if (fnc_permiso($_SESSION['privilegio'], 9, 'upe_editar') == 1) { ?>
 													<?php if ($reg_fase['pte_orden'] != 1) { ?><a href="javascript:fnc_sube_fase(<?= $reg_fase['pte_id'] ?>);"><img src="../iconos/arriba.png" alt="Arriba" /> <?php } ?></a><?php } ?></td>
 											<td align="center"> <?php if (fnc_permiso($_SESSION['privilegio'], 9, 'upe_editar') == 1) { ?>
 													<?php if ($reg_fase['pte_orden'] != $tot_fase) { ?><a href="javascript:fnc_bajar_fase(<?= $reg_fase['pte_id'] ?>);"><img src="../iconos/abajo.png" alt="Abajo" /> <?php } ?></a><?php } ?></td>
 										</tr>
 								<?php }

										$flt_hras += $reg_fase['pe_hr_maxima'];
									} while ($reg_fase = mysqli_fetch_assoc($cad_fase)); ?>
 								<tr>
 									<td colspan="2">&nbsp;</td>
 									<td align="center"><?php echo $flt_hras; ?></td>
 								</tr>
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
 				<?php if (fnc_permiso($_SESSION['privilegio'], 9, 'upe_editar') == 1) { ?> <button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button> <?php } ?>
 			</div>
 		</form>
 	</div>
 </div>