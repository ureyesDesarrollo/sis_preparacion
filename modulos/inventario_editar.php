 <?php
	/*Desarrollado por: CCA Consultores TI */
	/*Contacto: info@ccaconsultoresti.com */
	/*Actualizado: Septiembre-2023*/
	include "../conexion/conexion.php";
	include "../funciones/funciones.php";
	$cnx =  Conectarse();
	extract($_POST);
	$cadena = mysqli_query($cnx, "SELECT *
 	FROM inventario
 	WHERE inv_id = '" . $_POST['hdd_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
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
 			var kilos = document.getElementById("txtKgTotales").value;
 			if (kilos < 0) {
 				alert('No puede ingresar cantidades negativas');
 			} else {
 				var formData = $(this).serialize();
 				$.ajax({
 					url: "inventario_actualizar.php",
 					type: 'POST',
 					data: formData,
 					success: function(result) {
 						data = JSON.parse(result);
 						//alert("Guardo el registro");
 						alertas("#alerta-errorProvEditar", 'Listo!', data["mensaje"], 1, true, 5000);
 						$('#formE').each(function() {
 							this.reset();
 						});
 					}
 				});
 				return confirmEnviar();
 			}
 			return false;
 		});
 	});


 	//Bloquear boton modal de lavadores a paleto
 	function confirmEnviar() {

 		formE.btn.disabled = true;
 		formE.btn.value = "Enviando...";

 		setTimeout(function() {
 			formE.btn.disabled = true;
 			formE.btn.value = "Guardar";
 		}, 2000);

 		var statSend = false;
 		return false;
 	}
 </script>
 <div class="modal-dialog modal-lg" role="document">
 	<div class="modal-content">
 		<form name="formE" id="formE">
 			<div class="modal-header">
 				<h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Recibir inventario extranjero</h5>
 				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
 					<span aria-hidden="true">&times;</span>
 				</button>
 			</div>

 			<div class="modal-body">
 				<div class="row">
 					<div class="col-md-3">
 						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Maquila:</label>
 						<!-- <span class="col-md-3">-->
 						<input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['inv_id'] ?>" />
 						<input name="hddMaterial" type="hidden" id="hddMaterial" value="<?php echo $registros['mat_id'] ?>" />

 						<select class="form-control" name="cbxProveedor" id="cbxProveedor" require>
 							<option value="">Seleccione maquila</option>
 							<?php
								if (isset($maquila)) {
									$maquila = $maquila;
								} else {
									$maquila = 0;
								}
								$cad_maquila = mysqli_query($cnx, "SELECT * FROM proveedores WHERE prv_mql = 'S' OR prv_mql = 'C' order by prv_nombre asc");
								while ($reg_maquila =  mysqli_fetch_assoc($cad_maquila)) { ?>
 								<option value="<?php echo mb_convert_encoding($reg_maquila['prv_id'], "UTF-8");  ?>" <?php if (mb_convert_encoding($reg_maquila['prv_id'], "UTF-8") == $maquila) { ?>selected="selected" <?php } ?>><?php echo mb_convert_encoding($reg_maquila['prv_nombre'], "UTF-8");  ?></option>
 							<?php } ?>
 						</select>
 						<!--</span>-->
 					</div>
 					<div class="col-md-2">
 						<label for="recipient-name" class="col-form-label"><span>*</span> Folio interno:</label>
 						<input name="txt_folio_interno" type="text" class="form-control" id="txt_folio_interno" placeholder="Folio interno" required onkeypress="return isNumberKey(event, this);" value="<?php echo fnc_folio_mensual(); ?>">
 					</div>

 					<!--<div class="col-md-2">
						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG TOTALES:</label>
						<input name="txtKgTotales" type="text" class="form-control" id="txtKgTotales"  placeholder="Kilos" required >
					</div>-->
 					<div class="col-md-3">
 						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG Entrada:</label>
 						<input name="txtKg" type="text" class="form-control" id="txtKg" placeholder="Kilos" required onkeypress="return isNumberKey(event, this);" onchange="fnc_calculaTotalM()" value="<?php echo $registros['inv_kilos'] ?>" readonly="readonly">
 					</div>

 					<!--<div class="col-md-3">
							<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG Carga lavador:</label>
							<input name="txtKgLavador" type="hidden" class="form-control" id="txtKgLavador"  placeholder="Kilos carga lavador" value="<?php echo $registros['inv_kilos'] ?>"  required >
						</div>-->


 					<div class="col-md-3">
 						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG entrada Maq.:</label>
 						<input onchange="valida_entrada();" onkeyup="fnc_calculaTotalEM();" name="txtKgEntradaMaq" type="text" class="form-control" id="txtKgEntradaMaq" placeholder="KG entrada maquila" required onkeypress="return isNumberKey(event, this);">
 					</div>

 					<!-- <div class="col-md-2">
							<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Hinchamiento:</label>
							<input name="txtHinchamiento" type="text" class="form-control" id="txtHinchamiento"  placeholder="Inchamiento" required >
						</div>-->

 					<div class="col-md-2">
 						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Prueba secador:</label>
 						<input onkeypress="return isNumberKey(event, this);" name="txtPrbSecador" type="text" class="form-control" id="txtPrbSecador" placeholder="Prueba secador" required value="">
 					</div>
 					<div class="col-md-2">
 						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descto. Agua:</label>
 						<input name="txtDAgua" type="text" class="form-control" id="txtDAgua" placeholder="Descuento" onkeyup="fnc_calculaTotalEM();" required onkeypress="return isNumberKey(event, this);" value="">
 					</div>
 					<div class="col-md-3">
 						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descto. Descarne:</label>
 						<input name="txtDescarne" type="text" class="form-control" id="txtDescarne" placeholder="Descarne" required onkeyup="fnc_calculaTotalEM();" onkeypress="return isNumberKey(event, this);" value="">
 					</div>
 					<div class="col-md-3">
 						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descto. Rendimiento:</label>
 						<input name="txtDRendimiento" type="text" class="form-control" id="txtDRendimiento" placeholder="Descuento" required="required" onkeyup="fnc_calculaTotalEM();" value="" onkeypress="return isNumberKey(event, this);" />
 					</div>
 					<div class="col-md-2">
 						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG TOTALES:</label>
 						<input name="txtKgTotales" type="text" class="form-control" id="txtKgTotales" placeholder="Kilos" required readonly="readonly">
 					</div>
 					<div class="col-md-3">
 						<label for="recipient-name" class="col-form-label"><span>*</span> Fecha y hora entrada:</label>
 						<input name="txt_hora_entrada" onchange="fnc_calculaTotalEM();" type="datetime-local" class="form-control" id="txt_hora_entrada" required>
 					</div>
 					<div class="col-md-3">
 						<label for="recipient-name" class="col-form-label"><span>*</span> Fecha y hora salida:</label>
 						<input name="txt_hora_salida" onchange="fnc_calculaTotalEM();" type="datetime-local" class="form-control" id="txt_hora_salida" required>
 					</div>
 					<div class="col-md-2">
 						<label for="recipient-name" class="col-form-label"><span>*</span> Ubicación:</label>
 						<!-- <span class="col-md-3">-->
 						<select name="cbxUbicacion" class="form-control" id="cbxUbicacion" required="required">
 							<option value="">Seleccionar ubicación</option>
 							<?php
								$cad_cbx =  mysqli_query($cnx, "SELECT * FROM almacen_cajones WHERE ac_estatus = 'A' AND ac_ban = 'M' ORDER BY ac_descripcion") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
								$reg_cbx =  mysqli_fetch_array($cad_cbx);

								do {
								?>
 								<option value="<?php echo $reg_cbx['ac_id'] ?>"><?php echo $reg_cbx['ac_descripcion']; ?></option>
 							<?php
								} while ($reg_cbx =  mysqli_fetch_array($cad_cbx));
								?>
 						</select>
 						<!--</span>-->
 					</div>

 				</div>
 				<div class="row" style="margin-top: 3rem;">
 					<div class="col-md-12">
 						<label for="recipient-name" class="col-form-label">Notas: </label><br>
 						<ol>
 							<li>Actualmente no se se realizan pruebas de: secador,descuento agua, descuento,descarne,descuento rendiemiento. Se coloca 0.
 							</li>
 							<li>kg totales se calcula de acuerdo a (Kg entrada - descuento de agua, desc. descarne, desc. rendimiento)
 							</li>
 						</ol>
 					</div>
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
 				<button class="btn btn-primary" type="submit" id="btn" name="btn"><img src="../iconos/guardar.png" alt=""> Guardar</button>
 			</div>
 		</form>
 	</div>
 </div>