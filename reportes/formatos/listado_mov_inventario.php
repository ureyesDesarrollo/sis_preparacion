<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT * from inventario_diario_materiales") or die(mysqli_error($cnx) . "Error: en consultar el inventario");
$registros = mysqli_fetch_assoc($cadena);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Listado movimientos de inventario</title>
	<link rel="stylesheet" href="../../css/estilos_formatos.css">
	<style>
		@media print {
			footer {
				height: 15px;
			}
		}
	</style>
</head>

<body>
	<div class="container">
		<center>

			<div class="tablehead">
				<table>
					<tr>
						<td><img src="../../imagenes/logo_progel_v3.png"></td>
						<td>
							<h1>Listado movimientos de inventario</h1>
						</td>
					</tr>
					<tr></tr>
				</table>

			</div>

			<div class="tablecuerpo" style="margin-bottom: 60px">
				<table>
					<thead>
						<tr>
							<th>&nbsp;Ren&nbsp;</th>
							<th>&nbsp;Usuario&nbsp;</th>
							<th>&nbsp;Fecha&nbsp;</th>
							<th>&nbsp;Documento&nbsp;</th>
							<th>&nbsp;Material&nbsp;</th>
							<th>&nbsp;Cant. Ingresada&nbsp;</th>
							<th>&nbsp;Cant. Anterior&nbsp;</th>
							<th>&nbsp;Cant. Nueva&nbsp;</th>
							<th>Proceso</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$ren = 1;

						do {
							if (isset($registros['inv_id'])) {

								$cad_pro =  mysqli_query($cnx, "SELECT pro_id FROM procesos_materiales WHERE inv_id = '$registros[inv_id]' ") or die(mysqli_error($cnx) . "Error: al consultar");
								$reg_pro = mysqli_fetch_assoc($cad_pro);
						?>
								<tr>
									<td align="center"><?php echo $registros['idm_id'] ?></td>
									<td><?php echo fnc_nom_usuario($registros['usu_id']); ?></td>
									<td><?php echo fnc_formato_fecha_hr($registros['idm_fecha']) ?></td>
									<td><?php echo $registros['idm_documento'] ?></td>
									<td><?php echo fnc_nom_material($registros['mat_id']); ?></td>
									<td><?php echo $registros['idm_cant_ing'] ?></td>
									<td><?php echo $registros['idm_cant_ant'] ?></td>
									<td><?php echo $registros['idm_cant_new'] ?></td>
									<td><?php if (isset($reg_pro['pro_id'])) {
											echo $reg_pro['pro_id'];
										} ?></td>
								</tr>
						<?php
								$ren += 1;
							}
						} while ($registros = mysqli_fetch_assoc($cadena));  ?>
					</tbody>
					<tfoot>
						<?php for ($i = $ren; $i <= 40; $i++) { ?>

						<?php } ?>
					</tfoot>
				</table>
			</div>
			<!--	</center>	
	</div>-->

			<?php include "../../generales/pie_pagina_formato.php"; ?>
</body>

</html>