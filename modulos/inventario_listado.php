<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../funciones/funciones.php";
$cnx =  Conectarse();

/*if ($_SESSION['privilegio'] == 7)//MC
{
	$filtro = '';
}else
{
	$filtro = "or (inv_fecha >= '2024-05-01' and inv_costo = 0) ";	
}*/

if ($_SESSION['privilegio'] == 11) {
	$date_now = date('d-m-Y');
	$str_fecha = strtotime('-3 day', strtotime($date_now));
	$str_fecha = date('Y-m-d', $str_fecha);

	//echo "x ".$str_fecha;

	$filtro = " (inv_fecha >= '" . $str_fecha . "') or (inv_fecha >= '2024-05-01' and inv_costo = 0) ";
} else {
	$filtro = "(inv_fecha >= '" . date("Y-m-d") . "')";
}

$cadena = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre,m.mt_id,p.prv_ncorto
						 FROM inventario as i
						 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
						 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
						 WHERE " . $filtro . " ORDER BY prv_tipo") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
$registros = mysqli_fetch_assoc($cadena);
?>
<!--formato de tablas -->
<link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	//-paginación de tabla
	$(document).ready(function() {
		$('#tabla_inventario').dataTable({
			"sPaginationType": "full_numbers"
		});

	})

	function abre_modal_costo_maquila() {
		$.ajax({
			type: 'POST',
			url: 'modal_costo_maquila.php',
			success: function(result) {
				$("#modal_costo_maquila").html(result);
				$('#modal_costo_maquila').modal('show')
			}
		});
	};
</script>


<div class="row" style="margin-top:2rem;">
	<div class="col-md-3 col-sm-12">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Funciones</li>
				<li class="breadcrumb-item active" aria-current="page">Inventario</li>
			</ol>
		</nav>
	</div>
	<div class="col-sm-1 col-md-9" style="text-align:right">
		<?php if ($_SESSION['privilegio'] == 1 || $_SESSION['privilegio'] == 2 || fnc_permiso($_SESSION['privilegio'], 29, 'upe_agregar') == 1) { ?> <!-- Agregar el valor del perfil de Ara -->

			<a class="iconos" href="#" data-bs-toggle="modal" data-bs-target="#costoMqlModal" onclick="abre_modal_costo_maquila();">
				<i class="fa-solid fa-dollar-sign fa-2xl"></i> Costo
			</a>
		<?php } ?>
		<?php if (fnc_permiso($_SESSION['privilegio'], 3, 'upe_borrar') == 1) { ?>
			<a class="iconos" href="../catalogos/formatos/listado_materiales_disponible2.php" target="_blank"><i class="fa-solid fa-square-caret-down fa-2xl"></i>Baja</a>

			<a class="iconos" href="../catalogos/formatos/listado_materiales_disponible_dev.php" target="_blank"><i class="fa-solid fa-down-long fa-2xl"></i>Devolución</a>
		<?php } ?>
		<?php if (fnc_permiso($_SESSION['privilegio'], 3, 'upe_listar') == 1) { ?>
			<a class="iconos" href="formatos/listado_inventario_nuevo.php" target="_blank"><i class="fa-solid fa-file fa-2xl"></i>Formato</a>

			<a class="iconos" href="exportar/inventario.php" target="_blank"><i class="fa-solid fa-file-excel fa-2xl"></i>Día</a>

			<a class="iconos" href="exportar/inventario_total.php" target="_blank"><i class="fa-solid fa-file-excel fa-2xl"></i>Todo</a>
		<?php } ?>

		<?php if (fnc_permiso($_SESSION['privilegio'], 3, 'upe_agregar') == 1) { ?>

			<a class="iconos" href="#" data-toggle="modal" data-target="#ModalAlta" data-whatever="@getbootstrap">
				<i class="fa-solid fa-square-plus fa-2xl"></i>Inventario</a>
		<?php } ?>
	</div>
</div>

<div class="row">
	<div class="container" style="border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
		<div id="tab2">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_inventario">
				<thead>
					<tr align="center">
						<th>&nbsp;Fecha&nbsp;</th>
						<th>&nbsp;Dia&nbsp;</th>
						<th>&nbsp;No. Ticket&nbsp;</th>
						<th>&nbsp;Placas&nbsp;</th>
						<th>&nbsp;Camioneta&nbsp;</th>
						<th>&nbsp;Proveedor&nbsp;</th>
						<th>&nbsp;Tipo&nbsp;</th>
						<th>&nbsp;Origen / Material&nbsp;</th>
						<th>&nbsp;Kg entrada&nbsp;</th>
						<th>&nbsp;Prueba<br /> secador&nbsp;</th>
						<th>&nbsp;Kg totales</th>
						<!-- <th width="20">Precio</th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					$ren = 1;
					$flt_kg = 0;
					$flt_kg_t = 0;
					do {

						if (isset($registros['inv_dia'])) {
							/* $registros['inv_dia'] = 7; */

							$clasificacion = mysqli_query($cnx, "SELECT * FROM materiales_tipo WHERE mt_id = '" . $registros['mt_id'] . "' ") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
							$registros_clas = mysqli_fetch_assoc($clasificacion);
					?>
							<tr height="20">
								<td align="center"><?php echo $registros['inv_fecha'] ?></td>
								<td><?php echo fnc_nom_dia($registros['inv_dia']); ?></td>
								<td><?php echo $registros['inv_no_ticket'] ?></td>
								<td><?php echo $registros['inv_placas'] ?></td>
								<td><?php echo $registros['inv_camioneta'] ?></td>
								<td> <?php if ($reg_autorizado['up_ban'] == 1) {
											echo $registros['prv_nombre'];
										} else {
											echo $registros['prv_ncorto'];
										} ?></td>
								<?php
								if ($registros['prv_tipo']  == 'L') { ?>
									<td><?php echo "Local" ?></td>

								<?php
								} else { ?>
									<td><?php echo "Extranjero" ?></td>
								<?php } ?>
								<td><?php echo $registros_clas['mt_descripcion'] . ' / ' . $registros['mat_nombre'] ?></td>
								<td align="right"><?php echo number_format($registros['inv_kilos'], 2) ?></td>
								<td><?php echo $registros['inv_prueba'] ?></td>
								<td align="right"><?php echo number_format($registros['inv_kg_totales'], 2) ?></td>
								<!-- <td><?php if (fnc_permiso($_SESSION['privilegio'], 29, 'upe_agregar') == 1) {
												if ($registros['inv_costo'] != 0) {
													echo $registros['inv_costo'] . " ";
												}
											?>

										<?php //if ($registros['inv_costo'] == 0) { 
										?>
										<a href="javascript:costos_inventario(<?= $registros['inv_id'] ?>, <?= $registros['mat_id'] ?>,<?= $registros['prv_id'] ?>);"> <i class="fa-solid fa-dollar-sign"></i></a>

										<?php //} else {
												//echo $registros['inv_costo'];
												//} 
										?>

									<?php } ?>
								</td> -->

							</tr>
					<?php
							$ren += 1;

							$flt_kg += $registros['inv_kilos'];
							$flt_kg_t += $registros['inv_kg_totales'];
						}
					} while ($registros = mysqli_fetch_assoc($cadena));
					?>

				</tbody>

				<tfoot>
					<?php for ($i = $ren; $i <= 12; $i++) { ?>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					<?php } ?>
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th>Total Kg:</th>
						<th align="right"><?php echo number_format($flt_kg, 2); ?></th>
						<th></th>
						<th align="right"><?php echo number_format($flt_kg_t, 2); ?></th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<div class="modal" id="modal_costo_maquila" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
</div>