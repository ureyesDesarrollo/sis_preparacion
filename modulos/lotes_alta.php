<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
//include "../conexion/conexion.php";
//include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT p.*, l.le_estatus
FROM preparacion_paletos as p
INNER JOIN listado_estatus as l on (p.le_id = l.le_id)
WHERE p.pp_id = '" . $_POST['hdd_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar las etapas");
$registros = mysqli_fetch_assoc($cadena);
?>
  <script>
  	$(document).ready(function() {
  		$("#formEstEditar").submit(function() {
  			//alert('editar');
  			var formData = $(this).serialize();
  			$.ajax({
  				url: "lotes_insertar.php",
  				type: 'POST',
  				data: formData,
  				success: function(result) {
  					data = JSON.parse(result);
  					//alert("Guardo el registro");
  					alertas("#alerta-errorEtapaEditar", 'Listo!', data["mensaje"], 1, true, 3000);
  					$('#formEstEditar').each(function() {
  						this.reset();
  					});

  					// var hdd_id =  document.getElementById("hdd_id").value; 
  					setTimeout(cargar('#main', 'lote_consultar.php'), 2000);
  					return confirmEnviar();
  				}
  			});
  			return false;
  		});
  	});


  	function cargar(div, desde) {
  		$(div).load(desde);
  	}

  	function confirmEnviar() {

  		formEstEditar.btnEnviar.disabled = true;
  		formEstEditar.btnEnviar.value = "Registro guardado";

  		setTimeout(function() {
  			formEstEditar.btnEnviar.disabled = false;
  			formEstEditar.btnEnviar.value = "Guardar";
  		}, 3000);

  		var statSend = false;
  		return false;
  	}
  </script>


  <div class="modal-dialog modal-lg" role="document" style="height: 200px">
  	<div class="modal-content">
  		<form id="formEstEditar">
  			<div class="modal-header">
  				<h5 class="modal-title" id="exampleModalLabel" style="color:#4AB5B9; font-size:18px; font-weight:bold;">Agregar lote</h5>
  				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
  					<span aria-hidden="true">&times;</span>
  				</button>
  			</div>

  			<div class="modal-body">
  				<div class="row">
  					<div id="main">
  						<div class="col-md-2">
  							<label for="recipient-name" class="col-form-label">Fecha:</label>
  							<input name="txt_fecha" id="txt_fecha" type="text" size="10" value="<?php echo date("d-m-Y") ?>" readonly="true" class="form-control is-valid" />
  						</div>
  						<div class="col-md-2">
  							<label for="recipient-name" class="col-form-label">Hora:</label>
  							<input name="txt_hora" id="txt_hora" type="text" size="5" value="<?php echo date("h:i:s") ?>" readonly="true" class="form-control is-valid" />
  						</div>
  						<div class="col-md-2">
  							<label for="recipient-name" class="col-form-label">Mes:</label>
  							<input name="txt_mes" id="txt_mes" type="text" size="10" value="<?php echo fnc_formato_mes(date("m")) ?>" readonly="true" class="form-control is-valid" />
  						</div>
  						<div class="col-md-2">
  							<label for="recipient-name" class="col-form-label">Turno:</label>
  							<select name="slc_turno" id="slc_turno" class="form-control is-valid" required>
  								<option value="">Selecciona...</option>
  								<option value="D">Dia</option>
  								<option value="N">Noche</option>
  							</select>
  						</div>
  						<div class="col-md-2">
  							<label for="recipient-name" class="col-form-label">Lote:</label>
  							<input name="txt_lote" id="txt_lote" type="text" size="10" value="<?php echo fnc_lote(date("m")) ?>" readonly="true" class="form-control is-valid" required />
  						</div>
  						<div class="col-md-2">
  							<label for="recipient-name" class="col-form-label">Proceso/Paleto:</label>
  							<select name="cbxPaleto" class="form-control is-valid" id="cbxPaleto" placeholder="" required>
  								<option value="">Selecciona...</option>
  								<?php
									//$cad_est = mysqli_query($cnx,"select * from listado_estatus WHERE le_aplica = 'P' order by le_estatus asc");
									//$cad_est = mysqli_query($cnx, "select * from procesos_paletos WHERE pp_id IN (1,2) and prop_estatus = 1");

									$cad_est =  mysqli_query($cnx, "SELECT e.ep_id,e.ep_descripcion from equipos_preparacion as e
									inner join equipos_tipos as t on(e.ep_tipo = t.et_tipo)
									where e.le_id IN (9,11) and e.estatus = 'A' and ban_almacena = 'S' order by e.ep_descripcion asc ");
									$reg_est =  mysqli_fetch_array($cadena);

									while ($reg_est =  mysqli_fetch_assoc($cad_est)) {


									?>
  									<option value="<?php echo mb_convert_encoding($reg_est['ep_id'], "UTF-8");  ?>">

  										<?php echo mb_convert_encoding($reg_est['ep_descripcion'], "UTF-8");  ?>
  									</option>
  								<?php } ?>
  							</select>
  						</div>
  					</div>

  					<div class="modal-footer" style="margin-top: 8%;">
  						<!--mensajes-->
  						<div class="alert alert-info hide" id="alerta-errorEtapaEditar" style="height: 40px;width: 400px;text-align: left;position: fixed;float: left;margin-top: -5px;z-index: 10">
  							<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
  							<strong>Titulo</strong> &nbsp;&nbsp;
  							<span> Mensaje </span>
  						</div>
  						<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refresh()"><img src="../iconos/close.png" alt="">Cerrar</button>
  						<!--<button class="btn btn-primary" type="submit"><img src="../iconos/guardar.png" alt=""> Guardar</button>-->
  						<input type="submit" class="btn btn-primary" name="btnEnviar" value="Guardar" />
  					</div>
  				</div>
  			</div>
  		</form>

  	</div>
  </div>