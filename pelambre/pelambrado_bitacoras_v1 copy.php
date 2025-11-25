<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
include "../funciones/funciones_procesos.php";
include "../seguridad/user_seguridad.php";

$cnx =  Conectarse();

extract($_GET);
?>
<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
<script src="../assets/fontawesome/fontawesome.js"></script>
<link rel="stylesheet" href="../assets/css/indicadores.css">
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/alerta.js"></script>

<?php
$cad_equipos = mysqli_query($cnx, "SELECT ep_descripcion FROM equipos_preparacion
WHERE ep_id = '$id_e'") or die(mysqli_error($cnx) . "Error: en consultar equipos");
$reg_equipos = mysqli_fetch_assoc($cad_equipos);

//selecciona pelambre en proceso
$cad_pelambre = mysqli_query($cnx, "SELECT * FROM inventario_pelambre
WHERE ep_id = '$id_e' and ip_ban = 1") or die(mysqli_error($cnx) . "Error: en consultar pelambre");
$reg_pelambre = mysqli_fetch_assoc($cad_pelambre);

#Obtener el inventario y material del pelambre
$inventario = mysqli_query($cnx, "SELECT inv_no_ticket, inv_kilos ,fnc_nombre_material (inv_id) as material FROM 
inventario WHERE inv_id ='" . $reg_pelambre['inv_id'] . "'");
$inventario = mysqli_fetch_assoc($inventario);

#En el encabezo usa el nombre listado pelambre, se reutilizo el nombre para usarlo en varias en las dos bitacoras
$listado_pelambre = $reg_pelambre;
$listado_pelambre['ip_fecha_envio'] = substr($listado_pelambre['ip_fecha_envio'], 0, 10);
$listado_pelambre['ip_fecha_remojo'] = substr($listado_pelambre['ip_fecha_remojo'], 0, 10);
$listado_pelambre['ep_descripcion'] = $reg_equipos['ep_descripcion'];
$listado_pelambre['inv_no_ticket'] = $inventario['inv_no_ticket'];
$listado_pelambre['inv_kilos'] = $inventario['inv_kilos'];
$listado_pelambre['material'] = $inventario['material'];
?>
<div class="imagen-header1">
	<p class="text-header">
		Formula pelambre
		<!-- Equipo <?php echo $reg_equipos['ep_descripcion']; ?> -->
	</p>
</div>
<div class="container-fluid">
	<?php include 'formatos/pelambrado_encabezado.php' ?>
	<nav aria-label="breadcrumb" style="font-weight:bolder">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active" aria-current="page">ETAPA REMOJO</li>
		</ol>
	</nav>
	<div class="row">
		<?php include "pelambrado_etapa2.php";
		?>
	</div>

	<nav aria-label="breadcrumb" style="font-weight:bolder">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active" aria-current="page">ETAPA PELAMBRE</li>
		</ol>
	</nav>
	<div class="row">
		<?php include "pelambrado_etapa3.php";	?>
	</div>


	<nav aria-label="breadcrumb" style="font-weight:bolder">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active" aria-current="page">ETAPA DE LAVADOS</li>
		</ol>
	</nav>
	<div class="row">
		<?php include "pelambrado_etapa4.php"; ?>
	</div>


	<nav aria-label="breadcrumb" style="font-weight:bolder">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active" aria-current="page">ETAPA DE PREBLANQUEO</li>
		</ol>
	</nav>
	<div class="row">
		<?php include "pelambrado_etapa5.php";	?>
	</div>

	<div class="row">
		<?php include "pelambrado_etapa_descarga.php" ?>
	</div>

</div>
<style>
	.imagen-header1 {
		background-image: url("../imagenes/banner_progel2.png");
		width: 95%;
		background-size: cover;
		background-position: center;
		background-repeat: no-repeat;
		margin: 0 auto;
		text-align: center;
		height: 90px;
		/* background-size: contain; */
		/* Hacer la imagen lo suficientemente pequeña como para ajustarse completamente dentro del div */
		color: #fff;
		background-position: -20px;
	}

	.text-header {
		padding-top: 1.5rem;
		font-size: 26px;
	}

	.container:not(.encabezado) {
		border-bottom-left-radius: 5px;
		border-bottom-right-radius: 5px;
		border-left: 2px solid #F399B1;
		border-right: 2px solid #F399B1;
		border-bottom: 2px solid #F399B1;
	}

	.row:not(.renglones) {
		margin-right: 0.5rem;
		margin-left: 0.5rem;
		border: 1px solid#e6e6;
		border-radius: 3px;
		margin-bottom: 1rem;
	}

	#titulos {
		font-weight: bold;
		color: rgba(33, 37, 41, 0.75);
	}

	#boton {
		text-align: right;
	}

	#etiqueta_niveles {
		font-size: 12px;
		font-weight: bold;
		background-color: yellow;
	}

	.container-fluid {
		padding-left: 50px;
		padding-right: 50px;
	}

	.btn-primary {
		color: #fff;
		background-color: #337ab7;
		border-color: #2e6da4;
	}
</style>
<script>
	/* registro fase remojo */
	$(document).ready(function() {
		$("#form_remojo").submit(function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: "pelambrado_etapa2_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {
					console.log(result);
					data = JSON.parse(result);
					console.log(data);
					if (data["mensaje"] == "Registro realizado") {
						alertas_v5("#alerta-accion_remojo", '', data["mensaje"], 1, true, 5000);
					} else {
						alertas_v5("#alerta-accion_remojo", '', data["mensaje"], 3, true, 5000);
					}
					setTimeout('location.reload()', 1000);
					/* $('#form_remojo').each(function() {
						this.reset();
					}); */
				}
			});
			return false;
		});
	});

	$(document).ready(function() {
		$("#form_pelambre").submit(function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: "pelambrado_etapa3_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {
					console.log(result);
					data = JSON.parse(result);
					console.log(data);
					if (data["mensaje"] == "Registro realizado") {
						alertas_v5("#alerta-accion_pelambre", '', data["mensaje"], 1, true, 5000);
					} else {
						alertas_v5("#alerta-accion_pelambre", '', data["mensaje"], 3, true, 5000);
					}
					setTimeout('location.reload()', 1000);
					/* $('#form_remojo').each(function() {
						this.reset();
					}); */
				}
			});
			return false;
		});
	});

	$(document).ready(function() {
		$("#form_lavado").submit(function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: "pelambrado_etapa4_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {
					console.log(result);
					data = JSON.parse(result);
					console.log(data);
					if (data["mensaje"] == "Registro realizado") {
						alertas_v5("#alerta-accion_lavados", '', data["mensaje"], 1, true, 5000);
					} else {
						alertas_v5("#alerta-accion_lavados", '', data["mensaje"], 3, true, 5000);
					}
					setTimeout('location.reload()', 1000);
					/* $('#form_remojo').each(function() {
						this.reset();
					}); */
				}
			});
			return false;
		});
	});

	$(document).ready(function() {
		$("#form_preblanqueo").submit(function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: "pelambrado_etapa5_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {
					console.log(result);
					data = JSON.parse(result);
					console.log(data);
					if (data["mensaje"] == "Registro realizado") {
						alertas_v5("#alerta-accion_preblanqueo", '', data["mensaje"], 1, true, 5000);
					} else {
						alertas_v5("#alerta-accion_preblanqueo", '', data["mensaje"], 3, true, 5000);
					}
					setTimeout('location.reload()', 1000);
					/* $('#form_remojo').each(function() {
						this.reset();
					}); */
				}
			});
			return false;
		});
	});

	$(document).ready(function() {
		$("#form_descarga").submit(function(e) {
			e.preventDefault();
			var formData = $(this).serialize();

			// Convierte la cadena serializada a un objeto
			let formObject = $(this).serializeArray().reduce(function(obj, item) {
				obj[item.name] = item.value;
				return obj;
			}, {});

			console.log(formObject);
			$.ajax({
				url: "pelambrado_etapa_descarga_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {
					console.log(result);
					data = JSON.parse(result);
					console.log(data);
					if (data["mensaje"] == "Registro realizado") {
						alertas_v5("#alerta-accion_descarga", '', data["mensaje"], 1, true, 5000);
					} else {
						alertas_v5("#alerta-accion_descarga", '', data["mensaje"], 3, true, 5000);
					}
					setTimeout('location.reload()', 1000);
					$('#form_remojo').each(function() {
						this.reset();
					});
				}
			});
			return false;
		});
	});


	/* RENGLONES Y BLOQUEO DE ESTAPAS */
	/* REMOJO - BLOQUEAR FECHA*/
	var ipe_lote3 = $("txt_lote3").val();
	var fe_ini3 = $("#txt_fe_inicio3").val();
	var fe_fin3 = $("#txt_fe_final3").val();
	var obs3 = $("#txt_obs3").val();

	var hora_remojo = $("#txt_hora_termina_remojo").val();

	if (hora_remojo === '' && (ipe_lote3 === '' && fe_ini3 === '' && fe_fin3 === '' && obs3 === '')) {
		$("#txt_hora_termina_remojo").prop('readonly', true);
	} else {
		if (hora_remojo != '') {
			$("#txt_hora_termina_remojo").prop('readonly', true);
		} else {
			$("#txt_hora_termina_remojo").removeAttr('readonly');
		}
	}

	/*PELAMBRE - BLOQUEAR FORMULARIO */
	var botonGuardar = $("#btnGuardar");
	var camposFormulario = $("#form_pelambre input, #form_pelambre select, #form_pelambre textarea");

	// Verifica si el campo requerido está vacío
	if (hora_remojo === "") {
		// Si está vacío, bloquea todos los campos del formulario y desactiva el botón de guardar
		camposFormulario.prop('disabled', true);
		botonGuardar.prop('disabled', true);
	} else {
		// Si no está vacío, desbloquea todos los campos del formulario y activa el botón de guardar
		botonGuardar.prop('disabled', false);
	}


	/* PELAMBRE - BLOQUEAR FECHA, PH, CE*/
	var fe_ini_11 = $("#txt_fe_inicio11").val();
	var fe_fin_11 = $("#txt_fe_final11").val();
	var obs_11 = $("#txt_obs11").val();

	if (fe_ini_11 === '' && fe_fin_11 === '' && obs_11 === '') {
		$("#txt_fe_ter_encalado").prop('readonly', true);
		$("#txt_ph").prop('readonly', true);
		$("#txt_lav").prop('readonly', true);
	}


	/*LAVADOS - BLOQUEAR FORMULARIO */
	var fecha_encalado = $("#txt_fe_ter_encalado").val();
	var ph = $("#txt_ph").val();
	var lav = $("#txt_lav").val();

	var botonGuardar = $("#btnGuardar_lavados");
	var camposFormulario = $("#form_lavado input, #form_lavado select, #form_lavado textarea");

	// Verifica si el campo requerido está vacío
	if (fecha_encalado === "" || ph === "" || lav === "") {
		// Si está vacío, bloquea todos los campos del formulario y desactiva el botón de guardar
		camposFormulario.prop('disabled', true);
		botonGuardar.prop('disabled', true);
	} else {
		// Si no está vacío, desbloquea todos los campos del formulario y activa el botón de guardar
		botonGuardar.prop('disabled', false);
	}

	/*BLANQUEO - BLOQUEAR FORMULARIO */
	var fecha_lavados = $("#txt_fe_ini_lav1").val();

	var botonGuardar = $("#btnGuardar_blanco");
	var camposFormulario = $("#form_preblanqueo input, #form_preblanqueo select, #form_preblanqueo textarea");

	// Verifica si el campo requerido está vacío
	if (fecha_lavados === "") {
		// Si está vacío, bloquea todos los campos del formulario y desactiva el botón de guardar
		camposFormulario.prop('disabled', true);
		botonGuardar.prop('disabled', true);
	} else {
		// Si no está vacío, desbloquea todos los campos del formulario y activa el botón de guardar
		botonGuardar.prop('disabled', false);
	}
	/*DESCARGA - BLOQUEAR FORMULARIO */
	var botonGuardar = $("#btnGuardar_descarga");
	var camposFormulario = $("#form_descarga input, #form_descarga select, #form_descarga textarea");

	var fecha_blanqueo = $("#txt_fe_ini_bla1").val();
	/*  var ph = $("#txt_ph").val();
	 var lav = $("#txt_lav").val(); */

	// Verifica si el campo requerido está vacío
	if (fecha_blanqueo === "") {
		// Si está vacío, bloquea todos los campos del formulario y desactiva el botón de guardar
		camposFormulario.prop('disabled', true);
		botonGuardar.prop('disabled', true);
	} else {
		// Si no está vacío, desbloquea todos los campos del formulario y activa el botón de guardar
		botonGuardar.prop('disabled', false);
	}

	// Definimos una función para procesar los campos
	function procesarCampos(fe_ini, fe_fin, obs, fe_ini_siguiente, fe_fin_siguiente, obs_siguiente) {
		// Bloquear campos del renglón siguiente si los del renglón actual están vacíos
		/* if (fe_ini === "" || fe_fin === "" || obs === "") { */
		if (fe_ini === "" || fe_fin === "") {
			fe_ini_siguiente.prop('readonly', true);
			fe_fin_siguiente.prop('readonly', true);
			obs_siguiente.prop('readonly', true);

			// Hacer campos de renglón actual de solo lectura si están llenos
			if (fe_ini !== "") {
				fe_ini_siguiente.prop('readonly', true);
			}
			if (fe_fin !== "") {
				fe_fin_siguiente.prop('readonly', true);
			}
			if (obs !== "") {
				obs_siguiente.prop('readonly', true);
			}
		}
	}

	// Ejecutamos la función para los campos del primer renglón
	procesarCampos(
		//renglon actual
		$("#txt_fe_inicio1").val(),
		$("#txt_fe_final1").val(),
		$("#txt_obs1").val(),

		//renglon siguiente
		$("#txt_fe_inicio2"),
		$("#txt_fe_final2"),
		$("#txt_obs2"),
	);
	// Ejecutamos la función para los campos del segundo renglón
	procesarCampos(
		//renglon actual
		$("#txt_fe_inicio2").val(),
		$("#txt_fe_final2").val(),
		$("#txt_obs2").val(),

		//renglon siguiente
		$("#txt_fe_inicio3"),
		$("#txt_fe_final3"),
		$("#txt_obs3"),
	);
	// Ejecutamos la función para los campos del segundo renglón
	procesarCampos(
		//renglon actual
		$("#txt_fe_inicio3").val(),
		$("#txt_fe_final3").val(),
		$("#txt_obs3").val(),

		//renglon siguiente
		$("#txt_fe_inicio4"),
		$("#txt_fe_final4"),
		$("#txt_obs4"),
	);
	procesarCampos(
		//renglon actual
		$("#txt_fe_inicio4").val(),
		$("#txt_fe_final4").val(),
		$("#txt_obs4").val(),

		//renglon siguiente
		$("#txt_fe_inicio5"),
		$("#txt_fe_final5"),
		$("#txt_obs5"),
	);
	procesarCampos(
		//renglon actual
		$("#txt_fe_inicio5").val(),
		$("#txt_fe_final5").val(),
		$("#txt_obs5").val(),

		//renglon siguiente
		$("#txt_fe_inicio6"),
		$("#txt_fe_final6"),
		$("#txt_obs6"),
	);
	procesarCampos(
		//renglon actual
		$("#txt_fe_inicio6").val(),
		$("#txt_fe_final6").val(),
		$("#txt_obs6").val(),

		//renglon siguiente
		$("#txt_fe_inicio7"),
		$("#txt_fe_final7"),
		$("#txt_obs7"),
	);
	procesarCampos(
		//renglon actual
		$("#txt_fe_inicio7").val(),
		$("#txt_fe_final7").val(),
		$("#txt_obs7").val(),

		//renglon siguiente
		$("#txt_fe_inicio8"),
		$("#txt_fe_final8"),
		$("#txt_obs8"),
	);
	procesarCampos(
		//renglon actual
		$("#txt_fe_inicio8").val(),
		$("#txt_fe_final8").val(),
		$("#txt_obs8").val(),

		//renglon siguiente
		$("#txt_fe_inicio9"),
		$("#txt_fe_final9"),
		$("#txt_obs9"),
	);
	procesarCampos(
		//renglon actual
		$("#txt_fe_inicio9").val(),
		$("#txt_fe_final9").val(),
		$("#txt_obs9").val(),

		//renglon siguiente
		$("#txt_fe_inicio10"),
		$("#txt_fe_final10"),
		$("#txt_obs10"),
	);
	procesarCampos(
		//renglon actual
		$("#txt_fe_inicio10").val(),
		$("#txt_fe_final10").val(),
		$("#txt_obs10").val(),

		//renglon siguiente
		$("#txt_fe_inicio11"),
		$("#txt_fe_final11"),
		$("#txt_obs11"),
	);

	function procesarCampos2(fe_ini_lav, hora_ini, hora_fin, ph, ce, fe_ini_lav_siguiente, hora_ini_siguiente, hora_fin_siguiente, ph_siguiente, ce_siguiente) {
		// Bloquear campos del renglón siguiente si los del renglón actual están vacíos
		if (fe_ini_lav === "" && hora_ini === "" && hora_fin === "" && ph === "" && ce === "") {
			fe_ini_lav_siguiente.prop('readonly', true);
			hora_ini_siguiente.prop('readonly', true);
			hora_fin_siguiente.prop('readonly', true);
			ph_siguiente.prop('readonly', true);
			ce_siguiente.prop('readonly', true);
		}
	}

	procesarCampos2(
		//renglon actual
		$("#txt_fe_ini_lav1").val(),
		$("#txt_hora_ini1").val(),
		$("#txt_hora_fin1").val(),
		$("#txt_ph_ag1").val(),
		$("#txt_ce_ag1").val(),

		//renglon siguiente
		$("#txt_fe_ini_lav2"),
		$("#txt_hora_ini2"),
		$("#txt_hora_fin2"),
		$("#txt_ph_ag2"),
		$("#txt_ce_ag2"),
	);
	procesarCampos2(
		//renglon actual
		$("#txt_fe_ini_lav2").val(),
		$("#txt_hora_ini2").val(),
		$("#txt_hora_fin2").val(),
		$("#txt_ph_ag2").val(),
		$("#txt_ce_ag2").val(),

		//renglon siguiente
		$("#txt_fe_ini_lav3"),
		$("#txt_hora_ini3"),
		$("#txt_hora_fin3"),
		$("#txt_ph_ag3"),
		$("#txt_ce_ag3"),
	);
	procesarCampos2(
		//renglon actual
		$("#txt_fe_ini_lav3").val(),
		$("#txt_hora_ini3").val(),
		$("#txt_hora_fin3").val(),
		$("#txt_ph_ag3").val(),
		$("#txt_ce_ag3").val(),

		//renglon siguiente
		$("#txt_fe_ini_lav4"),
		$("#txt_hora_ini4"),
		$("#txt_hora_fin4"),
		$("#txt_ph_ag4"),
		$("#txt_ce_ag4"),
	);
</script>