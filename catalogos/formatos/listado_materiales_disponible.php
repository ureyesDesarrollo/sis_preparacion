<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
include('../../seguridad/user_seguridad.php');

$cnx =  Conectarse();

$reg_autorizado = mysqli_fetch_assoc(mysqli_query($cnx, "SELECT * FROM usuarios_perfiles WHERE up_id = '" . $_SESSION['privilegio'] . "'"));

if ($reg_autorizado['up_ban'] == 1) 
{
	$str_campo = 'prv_nombre';
} else {
	$str_campo = 'prv_ncorto';
}

$cadena = mysqli_query($cnx, "SELECT m.mat_nombre, i.inv_kg_totales, $str_campo as nombre, i.inv_fecha, i.inv_id 
                                FROM inventario as i
                                INNER JOIN materiales as m ON (i.mat_id = m.mat_id)
                                INNER JOIN proveedores as p ON (i.prv_id = p.prv_id)
                                WHERE inv_tomado = 0 AND
                                ((p.prv_tipo = 'L' and i.inv_enviado = 0) or (p.prv_tipo = 'L' and i.inv_enviado = 2) or (p.prv_tipo != 'L' and i.inv_enviado = 2))
                                and inv_kg_totales > 0
                                ORDER BY m.mat_nombre ASC, inv_fecha ASC") or die(mysqli_error($cnx) . "Error: en consultar el material");
$registros = mysqli_fetch_assoc($cadena);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Listado de Materiales</title>
	<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../css/estilos_formatos.css">
	<script src="../../js/jquery.min.js"></script>
	<script type="text/javascript" src="../../js/alerta.js"></script>
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

					if (checkbox.value.trim() !== "" && comentario.value.trim() === "") {
						//alert("Debes ingresar comentarios antes de guardar.");
						var ban = 1;
						//return false; // Evita que se guarde la información
					}
				}

				if (ban != 1) {
					var formData = $(this).serialize();
					//alert(formData);
					$.ajax({
						url: "../materiales_agregar_proceso.php",
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
	<form id="formMatDisp2">
		<div class="container">
			<center>

				<div class="tablehead">
					<table>
						<tr>
							<td><img src="../../imagenes/logo_progel_v3.png" style="width: 80px"></td>
							<td>
								<h1>Listado de Materiales Disponible</h1>
							</td>
						</tr>
						<tr></tr>
					</table>

				</div>

				<div class="tablecuerpo" style="margin-bottom: 60px">

					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Material</th>
								<th>Proveedor</th>
								<th>Kilos</th>
								<th>Fecha Entrada</th>
								<th>
									<?php if (fnc_permiso($_SESSION['privilegio'], 30, 'upe_editar') == 1) { ?>
										Proceso
									<?php } ?>

								</th>
								<th>
									<?php if (fnc_permiso($_SESSION['privilegio'], 30, 'upe_editar') == 1) { ?>
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
								if (isset($registros['mat_nombre'])) {
							?>
									<tr height="20">
										<td><?php echo $registros['mat_nombre'] ?></td>
										<td><?php echo $registros['nombre'] ?></td>
										<td align="right"><?php echo $registros['inv_kg_totales'] ?>&nbsp;</td>
										<td><?php echo $registros['inv_fecha'] ?></td>
										<td><input type="hidden" name="hdd_inv_<?php echo $ren; ?>" id="hdd_inv_<?php echo $ren; ?>" value="<?php echo $registros['inv_id']; ?>">
											<?php if (fnc_permiso($_SESSION['privilegio'], 30, 'upe_editar') == 1) { ?>

												<select id="cbx_proceso_<?php echo $ren; ?>" class="form-control chk_baja" name="cbx_proceso_<?php echo $ren; ?>">
													<option value="">Seleccionar</option>
													<?php
													$cad_pro =  mysqli_query($cnx, "SELECT pro_id from procesos WHERE pro_estatus = '1' ");
													$reg_pro =  mysqli_fetch_array($cad_pro);
													do { ?>
														<option value="<?php echo $reg_pro['pro_id'] ?>"> <?php echo $reg_pro['pro_id'] ?> </option>
													<?php   } while ($reg_pro =  mysqli_fetch_array($cad_pro)); ?>
												</select>
											<?php } ?>
										</td>
										<td align="center">
											<?php if (fnc_permiso($_SESSION['privilegio'], 30, 'upe_editar') == 1) { ?>
												<textarea class="form-control txt_comentario" name="txt_comentario<?php echo $ren; ?>" id="txt_comentario<?php echo $ren; ?>" cols="30" rows="2"></textarea>
											<?php } ?>
										</td>
								<?php
									$ren += 1;
									$flt_kg += $registros['inv_kg_totales'];
								}
							} while ($registros = mysqli_fetch_assoc($cadena)); ?>
									<tr>
										<td style="text-align: right;font-weight: bold;font-size: 18px;border-left: 2px solid#fff;border-bottom: 1px solid#fff" colspan="2">
											<input type="hidden" value="<?php echo $ren; ?>" name="hdd_cont" , id="hdd_cont">
											Total:
										</td>
										<td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo number_format($flt_kg, 2); ?>&nbsp;</td>
										<td align="right" colspan="2">
											<input name="hdd_cont" id="hdd_cont" type="hidden" value="<?php echo $ren; ?>">
											<div class="alert alert-info hide" id="alerta-errorMatBaja2" style="height: 40px;width: 300px;text-align: left;margin-top: 10px">
												<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
												<strong>Titulo</strong> &nbsp;&nbsp;
												<span> Mensaje </span>
											</div>
										</td>
										<?php if (fnc_permiso($_SESSION['privilegio'], 30, 'upe_editar') == 1) { ?>

											<td> <button class="btn btn-primary" type="submit"><img src="../../iconos/guardar.png" alt=""> Guardar</button></td>
										<?php }?>
									</tr>
						</tbody>
						<tfoot>
						</tfoot>
					</table>
				</div>
			</center>
		</div>

		<?php include "../../generales/pie_pagina_formato.php"; ?>
	</form>
</body>

</html>