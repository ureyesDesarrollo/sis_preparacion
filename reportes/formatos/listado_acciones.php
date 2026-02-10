<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT b.*, m.bm_descripcion from bitacora_acciones as b 
							inner join bitacora_modulos as m on (b.bm_id = m.bm_id)") or die(mysql_error()."Error: en consultar el inventario");
$registros = mysqli_fetch_assoc($cadena);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Listado bitácora de acciones</title>
	<link rel="stylesheet" href="../../css/estilos_formatos.css">

</head>

<body>
	<div class="container">
		<center>

<div class="tablehead">
	<table>
		<tr>
			<td><img src="../../imagenes/logo_progel_v3.png"></td>
			<td><h1>Listado bitácora de acciones</h1></td>
		</tr>
		<tr></tr>
	</table>

</div>

<div class="tablecuerpo">
<table>
	<thead>
	  <tr>
		<th>&nbsp;Clave&nbsp;</th>
		<th>&nbsp;Usuario&nbsp;</th>
		<th>&nbsp;Fecha&nbsp;</th>
		<th>&nbsp;Modulo&nbsp;</th>
		<th>&nbsp;Acción&nbsp;</th>
		<th>&nbsp;Valor&nbsp;</th>
	  </tr>
	</thead>
	<tbody>
	<?php 
	$ren = 1;
	
	do{?>
	  <tr>
	   <td align="center"><?php echo $registros['ba_id'] ?></td>
	   <td><?php echo fnc_nom_usuario($registros['usu_id']); ?></td>
	   <td><?php echo fnc_formato_fecha_hr($registros['ba_fecha']) ?></td>
	   <td><?php echo utf8_decode($registros['bm_descripcion']) ?></td>
	   <td><?php echo fnc_nom_accion($registros['ba_accion']); ?></td>
	   <td><?php echo $registros['ba_valor'] ?></td>
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