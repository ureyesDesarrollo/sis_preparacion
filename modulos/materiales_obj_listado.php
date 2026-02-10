<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT o.*, t.* , p.prv_nombre, p.prv_ncorto FROM materiales_tipo_obj AS o
								INNER JOIN materiales_tipo AS t ON (o.mt_id = t.mt_id)
								LEFT JOIN proveedores as p ON (o.prv_id = p.prv_id) where mto_fecha >= '2024-01-01'
		        ") or die(mysqli_error($cnx) . "Error: en consultar");
$registros = mysqli_fetch_assoc($cadena);
$perfil_autorizado = mysqli_query($cnx, "SELECT * FROM usuarios_perfiles WHERE up_id = '" . $_SESSION['privilegio'] . "'") or die(mysqli_error($cnx) . "Error: en consultar el inventario 1");
$reg_autorizado = mysqli_fetch_assoc($perfil_autorizado);
?>
<!--formato de tablas -->
<link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script>


<script type="text/javascript">
	$(document).ready(function() {
		$('#tabla_inventario').dataTable({
			"sPaginationType": "full_numbers"
		});
	})
</script>

<div class="container" style="border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_inventario">
		<thead>
			<tr align="center">
				<th>&nbsp;Clave&nbsp;</th>
				<th>&nbsp;Origen material&nbsp;</th>
				<th>&nbsp;Kilos&nbsp;</th>
				<th>&nbsp;Fecha&nbsp;</th>
				<th>&nbsp;Proveedor&nbsp;</th>
				<th>&nbsp;Editar&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$ren = 1;
			do {

			?>
				<tr height="20">
					<td align="center"><?php echo $registros['mto_id'] ?></td>
					<td><?php echo $registros['mt_descripcion']; ?></td>
					<td><?php echo $registros['mto_kilos'] ?></td>
					<td><?php echo $registros['mto_fecha'] ?></td>
					<td> <?php if ($reg_autorizado['up_ban'] == 1) {
								echo $registros['prv_nombre'];
							} else {
								echo $registros['prv_ncorto'];
							} ?></td>
					<!-- <td><?php echo $registros['prv_nombre'] ?></td> -->
					<td style="padding-left: 0px" align="center"><?php if (fnc_permiso($_SESSION['privilegio'], 17, 'upe_editar') == 1) { ?><a href="#" onClick="javascript:AbreModalEditar(<?= $registros['mto_id']; ?>)"><img src="../iconos/editar.png"></a><?php } ?></td>
				</tr>
			<?php
				$ren += 1;

				//$flt_kg += $registros['inv_kilos'];

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
				</tr>
			<?php } ?>
			<tr>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</tfoot>
	</table>
</div>