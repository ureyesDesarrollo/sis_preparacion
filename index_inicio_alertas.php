<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
/*Realizado: 21 - Agosto - 2018*/
require_once('conexion/conexion.php');
include "seguridad/user_seguridad.php";
include "funciones/funciones.php";
$cnx = Conectarse();



//fnc_alertas($etapa, $parametro, $proceso, $valor, $usuario, $lavador, $paleto, $tipo);
//echo "alerta 1".fnc_alertas(2, 'ppm', 3510, 2, 17, 2, 0, 'R');//Tipo R, L, M, N
//echo "alerta 1".fnc_alertas(3, 'Hr', 3498, 17, 17, 0, 8, 'R');

$cadena = mysqli_query($cnx, "SELECT usu_usuario 
                FROM usuarios WHERE usu_id =" . $_SESSION['idUsu']) or die(mysql_error() . "Error: en consultar el usuario");
$registros = mysqli_fetch_assoc($cadena);

/*session_start();

if ($_SESSION["autentificado"] != "SI") {
	header("Location: index.php");
	exit();
}*/

//Selección de etapas a monitorear por parametros por renglon
$cad_etapas_ren = mysqli_query($cnx, "select pep_id, pe_id, pep_nombre, pep_tipo, pep_inicio, pep_fin, pep_tabla, pep_columna, pep_tabla_p, pep_columna_p from preparacion_etapas_param where pep_control_renglon = 1 and pep_tipo <> 'N' ORDER BY pep_nombre") or die(mysql_error() . "Error: en consultar las etapas ren");
$reg_etapas_ren = mysqli_fetch_assoc($cad_etapas_ren);

//Selección de etapas a monitorear por parametros por tipo n
$cad_etapas_n = mysqli_query($cnx, "select pep_id, pe_id, pep_nombre, pep_tipo, pep_inicio, pep_fin, pep_tabla, pep_columna, pep_tabla_p, pep_columna_p from preparacion_etapas_param where pep_control_renglon = 1 and pep_tipo = 'N' ORDER BY pep_nombre") or die(mysql_error() . "Error: en consultar las etapas n");
$reg_etapas_n = mysqli_fetch_assoc($cad_etapas_n);

//Selección por materiales
$cad_etapas_mat = mysqli_query($cnx, "select pep_id, pe_id, pep_nombre, pep_tipo, pep_inicio, pep_fin, pep_tabla, pep_columna, pep_tabla_p, pep_columna_p from preparacion_etapas_param where pep_control_renglon = 1 and pep_control_material = '1' ORDER BY pep_nombre") or die(mysql_error() . "Error: en consultar las etapas n");
$reg_etapas_mat = mysqli_fetch_assoc($cad_etapas_mat);

//Selección de etapas a monitorear por parametros por liberación
$cad_etapas = mysqli_query($cnx, "select pep_id, pe_id, pep_nombre, pep_tipo, pep_inicio, pep_fin from preparacion_etapas_param where pep_control_lib = 1 ORDER BY pep_nombre") or die(mysql_error() . "Error: en consultar las etapas liberación");
$reg_etapas = mysqli_fetch_assoc($cad_etapas);

$hr = 1;

$fe_hr = date("Y-m-d H:i:s", strtotime("- {$hr} Hour"));

$cad_alerta = mysqli_query($cnx, "select * from bitacora_alertas WHERE ba_fecha >= '" . $fe_hr . "' ORDER BY ba_id desc ") or die(mysql_error() . "Error: en consultar los procesos asignados");
$reg_alerta = mysqli_fetch_assoc($cad_alerta);
$tot_alerta = mysqli_num_rows($cad_alerta);
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Sistema de Preparación Progel</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/estilos_menu_general.css">
	<link rel="icon" type="image/png" sizes="32x32" href="imagenes/favicon-32x32.png">
	<script src="assets/fontawesome/fontawesome.js"></script>

	<script language="javascript">
		// función encargada de la redirección
		/*	function redireccion() {
				window.location = "index.php";
			}
			
			// se llamará a la función que redirecciona después de 10 minutos (600.000 segundos)
			var temp = setTimeout(redireccion, 600000);
			
			// cuando se pulse en cualquier parte del documento
			document.addEventListener("click", function() {
				// borrar el temporizador que redireccionaba
				clearTimeout(temp);
				// y volver a iniciarlo
				temp = setTimeout(redireccion, 600000);
			})*/

		function ver_historial(pe_id) {
			var datos = {
				"pep_id": pe_id,
			}
			$.ajax({
				type: 'POST',
				url: 'alertas/historial_etapa_procesos.php',
				data: datos,
				success: function(result) {
					$("#modal_ver_historial").html(result);
					$("#modal_ver_historial").modal('show');
				}
			});
			return false;
		}

		function ver_historial_ren(pe_id) {
			var datos = {
				"pep_id": pe_id,
			}
			$.ajax({
				type: 'POST',
				url: 'alertas/historial_ren_procesos.php',
				data: datos,
				success: function(result) {
					$("#modal_ver_historial").html(result);
					$("#modal_ver_historial").modal('show');
				}
			});
			return false;
		}

		function ver_historial_mat(pe_id, mez_id) {
			var datos = {
				"pep_id": pe_id,
				"mez_id": mez_id,
			}
			$.ajax({
				type: 'POST',
				url: 'alertas/historial_mat_procesos.php',
				data: datos,
				success: function(result) {
					$("#modal_ver_historial").html(result);
					$("#modal_ver_historial").modal('show');
				}
			});
			return false;
		}
	</script>
</head>

<body>
	<div id="salir"></div>
	<nav class="navbar navbar" style="background: #333333">
		<div class="container-fluid">
			<div class="col-md-3">
				<img src="imagenes/logo_progel_v5.png" alt="Progel Mexicana">
			</div>
			<div class="col-md-9">

				<ul class="navbar-nav">
					<li class="active"><a href="index_inicio.php"><i class="fa-solid fa-house"></i> Inicio</a></li>
					<li class=""><a href="catalogos/submenu_catalogos.php"><i class="fa-solid fa-folder-tree"></i> Catálogos</a></li>
					<li class=""><a href="modulos/submenu_funciones.php"><i class="fa-solid fa-gears"></i> Funciones</a></li>
					<li class=""><a href="reportes/submenu_reportes.php"><i class="fa-solid fa-file-lines"></i> Reportes</a></li>
					<li class=""><a href="indicadores/index.php" target="_blank"><i class="fa-solid fa-shapes"></i> Indicadores</a></li>
				</ul>
				<ul class="navbar-nav navbar-right">
					<li class="">
						<?php if ($tot_alerta > 0) { ?>
							<a href="#" style="color: #F1F0EF">
								<img src="iconos/alarma.gif" id="img" alt="" width="40px" height="40px">
								¡ Alerta !
								<img src="iconos/alarma.gif" id="img" alt="" width="40px" height="40px">
							</a>
						<?php } ?>
					</li>
					<?php
					if ($_SESSION['privilegio'] == 7) {
						$str_manual = "manual_almacen.pdf";
					}
					if ($_SESSION['privilegio'] == 10) {
						$str_manual = "manual_aseguramiento.pdf";
					}
					if ($_SESSION['privilegio'] == 9) {
						$str_manual = "manual_estadistica.pdf";
					}
					if ($_SESSION['privilegio'] == 6) {
						$str_manual = "manual_laboratorio.pdf";
					}
					if ($_SESSION['privilegio'] == 3) {
						$str_manual = "manual_operador.pdf";
					}
					if ($_SESSION['privilegio'] == 4) {
						$str_manual = "manual_supervisor.pdf";
					}

					if ($_SESSION['privilegio'] == 7 or $_SESSION['privilegio'] == 10 or $_SESSION['privilegio'] == 9 or $_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 3 or $_SESSION['privilegio'] == 4) {
					?>
						<li class=""><a href="../manuales_pdf/<?php echo $str_manual; ?>" style="color: #F1F0EF"><img src="iconos/info.png" id="img" alt="">Ayuda</a></li><?php } ?>
					<li class=""><a href="seguridad/salir.php" style="color: #F1F0EF"><i class="fa-solid fa-user"></i>Cerrar sesión</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<body>

		<div class="col-md-12" style="background: -webkit-linear-gradient(#eee, #333);-webkit-background-clip: text;-webkit-text-fill-color: transparent;text-align: center;margin-bottom: 20px">

			<h2>¡ Buen día <?php echo $registros['usu_usuario'] ?> !<br> Bienvenido al sistema para el control de preparación</h2>

		</div>

		<?php if ($tot_alerta > 0) { ?>
			<div class="col-md-12 alert alert-danger" style="text-align: center;">
				¡Alerta! Parametros fuera de rango en la ultima hora
			</div>
		<?php } ?>

		<div class="col-md-12">
			<div class="row">
				<div class="col-md-4" style="background: -webkit-linear-gradient(#eee, #F91F05);-webkit-background-clip: text;-webkit-text-fill-color: transparent;text-align: center;">
					<h3>Tiempo desde ultimo LOTE</h3>
				</div>
				<div class="col-md-4" style="background: -webkit-linear-gradient(#eee, #F91F05);-webkit-background-clip: text;-webkit-text-fill-color: transparent;text-align: center;">
					<h3>Tiempo de ultima captura en laboratorio</h3>
				</div>
				<div class="col-md-4" style="background: -webkit-linear-gradient(#eee, #F91F05);-webkit-background-clip: text;-webkit-text-fill-color: transparent;text-align: center;">
					<h3>Procesos en Receptores</h3>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="row">
				<div class="col-md-4" style="text-align: center;">
					<?php include "alertas/tablero_creacion_lote.php"; ?>
				</div>
				<div class="col-md-4" style="text-align: center;">
					<?php include "alertas/tablero_laboratorio.php"; ?>
				</div>
				<div class="col-md-4" style="text-align: center;">
					<?php include "alertas/tablero_receptores.php"; ?>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6" style="background: -webkit-linear-gradient(#eee, #F91F05);-webkit-background-clip: text;-webkit-text-fill-color: transparent;text-align: center;text-transform: uppercase;">

					<h3>Monitoreo de etapas en procesos por renglon</h3>

				</div>
				<div class="col-md-6" style="background: -webkit-linear-gradient(#eee, #F91F05);-webkit-background-clip: text;-webkit-text-fill-color: transparent;text-align: center;text-transform: uppercase;">

					<h3>Monitoreo de etapas en procesos de liberación</h3>

				</div>
			</div>
			<div class="row">
				<div class="col-md-6" style="margin-top: 20px">
					<table class="table table-striped">
						<thead>
							<tr>
								<th scope="col">Clave</th>
								<th scope="col">Etapa</th>
								<th scope="col">Parametro</th>
								<th scope="col">R. ini</th>
								<th scope="col">R. fin</th>
								<th scope="col">
									<table>
										<tr>
											<th width="40">&nbsp;#&nbsp;</th>
											<th width="60">&nbsp;Proceso&nbsp;</th>
											<th width="70">&nbsp;Paleto&nbsp;</th>
											<th width="70">&nbsp;Lavador&nbsp;</th>
											<th width="40">&nbsp;Valor&nbsp;</th>
										</tr>
									</table>
								</th>
								<th scope="col">Historial</th>
							</tr>
						</thead>
						<tbody>

							<?php do {

								//Obtiente los procesos de lavadores
								$cad_pro_lav = mysqli_query($cnx, "SELECT p.pro_id, p.pt_id, t.pt_descripcion, t.pt_revision, p.pro_operador, p.pro_supervisor, p.pro_fe_carga, p.pro_hr_inicio, p.pl_id    
							   FROM procesos as p
							   INNER JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 
							   INNER JOIN preparacion_tipo_etapas As e ON (t.pt_id = e.pt_id)
							   WHERE p.pro_estatus = 1 and e.pe_id = '$reg_etapas_ren[pe_id]' and pl_id <> 0");
								$reg_pro_lav = mysqli_fetch_array($cad_pro_lav);


								//Obtiene los procesos de paletos ejecutandose actualmente

								$cad_procesos = mysqli_query($cnx, "SELECT p.pro_id, p.pt_id, t.pt_descripcion, t.pt_revision, p.pro_operador, p.pro_supervisor, p.pro_fe_carga, p.pro_hr_inicio, x.prop_id, x.prop_directo, x.pt_id as pt2, x.pp_id    
							   FROM procesos as p
							   INNER JOIN procesos_paletos_d As d ON(p.pro_id = d.pro_id)
 							   INNER JOIN procesos_paletos as x ON(d.prop_id = x.prop_id)
							   INNER JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id)
							   INNER JOIN preparacion_tipo_etapas As e ON (t.pt_id = e.pt_id)
							   WHERE  x.prop_estatus = 1 and e.pe_id = '$reg_etapas_ren[pe_id]' and pp_id > 2") or die(mysql_error() . "Error: en consultar el proces");
								$reg_procesos = mysqli_fetch_assoc($cad_procesos);

							?>
								<tr>
									<th scope="row"><?php echo $reg_etapas_ren['pe_id']; ?></th>
									<td><?php echo $reg_etapas_ren['pep_nombre']; ?></td>
									<td><?php echo $reg_etapas_ren['pep_tipo']; ?></td>
									<td><?php echo $reg_etapas_ren['pep_inicio']; ?></td>
									<td><?php echo $reg_etapas_ren['pep_fin']; ?></td>
									<td>
										<table>
											<?php $cont = 1;
											$dato = 0;
											do {
												//Consulta parametros de los renglones
												/*echo "select ".$reg_etapas_ren['pep_columna']." from ".$reg_etapas_ren['pep_tabla']." as d 
							inner join ".$reg_etapas_ren['pep_tabla_p']." as g on (g.".$reg_etapas_ren['pep_columna_p']." = d.".$reg_etapas_ren['pep_columna_p'].")
							where ".$reg_etapas_ren['pep_columna']." < '$reg_etapas_ren[pep_inicio]' or ".$reg_etapas_ren['pep_columna']." > '$reg_etapas_ren[pep_fin]' and pro_id = '$reg_pro_lav[pro_id]' ";*/
												$cad_renglones = mysqli_query($cnx, "select " . $reg_etapas_ren['pep_columna'] . " as val from " . $reg_etapas_ren['pep_tabla'] . " as d 
							inner join " . $reg_etapas_ren['pep_tabla_p'] . " as g on (g." . $reg_etapas_ren['pep_columna_p'] . " = d." . $reg_etapas_ren['pep_columna_p'] . ")
							where (" . $reg_etapas_ren['pep_columna'] . " < '$reg_etapas_ren[pep_inicio]' or " . $reg_etapas_ren['pep_columna'] . " > '$reg_etapas_ren[pep_fin]') and pro_id = '$reg_pro_lav[pro_id]' ")
													or die(mysqli_error($cnx) . "Error: en consultar los renglones x");
												$reg_renglones = mysqli_fetch_assoc($cad_renglones);

												$cad_lav = mysqli_query($cnx, "SELECT pl_descripcion FROM preparacion_lavadores WHERE pl_id = '$reg_pro_lav[pl_id]' ") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
												$reg_lav = mysqli_fetch_assoc($cad_lav);

												//$dato = $reg_renglones[fnc_tipo_campo($reg_etapas_ren['pep_tipo'])];
												$dato = $reg_renglones['val'];

												//
												//($dato == 0 and $reg_etapas_ren['pep_tipo'] == 'ppm') or $dato != ''
											?>
												<tr <?php
													if ($dato != 0 or $dato != '') {
														//echo "style='background:#FC8C8C;color:#fff'";
														if ($dato == 0.00 and $reg_etapas_ren['pep_tipo'] != 'ppm') {
															echo "style='background:#ffffff'";
														} else {
															echo "style='background:#FC8C8C; color:#fff'";
														}
													} else {
														echo "bgcolor='#ffffff'";
													} ?>>
													<td width="40"><?php echo $cont; ?></td>
													<td width="60"><?php echo $reg_pro_lav['pro_id']; ?></td>
													<td width="70"><?php echo "--"; ?></td>
													<td width="70"><?php echo $reg_lav['pl_descripcion']; ?></td>
													<td align="center" width="40"><?php echo $dato ?></td>
												</tr>
											<?php
												$cont += 1;
											} while ($reg_pro_lav = mysqli_fetch_array($cad_pro_lav));
											do {

												//Consulta parametros de los renglones

												$cad_renglones = mysqli_query($cnx, "select " . $reg_etapas_ren['pep_columna'] . " as val from " . $reg_etapas_ren['pep_tabla'] . " as d 
							inner join " . $reg_etapas_ren['pep_tabla_p'] . " as g on (g." . $reg_etapas_ren['pep_columna_p'] . " = d." . $reg_etapas_ren['pep_columna_p'] . ")
							where (" . $reg_etapas_ren['pep_columna'] . " < '$reg_etapas_ren[pep_inicio]' or " . $reg_etapas_ren['pep_columna'] . " > '$reg_etapas_ren[pep_fin]') and pro_id = '$reg_procesos[pro_id]' ")
													or die(mysqli_error($cnx) . "Error: en consultar los renglones x");
												$reg_renglones = mysqli_fetch_assoc($cad_renglones);

												$cad_pal = mysqli_query($cnx, "SELECT pp_descripcion FROM preparacion_paletos WHERE pp_id = '$reg_procesos[pp_id]' ") or die(mysql_error() . "Error: en consultar los paletos");
												$reg_pal = mysqli_fetch_assoc($cad_pal);

												$dato = $reg_renglones['val'];
											?>
												<tr <?php //if($dato != 0 or $dato != ''){echo "style='background:#FC8C8C;color:#fff'";}else{echo "bgcolor='#ffffff'";} 
													if ($dato != 0 or $dato != '') {
														//echo "style='background:#FC8C8C;color:#fff'";
														if ($dato == 0.00 and $reg_etapas_ren['pep_tipo'] != 'ppm') {
															echo "style='background:#ffffff'";
														} else {
															echo "style='background:#FC8C8C; color:#fff'";
														}
													} else {
														echo "bgcolor='#ffffff'";
													}
													?>>
													<td><?php echo $cont; ?></td>
													<td><?php echo $reg_procesos['pro_id']; ?></td>
													<td><?php echo $reg_pal['pp_descripcion']; ?></td>
													<td><?php echo "--"; ?></td>
													<td align="center"><?php echo $dato ?></td>
												</tr>
											<?php
												$cont += 1;
											} while ($reg_procesos = mysqli_fetch_assoc($cad_procesos)); ?>
										</table>
									</td>
									<td>
										<a href="#" onClick="javascript:ver_historial_ren(<?php echo $reg_etapas_ren['pep_id'] ?>);"><img src="iconos/movimientos.png" alt=""></a>
									</td>
								</tr>
							<?php } while ($reg_etapas_ren = mysqli_fetch_assoc($cad_etapas_ren)); ?>
						</tbody>
					</table>

				</div>

				<div class="col-md-6" style="margin-top: 20px;">
					<table class="table table-striped">
						<thead>
							<tr>
								<th scope="col">Clave</th>
								<th scope="col">Etapa</th>
								<th scope="col">Parametro</th>
								<th scope="col">R. ini</th>
								<th scope="col">R. fin</th>
								<th scope="col">
									<table>
										<tr>
											<th width="40">&nbsp;#&nbsp;</th>
											<th width="60">&nbsp;Proceso&nbsp;</th>
											<th width="70">&nbsp;Paleto&nbsp;</th>
											<th width="70">&nbsp;Lavador&nbsp;</th>
											<th width="40">&nbsp;Valor&nbsp;</th>
										</tr>
									</table>
								</th>
								<th scope="col">Historial</th>
							</tr>
						</thead>
						<tbody>

							<?php do {

								//Obtiente los procesos de lavadores
								$cad_pro_lav = mysqli_query($cnx, "SELECT p.pro_id, p.pt_id, t.pt_descripcion, t.pt_revision, p.pro_operador, p.pro_supervisor, p.pro_fe_carga, p.pro_hr_inicio, p.pl_id    
							   FROM procesos as p
							   INNER JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 
							   INNER JOIN preparacion_tipo_etapas As e ON (t.pt_id = e.pt_id)
							   WHERE p.pro_estatus = 1 and e.pe_id = '$reg_etapas[pe_id]' and pl_id <> 0");
								$reg_pro_lav = mysqli_fetch_array($cad_pro_lav);


								//Obtiene los procesos de paletos ejecutandose actualmente

								$cad_procesos = mysqli_query($cnx, "SELECT p.pro_id, p.pt_id, t.pt_descripcion, t.pt_revision, p.pro_operador, p.pro_supervisor, p.pro_fe_carga, p.pro_hr_inicio, x.prop_id, x.prop_directo, x.pt_id as pt2, x.pp_id    
							   FROM procesos as p
							   INNER JOIN procesos_paletos_d As d ON(p.pro_id = d.pro_id)
 							   INNER JOIN procesos_paletos as x ON(d.prop_id = x.prop_id)
							   INNER JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id)
							   INNER JOIN preparacion_tipo_etapas As e ON (t.pt_id = e.pt_id)
							   WHERE  x.prop_estatus = 1 and e.pe_id = '$reg_etapas[pe_id]' and pp_id > 2") or die(mysql_error() . "Error: en consultar el proces");
								$reg_procesos = mysqli_fetch_assoc($cad_procesos);



							?>
								<tr>
									<th scope="row"><?php echo $reg_etapas['pe_id']; ?></th>
									<td><?php echo $reg_etapas['pep_nombre']; ?></td>
									<td><?php echo $reg_etapas['pep_tipo']; ?></td>
									<td><?php echo $reg_etapas['pep_inicio']; ?></td>
									<td><?php echo $reg_etapas['pep_fin']; ?></td>
									<td>
										<table>
											<?php $cont = 1;
											do {
												//Consulta parametros de liberación
												$cad_parametros = mysqli_query($cnx, "select " . fnc_tipo_campo($reg_etapas['pep_tipo']) . " from procesos_liberacion 
							where pe_id = '$reg_etapas[pe_id]' and (" . fnc_tipo_campo($reg_etapas['pep_tipo']) . " < '$reg_etapas[pep_inicio]' or " . fnc_tipo_campo($reg_etapas['pep_tipo']) . " > '$reg_etapas[pep_fin]') and pro_id = '$reg_pro_lav[pro_id]' ")
													or die(mysql_error() . "Error: en consultar el proces");
												$reg_parametros = mysqli_fetch_assoc($cad_parametros);

												$cad_lav = mysqli_query($cnx, "SELECT pl_descripcion FROM preparacion_lavadores WHERE pl_id = '$reg_pro_lav[pl_id]' ") or die(mysql_error() . "Error: en consultar los paletos");
												$reg_lav = mysqli_fetch_assoc($cad_lav);

												$dato = $reg_parametros[fnc_tipo_campo($reg_etapas['pep_tipo'])];

											?>
												<tr <?php //if($dato != 0 or $dato != ''){echo "style='background:#FC8C8C;color:#fff'";}else{echo "bgcolor='#ffffff'";} 
													if ($dato != 0 or $dato != '') {
														//echo "style='background:#FC8C8C;color:#fff'";
														if ($dato == 0.00 and $reg_etapas['pep_tipo'] != 'ppm') {
															echo "style='background:#ffffff'";
														} else {
															echo "style='background:#FC8C8C; color:#fff'";
														}
													} else {
														echo "bgcolor='#ffffff'";
													}
													?>>
													<td width="40"><?php echo $cont; ?></td>
													<td width="60"><?php echo $reg_pro_lav['pro_id']; ?></td>
													<td width="70"><?php echo "--"; ?></td>
													<td width="70"><?php echo $reg_lav['pl_descripcion']; ?></td>
													<td align="center" width="40"><?php echo $dato ?></td>
												</tr>
											<?php
												$cont += 1;
											} while ($reg_pro_lav = mysqli_fetch_array($cad_pro_lav));
											do {
												//Consulta parametros de liberación
												$cad_parametros = mysqli_query($cnx, "select " . fnc_tipo_campo($reg_etapas['pep_tipo']) . " from procesos_liberacion 
							where pe_id = '$reg_etapas[pe_id]' and (" . fnc_tipo_campo($reg_etapas['pep_tipo']) . " < '$reg_etapas[pep_inicio]' or " . fnc_tipo_campo($reg_etapas['pep_tipo']) . " > '$reg_etapas[pep_fin]') and pro_id = '$reg_procesos[pro_id]' ")
													or die(mysql_error() . "Error: en consultar el proces");
												$reg_parametros = mysqli_fetch_assoc($cad_parametros);

												$cad_pal = mysqli_query($cnx, "SELECT pp_descripcion FROM preparacion_paletos WHERE pp_id = '$reg_procesos[pp_id]' ") or die(mysql_error() . "Error: en consultar los paletos");
												$reg_pal = mysqli_fetch_assoc($cad_pal);

												$dato = $reg_parametros[fnc_tipo_campo($reg_etapas['pep_tipo'])];
											?>
												<tr <?php //if($dato != 0 or $dato != ''){echo "style='background:#FC8C8C;color:#fff'";}else{echo "bgcolor='#ffffff'";}
													if ($dato != 0 or $dato != '') {
														//echo "style='background:#FC8C8C;color:#fff'";
														if ($dato == 0.00 and $reg_etapas['pep_tipo'] != 'ppm') {
															echo "style='background:#ffffff'";
														} else {
															echo "style='background:#FC8C8C; color:#fff'";
														}
													} else {
														echo "bgcolor='#ffffff'";
													}
													?>>
													<td><?php echo $cont; ?></td>
													<td><?php echo $reg_procesos['pro_id']; ?></td>
													<td><?php echo $reg_pal['pp_descripcion']; ?></td>
													<td><?php echo "--"; ?></td>
													<td align="center"><?php echo $dato ?></td>
												</tr>
											<?php
												$cont += 1;
											} while ($reg_procesos = mysqli_fetch_assoc($cad_procesos)); ?>
										</table>
									</td>
									<td>
										<a href="#" onClick="javascript:ver_historial(<?php echo $reg_etapas['pep_id'] ?>);"><img src="iconos/movimientos.png" alt=""></a>
									</td>
								</tr>
							<?php } while ($reg_etapas = mysqli_fetch_assoc($cad_etapas)); ?>
						</tbody>
					</table>

				</div>
			</div>

			<div class="col-md-12">
				<div class="row">
					<div class="col-md-6" style="background: -webkit-linear-gradient(#eee, #F91F05);-webkit-background-clip: text;-webkit-text-fill-color: transparent;text-align: center;text-transform: uppercase;">

						<h3>Monitoreo de etapas en procesos por materiales</h3>

					</div>
					<div class="col-md-6" style="background: -webkit-linear-gradient(#eee, #F91F05);-webkit-background-clip: text;-webkit-text-fill-color: transparent;text-align: center;text-transform: uppercase;">

						<h3>Monitoreo de etapas en procesos de renglón N </h3>

					</div>
				</div>
				<div class="row">

					<!-- Revisión de procesos y materiales -->
					<div class="col-md-6" style="margin-top: 20px">
						<table class="table table-striped" border="1px">
							<thead>
								<tr>
									<th scope="col">Clave</th>
									<th scope="col">Etapa</th>
									<th scope="col">Parametro</th>
									<th scope="col">R. ini</th>
									<th scope="col">R. fin</th>
									<th scope="col">Mezcla</th>
									<th scope="col">Materiales</th>
									<th scope="col">Historial</th>
								</tr>
							</thead>
							<tbody>

								<?php do {  ?>
									<tr>
										<th scope="row"><?php echo $reg_etapas_mat['pe_id']; ?></th>
										<td><?php echo $reg_etapas_mat['pep_nombre']; ?></td>
										<td><?php echo $reg_etapas_mat['pep_tipo']; ?></td>
										<td><?php echo $reg_etapas_mat['pep_inicio']; ?></td>
										<td><?php echo $reg_etapas_mat['pep_fin']; ?></td>
										<td>-</td>
										<td>-</td>
										<td>-</td>
									</tr>
									<?php
									//Consulta la Mezcla
									$cad_mez = mysqli_query($cnx, "SELECT m.*, x.mez_nombre, x.mez_id  
									FROM preparacion_etapas_mezclas as m
									LEFT JOIN mezclas as x on (m.mez_id = x.mez_id)
									WHERE m.pep_id = '$reg_etapas_mat[pep_id]' ") or die(mysql_error() . "Error: en consultar las mezclas");
									$reg_mez = mysqli_fetch_assoc($cad_mez);
									$tot_mez = mysqli_num_rows($cad_mez);

									if ($tot_mez > 0) {
										do {

											echo "<tr><td colspan='5'></td><td>" . $reg_mez['mez_nombre'] . "</td><td></td><td>";
											echo '<a href="#" onClick="javascript:ver_historial_mat(' . $reg_etapas_mat['pep_id'] . ', ' . $reg_mez['mez_id'] . ');"><img src="iconos/movimientos.png" alt=""></a>';
											echo "</td></tr>";

											//Consulta los materiales de la mezcla
											$cad_mat = mysqli_query($cnx, "SELECT DISTINCT m.mat_id, m.mat_nombre 
									FROM mezclas_materiales as x
									LEFT JOIN materiales as m on (x.mat_id = m.mat_id)
									WHERE x.mez_id = '$reg_mez[mez_id]' 
									ORDER BY m.mat_id
									") or die(mysql_error() . "Error: en consultar las mezclas");
											$reg_mat = mysqli_fetch_assoc($cad_mat);

											//$ids = '';

											//echo "(";
											do {
												echo "<tr><td colspan='6'></td><td>" . $reg_mat['mat_nombre'] . "</td><td colspan='2'></td>"; //."<br>"
												//$ids .= $reg_mat['mat_id'];
											} while ($reg_mat = mysqli_fetch_assoc($cad_mat));
											//echo ")";

											//echo "";

										} while ($reg_mez = mysqli_fetch_assoc($cad_mez));
									}

									?>
								<?php } while ($reg_etapas_mat = mysqli_fetch_assoc($cad_etapas_mat)); ?>
							</tbody>
						</table>

					</div>

					<!-- Revisión de etapas y campo N -->
					<div class="col-md-6" style="margin-top: 20px">
						<table class="table table-striped">
							<thead>
								<tr>
									<th scope="col">Clave</th>
									<th scope="col">Etapa</th>
									<th scope="col">Parametro</th>
									<th scope="col">R. ini</th>
									<th scope="col">R. fin</th>
									<th scope="col">
										<table>
											<tr>
												<th width="40">&nbsp;#&nbsp;</th>
												<th width="60">&nbsp;Proceso&nbsp;</th>
												<th width="70">&nbsp;Paleto&nbsp;</th>
												<th width="70">&nbsp;Lavador&nbsp;</th>
												<th width="40">&nbsp;Valor&nbsp;</th>
											</tr>
										</table>
									</th>
									<th scope="col">Historial</th>
								</tr>
							</thead>
							<tbody>

								<?php do {

									//Obtiente los procesos de lavadores
									$cad_pro_lav = mysqli_query($cnx, "SELECT p.pro_id, p.pt_id, t.pt_descripcion, t.pt_revision, p.pro_operador, p.pro_supervisor, p.pro_fe_carga, p.pro_hr_inicio, p.pl_id    
							   FROM procesos as p
							   INNER JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id) 
							   INNER JOIN preparacion_tipo_etapas As e ON (t.pt_id = e.pt_id)
							   WHERE p.pro_estatus = 1 and e.pe_id = '$reg_etapas_n[pe_id]' and pl_id <> 0");
									$reg_pro_lav = mysqli_fetch_array($cad_pro_lav);


									//Obtiene los procesos de paletos ejecutandose actualmente

									$cad_procesos = mysqli_query($cnx, "SELECT p.pro_id, p.pt_id, t.pt_descripcion, t.pt_revision, p.pro_operador, p.pro_supervisor, p.pro_fe_carga, p.pro_hr_inicio, x.prop_id, x.prop_directo, x.pt_id as pt2, x.pp_id    
							   FROM procesos as p
							   INNER JOIN procesos_paletos_d As d ON(p.pro_id = d.pro_id)
 							   INNER JOIN procesos_paletos as x ON(d.prop_id = x.prop_id)
							   INNER JOIN preparacion_tipo AS t ON(p.pt_id = t.pt_id)
							   INNER JOIN preparacion_tipo_etapas As e ON (t.pt_id = e.pt_id)
							   WHERE  x.prop_estatus = 1 and e.pe_id = '$reg_etapas_n[pe_id]' and pp_id > 2") or die(mysql_error() . "Error: en consultar el proces");
									$reg_procesos = mysqli_fetch_assoc($cad_procesos);

								?>
									<tr>
										<th scope="row"><?php echo $reg_etapas_n['pe_id']; ?></th>
										<td><?php echo $reg_etapas_n['pep_nombre']; ?></td>
										<td><?php echo $reg_etapas_n['pep_tipo']; ?></td>
										<td><?php echo $reg_etapas_n['pep_inicio']; ?></td>
										<td><?php echo $reg_etapas_n['pep_fin']; ?></td>
										<td>
											<table>
												<?php $cont = 1;
												do {
													//Consulta parametros de los renglones
													/*echo "select ".$reg_etapas_ren['pep_columna']." from ".$reg_etapas_ren['pep_tabla']." as d 
							inner join ".$reg_etapas_ren['pep_tabla_p']." as g on (g.".$reg_etapas_ren['pep_columna_p']." = d.".$reg_etapas_ren['pep_columna_p'].")
							where ".$reg_etapas_ren['pep_columna']." < '$reg_etapas_ren[pep_inicio]' or ".$reg_etapas_ren['pep_columna'];*/
													$cad_renglones = mysqli_query($cnx, "select " . $reg_etapas_n['pep_columna'] . " as val from " . $reg_etapas_n['pep_tabla'] . " as d 
							inner join " . $reg_etapas_n['pep_tabla_p'] . " as g on (g." . $reg_etapas_n['pep_columna_p'] . " = d." . $reg_etapas_n['pep_columna_p'] . ")
							where (" . $reg_etapas_n['pep_columna'] . " < '$reg_etapas_n[pep_inicio]' or " . $reg_etapas_n['pep_columna'] . " > '$reg_etapas_n[pep_fin]') and pro_id = '$reg_pro_lav[pro_id]' ")
														or die(mysqli_error($cnx) . "Error: en consultar los renglones x");
													$reg_renglones = mysqli_fetch_assoc($cad_renglones);

													$cad_lav = mysqli_query($cnx, "SELECT pl_descripcion FROM preparacion_lavadores WHERE pl_id = '$reg_pro_lav[pl_id]' ") or die(mysqli_error($cnx) . "Error: en consultar los paletos");
													$reg_lav = mysqli_fetch_assoc($cad_lav);

													$dato = $reg_renglones['val'];

												?>
													<tr <?php //if($dato != 0 or $dato != ''){echo "style='background:#FC8C8C;color:#fff'";}else{echo "bgcolor='#ffffff'";} 
														if ($dato != 0 or $dato != '') {
															//echo "style='background:#FC8C8C;color:#fff'";
															if ($dato == 0.00 and $reg_etapas_n['pep_tipo'] != 'ppm') {
																echo "style='background:#ffffff'";
															} else {
																echo "style='background:#FC8C8C; color:#fff'";
															}
														} else {
															echo "bgcolor='#ffffff'";
														}
														?>>
														<td width="40"><?php echo $cont; ?></td>
														<td width="60"><?php echo $reg_pro_lav['pro_id']; ?></td>
														<td width="70"><?php echo "--"; ?></td>
														<td width="70"><?php echo $reg_lav['pl_descripcion']; ?></td>
														<td align="center" width="40"><?php echo $dato ?></td>
													</tr>
												<?php
													$cont += 1;
												} while ($reg_pro_lav = mysqli_fetch_array($cad_pro_lav));
												do {
													//Consulta parametros de los renglones
													$cad_renglones = mysqli_query($cnx, "select " . $reg_etapas_n['pep_columna'] . " as val from " . $reg_etapas_n['pep_tabla'] . " as d 
							inner join " . $reg_etapas_n['pep_tabla_p'] . " as g on (g." . $reg_etapas_n['pep_columna_p'] . " = d." . $reg_etapas_n['pep_columna_p'] . ")
							where (" . $reg_etapas_n['pep_columna'] . " < '$reg_etapas_n[pep_inicio]' or " . $reg_etapas_n['pep_columna'] . " > '$reg_etapas_n[pep_fin]') and pro_id = '$reg_procesos[pro_id]' ")
														or die(mysqli_error($cnx) . "Error: en consultar los renglones x");
													$reg_renglones = mysqli_fetch_assoc($cad_renglones);

													$cad_pal = mysqli_query($cnx, "SELECT pp_descripcion FROM preparacion_paletos WHERE pp_id = '$reg_procesos[pp_id]' ") or die(mysql_error() . "Error: en consultar los paletos");
													$reg_pal = mysqli_fetch_assoc($cad_pal);

													$dato = $reg_renglones['val'];
												?>
													<tr <?php //if($dato != 0 or $dato != ''){echo "style='background:#FC8C8C;color:#fff'";}else{echo "bgcolor='#ffffff'";}
														if ($dato != 0 or $dato != '') {
															//echo "style='background:#FC8C8C;color:#fff'";
															if ($dato == 0.00 and $reg_etapas_n['pep_tipo'] != 'ppm') {
																echo "style='background:#ffffff'";
															} else {
																echo "style='background:#FC8C8C; color:#fff'";
															}
														} else {
															echo "bgcolor='#ffffff'";
														}
														?>>
														<td><?php echo $cont; ?></td>
														<td><?php echo $reg_procesos['pro_id']; ?></td>
														<td><?php echo $reg_pal['pp_descripcion']; ?></td>
														<td><?php echo "--"; ?></td>
														<td align="center"><?php echo $dato ?></td>
													</tr>
												<?php
													$cont += 1;
												} while ($reg_procesos = mysqli_fetch_assoc($cad_procesos)); ?>
											</table>
										</td>

										<td>
											<a href="#" onClick="javascript:ver_historial_ren(<?php echo $reg_etapas_n['pep_id'] ?>);"><img src="iconos/movimientos.png" alt=""></a>
										</td>
									</tr>
								<?php } while ($reg_etapas_n = mysqli_fetch_assoc($cad_etapas_n)); ?>
							</tbody>
						</table>

					</div>
				</div>

			</div>

			<div class="col-md-6">
				<div class="row">
					<div style="background: -webkit-linear-gradient(#eee, #F91F05);-webkit-background-clip: text;-webkit-text-fill-color: transparent;text-align: center;text-transform: uppercase;">
						<h3>Resumen Materiales cargados</h3>
					</div>
				</div>
				<div class="row">
					<?php include "alertas/tablero_materiales.php"; ?>
				</div>

			</div>

			<div class="col-md-6">
				<div class="row">
					<div style="background: -webkit-linear-gradient(#eee, #F91F05);-webkit-background-clip: text;-webkit-text-fill-color: transparent;text-align: center;text-transform: uppercase;">
						<h3>Resumen Materiales disponibles</h3>
					</div>
				</div>
				<div class="row">
					<?php include "alertas/tablero_materiales_disponibles.php"; ?>
				</div>
			</div>

			<link rel="stylesheet" href="css/estilos_footer.css">

			<?php include "generales/pie_pagina.php"; ?>

			<div class="modal right" id="modal_ver_historial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
			</div>

	</body>

</html>