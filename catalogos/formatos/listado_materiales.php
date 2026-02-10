<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
include('../../seguridad/user_seguridad.php');
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT * FROM materiales ") or die(mysqli_error($cnx) . "Error: en consultar el material");
$registros = mysqli_fetch_assoc($cadena);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Listado de Materiales</title>
	<link rel="stylesheet" href="../../css/estilos_formatos.css">
	<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
</head>

<body>
	<div class="container">
		<center>

			<div class="tablehead">
				<table>
					<tr>
						<td><img src="../../imagenes/logo_progel_v3.png" style="width: 80px"></td>
						<td>
							<h1>Listado de Materiales</h1>
						</td>
					</tr>
					<tr></tr>
				</table>

			</div>

			<div class="tablecuerpo">


				<table class="table table-bordered" style="width: 100%;margin-top:2rem">
					<thead>
						<tr>
							<th>Origen material</th>
							<th>Material</th>
							<th>Unidad medida</th>
							<th>Costo</th>
							<th align="right">Stock minimo</th>
							<th align="right">Stock maximo</th>
							<th align="right">Existencia</th>
							<th align="center">Estatus</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$ren = 1;
						$flt_kg  = 0;
						do {
							$cad_matTip = mysqli_query($cnx, "select mt_descripcion from materiales_tipo where mt_id = '$registros[mt_id]'");
							$reg_matTip =  mysqli_fetch_assoc($cad_matTip);

							$cad_Um = mysqli_query($cnx, "select um_descripcion from unidades_medida where um_id = '$registros[um_id]'");
							$reg_um =  mysqli_fetch_assoc($cad_Um);
						?>
							<tr height="20">
								<td><?php echo $reg_matTip['mt_descripcion'] ?></td>
								<td><?php echo $registros['mat_nombre'] ?></td>
								<td><?php echo $reg_um['um_descripcion'] ?></td>
								<td align="right">
									<?php if (fnc_permiso($_SESSION['privilegio'], 5, 'upe_agregar') == 1) {
										echo $registros['mat_costo'];
									} ?></td>
								<td align="right"><?php echo $registros['mat_stock_min'] ?></td>
								<td align="right"><?php echo $registros['mat_stock_max'] ?></td>
								<td align="right"><?php echo $registros['mat_existencia'] ?>&nbsp;</td>
								<td align="center"><?php echo $registros['mat_est'] ?></td>
							</tr>
						<?php
							$ren += 1;
							$flt_kg += $registros['mat_existencia'];
						} while ($registros = mysqli_fetch_assoc($cadena)); ?>
						<tr>
							<td colspan="5" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff"></td>
							<td style="text-align: right;font-weight: bold;font-size: 18px;border-left: 2px solid#fff;border-bottom: 1px solid#fff">Existencia Total:</td>
							<td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo number_format($flt_kg, 2); ?>&nbsp;</td>
						</tr>
					</tbody>
					<tfoot>
						<?php for ($i = $ren; $i <= 40; $i++) { ?>

						<?php } ?>
					</tfoot>
				</table>
			</div>
		</center>
	</div>

	<?php include "../../generales/pie_pagina_formato.php"; ?>
</body>

</html>