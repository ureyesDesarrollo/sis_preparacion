<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include('../../seguridad/user_seguridad.php');
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT inv_id, mat_nombre, inv_kilos, prv_nombre, inv_fecha, inv_no_ticket, inv_kg_totales,inv_id_key,inv_enviado,p.prv_ncorto
	FROM inventario as i
	INNER JOIN materiales as m ON (i.mat_id = m.mat_id)
	INNER JOIN proveedores as p ON (i.prv_id = p.prv_id)
	WHERE inv_tomado = 0 AND inv_enviado IN (0,1, 2) and inv_kg_totales > 0
	ORDER BY m.mat_nombre,i.inv_no_ticket ") or die(mysqli_error($cnx) . "Error: en consultar el material");
$registros = mysqli_fetch_assoc($cadena);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Listado de Materiales</title>
	<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../css/estilos_formatos.css">
	<script type="text/javascript" src="../../js/alerta.js"></script>
	<script src="../../js/jquery.min.js"></script>
	<script src="../../js/bootstrap.min.js"></script>
	<style>
		@media print {
			footer {
				height: 15px;
			}
		}
	</style>
	<script>
		/*Manipular el formulario*/
		$(document).ready(function() {
			$("#formMatDisp2").submit(function() {
				// Obtén todos los elementos con la clase 'chk_baja' y 'txt_comentario'
				var checkboxes = document.querySelectorAll('.chk_baja');
				var comentarios = document.querySelectorAll('.txt_comentario');

				// Itera sobre los elementos y realiza la validación
				for (var i = 0; i < checkboxes.length; i++) {
					var checkbox = checkboxes[i];
					var comentario = comentarios[i];

					if (checkbox.checked && comentario.value.trim() === "") {
						//alert("Debes ingresar comentarios antes de guardar.");
						var ban = 1;
						//return false; // Evita que se guarde la información
					}
				}

				if (ban != 1) {
					var formData = $(this).serialize();
					//alert(formData);
					$.ajax({
						url: "../materiales_disp_baja.php",
						type: 'POST',
						data: formData,
						success: function(result) {
							data = JSON.parse(result);
							/*alert("Guardo el registro");*/
							alertas("#alerta-errorMatBaja2", 'Listo!', data["mensaje"], 1, true, 5000);
							$('#formMatDisp2').each(function() {
								this.reset();
							});
							//setTimeout("location.reload()", 1000)
							setTimeout('location.reload()', 1000)
						}
					});
					return false;
				} else {
					alert("Debes ingresar comentarios antes de guardar.");
					return false;
				}
			});
		});
	</script>
</head>

<body>
	<div class="container">
		<center>
			<form id="formMatDisp2">
				<div class="tablecuerpo" style="margin-bottom: 60px">

					<table class="table table-bordered" style="width: 100%;margin-top:2rem">
						<thead>
							<tr>
								<th colspan="9" style="text-align: center;">
									<img src="../../imagenes/logo_progel_v3.png" style="width: 80px">
									<h4 style="display: inline;">Materiales diponible para baja</h4>
								</th>
							</tr>
							<tr>
								<th>Clave</th>
								<th>No. ticket</th>
								<th>Material</th>
								<th style="width: 20%;padding-right:2%;text-align:right">Kilos</th>
								<th>Estatus</th>
								<th align="center">Proveedor</th>
								<th>Fecha Entrada</th>
								<th width="5%">
									<?php if (fnc_permiso($_SESSION['privilegio'], 3, 'upe_borrar') == 1) { ?>
										Baja
									<?php } ?>
								</th>
								<th>
									<?php if (fnc_permiso($_SESSION['privilegio'], 3, 'upe_borrar') == 1) { ?>
										Comentarios
									<?php } ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$ren = 1;
							$flt_kg  = 0;
							do {

								//1.vreifica si hay parcialidad
								$parcialidad = mysqli_query($cnx, "SELECT inv_id, inv_kilos, inv_fecha, inv_no_ticket, inv_kg_totales,inv_id_key,inv_enviado
								FROM inventario where inv_id_key = '" . $registros['inv_id'] . "' ") or die(mysqli_error($cnx) . "Error: en consultar el material");
								$reg_parcialidad = mysqli_fetch_assoc($parcialidad);
								$tot_parcialidad = mysqli_num_rows($parcialidad);

								//si hay parcialidad en maquila toma inv_kilos para descontar a inventario
								if ($registros['inv_id_key'] != NULL && $registros['inv_enviado'] == 1) {
									$kilos = $registros['inv_kilos'];
								} else {

									//2. verifica si quedó material en bodega  y toma el campo inv_kilos
									$can_en_bodega = mysqli_query($cnx, "SELECT inv_id, inv_kilos, inv_fecha, inv_no_ticket, inv_kg_totales,inv_id_key,inv_enviado
									FROM inventario where inv_id = '" . $registros['inv_id_key'] . "' ") or die(mysqli_error($cnx) . "Error: en consultar");
									$reg_en_bodega = mysqli_fetch_assoc($can_en_bodega);
									$tot_parcialidad = mysqli_num_rows($parcialidad);

									//si hubo parcialización de envio a maquila y aun quedó material en bodega toma  inv_kilos para descontar de inventario
									if ($tot_parcialidad > 0 && $registros['inv_enviado'] == 0) {
										$kilos = $registros['inv_kilos'];
									} else {
										$kilos = $registros['inv_kg_totales'];
									}
								}

								if ($registros['inv_enviado'] == 0) {
									$estatus = "En bodega";
								} else if ($registros['inv_enviado'] == 1) {
									$estatus = "En maquila";
								} else {
									$estatus = "Recibido de maquila";
								}
							?>
								<tr height="20">
									<td><?php echo $registros['inv_id'] ?></td>
									<td><?php echo $registros['inv_no_ticket'] ?></td>
									<td><?php echo $registros['mat_nombre'] ?></td>
									<td style="width: 20%;padding-right:2%;text-align:right"><?php echo $kilos ?></td>
									<td><?php echo $estatus ?></td>
									<td><?php if ($reg_autorizado['up_ban'] == 1) {
											echo $registros['prv_nombre'];
										} else {
											echo $registros['prv_ncorto'];
										} ?></td>
									<td><?php echo $registros['inv_fecha'] ?></td>
									<td align="center">
										<?php if (fnc_permiso($_SESSION['privilegio'], 3, 'upe_borrar') == 1) { ?>

											<input class="chk_baja" name="chk_baja<?php echo $ren; ?>" type="checkbox" value="<?php echo $registros['inv_id'] ?>" id="chk_baja<?php echo $ren; ?>">

										<?php } ?>
									</td>
									<td align="center">
										<?php if (fnc_permiso($_SESSION['privilegio'], 3, 'upe_borrar') == 1) { ?>

											<textarea class="form-control txt_comentario" name="txt_comentario<?php echo $ren; ?>" id="txt_comentario<?php echo $ren; ?>" cols="30" rows="2"></textarea>
										<?php } ?>
									</td>
								</tr>
							<?php
								$ren += 1;
								$flt_kg += $registros['inv_kg_totales'];
							} while ($registros = mysqli_fetch_assoc($cadena)); ?>
							<tr style="border-bottom :2px solid#fff;">
								<td style="text-align: right;font-weight: bold;font-size: 18px;border-left: 2px solid#fff;border-right: 1px solid#fff;border-bottom :2px solid#fff;" colspan="3">Existencia Total:</td>
								<td style="text-align: right;font-weight: bold;font-size: 18px;border-right :2px solid#fff;border-bottom :2px solid#fff;"><?php echo number_format($flt_kg, 2); ?>&nbsp;</td>
								<td align="right" colspan="2" style="border-right :2px solid#fff;border-bottom :2px solid#fff;">
									<input name="hdd_cont" id="hdd_cont" type="hidden" value="<?php echo $ren; ?>">
									<div class="alert alert-info hide" id="alerta-errorMatBaja2" style="height: 40px;width: 300px;text-align: left;margin-top: 10px">
										<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
										<strong>Titulo</strong> &nbsp;&nbsp;
										<span> Mensaje </span>
									</div>
								</td>
								<td colspan="2" style="border-right :2px solid#fff;border-bottom :2px solid#fff;"></td>
								<td style="border-left :2px solid#fff;border-right :2px solid#fff;text-align:right;border-bottom :2px solid#fff;"> <button class="btn btn-primary" type="submit"><img src="../../iconos/guardar.png" alt=""> Guardar</button></td>


							</tr>
						</tbody>
						<tfoot>
						</tfoot>
					</table>

				</div>

				<?php
				$cadena = mysqli_query($cnx, "SELECT inv_id, mat_nombre, inv_kg_totales, prv_nombre, inv_fecha,inv_no_ticket, inv_observaciones,p.prv_ncorto
					FROM inventario as i
					INNER JOIN materiales as m ON (i.mat_id = m.mat_id)
					INNER JOIN proveedores as p ON (i.prv_id = p.prv_id)
					WHERE inv_tomado = 0 AND inv_enviado = 3 and inv_kg_totales > 0 and inv_fecha > '2023-01-01'
					ORDER BY m.mat_nombre ") or die(mysqli_error($cnx) . "Error: en consultar el material");
				$registros = mysqli_fetch_assoc($cadena);
				?>

				<div class="tablecuerpo" style="margin-bottom: 60px">
					<table class="table table-bordered" style="width: 100%">
						<thead>
							<tr>
								<th colspan="7" style="text-align: center;">
									<img src="../../imagenes/logo_progel_v3.png" style="width: 80px">
									<h4 style="display: inline;">Listado de materiales con baja</h4>
								</th>
							</tr>
							<tr>
								<th>Clave</th>
								<th>No. ticket</th>
								<th>Material</th>
								<th>Kilos</th>
								<th>Proveedor</th>
								<th>Fecha Entrada</th>
								<th>Comentarios</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$ren = 1;
							$flt_kg  = 0;
							do {
							?>
								<tr height="20">
									<td><?php echo $registros['inv_id'] ?></td>
									<td><?php echo $registros['inv_no_ticket'] ?></td>
									<td><?php echo $registros['mat_nombre'] ?></td>
									<td align="right"><?php echo $registros['inv_kg_totales'] ?>&nbsp;</td>
									<td><?php if ($reg_autorizado['up_ban'] == 1) {
											echo $registros['prv_nombre'];
										} else {
											echo $registros['prv_ncorto'];
										} ?></td>
									<td><?php echo $registros['inv_fecha'] ?></td>
									<td><?php echo $registros['inv_observaciones'] ?></td>
								</tr>
							<?php
								$ren += 1;
								$flt_kg += $registros['inv_kg_totales'];
							} while ($registros = mysqli_fetch_assoc($cadena)); ?>
							<tr>
								<td style="text-align: right;font-weight: bold;font-size: 18px;border-left: 2px solid#fff;border-bottom: 1px solid#fff" colspan="3">Existencia Total daba de baja:</td>
								<td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo number_format($flt_kg, 2); ?>&nbsp;</td>
							</tr>
						</tbody>
						<tfoot>
						</tfoot>
					</table>
				</div>
			</form>
		</center>
	</div>

	<?php include "../../generales/pie_pagina_formato.php"; ?>
</body>

</html>