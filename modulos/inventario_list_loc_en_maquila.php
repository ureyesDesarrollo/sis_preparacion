<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
$cnx =  Conectarse();

$cadenaT = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
						 FROM inventario as i
						 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
						 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
						 WHERE (inv_enviado = 1 or inv_enviado = 5) and inv_tomado = 0 and p.prv_ban = 1 ORDER BY prv_tipo") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
$registrosT = mysqli_fetch_assoc($cadenaT);

?>
<!--formato de tablas -->
<link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	/* paginación de tabla*/
	$(document).ready(function() {
		$('#tabla_inventarioML').dataTable({
			"sPaginationType": "full_numbers"
		});
	})
</script>

<div class="row" style="margin-top:2rem;">
	<div class="col-sm-12 col-md-4 ">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Funciones</li>
				<li class="breadcrumb-item active" aria-current="page">Inventario Local en Maquila</li>
			</ol>
		</nav>
	</div>

	<div class="col-sm-12 col-md-8" style="text-align:right">
		<a class="iconos" href="formatos/listado_local_en_maquila.php" target="_blank"><i class="fa-solid fa-print fa-2xl"></i>
			Imprimir</a>
		<a class="iconos" href="exportar/local_en_maquila.php" target="_blank"><i class="fa-solid fa-file-excel fa-2xl"></i>
			Exp.excel</a>
	</div>
</div>
<div class="container" style="border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_inventarioML">
		<thead>
			<tr align="center">
				<th width="70">&nbsp;Fecha&nbsp;</th>
				<th>&nbsp;Dia&nbsp;</th>
				<th>&nbsp;No. Ticket&nbsp;</th>
				<th>&nbsp;Placas&nbsp;</th>
				<th width="100">&nbsp;Camioneta&nbsp;</th>
				<th width="130">&nbsp;Proveedor&nbsp;</th>
				<th>&nbsp;Tipo&nbsp;</th>
				<th>&nbsp;Material&nbsp;</th>
				<th>&nbsp;Kilos&nbsp;</th>
				<th>&nbsp;Prueba<br /> secador&nbsp;</th>
				<th>&nbsp;Kilos<br />Entrada&nbsp;</th>
				<th width="20">Recibir</th>
				<!--<th width="20">Baja</th>-->
			</tr>
		</thead>
		<tbody>
			<?php
			$ren = 1;
			$flt_kg = 0;
			$flt_kg_t = 0;
			do {

				if (isset($registrosT['inv_dia'])) {
					/* $registrosT['inv_dia'] = 7; */

			?>
					<tr height="20">
						<td align="center"><?php echo $registrosT['inv_fecha'] ?></td>
						<td><?php echo fnc_nom_dia($registrosT['inv_dia']); ?></td>
						<td><?php echo $registrosT['inv_no_ticket'] ?></td>
						<td><?php echo $registrosT['inv_placas'] ?></td>
						<td><?php echo $registrosT['inv_camioneta'] ?></td>
						<td><?php echo $registrosT['prv_nombre'] ?></td>
						<?php
						if ($registrosT['prv_tipo']  == 'L') { ?>
							<td><?php echo "Local" ?></td>

						<?php
						} else { ?>
							<td><?php echo "" ?></td>
						<?php } ?>
						<td><?php echo $registrosT['mat_nombre'] ?></td>
						<td align="right"><?php echo $registrosT['inv_kilos'] ?></td>
						<td><?php echo $registrosT['inv_prueba'] ?></td>
						<td align="right"><?php echo $registrosT['inv_kg_totales'] ?></td>
						<td style="padding-left: 0px" align="center"><?php if ($_SESSION['privilegio'] == 7 or $_SESSION['privilegio'] == 4 or $_SESSION['privilegio'] == 11) { ?><a href="#" onClick="javascript:AbreModalRecibir2(<?= $registrosT['inv_id']; ?>)"><img src="../iconos/editar.png"></a><?php } else {
																																																																										echo "";
																																																																									} ?></td>
						<!--<td style="padding-left: 0px"><a href="javascript:fnc_enviar(<?= $registrosT['inv_id'] ?>);"><img src="../iconos/disp.png"/></a></td>-->
					</tr>
			<?php
					$ren += 1;

					$flt_kg += $registrosT['inv_kilos'];
					$flt_kg_t += $registrosT['inv_kg_totales'];
				}
			} while ($registrosT = mysqli_fetch_assoc($cadenaT)); ?>

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
				<th align="right"><?php echo $flt_kg; ?></th>

				<th align="right"><?php echo $flt_kg_t; ?></th>
				<th></th>
			</tr>
		</tfoot>
	</table>
</div>