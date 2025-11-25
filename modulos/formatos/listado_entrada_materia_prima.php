<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include('../../seguridad/user_seguridad.php');
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

//$str_fecha = date("Y-m-d");
$str_fecha = "2023-10-09";

$cadena = mysqli_query($cnx, "SELECT  c.mt_descripcion, o.mt_id 
FROM materiales_tipo_obj as o
INNER JOIN materiales_tipo as c on (o.mt_id = c.mt_id)
where o.mto_fecha = '".$str_fecha."'
group by c.mt_id 
ORDER BY c.mt_descripcion ") or die(mysqli_error($cnx) . "Error: en consultar el material");
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
				var formData = $(this).serialize();
				//alert(formData);
				$.ajax({
					url: "../materiales_disp_dev.php",
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
			});
		});
	</script>
</head>

<body>
	
	<table class="table">
		<tr>
			<th colspan="7" style="text-align: center;">
				<img src="../../imagenes/logo_progel_v3.png" style="width: 80px">
				<h4 style="display: inline;">Entrada de material prima por semana</h4>
			</th>
			<th style="width: 10%;">Fecha: <?php echo $str_fecha;?></th>
		</tr>
	</table>
	<?php do{ 
		
		$cad_prov = mysqli_query($cnx, "SELECT p.prv_nombre, o.mto_kilos, p.prv_id 
		FROM materiales_tipo_obj as o
		INNER JOIN proveedores as p ON (o.prv_id = p.prv_id)
		where o.mto_fecha = '".$str_fecha."' and o.mt_id = '$registros[mt_id]'
		ORDER BY p.prv_nombre ") or die(mysqli_error($cnx) . "Error: en consultar el material");
		$reg_prov = mysqli_fetch_assoc($cad_prov);

	?>
	<table class="table table-bordered" style="width: 100%;margin-top:2rem">
		<thead>
			<tr>
				<th colspan="11" style="text-align: center;"><?php echo $registros['mt_descripcion']; ?></th>
			</tr>
			<tr>
				<th rowspan="2">Proveedor</th>
				<th rowspan="2">Objetivo entrada<br> x semana</th>
				<th>Lunes</th>
				<th>Martes</th>
				<th>Miercoles</th>
				<th>Jueves</th>
				<th>Viernes</th>
				<th>Sabado</th>
				<th rowspan="2">Total acumulado de la semana</th>
				<th rowspan="2">Diferencia positiva</th>
				<th rowspan="2">Diferencia negativa</th>
			</tr>
			<tr>
					<td><?php $lunes = $str_fecha; echo $lunes;?></td>
					<td><?php $martes = date("Y-m-d",strtotime($str_fecha."+ 1 days")); echo $martes; ?></td>
					<td><?php $miercoles = date("Y-m-d",strtotime($str_fecha."+ 2 days")); echo $miercoles; ?></td>
					<td><?php $jueves = date("Y-m-d",strtotime($str_fecha."+ 3 days")); echo $jueves; ?></td>
					<td><?php $viernes = date("Y-m-d",strtotime($str_fecha."+ 4 days")); echo $viernes; ?></td>
					<td><?php $sabado = date("Y-m-d",strtotime($str_fecha."+ 5 days")); echo $sabado; ?></td>
				</tr>
		</thead>
		<tbody>
			<?php 
			$ci = $flt_kg_ob = $tot_lunes = $tot_martes = 0;
			do{?>
				<tr>
					<td><?php echo $reg_prov['prv_nombre'] ?></td>
					<td><?php echo $reg_prov['mto_kilos'] ?></td>
					<td>
					<?php 
					$cad1 = mysqli_query($cnx, "SELECT SUM(i.inv_kg_totales) as tot 
						FROM inventario as i
						INNER JOIN materiales as m on (i.mat_id = m.mat_id)
						WHERE i.inv_fecha >= '$lunes' AND i.inv_fecha < '$martes' and i.inv_enviado in (0,2) and i.prv_id = '$reg_prov[prv_id]' and m.mt_id = '$registros[mt_id]'") or die(mysqli_error($cnx)."Error de sistema 1");
					$reg1 = mysqli_fetch_array($cad1);
					$tot_lunes += $reg1['tot'];
					echo $reg1['tot'];
					?>
					</td>
					<td><?php 
					$cad2 = mysqli_query($cnx, "SELECT SUM(i.inv_kg_totales) as tot 
						FROM inventario as i
						INNER JOIN materiales as m on (i.mat_id = m.mat_id)
						WHERE i.inv_fecha >= '$martes' AND i.inv_fecha < '$miercoles' and i.inv_enviado in (0,2) and i.prv_id = '$reg_prov[prv_id]' and m.mt_id = '$registros[mt_id]'") or die(mysqli_error($cnx)."Error de sistema 1");
					$reg2 = mysqli_fetch_array($cad2);
					$tot_martes += $reg2['tot'];
					echo $reg2['tot'];
					?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			<?php
			$ci += 1;
			$flt_kg_ob += $reg_prov['mto_kilos'];
			}while($reg_prov = mysqli_fetch_assoc($cad_prov));

			for ($i = $ci; $i <= 5; $i++) { ?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			<?php	}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td>Objetivo de semana</td>
				<td><?php echo $flt_kg_ob * 6;?></td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Objetivo de día</td>
				<td><?php echo $flt_kg_ob;?></td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Entrada del día</td>
				<td>-</td>
				<td><?php echo $tot_lunes;?></td>
				<td><?php echo $tot_martes;?></td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tfoot>
	</table>
	<?php }while($registros = mysqli_fetch_assoc($cadena));?>
	</div>


	</form>
	</div>

	<?php include "../../generales/pie_pagina_formato.php"; ?>
</body>

</html>