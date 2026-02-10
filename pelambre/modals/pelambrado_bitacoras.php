<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../../../conexion/conexion.php";
include "../../../funciones/funciones.php";
include "../../../funciones/funciones_procesos.php";
include "../../../seguridad/user_seguridad.php";
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
$cad_equipos = mysqli_query($cnx, "SELECT * FROM equipos_preparacion
WHERE ep_id = '$id_e'") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
$reg_equipos = mysqli_fetch_assoc($cad_equipos);

//selecciona pelambre en proceso
$cad_pelambre = mysqli_query($cnx, "SELECT * FROM inventario_pelambre
WHERE ep_id = '$id_e' and ip_ban = 1") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
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
		Formula de remojo pelambre
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
		<form id="form_remojo">
			<div class="row renglones" id="titulos">
				<input type="hidden" name="hdd_id_pelambre" value="<?php echo $reg_pelambre['ip_id'] ?>">
				<!-- <div class="col-md-1">
					<label for="formFile" class="form-label">Bultos</label>
				</div> -->
				<div class="col-md-1">
					<label for="formFile" class="form-label">%</label>
				</div>
				<div class="col-md-1">
					<label for="formFile" class="form-label">Cantidad</label>
				</div>
				<div class="col-md-1" style="text-align: center;">
					<label for="formFile" class="form-label"></label>
				</div>
				<div class="col-md-1">
					<label for="formFile" class="form-label">Material</label>
				</div>
				<div class="col-md-1">
					<label for="formFile" class="form-label">Horas</label>
				</div>
				<div class="col-md-1">
					<label for="formFile" class="form-label">Minutos</label>
				</div>
				<div class="col-md-2">
					<label for="formFile" class="form-label">Fecha/hora inicio</label>
				</div>
				<div class="col-md-2">
					<label for="formFile" class="form-label">Fecha/hora final</label>
				</div>
				<div class="col-md-2">
					<label for="formFile" class="form-label">Obs</label>
				</div>
			</div>
			<?php
			$etiqueta = 0;
			$material = 0;

			for ($i = 1; $i <= 3; $i++) {

				/* PORCENTAJES */
				if ($i == 1) {
					$porcentaje = "100";
					$cantidad = $inventario['inv_kilos'];
				}
				if ($i == 2) {
					$porcentaje = "0.4";
					$cantidad = $inventario['inv_kilos'] * 0.004;
				}
				if ($i == 3) {
					$porcentaje = "0.2";
					$cantidad = $inventario['inv_kilos'] * 0.002;
				}


				/* ETIQUETAS */
				if ($i == 1) {
					$etiqueta = "Litros";
				} else {
					$etiqueta = "Kilos";
				}

				/* MATERIAL */
				if ($i == 1) {
					$consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'agua'");
					$reg = mysqli_fetch_assoc($consulta);
					$material = $reg['quimico_descripcion'];
				}
				if ($i == 2) {
					$consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'carbonato'");
					$reg = mysqli_fetch_assoc($consulta);
					$material = $reg['quimico_descripcion'];
				}
				if ($i == 3) {
					$consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'Sulfhidrato'");
					$reg = mysqli_fetch_assoc($consulta);
					$material = $reg['quimico_descripcion'];
				}


				/* ETIQUETA TIEMPOS */
				if ($i == 3) {
					$horas = "16 horas";
				}
				if ($i == 3) {
					$minutos = "";
				}
			?>
				<div class="row g-3 align-items-center renglones">
					<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_renglon" . $i ?>" name="<?php echo "txt_renglon" . $i ?>" value="<?php echo $i ?>" readonly>
					<!-- <div class="col-md-1">
						<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_bultos" . $i ?>" name="<?php echo "txt_bultos" . $i ?>">
					</div> -->
					<div class="col-md-1">
						<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_porcentaje" . $i ?>" name="<?php echo "txt_porcentaje" . $i ?>" value="<?php echo $porcentaje ?>" readonly>
					</div>
					<div class="col-md-1">
						<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_cantidad" . $i ?>" name="<?php echo "txt_cantidad" . $i ?>" value="<?php echo $cantidad ?>" readonly>
					</div>
					<div class="col-md-1" style="text-align: center;">
						<label for="inputPassword6" class="col-form-label"><?php echo $etiqueta ?></label>
					</div>
					<div class="col-md-1">
						<input type="hidden" class="form-control" value="<?php echo $reg['quimico_id'] ?>" onkeypress="return isNumberKey(event, this);" id="<?php echo "hdd_id_mat" . $i ?>" name="<?php echo "hdd_id_mat" . $i ?>" readonly>
						<input type="text" class="form-control" value="<?php echo $material ?>" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_quimico" . $i ?>" name="<?php echo "txt_quimico" . $i ?>" readonly>
					</div>
					<div class="col-md-1" style="text-align: center;">
						<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_horas" . $i ?>" name="<?php echo "txt_horas" . $i ?>" value="<?php echo $horas ?>">
						<label for="inputPassword6" class="col-form-label"><?php echo $horas ?></label>
					</div>
					<div class="col-md-1" style="text-align: center;">
						<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_minutos" . $i ?>" name="<?php echo "txt_minutos" . $i ?>">
						<label for="inputPassword6" class="col-form-label"><?php echo $minutos ?></label>
					</div>
					<div class="col-md-2">
						<input type="datetime-local" class="form-control" id="<?php echo "txt_fe_inicio" . $i ?>" name="<?php echo "txt_fe_inicio" . $i ?>">
					</div>
					<div class="col-md-2">
						<input type="datetime-local" class="form-control" id="<?php echo "txt_fe_final" . $i ?>" name="<?php echo "txt_fe_final" . $i ?>">
					</div>
					<div class="col-md-2">
						<input type="text" class="form-control" id="<?php echo "txt_obs" . $i ?>" name="<?php echo "txt_obs" . $i ?>">
					</div>
				</div>
			<?php }

			$renglon += $i;
			?>
			<div class="row renglones">
				<div class="col-md-4">
					<label for="formFile" class="form-label">Hora termina remojo</label>
					<input type="datetime-local" class="form-control" id="<?php echo "txt_hora_termina_remojo" . $i ?>" name="<?php echo "txt_hora_termina_remojo" . $i ?>">
				</div>
				<!--mensajes-->
				<div class="col-md-6">
					<div id="alerta-accion_remojo" class="alert d-none">
						<strong class="alert-heading">¡Error!</strong>
						<span class="alert-body"></span>
					</div>
				</div>
				<div class="col-md-2" id="boton">
					<button style="margin-top:2rem;" type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xl" style="color: #000;"></i> Guardar</button>
				</div>

			</div>
		</form>
	</div>

	<nav aria-label="breadcrumb" style="font-weight:bolder">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active" aria-current="page">ETAPA PELAMBRE</li>
		</ol>
	</nav>
	<div class="row">
		<form id="form_pelambre">
			<div class="row renglones" id="titulos">
				<input type="hidden" name="hdd_id_pelambre" value="<?php echo $reg_pelambre['ip_id'] ?>">
				<!-- <div class="col-md-1">
					<label for="formFile" class="form-label">Bultos</label>
				</div> -->
				<div class="col-md-1">
					<label for="formFile" class="form-label">%</label>
				</div>
				<div class="col-md-1">
					<label for="formFile" class="form-label">Cantidad</label>
				</div>
				<div class="col-md-1" style="text-align: center;">
					<label for="formFile" class="form-label"></label>
				</div>
				<div class="col-md-1">
					<label for="formFile" class="form-label">Material</label>
				</div>
				<div class="col-md-1">
					<label for="formFile" class="form-label">Horas</label>
				</div>
				<div class="col-md-1">
					<label for="formFile" class="form-label">Minutos</label>
				</div>
				<div class="col-md-2">
					<label for="formFile" class="form-label">Fecha/hora inicio</label>
				</div>
				<div class="col-md-2">
					<label for="formFile" class="form-label">Fecha/hora final</label>
				</div>
				<div class="col-md-2">
					<label for="formFile" class="form-label">Obs</label>
				</div>
			</div>
			<?php

			for ($i = $renglon; $i <= 10; $i++) {

				/* PORCENTAJES */
				if ($i == 4) {
					$porcentaje = "50";
					$cantidad = $inventario['inv_kilos'] * 0.50;
				}
				if ($i == 5) {
					$porcentaje = "0.8";
					$cantidad = $inventario['inv_kilos'] * 0.008;
				}
				if ($i == 6 || $i == 9 || $i == 10 || $i == 11) {
					$porcentaje = "1";
					$cantidad = $inventario['inv_kilos'] * 0.01;
				}
				if ($i == 7 || $i == 8) {
					$porcentaje = "0.5";
					$cantidad = $inventario['inv_kilos'] * 0.005;
				}


				/* ETIQUETAS */
				if ($i == 4) {
					$etiqueta = "Litros";
				} else {
					$etiqueta = "Kilos";
				}

				/* MATERIAL */
				if ($i == 4) {
					$consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'agua'");
					$reg = mysqli_fetch_assoc($consulta);
					$material = $reg['quimico_descripcion'];
				}

				if ($i == 5) {
					$consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'Sulfhidrato'");
					$reg = mysqli_fetch_assoc($consulta);
					$material = $reg['quimico_descripcion'];
				}
				if ($i == 6 || $i == 9 || $i == 10) {
					$consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'cal'");
					$reg = mysqli_fetch_assoc($consulta);
					$material = $reg['quimico_descripcion'];
				}
				if ($i == 7 || $i == 8) {
					$consulta =  mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_est = 'P' AND quimico_descripcion = 'Sulfuro'");
					$reg = mysqli_fetch_assoc($consulta);
					$material = $reg['quimico_descripcion'];
				}


				/* ETIQUETA TIEMPOS */
				if ($i == 4) {
					$horas = "";
					$minutos = "";
				}
				if ($i == 5 || $i == 8 || $i == 9) {
					$horas = "";
					$minutos = "90 Minutos";
				}
				if ($i == 6 || $i == 7) {
					$horas = "";
					$minutos = "60 Minutos";
				}
				if ($i == 10) {
					$horas = "3 Horas";
					$minutos = "";
				}
				if ($i == 11) {
					$horas = "2 Horas";
					$minutos = "";
				}
			?><?php if ($i == 4) { ?>
			<div class="row g-3 align-items-center renglones">
				<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_renglon" . $i ?>" name="<?php echo "txt_renglon" . $i ?>" value="<?php echo $i ?>" readonly>
				<!-- <div class="col-md-1">
						<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_bultos" . $i ?>" name="<?php echo "txt_bultos" . $i ?>">
					</div> -->

				<div class="col-md-1">
					<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_porcentaje" . $i ?>" name="<?php echo "txt_porcentaje" . $i ?>" value="<?php echo $porcentaje ?>" readonly>
				</div>

				<div class="col-md-1">
					<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_cantidad" . $i ?>" name="<?php echo "txt_cantidad" . $i ?>" value="<?php echo $cantidad ?>" readonly>
				</div>
				<div class="col-md-1" style="text-align: center;">
					<label for="inputPassword6" class="col-form-label"><?php echo $etiqueta ?></label>
				</div>
				<div class="col-md-1">
					<input type="hidden" class="form-control" value="<?php echo $reg['quimico_id'] ?>" onkeypress="return isNumberKey(event, this);" id="<?php echo "hdd_id_mat" . $i ?>" name="<?php echo "hdd_id_mat" . $i ?>" readonly>
					<input type="text" class="form-control" value="<?php echo $material ?>" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_quimico" . $i ?>" name="<?php echo "txt_quimico" . $i ?>" readonly>
				</div>
				<div class="col-md-1" style="text-align: center;">
					<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_horas" . $i ?>" name="<?php echo "txt_horas" . $i ?>">
					<label for="inputPassword6" class="col-form-label"><?php echo $horas ?></label>
				</div>
				<div class="col-md-1" style="text-align: center;">
					<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_minutos" . $i ?>" name="<?php echo "txt_minutos" . $i ?>">
					<label for="inputPassword6" class="col-form-label"><?php echo $minutos ?></label>
				</div>
				<div class="col-md-2">
					<input type="datetime-local" class="form-control" id="<?php echo "txt_fe_inicio" . $i ?>" name="<?php echo "txt_fe_inicio" . $i ?>">
				</div>
				<div class="col-md-2">
					<input type="datetime-local" class="form-control" id="<?php echo "txt_fe_final" . $i ?>" name="<?php echo "txt_fe_final" . $i ?>">
				</div>
				<div class="col-md-2">
					<label for="formFile" class="form-label" id="etiqueta_niveles">BAJAR NIVEL DE AGUA LO MAS POSIBLE</label>
				</div>
			</div>

		<?php } else { ?>
			<div class="row g-3 align-items-center renglones">
				<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_renglon" . $i ?>" name="<?php echo "txt_renglon" . $i ?>" value="<?php echo $i ?>" readonly>
				<!-- <div class="col-md-1">
						<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_bultos" . $i ?>" name="<?php echo "txt_bultos" . $i ?>">
					</div> -->

				<div class="col-md-1">
					<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_porcentaje" . $i ?>" name="<?php echo "txt_porcentaje" . $i ?>" value="<?php echo $porcentaje ?>" readonly>
				</div>
				<div class=" col-md-1">
					<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_cantidad" . $i ?>" name="<?php echo "txt_cantidad" . $i ?>" value="<?php echo $cantidad ?>" readonly>
				</div>
				<div class="col-md-1" style="text-align: center;">
					<label for="inputPassword6" class="col-form-label"><?php echo $etiqueta ?></label>
				</div>
				<div class="col-md-1">
					<input type="hidden" class="form-control" value="<?php echo $reg['quimico_id'] ?>" onkeypress="return isNumberKey(event, this);" id="<?php echo "hdd_id_mat" . $i ?>" name="<?php echo "hdd_id_mat" . $i ?>" readonly>
					<input type="text" class="form-control" value="<?php echo $material ?>" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_quimico" . $i ?>" name="<?php echo "txt_quimico" . $i ?>" readonly>
				</div>
				<div class="col-md-1" style="text-align: center;">
					<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_horas" . $i ?>" name="<?php echo "txt_horas" . $i ?>">
					<label for="inputPassword6" class="col-form-label"><?php echo $horas ?></label>
				</div>
				<div class="col-md-1" style="text-align: center;">
					<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_minutos" . $i ?>" name="<?php echo "txt_minutos" . $i ?>">
					<label for="inputPassword6" class="col-form-label"><?php echo $minutos ?></label>
				</div>
				<div class="col-md-2">
					<input type="datetime-local" class="form-control" id="<?php echo "txt_fe_inicio" . $i ?>" name="<?php echo "txt_fe_inicio" . $i ?>">
				</div>
				<div class="col-md-2">
					<input type="datetime-local" class="form-control" id="<?php echo "txt_fe_final" . $i ?>" name="<?php echo "txt_fe_final" . $i ?>">
				</div>
				<div class="col-md-2">
					<input type="text" class="form-control" id="<?php echo "txt_obs" . $i ?>" name="<?php echo "txt_obs" . $i ?>">
				</div>
			</div>

	<?php }
			}
			$renglon = $i; ?>

	<div class="row g-3 align-items-center renglones">
		<div class="col-md-10"></div>

		<div class="col-md-2">
			<label for="formFile" class="form-label" id="etiqueta_niveles">CHECAR LIMPIEZA DE PELO</label>
		</div>
	</div>
	<?php
	for ($i = $renglon; $i <= 11; $i++) {
		/* ETIQUETA TIEMPOS */
		if ($i == 11) {
			$horas = "2 Horas";
		}
	?>
		<div class="row g-3 align-items-center renglones">
			<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_renglon" . $i ?>" name="<?php echo "txt_renglon" . $i ?>" value="<?php echo $i ?>" readonly>
			<!-- <div class="col-md-1">
			<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_bultos" . $i ?>" name="<?php echo "txt_bultos" . $i ?>">
		</div> -->

			<div class="col-md-1">
				<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_porcentaje" . $i ?>" name="<?php echo "txt_porcentaje" . $i ?>" value="<?php echo $porcentaje ?>" readonly>
			</div>

			<div class="col-md-1">
				<input type="text" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_cantidad" . $i ?>" name="<?php echo "txt_cantidad" . $i ?>" value="<?php echo $cantidad ?>" readonly>
			</div>
			<div class="col-md-1" style="text-align: center;">
				<label for="inputPassword6" class="col-form-label"><?php echo $etiqueta ?></label>
			</div>
			<div class="col-md-1">
				<input type="hidden" class="form-control" value="<?php echo $reg['quimico_id'] ?>" onkeypress="return isNumberKey(event, this);" id="<?php echo "hdd_id_mat" . $i ?>" name="<?php echo "hdd_id_mat" . $i ?>" readonly>
				<input type="text" class="form-control" value="<?php echo $material ?>" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_quimico" . $i ?>" name="<?php echo "txt_quimico" . $i ?>" readonly>
			</div>
			<div class="col-md-1" style="text-align: center;">
				<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_horas" . $i ?>" name="<?php echo "txt_horas" . $i ?>">
				<label for="inputPassword6" class="col-form-label"><?php echo $horas ?></label>
			</div>
			<div class="col-md-1" style="text-align: center;">
				<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_minutos" . $i ?>" name="<?php echo "txt_minutos" . $i ?>">
				<label for="inputPassword6" class="col-form-label"><?php echo $minutos ?></label>
			</div>
			<div class="col-md-2">
				<input type="datetime-local" class="form-control" id="<?php echo "txt_fe_inicio" . $i ?>" name="<?php echo "txt_fe_inicio" . $i ?>">
			</div>
			<div class="col-md-2">
				<input type="datetime-local" class="form-control" id="<?php echo "txt_fe_final" . $i ?>" name="<?php echo "txt_fe_final" . $i ?>">
			</div>
			<div class="col-md-2">
				<input type="text" class="form-control" id="<?php echo "txt_obs" . $i ?>" name="<?php echo "txt_obs" . $i ?>">
			</div>
		</div>

	<?php } ?>

	<div class="row renglones" style="margin-top: 2rem;">
		<div class="col-md-5">
			<label for="formFile" class="form-label" style="font-weight: bold;">ENCALADO</label>
		</div>
		<div class="col-md-4">
			<label for="formFile" class="form-label" id="etiqueta_niveles">ADICIONAR AGUA HASTA CUBRIR LOS CUEROS</label>
		</div>
	</div>
	<div class="row renglones">
		<div class="col-md-2">
			<label for="formFile" class="form-label">Fecha termina encalado</label>
			<input type="datetime-local" class="form-control" id="inputPassword2">
		</div>
		<div class="col-md-2">
			<label for="formFile" class="form-label">Hora termina encalado</label>
			<input type="time" class="form-control" id="inputPassword2">
		</div>
		<div class="col-md-1">
			<label for="formFile" class="form-label" style="font-weight: bold;">10 horas</label>
		</div>
		<div class="col-md-1">
			<label for="formFile" class="form-label">PH</label>
			<input type="text" class="form-control" id="inputPassword2">
		</div>
		<div class="col-md-1">
			<label for="formFile" class="form-label">Lavado</label>
			<input type="text" class="form-control" id="inputPassword2">
		</div>
		<div class="col" id="boton">
			<button style="margin-top:2rem;" type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xl" style="color: #000;"></i> Guardar</button>
		</div>
	</div>
		</form>
	</div>


	<nav aria-label="breadcrumb" style="font-weight:bolder">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active" aria-current="page">ETAPA DE LAVADOS</li>
		</ol>
	</nav>
	<div class="row">
		<form id="form_lavado">
			<div class="row renglones" id="titulos">
				<div class="col">
					<label for="formFile" class="form-label">Fecha inicio</label>
				</div>
				<div class="col">
					<label for="formFile" class="form-label">Hora inicio</label>
				</div>
				<div class="col">
					<label for="formFile" class="form-label">Hora termino</label>
				</div>
				<div class="col">
					<label for="formFile" class="form-label">PH DEL AGUA</label>
				</div>
				<div class="col">
					<label for="formFile" class="form-label">CE DEL AGUA</label>
				</div>
			</div>
			<?php for ($i = 0; $i <= 5; $i++) {
			?>
				<div class="row renglones">
					<div class="col">
						<input type="date" class="form-control" id="inputPassword2">
					</div>
					<div class="col">
						<input type="time" class="form-control" id="inputPassword2">
					</div>
					<div class="col">
						<input type="time" class="form-control" id="inputPassword2">
					</div>
					<div class="col">
						<input type="text" class="form-control" id="inputPassword2">
					</div>
					<div class="col">
						<input type="text" class="form-control" id="inputPassword2">
					</div>
				</div>
			<?php } ?>
			<div class="row renglones">
				<div class="col-md-10">
				</div>

				<div class="col" id="boton">
					<button style="margin-top:2rem;" type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xl" style="color: #000;"></i> Guardar</button>
				</div>
			</div>
		</form>
	</div>


	<nav aria-label="breadcrumb" style="font-weight:bolder">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active" aria-current="page">ETAPA DE PREBLANQUEO</li>
		</ol>
	</nav>
	<div class="row">
		<form id="form_preblanqueo">
			<div class="row renglones" id="titulos">
				<div class="col">
					<label for="formFile" class="form-label">Fecha inicio</label>
					<input type="date" class="form-control" id="inputPassword2">
				</div>
				<div class="col">
					<label for="formFile" class="form-label">Hora inicio</label>
					<input type="time" class="form-control" id="inputPassword2">
				</div>
				<div class="col">
					<label for="formFile" class="form-label">Hora termino</label>
					<input type="time" class="form-control" id="inputPassword2">
				</div>
				<div class="col-md-1">
					<label for="formFile" class="form-label">PH</label>
					<input type="text" class="form-control" id="inputPassword2">
				</div>
				<div class="col-md-1">
					<label for="formFile" class="form-label">REDOX</label>
					<input type="text" class="form-control" id="inputPassword2">
				</div>
				<div class="col-md-1">
					<label for="formFile" class="form-label">CE</label>
					<input type="text" class="form-control" id="inputPassword2">
				</div>
				<div class="col">
					<label for="formFile" class="form-label">Horas totales proceso</label>
					<input type="text" class="form-control" id="inputPassword2">
				</div>
				<div class="col" id="boton">
					<button style="margin-top:2rem;" type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xl" style="color: #000;"></i> Guardar</button>
				</div>
			</div>
		</form>
	</div>

	<div class="row">
		<form id="form_descarga">
			<div class="row renglones" id="titulos">
				<input type="hidden" name="hdd_id_pelambre" value="<?php echo $reg_pelambre['ip_id'] ?>">
				<input type="hidden" name="hdd_id_inventario" value="<?php echo $reg_pelambre['inv_id'] ?>">
				<input type="hidden" name="hdd_id_equipo" value="<?php echo $reg_equipos['ep_id'] ?>">
				<div class="col-md-3">
					<label for=" formFile" class="form-label">Fecha en que se descargo en patio</label>
					<input type="date" class="form-control" id="inputPassword2" name="txt_fecha_descargo">
				</div>
				<div class="col-md-1">
					<label for="formFile" class="form-label">Kilos finales</label>
					<input type="text" class="form-control" id="inputPassword2" name="txt_kg_totales">
				</div>
				<div class="col-md-3">
					<label for="formFile" class="form-label">Observaciones</label>
					<textarea rows="1" class="form-control" name="txt_observaciones" id=""></textarea>
				</div>
				<div class="col-md-2">
					<label for="recipient-name" class="form-label">Ubicación:</label>

					<select name="cbxUbicacion" class="form-control" id="cbxUbicacion" required="required">
						<option value="">Seleccionar ubicación</option>
						<?php
						$cad_cbx =  mysqli_query($cnx, "SELECT * FROM almacen_cajones WHERE ac_estatus = 'A' AND ac_ban = 'M' ORDER BY ac_descripcion");
						$reg_cbx =  mysqli_fetch_array($cad_cbx);

						do {
						?>
							<option value="<?php echo $reg_cbx['ac_id'] ?>">Cajón <?php echo $reg_cbx['ac_descripcion']; ?></option>
						<?php
						} while ($reg_cbx =  mysqli_fetch_array($cad_cbx));
						?>
					</select>
					<!--</span>-->
				</div>
				<div class="col" id="boton">
					<button style="margin-top:2rem;" type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xl" style="color: #000;"></i> Guardar</button>
				</div>
				<!--mensajes-->
				<div class="col-md-6 p-3">
					<div id="alerta-accion_descarga" class="alert d-none">
						<strong class="alert-heading">¡Error!</strong>
						<span class="alert-body"></span>
					</div>
				</div>
			</div>
		</form>
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
					$('#form_remojo').each(function() {
						this.reset();
					});
				}
			});
			return false;
		});
	});


	$(document).ready(function() {
		$('#form_descarga').submit(function(e) {
			e.preventDefault();
			let formData = $(this).serialize();

			// Convierte la cadena serializada a un objeto
			let formObject = $(this).serializeArray().reduce(function(obj, item) {
				obj[item.name] = item.value;
				return obj;
			}, {});

			console.log(formObject);

			$.ajax({
				url: "pelambrado_etapa_descarga.php",
				type: "POST",
				data: formData,
				success: function(result) {
					data = JSON.parse(result);
					console.log(data);
					if (data["mensaje"] == "Registro realizado") {
						alertas_v5("#alerta-accion_descarga", '', data["mensaje"], 1, true, 5000);
					} else {
						alertas_v5("#alerta-accion_descarga", '', data["mensaje"], 3, true, 5000);
					}
					$('#form_descarga').each(function() {
						this.reset();
					});
				}
			});
		});
	});
</script>