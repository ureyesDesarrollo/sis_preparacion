<!--<link rel="stylesheet" href="../../css/promedio_formatos.css">-->
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
<?php 

include '../formatos/funciones_reporte.php';
include '../../funciones/funciones.php';
include('../../seguridad/user_seguridad.php');

$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT * FROM quimicos_etapas") or die(mysql_error()."Error: en consultar quimicos");
$registros = mysqli_fetch_assoc($cadena);
header('Content-type: application/vnd.ms-excel');

header("Content-Disposition: attachment; filename=listado_quimicos.xls");


header("Pragma: no-cache");
header("Expires: 0");

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>

<div class="rol">
	<table  cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_quimicos">
		<thead>
			<tr align="center">
				<th>&nbsp;Proceso&nbsp;</th>
				<th>&nbsp;Etapa&nbsp;</th>
				<th>&nbsp;Tipo químico&nbsp;</th>
				<th>&nbsp;Lote&nbsp;</th>
				<th>&nbsp;Litros&nbsp;</th>
				<th>Usuario</th>
				
			</tr>
		</thead>
		<tbody>
			<?php 
			$ren = 1;
			do{
				$cad_etapa = mysqli_query($cnx, "SELECT * FROM preparacion_etapas WHERE pe_id = '$registros[pe_id]'") or die(mysql_error()."Error: en consultar quimicos");
$reg_etapa = mysqli_fetch_assoc($cad_etapa);
?>
				<tr height="20">
					<td><?php echo $registros['pro_id'] ?></td>
					<td><?php echo $reg_etapa['pe_descripcion'] ?></td>
					<td>
						<?php 
						if ($registros['quim_tipo'] == '1') {
							echo "Ácido";
						} 
						if ($registros['quim_tipo'] == '2') {
							echo "Enzima";
						} 
						if ($registros['quim_tipo'] == '3') {
							echo "Enzima liquida";
						} 
						if ($registros['quim_tipo'] == '4') {
							echo "Sosa";
						} 
						if ($registros['quim_tipo'] == '5') {
							echo "Peróxido";
						} 
						?>
					</td>
					<td><?php echo $registros['quim_lote'] ?></td>
					<td><?php echo $registros['quim_litros'] ?></td>
					<td><?php echo fnc_nom_usuario($registros['usu_id']); ?></td>
				</tr>
				<?php 
				$ren += 1;

			}while($registros = mysqli_fetch_assoc($cadena));?>

		</tbody>

		<tfoot>
			<?php for($i=$ren; $i <= 12; $i++){?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			<?php }?>
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

	
</body>
</html>