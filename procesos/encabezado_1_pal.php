<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
?>
<script src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/alerta.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script>
	//bloque renglones 
	$(document).ready(function() {
		for (var i = 2; i <= 12; i++) {

			$('#cbxMaterial' + i).prop('disabled', 'disabled');
			$('#cbxKilosID' + i).prop('disabled', 'disabled');
			$('#btn' + i).prop("style", "pointer-events: none;opacity:0.5");
		}
	});


	function obtenerInv(param) {
		var datos = {

			"mat_id": $("#cbxMaterial" + param).val(),

			"inv_id1": $("#cbxKilosID1").val(),
			"inv_id2": $("#cbxKilosID2").val(),
			"inv_id3": $("#cbxKilosID3").val(),
			"inv_id4": $("#cbxKilosID4").val(),
			"inv_id5": $("#cbxKilosID5").val(),
			"inv_id6": $("#cbxKilosID6").val(),
			"inv_id7": $("#cbxKilosID7").val(),
			"inv_id8": $("#cbxKilosID8").val(),
			"inv_id9": $("#cbxKilosID9").val(),
			"inv_id10": $("#cbxKilosID10").val(),
			"inv_id11": $("#cbxKilosID11").val(),
			"inv_id12": $("#cbxKilosID12").val(),
		}
		$.ajax({
			type: 'post',
			url: 'getInvTot.php',
			data: datos,
			//data: {nombre:n},
			success: function(d) {
				$("#cbxKilosID" + param).html(d);
				//alert(d);

				//agregado por cc 19/01/22
				//desbloque renglon si el anterior ya tiene datos capturados
				$('#cbxKilosID' + param).change(function() {
					var incrementa = param + 1;
					$('#cbxMaterial' + incrementa).removeAttr('disabled');
					$('#cbxKilosID' + incrementa).removeAttr('disabled');
					$('#btn' + incrementa).removeAttr('disabled');
				});
			}
		});
		return false;
	}


	//cargar combos fecha de entrada
	//PONE EL CONTADOR A 0
	var count_click = 0;

	//AÑADE UN CLICK AL EJECUTAR LA FUNCIÓN
	function count_click_add() {
		count_click += 1;
	}
	$(document).ready(function() {
		$("button[name='count_click']").click(function() {
			count_click_add();
			if (count_click == 7) {
				formEncabezado1.count_click.disabled = true;
			}
		});
	});

	function obtenerFecha(param) {
		var datos = {

			"inv_id": $("#cbxKilosID" + param).val(),
		}
		$.ajax({
			type: 'post',
			url: 'getFecha.php',
			data: datos,
			//data: {nombre:n},
			success: function(d) {
				$("#txtFecha" + param).val(d);
				//alert(d);
				$('#btn' + param).removeAttr("style", "pointer-events: none;opacity:0.5");

			}
		});
		return false;
	}


	function sumCantidad(param) {
		//alert("este no");
		var cantTotal = document.getElementById('txtTotKilos').value = 0;
		for (i = 1; i <= param; i++) {
			var combo = document.getElementById('cbxKilosID' + i);
			var val = combo.options[combo.selectedIndex].text;
			/*document.getElementById('txtKilos'+i).value = val;
			if (val == 'Seleccionar') 
			{
				var val = 0;
			}*/
			//separa cadena cuando encuentre un espacio
			var kilos = val.split(" ");

			document.getElementById('txtKilos' + i).value = parseFloat(kilos[0]);

			//alert(val);

			cantTotal = parseFloat(cantTotal) + parseFloat(val);
		}

		var x = document.getElementById('txtTotKilos').value = parseFloat(cantTotal);


	}



	function sumCantidadD(param) {
		var cantTotal = document.getElementById('txtTotKilos').value = 0;
		for (i = 1; i <= param; i++) {
			var combo = document.getElementById('cbxKilosID' + i);
			var val = combo.options[combo.selectedIndex].text;
			/*document.getElementById('txtKilos'+i).value = val;
			
			if (val == 'Seleccionar') 
			{
				var val = 0;
			}*/

			//separa cadena cuando encuentre un espacio
			var kilos = val.split(" ");

			document.getElementById('txtKilos' + i).value = parseFloat(kilos[0]);

			//alert(val);

			cantTotal = parseFloat(cantTotal) + parseFloat(val);
		}

		var x = document.getElementById('txtTotKilos').value = parseFloat(cantTotal);


	}


	$(document).ready(function() {
		$("#formEncabezado1").submit(function() {

			var material = document.getElementById('cbxMaterial1').value;
			var kilos = document.getElementById('cbxKilosID1').value;
			if (material == 0 || kilos == 0) {
				alert('Seleccione al menos un material y kilos a procesar');
				document.getElementById("cbxMaterial1").focus();
				return false;
			} else {

				var formData = $(this).serialize();
				$.ajax({
					url: "encabezado_1_pal_insertar.php",
					type: 'POST',
					data: formData,
					success: function(result) {

						data = JSON.parse(result);
						alertas("#alerta-errorEncabezado1", 'Listo!', data["mensaje"], 1, true, 5000);
						$('#formEncabezado1').each(function() {
							this.reset();
						});
						setTimeout("location.reload()", 2000);
					}
				});
				return confirmEnviar();
				return false;
				//}
			}
		});
	});



	function AbreModalEditar(param) {
		//alert('aqui');
		var datos = {
			"inv_id": $("#cbxKilosID" + param).val(),
			"param": param,
		}
		//alert($("#cbxKilosID"+param).val());
		$.ajax({
			type: 'post',
			url: 'modal_dividir.php',
			data: datos,
			//data: {nombre:n},
			success: function(result) {
				$("#modalDividir").html(result);
				$('#modalDividir').modal('show')
			}
		});
		return false;
	}

	function kilos(par, inv_id, mat_id) {
		var datos = {
			"inv_id": inv_id,

			"mat_id": mat_id,
		}

		//var par = $("#cbxKilosID1").val()
		//alert(par);
		$.ajax({
			type: 'post',
			url: "getKilos.php",
			data: datos,
			//data: {nombre:n},
			success: function(d) {
				$("#cbxKilosID" + par).html(d);
				sumCantidad(par);

			}
		});
	}


	function refresh() {
		location.reload();
	}


	function confirmEnviar() {

		formEncabezado1.btnEnviar.disabled = true;
		formEncabezado1.btnEnviar.value = "Enviando...";

		setTimeout(function() {
			formEncabezado1.btnEnviar.disabled = true;
			formEncabezado1.btnEnviar.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}


	//agregado por CC 26-01-202
	a = 5;

	function agregarRenglon() {
		a++;
		var div = document.createElement('div');
		//div.setAttribute('class', 'form-row col-md-12');
		div.setAttribute('style', '');

		var ren_ant = a - 1;
		var mat_ant = $("#cbxKilosID" + ren_ant).val();

		div.innerHTML =
			'<div class="form-row col-md-12" style="margin-top:-15px"><div class="form-group col-sm-3 col-md-2">' +
			'<select disabled class="form-control" id="cbxMaterial' + a + '" name="cbxMaterial' + a + '" " onchange="obtenerInv(' + a + ')">' +
			'<option value="">Seleccionar</option>' +
			<?php
			$query =  $cadena_mat =  mysqli_query($cnx, "SELECT * from materiales WHERE mat_est = 'A' ");
			while ($registros = mysqli_fetch_array($query)) { ?> '<option value="<?php echo $registros['mat_id'] ?>" > <?php echo $registros['mat_nombre'] ?> </option>' +
			<?php }
			?> '</select></div>' +

			'<div class="form-group col-sm-6 col-md-6">' +
			'<select disabled class="form-control" id="cbxKilosID' + a + '" name="cbxKilosID' + a + '" onchange="sumCantidadD(' + a + ');obtenerFecha(' + a + ')">' +
			'<option value="">Seleccionar</option>' +
			'</select>' +
			'<input type="hidden" id="txtKilos' + a + '" name="txtKilos' + a + '">' +
			'</div>' +



			'<div class="form-group col-sm-2 col-sm-4 col-md-2" style="width: 70px">' +
			'<a style="pointer-events: none;opacity:0.5" id="btn' + a + '" href="#" onClick="javascript:AbreModalEditar(' + a + ')"><img src="../iconos/division.png" style="padding-bottom: 5px"></a>' +
			'</div>' +


			'<div class="form-group col-sm-2 col-md-1" style="width: 130px">' +
			'<input type="text" class="form-control" id="txtFecha' + a + '" style="width: 100px" name="txtFecha' + a + '"  readonly="true" size="10">' +
			'</div></div>';

		document.getElementById('campos').appendChild(div);

		if (mat_ant != '') {
			$('#cbxMaterial' + a).removeAttr('disabled');
			$("#cbxKilosID" + a).removeAttr('disabled');
			$("#btn" + a).removeAttr('disabled');
		}
	}
</script>

<form id="formEncabezado1">
	<!--supervisor-->
	<input name="hdd_user" type="hidden" value="<?php echo $_SESSION['idUsu']; ?>" id="hdd_user" />
	<div class="form-row">
		<div class="form-group col-md-4">
			<label for="inputState">Tipo Preparacion</label>
			<select id="cbxProceso" class="form-control" name="cbxProceso" required>
				<option value="">Seleccionar</option>
				<?php
				$html = '';

				$cadena =  mysqli_query($cnx, "SELECT * from preparacion_tipo WHERE pt_estatus = 'A' AND pt_para = 'P' ORDER BY pt_descripcion");
				$registros =  mysqli_fetch_array($cadena);

				do {
					$html .= "<option value='" . $registros['pt_id'] . "'>" . $registros['pt_descripcion'] . "</option>";
				} while ($registros =  mysqli_fetch_array($cadena));

				echo $html;

				mysqli_free_result($registros);

				?>
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="inputState">Paleto</label>
			<!--<select id="cbxPaleto" class="form-control" style="width: 250px" name="cbxPaleto" required>
				<option value="">Seleccionar</option>
				<?php
				$html = '';

				$cadena =  mysqli_query($cnx, "SELECT * from preparacion_paletos where le_id = 2 and pp_id > 2");
				$registros =  mysqli_fetch_array($cadena);

				do {
					$html .= "<option value='" . $registros['pp_id'] . "'>" . $registros['pp_descripcion'] . "</option>";
				} while ($registros =  mysqli_fetch_array($cadena));

				echo $html;

				mysqli_free_result($registros);

				?>
			</select>-->
			<select id="cbxPaleto" class="form-control" style="width: 250px" name="cbxPaleto">
				<option value="">Seleccionar</option>
				<?php
				$cad =  mysqli_query($cnx, "SELECT * from preparacion_paletos where pp_id =  " . $_GET['id_p'] . " ");
				$reg =  mysqli_fetch_array($cad);

				$cadena =  mysqli_query($cnx, "SELECT * from preparacion_paletos where le_id = 2 and pp_id > 2");
				$registros =  mysqli_fetch_array($cadena);

				do {
				?><option value="<?php echo $registros['pp_id'] ?>" <?php if ($registros['pp_id'] == $reg['pp_id']) { ?>selected="selected" <?php } ?>><?php echo $registros['pp_descripcion'] ?></option><?php
																																																		} while ($registros =  mysqli_fetch_array($cadena));

																																																		//mysqli_free_result($registros);

																																																			?>
			</select>
		</div>


		<div class="form-group col-md-4">
			<div class="alert alert-info hide" id="alerta-errorEncabezado1" style="height: 40px;width: 300px;text-align: left;z-index: 10;margin-top: 10px;margin-bottom: -60px;position: fixed;">
				<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
				<strong>Titulo</strong> &nbsp;&nbsp;
				<span> Mensaje </span>
			</div>
		</div>
		<div class="form-group col-md-1">
			<div style="height: 40px;width: 300px;text-align: left;z-index: 10;margin-top:10px;float: right;width: 130px;margin-bottom: 0px">
				<!--<button type="submit" class="btn btn-primary" id="btnEnviar" name="btnEnviar"><img src="../iconos/guardar.png" alt="">Guardar</button>-->
				<input type="submit" class="btn btn-primary" style="float: right;margin-right: 10px;margin-left: 10px" name="btnEnviar" value="Guardar" id="btnEnviar" />

			</div>
		</div>
	</div>

	<div class="form-row col-md-12">
		<div class="form-group col-xs-3  col-sm-3 col-md-2">
			<label for="inputPassword4">Tipo material</label>
			<?php for ($i = 1; $i <= 5; $i++) { ?>
				<select id="<?php echo "cbxMaterial" . $i ?>" class="form-control" name="<?php echo "cbxMaterial" . $i ?>" onchange="obtenerInv(<?php echo $i; ?>)">
					<option value="">Seleccionar</option>
					<?php
					$html = '';
					$cadena_mat =  mysqli_query($cnx, "SELECT * from materiales WHERE mat_est = 'A'");
					$registros =  mysqli_fetch_array($cadena_mat);
					do {
						$html .= "<option value='" . $registros['mat_id'] . "'>" . $registros['mat_nombre'] . "</option>";
					} while ($registros =  mysqli_fetch_array($cadena_mat));
					echo $html;
					?>
				</select>
			<?php } ?>
		</div>

		<div class="form-group col-xs-5 col-sm-6 col-md-6">
			<label for="inputPassword4">[Kg]/Proveedor/F. entrada/F. ent maq</label>
			<?php for ($i = 1; $i <= 5; $i++) { ?>
				<select id="<?php echo "cbxKilosID" . $i ?>" class="form-control" name="<?php echo "cbxKilosID" . $i ?>" onchange="sumCantidadD(<?php echo $i ?>);obtenerFecha(<?php echo $i; ?>);">
					<option value="">Seleccionar</option>
				</select>
				<input type="hidden" id="<?php echo "txtKilos" . $i ?>" name="<?php echo "txtKilos" . $i ?>">
			<?php } ?>
			<input type="hidden" id="txtSobraH" name="txtSobraH">
		</div>
		<div class="form-group col-md-1" style="width: 70px">
			<label for="inputPassword4" style="color: #fff">Toneladas</label>

			<?php for ($i = 1; $i <= 5; $i++) { ?>
				<a style="pointer-events: none;opacity:0.5" id="<?php echo "btn" . $i ?>" href="#" onClick="javascript:AbreModalEditar(<?php echo $i ?>)"><img src="../iconos/division.png" style="padding-bottom: 5px"></a>
			<?php } ?>
		</div>
		<div class="form-group col-md-1" style="width: 130px">
			<label for="inputPassword4">Fecha entrada</label>
			<?php for ($i = 1; $i <= 5; $i++) { ?>
				<input type="text" class="form-control" id="<?php echo "txtFecha" . $i ?>" style="width: 100px" name="<?php echo "txtFecha" . $i ?>" readonly="true" size="10">
			<?php } ?>
		</div>
	</div>
	<div style="background: #000;" id="campos"></div>

	<?php
	//include "tabla_1.php";
	//include "tabla_2.php";
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group col-md-2">
				<label for="inputPassword4" style="color: #fff">N/A</label>
				<button type="button" class="btn btn-primary" style="padding: 2px;margin-top: -3px" id="count_click" name="count_click" onclick="agregarRenglon()"><img src="../iconos/add.png" alt=""> Agregar renglón </button>
			</div>
			<div class="form-group col-md-2">
				<label for="inputPassword4">Total kgs:</label>
				<input style="width:105px" type="text" class="form-control" id="txtTotKilos" name="txtTotKilos" readonly="">
				<input type="hidden" class="form-control" id="txtAux" name="txtAux" readonly="">
			</div>
			<!--<div class="form-group col-md-3">
					<label for="inputPassword4">Fecha que carga lavador:</label>
					<input type="date" class="form-control" id="txtFechaCarga" placeholder="Fecha de Carga" name="txtFechaCarga" value="<?php echo date("Y-m-d") ?>">
				</div>
				<div class="form-group col-md-2">
					<label for="inputPassword4">Hora Inicia:</label>
					<input type="time" class="form-control" id="txtHrIni" placeholder="" name="txtHrIni" value="<?php echo date("h:i:s") ?>">
				</div>
				<div class="form-group col-md-2">
					<label for="inputPassword4">Hora Termina</label>
					<input type="time" class="form-control" id="txtHrFin" placeholder="" name="txtHrFin" value="<?php echo date("h:i:s") ?>">
				</div>-->
		</div>
	</div>
	</div>
</form>

<div class="modal" id="modalDividir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>