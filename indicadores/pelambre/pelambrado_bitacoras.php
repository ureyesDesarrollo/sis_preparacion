<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
include "../../funciones/funciones_procesos.php";
include "../../seguridad/user_seguridad.php";

$cnx =  Conectarse();

extract($_GET);
?>
<link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
<script src="../../assets/fontawesome/fontawesome.js"></script>
<link rel="stylesheet" href="../../assets/css/indicadores.css">
<script src="../../js/jquery.min.js"></script>
<script src="../../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../js/alerta.js"></script>

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
		background-image: url("../../imagenes/banner_progel2.png");
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

</script>