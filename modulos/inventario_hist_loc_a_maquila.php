<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
	FROM inventario as i
	INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
	INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
	WHERE inv_enviado in (1,0) and p.prv_tipo = 'L' and p.prv_ban = 1 ORDER BY prv_tipo") or die(mysql_error() . "Error: en consultar el inventario");
$registros = mysqli_fetch_assoc($cadena);

?>
<!--formato de tablas -->
<link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script>


<script type="text/javascript">
	<!--paginación de tabla
	-->
	$(document).ready(function()
	{
	$('#tabla_inventarioML2').dataTable(
	{
	"sPaginationType":
	"full_numbers"
	}
	);
	})
</script>

<div class="row" style="margin-top:2rem;">
	<div class="col-sm-12 col-md-4 ">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Funciones</li>
				<li class="breadcrumb-item active" aria-current="page">Historial Inventario Local</li>
			</ol>
		</nav>
	</div>

	<div class="col-sm-12 col-md-8" style="text-align:right">
		<a class="iconos" href="formatos/listado_historial_local.php" target="_blank"><i class="fa-solid fa-print fa-2xl"></i>
			Imprimir</a>
		<a class="iconos" href="exportar/historial_local.php" target="_blank"><i class="fa-solid fa-file-excel fa-2xl"></i>
			Exp.excel</a>
	</div>
</div>

<div class="container" style="border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_inventarioML2">
		<thead>
			<tr align="center">
				<th width="70">&nbsp;Fecha&nbsp;</th>
				<th>&nbsp;Dia&nbsp;</th>
				<th>&nbsp;No. Ticket&nbsp;</th>
				<th>&nbsp;Placas&nbsp;</th>
				<th>&nbsp;Camioneta&nbsp;</th>
				<th>&nbsp;Proveedor&nbsp;</th>
				<th>&nbsp;Tipo&nbsp;</th>
				<th>&nbsp;Material&nbsp;</th>
				<th>&nbsp;Kilos&nbsp;</th>
				<th>&nbsp;Prueba<br /> secador&nbsp;</th>
				<th>&nbsp;Kilos<br />Entrada&nbsp;</th>
				<!--<th width="20">Enviar<br />Maquila</th>-->
				<!--<th width="20">Baja</th>-->
			</tr>
		</thead>
		<tbody>
			<?php
			$ren = 1;
			$flt_kg = 0;
			$flt_kg_t = 0;
			do {

				if ($registros['inv_dia'] == '') {
					$registros['inv_dia'] = 7;
				}
			?>
				<tr height="20">
					<td align="center"><?php echo $registros['inv_fecha'] ?></td>
					<td><?php echo fnc_nom_dia($registros['inv_dia']); ?></td>
					<td><?php echo $registros['inv_no_ticket'] ?></td>
					<td><?php echo $registros['inv_placas'] ?></td>
					<td><?php echo $registros['inv_camioneta'] ?></td>
					<td><?php echo $registros['prv_nombre'] ?></td>
					<?php
					if ($registros['prv_tipo']  == 'L') { ?>
						<td><?php echo "Local" ?></td>

					<?php
					} else { ?>
						<td><?php echo "Extranjero" ?></td>
					<?php } ?>
					<td><?php echo $registros['mat_nombre'] ?></td>
					<td align="right"><?php echo $registros['inv_kilos'] ?></td>
					<td><?php echo $registros['inv_prueba'] ?></td>
					<td align="right"><?php echo $registros['inv_kg_totales'] ?></td>
					<!--<td style="padding-left: 0px" align="center"><a href="#" onClick="javascript:AbreModalEditar(<?= $registros['prv_id']; ?>)"><img src="../iconos/editar.png"></a></td>-->
					<!--<td style="padding-left: 0px">
							<?php /*if($_SESSION['privilegio'] == 7 and $registros['inv_tomado'] == 0){?>
								<a href="javascript:fnc_enviar(<?=$registros['inv_id']?>);"><img src="../iconos/disp.png"/></a>
								<?php }else{echo "";}*/ ?>
							</td>-->
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
				<th align="right"><?php echo $flt_kg; ?></th>

				<th align="right"><?php echo $flt_kg_t; ?></th>
				<th></th>
			</tr>
		</tfoot>
	</table>
</div>