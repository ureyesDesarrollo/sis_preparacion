 <?php 
 /*Desarrollado por: Ca & Ce Technologies */
 /*Contacto: mc.munoz.rz@gmail.com */
 /*21 - Agosto - 2018*/
 include "../conexion/conexion.php";
 include "../funciones/funciones.php";
 $cnx =  Conectarse();

 $cadena = mysqli_query($cnx, "SELECT *
 	FROM inventario
 	WHERE inv_id = '".$_POST['hdd_id']."'") or die(mysql_error()."Error: en consultar el proveedor");
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
		var kilos = document.getElementById("txtKgTotales").value;
		if (kilos < 0) {
			alert('No puede ingresar cantidades negativas');
		}else{
			var formData = $(this).serialize();
			$.ajax({
				url: "inventario_actualizar_local.php",
				type: 'POST',
				data: formData,
				success: function(result) {
					data = JSON.parse(result);
					//alert("Guardo el registro");
					alertas("#alerta-errorProvEditar", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formE').each (function(){this.reset();});  
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

		setTimeout(function(){
			formE.btn.disabled = true;
			formE.btn.value = "Guardar";
		},2000);

		var statSend = false;
		return false;
	}

	function valida_carga_tambor(){
		var entrada = parseInt(document.getElementById('txtKg').value);
   		var tambor = parseInt(document.getElementById('txtKgLavador').value);

   		if (tambor > entrada) {
   			alert('Los kG carga tambor no puede ser mayor a los KG de entrada');
   			document.getElementById('txtKgLavador').value  = '';
   			document.getElementById('txt_pendientes').value  = '';
   		}
	}
</script>
<div class="modal-dialog modal-lg" role="document">
	<div class="modal-content" style="width: 900px">
		<form name="formE" id="formE">	
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Recibir inventario local</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
			<div class="row">
				<div class="col-md-3">
					<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Proveedor:</label>
					<!-- <span class="col-md-3">-->
						<input name="hdd_id" type="hidden" id="hdd_id" value="<?php echo $registros['inv_id'] ?>"/>
						<input name="hddMaterial" type="hidden" id="hddMaterial" value="<?php echo $registros['mat_id'] ?>"/>
						<select name="cbxProveedor" class="form-control" id="cbxProveedor" required="required" >
							<option value="">Seleccionar Proveedor</option>
							<?php
							$cad_cbx =  mysqli_query($cnx, "SELECT * FROM proveedores WHERE prv_est = 'A' ORDER BY prv_nombre") or die(mysql_error()."Error: en consultar el proveedor");
							$reg_cbx =  mysqli_fetch_array($cad_cbx);

							do
							{?>
								<?php
								  if ( $reg_cbx['prv_tipo'] == 'L' && $reg_cbx['prv_ban'] == '1') { ?> 
									 <option  style="background:#E6E6" value="<?php echo $reg_cbx['prv_id'] ?>"><?php echo $reg_cbx['prv_nombre'] ?></option>
								  <?php } elseif ($reg_cbx['prv_tipo'] == 'L') { ?>
									<option  style="background:#FFF" value="<?php echo $reg_cbx['prv_id'] ?>"><?php echo $reg_cbx['prv_nombre'] ?></option>
								 <?php }
								  if ($reg_cbx['prv_tipo'] == 'E') { ?>
								   <option  style="background:#F7FEA0" value="<?php echo $reg_cbx['prv_id'] ?>"><?php echo $reg_cbx['prv_nombre'] ?></option>
								  <?php }
									
							}while($reg_cbx =  mysqli_fetch_array($cad_cbx));
							?>
						</select>
						<!--</span>-->
					</div>
					
					
					<!--<div class="col-md-2">
						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG TOTALES:</label>
						<input name="txtKgTotales" type="text" class="form-control" id="txtKgTotales"  placeholder="Kilos" required >
					</div>-->
					<div class="col-md-2">
						<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG Entrada:</label>
						<input name="txtKg" type="text" class="form-control" id="txtKg" placeholder="Kilos" required onkeypress="return isNumberKey(event, this);" onchange="fnc_calculaTotalEM()" value="<?php echo $registros['inv_kg_totales']?>" readonly="readonly">
					  </div>

					<div class="col-md-3">
							<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG Carga tambor:</label>
							<input name="txtKgLavador" type="text" class="form-control" id="txtKgLavador" onkeyup="kilos_pendientes_maquila_loc();valida_carga_tambor();" onkeypress="return isNumberKey(event, this);" placeholder="Kilos carga tambor" required >
						</div>

						<div class="col-md-2">
							<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG Pendientes:</label>
							<input readonly="" name="txt_pendientes" type="text" class="form-control" id="txt_pendientes"  placeholder="Kg pendientes" required >
						</div>

						<div class="col-md-2">
							<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG entrada Maq</label>
							<input name="txtKgEntradaMaq" type="text" class="form-control" id="txtKgEntradaMaq"  placeholder="KG entrada maquila" required onchange="fnc_calculaTotalEM();valida_entrada_local();" onkeypress="return isNumberKey(event, this);" >
						</div>
						
						<!-- <div class="col-md-2">
							<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Hinchamiento:</label>
							<input name="txtHinchamiento" type="text" class="form-control" id="txtHinchamiento"  placeholder="Inchamiento" required >
						</div>-->
					</div>
		<div class="row">
		
		</div>
		<div class="row">
		    <div class="col-md-2">
				<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Prueba secador:</label>
				<input name="txtPrbSecador" type="text" class="form-control" id="txtPrbSecador"  placeholder="Prueba secador" required >
			</div>
			 <div class="col-md-2">
				<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descto. Agua:</label>
				<input name="txtDAgua" type="text" class="form-control" id="txtDAgua" placeholder="Descuento" required onchange="fnc_calculaTotalEM()" onkeypress="return isNumberKey(event, this);">
			  </div>
			  <div class="col-md-3">
				<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descto. Descarne:</label>
				<input name="txtDescarne" type="text" class="form-control" id="txtDescarne" placeholder="Descarne" required onchange="fnc_calculaTotalEM()" onkeypress="return isNumberKey(event, this);">
			  </div>
			<div class="col-md-3">
				<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> Descto. Rendimiento:</label>
				<input name="txtDRendimiento" type="text" class="form-control" id="txtDRendimiento" placeholder="Descuento" required="required" onchange="fnc_calculaTotalEM()" onkeypress="return isNumberKey(event, this);"/>
			</div>
			  <div class="col-md-2">
				<label for="recipient-name" class="col-form-label"><span style="color:#FF0000; font-weight:bold;">*</span> KG TOTALES:</label>
				<input name="txtKgTotales" type="text" class="form-control" id="txtKgTotales" placeholder="Kilos" required readonly="readonly">
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