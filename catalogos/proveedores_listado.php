<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
						 FROM proveedores ") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
$registros = mysqli_fetch_assoc($cadena);
?>
<!--formato de tablas -->
<link type="text/css" href="../css/estilos_listado.css" rel="stylesheet" />
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="../js/jquery-ui.css" />
<script src="../js/jquery-ui.js"></script>


<script type="text/javascript">
	/* <!--paginación de tabla--> */
	$(document).ready(function() {
		$('#tabla_lista_proveedores').dataTable({
			"sPaginationType": "full_numbers"
		});
	})
</script>

<div class="container" style="margin-top:60px;border: 1px solid #cccccc; padding: 0px;border-radius: 10px;padding-top: 10px;margin-bottom: 50px;width: 100%">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_lista_proveedores">
		<thead>
			<tr align="center">
				<th>&nbsp;Clave&nbsp;</th>
				<th>&nbsp;Nombre&nbsp;</th>
				<th>&nbsp;Nombre comercial&nbsp;</th>
				<th>&nbsp;Tipo&nbsp;</th>
				<th>&nbsp;RFC&nbsp;</th>
				<th>&nbsp;Correo&nbsp;</th>
				<th>&nbsp;Teléfono&nbsp;</th>
				<th>&nbsp;Contacto&nbsp;</th>
				<th>&nbsp;Estatus&nbsp;</th>
				<th width="20">Editar</th>
				<th width="20">Baja</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$ren = 1;
			do { ?>
				<tr height="20">
					<td align="center"><?php echo $registros['prv_id'] ?></td>

					<?php if ($registros['prv_ban'] == 2) {
						$estilo = "<span style='font-weight:bold'> (Maquila) </span>";
					} else if ($registros['prv_ban'] == 1) {
						$estilo = "<span style='font-weight:bold'> (Especial) </span>";
					} else {
						$estilo = "";
					}
					?>
					<td><?php echo $registros['prv_nombre'] . $estilo ?></td>
					<td><?php echo $registros['prv_nom_comercial'] ?></td>

					<td><?php
						if ($registros['prv_tipo']  == 'L') { ?>
							<?php echo "Local" ?>

						<?php
						} else { ?>
							<?php echo "Extranjero" ?>
						<?php } ?>
					</td>
					<td><?php echo $registros['prv_rfc'] ?></td>
					<td><?php echo $registros['prv_email'] ?></td>
					<td><?php echo $registros['prv_telefono'] ?></td>
					<td><?php echo $registros['prv_contacto'] ?></td>
					<td><?php if ($registros['prv_est'] == 'A') {
							echo "Activo";
						} else {
							echo "Baja";
						} ?></td>
					<td style="padding-left: 0px" align="center"><?php if (fnc_permiso($_SESSION['privilegio'], 7, 'upe_editar') == 1) { ?><a href="#" onClick="javascript:AbreModalEditar(<?= $registros['prv_id']; ?>)"><img src="../iconos/editar.png"></a><?php } ?></td>
					<td style="padding-left: 0px"><?php if (fnc_permiso($_SESSION['privilegio'], 7, 'upe_borrar') == 1) { ?><a href="javascript:fnc_baja(<?= $registros['prv_id'] ?>);"><img src="../iconos/borrar.png" /></a><?php } ?></td>
				</tr>
			<?php
				$ren += 1;
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
				<th></th>
				<th></th>
			</tr>
		</tfoot>
	</table>
</div>