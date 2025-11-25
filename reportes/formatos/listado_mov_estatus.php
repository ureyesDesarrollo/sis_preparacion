<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT * from bitacora_cambio_estatus") or die(mysql_error()."Error: en consultar el inventario");
$registros = mysqli_fetch_assoc($cadena);

?>
<!DOCTYPE html>
<html lang="en" >

<head>
	<meta charset="UTF-8">
	<title>Listado movimientos de estatus</title>
	<link rel="stylesheet" href="../../css/estilos_formatos.css">

</head>

<body>
	<div class="container">
		<center>

<div class="tablehead">
	<table>
		<tr>
			<td><img src="../../imagenes/logo_progel_v3.png"></td>
			<td><h1>Listado movimientos de estatus</h1></td>
		</tr>
		<tr></tr>
	</table>

</div>

<div class="tablecuerpo">
<table>
	<thead>
	  <tr>
		<th>&nbsp;Ren&nbsp;</th>
		<th>&nbsp;Usuario&nbsp;</th>
		<th>&nbsp;Fecha&nbsp;</th>
		<th>&nbsp;Est. Actual&nbsp;</th>
		<th>&nbsp;Est. Nuevo&nbsp;</th>
		<th>&nbsp;Paleto&nbsp;</th>
		<th>&nbsp;Lavador&nbsp;</th>
		<th>&nbsp;Comentarios&nbsp;</th>
	  </tr>
	</thead>
	<tbody>
	<?php 
	$ren = 1;
	
	do{?>
	  <tr>
	   <td align="center"><?php echo $registros['bce_id'] ?></td>
	   <td><?php echo fnc_nom_usuario($registros['usu_id']); ?></td>
	   <td><?php echo fnc_formato_fecha_hr($registros['bce_fecha']) ?></td>
	   <td><?php echo fnc_nom_estatus($registros['bce_est_actual']); ?></td>
	   <td><?php echo fnc_nom_estatus($registros['bce_est_nuevo']); ?></td>
	   <td><?php echo $registros['pp_id'] ?></td>
	   <td><?php echo $registros['pl_id'] ?></td>
	   <td><?php echo $registros['bce_descripcion'] ?></td>
	  </tr>
	  <?php 
	  $ren += 1;
	  }while($registros = mysqli_fetch_assoc($cadena));?>	 
  </tbody>
   <tfoot>
   <?php for($i=$ren; $i <= 40; $i++){?>

	<?php }?>
	</tfoot>
</table>
</div>
	</center>	
	</div>
	
	<?php include "../../generales/pie_pagina_formato.php";?>
</body>
</html>