<?php
#Desarrollado por CCA Consultores
#23 - febrero - 2020
include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx =  Conectarse();
extract($_POST);

if ($ticket != '') {
	$cadena = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre,m.mt_id,m.mat_id,p.prv_ncorto
							 FROM inventario as i
							 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
							 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
							 WHERE inv_no_ticket = '$ticket' ORDER BY inv_fecha") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
	$registros = mysqli_fetch_assoc($cadena);
} else {
	if ($fechafin == '') {
		$cadena = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre,m.mt_id,p.prv_ncorto
							 FROM inventario as i
							 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
							 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
							 WHERE inv_fecha = '$fechaini' ORDER BY inv_fecha") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
		$registros = mysqli_fetch_assoc($cadena);
	} else {

		$cadena = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre,m.mt_id,p.prv_ncorto
							 FROM inventario as i
							 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
							 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
							 WHERE inv_fecha >= '$fechaini' AND inv_fecha <= '$fechafin' ORDER BY inv_fecha") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
		$registros = mysqli_fetch_assoc($cadena);
	}
}

?>

<style>
	@media print {
		thead {
			background: #000;
			color: #fff;
		}

		th {
			background: #000;
			color: #fff;
		}
	}
</style>

<div id="HTMLtoPDF">
	<div class="container" style="border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="tab_inventario">
			<thead>
				<tr align="center">
					<th>&nbsp;No&nbsp;</th>
					<th>&nbsp;Fecha&nbsp;</th>
					<th>&nbsp;Dia&nbsp;</th>
					<th>&nbsp;No. Ticket&nbsp;</th>
					<th>&nbsp;Placas&nbsp;</th>
					<th>&nbsp;Camioneta&nbsp;</th>
					<th>&nbsp;Proveedor&nbsp;</th>
					<th>&nbsp;Tipo&nbsp;</th>
					<th>&nbsp;Material&nbsp;</th>
					<th>&nbsp;Kg entrada&nbsp;</th>
					<th>&nbsp;Prueba<br /> secador&nbsp;</th>
					<th>&nbsp;Kg totales&nbsp;</th>

					<th width="20">Editar</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$ren = 1;
				$cont = 1;
				$flt_kg = 0;
				$flt_kg_t = 0;
				do {

					if ($registros['inv_dia'] == '') {
						$registros['inv_dia'] = 7;
					}

					if ($registros['inv_especial'] == '0') {
						$str_resalta = '';
					} else {
						$str_resalta = 'background: #CC9838;';
					}
				?>
					<tr height="20" style="<?php echo $str_resalta; ?>">

						<td><?php echo $cont++ ?></td>
						<td align="center"><?php echo $registros['inv_fecha'] ?></td>
						<td><?php echo fnc_nom_dia($registros['inv_dia']); ?></td>
						<td><?php echo $registros['inv_no_ticket'] ?></td>
						<td><?php echo $registros['inv_placas'] ?></td>
						<td><?php echo $registros['inv_camioneta'] ?></td>
						<td><?php if ($reg_autorizado['up_ban'] == 1) {
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
						<td><?php echo $registros['mat_nombre'] ?></td>
						<td align="right"><?php echo number_format($registros['inv_kilos'], 2) ?></td>
						<td><?php echo $registros['inv_prueba'] ?></td>
						<td align="right"><?php echo number_format($registros['inv_kg_totales'], 2) ?></td>
						<!--<td style="padding-left: 0px" align="center">
							<?php /*
							if ($registros['inv_tomado'] == 1) {
								echo "Si";
							} else { ?>
								<a href="#" onClick="javascript:modal_proceso(<?= $registros['inv_id']; ?>)"><img src="../iconos/agregar.png"></a><?php } */ ?>
						</td>-->
						<td style="padding-left: 0px">
							<?php if ($_SESSION['privilegio'] == 18): ?>
								<?php if ($registros['mt_id'] == 14 || $registros['mat_id'] == 12 || $registros['mat_id'] == 7): ?>
									<?php if (is_null($registros['inv_humedad_origen'])): ?>
										<a href="javascript:agregar_humedad_origen(<?= $registros['inv_id'] ?>);"><img src="../iconos/editar.png" alt="Editar" /></a>
									<?php else: ?>
										<a href="javascript:agregar_extractibilidad(<?= $registros['inv_id'] ?>);">
											<img src="../iconos/editar.png" alt="Editar" />
										</a>
									<?php endif; ?>
								<?php else: ?>
									<a href="javascript:agregar_extractibilidad(<?= $registros['inv_id'] ?>);">
										<img src="../iconos/editar.png" alt="Editar" />
									</a>
								<?php endif; ?>
							<?php endif; ?>
						</td>


					</tr>
				<?php
					$ren += 1;

					$flt_kg += $registros['inv_kilos'];
					$flt_kg_t += $registros['inv_kg_totales'];
				} while ($registros = mysqli_fetch_assoc($cadena)); ?>

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
					<th></th>
					<th>Total Kg:</th>
					<th style="text-align: right;"><?php echo number_format($flt_kg, 2); ?></th>
					<th></th>
					<th style="text-align: right;"><?php echo number_format($flt_kg_t, 2); ?></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>





<!--https://www.youtube.com/watch?v=_EqYMNdbrsc
	https://blog.syncfusion.com/post/HTML-to-PDF-Conversion-by-using-the-WebKit-Rendering-Engine.aspx
	-->