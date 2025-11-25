<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
						 FROM preparacion_tipo ") or die(mysql_error()."Error: en consultar los tipos de proceso");
$registros = mysqli_fetch_assoc($cadena);

?>
<!DOCTYPE html>
<html lang="en" >

<head>
	<meta charset="UTF-8">
	<title>Listado de Tipos proceso</title>
	<link rel="stylesheet" href="../../css/estilos_formatos.css">

</head>

<body>
	<div class="container">
		<center>

<div class="tablehead">
	<table>
		<tr>
			<td><img src="../../imagenes/logo_progel_v3.png"></td>
			<td><h1>Listado de tipos proceso</h1></td>
		</tr>
		<tr></tr>
	</table>

</div>

<div class="tablecuerpo">
	

<table>
	<thead>
	  <tr>
		<th>Clave</th>
		<th>Descripción</th>
		<th>Revisión</th>
		<th>Estatus</th>
	  </tr>
	</thead>
	<tbody>
	<?php 
	$ren = 1;
	do{?>
	  <tr>
	   <td><?php echo $registros['pt_id'] ?></td>
	   <td><?php echo $registros['pt_descripcion'] ?></td>
	   <td><?php echo $registros['pt_revision'] ?></td>
	   <td><?php if($registros['pt_estatus'] == 'A'){echo "Activo";}else{ echo "Baja";} ?></td>
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