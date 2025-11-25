<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT * FROM materiales_tipo_obj AS o
								INNER JOIN materiales_tipo AS t ON (o.mt_id = t.mt_id)  where mto_fecha >= '2024-01-01' ") or die(mysql_error()."Error: en consultar los tipos de proceso");
$registros = mysqli_fetch_assoc($cadena);

?>
<!DOCTYPE html>
<html lang="en" >

<head>
	<meta charset="UTF-8">
	<title>Listado de Materiales Objetivo</title>
	<link rel="stylesheet" href="../../css/estilos_formatos.css">

</head>

<body>
	<div class="container">
		<center>

<div class="tablehead">
	<table>
		<tr>
			<td><img src="../../imagenes/logo_progel_v3.png"></td>
			<td><h1>Listado de materiales objetivo</h1></td>
		</tr>
		<tr></tr>
	</table>

</div>

<div class="tablecuerpo">
	

<table style="width: 1000px">
	<thead>
	  <tr>
		<th>Clave</th>
		<th>&nbsp;Origen material&nbsp;</th>
		<th>&nbsp;Kilos&nbsp;</th>
		<th>&nbsp;Fecha&nbsp;</th>
	  </tr>
	</thead>
	<tbody>
	<?php 
	$ren = 1;
	do{?>
	  <tr>
	   <td><?php echo $registros['mto_id'] ?></td>
	   <td><?php echo $registros['mt_descripcion'] ?></td>
	   <td><?php echo $registros['mto_kilos'] ?></td>
	   <td><?php echo fnc_formato_fecha($registros['mto_fecha']); ?></td>
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