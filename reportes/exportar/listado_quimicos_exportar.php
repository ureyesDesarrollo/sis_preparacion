<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx =  Conectarse();

extract($_GET);

if($fechaIni != '' and $fechaFin == ''){
	$filtro = "and SUBSTRING(quim_fecha,1,10) = '$fechaIni'";
}

if($fechaIni != '' and $fechaFin != '' ){
	$filtro .= "and SUBSTRING(quim_fecha,1,10) >= '$fechaIni' and SUBSTRING(quim_fecha,1,10) <= '$fechaFin'";
}

if($proceso != ''){
	$filtro .= "and pro_id = '$proceso'";
}

if($quimico != ''){
	$filtro .= "and quimico_id = '$quimico'";
}

    $cadena = mysqli_query($cnx, "SELECT q.*, SUBSTRING(q.quim_fecha, 1, 16) as quim_fecha FROM quimicos_etapas as q WHERE q.quim_id >= 1 $filtro ") or die(mysql_error()."Error: en consultar quimicos");
   $registros = mysqli_fetch_assoc($cadena);
 $tot = mysqli_num_rows($cadena);
 


header('Content-type: application/vnd.ms-excel');

header("Content-Disposition: attachment; filename=reporte_quimicos.xls");


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

	<div class="tablecuerpo" id="tab2">
		<table  cellpadding="0" cellspacing="0" border="1" class="display" id="tabla_quimicos">
					<thead style="background: #e6e6e6">
						<tr align="center">
							<th style="background: #e6e6e6">&nbsp;Proceso&nbsp;</th>
							<th style="background: #e6e6e6">&nbsp;Etapa&nbsp;</th>
							<th style="background: #e6e6e6">&nbsp;Tipo químico&nbsp;</th>
							<th style="background: #e6e6e6">&nbsp;Lote&nbsp;</th>
							<th style="background: #e6e6e6">&nbsp;Litros&nbsp;</th>
							<th style="background: #e6e6e6">Usuario</th>
							<th style="background: #e6e6e6" width="15%">Fecha y Hora</th>

						</tr>
					</thead>
					<tbody>
						<?php 
						$ren = 1;
						do{
							$cad_etapa = mysqli_query($cnx, "SELECT * FROM preparacion_etapas WHERE pe_id = '$registros[pe_id]'") or die(mysql_error()."Error: en consultar quimicos");
							$reg_etapa = mysqli_fetch_assoc($cad_etapa);

							$cad_quim = mysqli_query($cnx, "SELECT * FROM quimicos WHERE quimico_id = '$registros[quimico_id]'") or die(mysql_error()."Error: en consultar quimicos");
							$reg_quim = mysqli_fetch_assoc($cad_quim);

							?>
							<tr height="20">
								<td><?php echo $registros['pro_id'] ?></td>
								<td><?php echo $reg_etapa['pe_nombre'].' ('.$reg_etapa['pe_descripcion'].")"; ?></td>
								<td>
									<?php echo $reg_quim['quimico_descripcion'] ?>
								</td>
								<td><?php echo $registros['quim_lote'] ?></td>
								<td><?php echo $registros['quim_litros'] ?></td>
								<td><?php echo fnc_nom_usuario($registros['usu_id']); ?></td>
								<td><?php echo $registros['quim_fecha'] ?></td>
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
							<th></th>
						</tr>
					</tfoot>
				</table>
	</body>
	</html>