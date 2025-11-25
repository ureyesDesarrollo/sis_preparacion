<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$reg_autorizado = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT * FROM usuarios_perfiles WHERE up_id = '" . $_SESSION['privilegio'] . "'"));

if ($reg_autorizado['up_ban'] == 1) 
{
	$str_campo = 'prv_nombre';
} else {
	$str_campo = 'prv_ncorto';
}

$cadena = mysqli_query($cnx, "SELECT *, $str_campo as nombre
						 FROM proveedores ") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
$registros = mysqli_fetch_assoc($cadena);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Listado de Proveedores</title>
	<link rel="stylesheet" href="../../css/estilos_formatos.css">
	<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
</head>

<body>
	<div class="container" style="margin-bottom: 5rem;">
		<center>

			<div class="tablehead">
				<table>
					<tr>
						<td><img src="../../imagenes/logo_progel_v3.png" style="width: 80px"></td>
						<td>
							<h1>Listado de proveedores</h1>
						</td>
					</tr>
					<tr></tr>
				</table>

			</div>

			<div class="tablecuerpo">


				<table class="table table-bordered" style="width: 100%;margin-top:2rem">
					<thead>
						<tr>
							<th>Clave</th>
							<th>Nombre</th>
							<th>Nombre comercial</th>
							<th>Tipo de proveedor</th>
							<th>RFC</th>
							<th>Correo</th>
							<th>Teléfono</th>
							<th>Contacto</th>
							<th>Estatus</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$ren = 1;
						do { ?>
							<tr>
								<td><?php echo $registros['prv_id'] ?></td>
								<td><?php echo $registros['nombre'] ?></td>
								<td><?php echo $registros['prv_nom_comercial'] ?></td>

								<?php
								if ($registros['prv_tipo']  == 'L') { ?>
									<td><?php echo "Local" ?></td>

								<?php
								} else { ?>
									<td><?php echo "Extranjero" ?></td>
								<?php } ?>
								<td><?php echo $registros['prv_rfc'] ?></td>
								<td><?php echo $registros['prv_email'] ?></td>
								<td><?php echo $registros['prv_telefono'] ?></td>
								<td><?php echo $registros['prv_contacto'] ?></td>
								<td><?php if ($registros['prv_est'] == 'A') {
										echo "Activo";
									} else {
										echo "Baja";
									} ?></td>
							</tr>
						<?php
							$ren += 1;
						} while ($registros = mysqli_fetch_assoc($cadena)); ?>
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