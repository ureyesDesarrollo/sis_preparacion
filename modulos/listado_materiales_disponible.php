<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT mat_nombre, inv_kg_totales, prv_nombre, inv_fecha  
							  FROM inventario as i
							  INNER JOIN materiales as m ON (i.mat_id = m.mat_id)
							  INNER JOIN proveedores as p ON (i.prv_id = p.prv_id)
							  WHERE inv_tomado = 0 AND inv_enviado IN (0,2) and inv_kg_totales > 0
							  ORDER BY m.mat_nombre ASC, inv_fecha ASC") or die(mysql_error()."Error: en consultar el material");
$registros = mysqli_fetch_assoc($cadena);

?>
<!DOCTYPE html>
<html lang="en" >

<head>
	<meta charset="UTF-8">
	<title>Listado de Materiales</title>
	<link rel="stylesheet" href="../../css/estilos_formatos.css">
	<style>
		@media print {
  footer{
  	height: 15px;
  }
}
	</style>
</head>

<body>
	<div class="container">
		<center>

<div class="tablehead">
	<table>
		<tr>
			<td><img src="../../imagenes/logo_progel_v3.png"></td>
			<td><h1>Listado de Materiales Disponible</h1></td>
		</tr>
		<tr></tr>
	</table>

</div>

<div class="tablecuerpo" style="margin-bottom: 60px">
	

<table>
	 <thead>
	    <tr>
	      <th>Material</th>
	      <th>Kilos</th>
	      <th>Proveedor</th>
	      <th>Fecha Entrada</th>
	    </tr>
	  </thead>
	<tbody>
	<?php 
	$ren = 1;
	$flt_kg  = 0;
	do{
	    ?>
		<tr height="20">
	     <td><?php echo $registros['mat_nombre'] ?></td>
	     <td align="right"><?php echo $registros['inv_kg_totales'] ?>&nbsp;</td>
	     <td><?php echo $registros['prv_nombre'] ?></td>
	     <td><?php echo $registros['inv_fecha'] ?></td>
	   </tr>
	  <?php 
	  $ren += 1;
	  $flt_kg += $registros['inv_kg_totales'];
	  }while($registros = mysqli_fetch_assoc($cadena));?>
	  <tr>
        <td style="text-align: right;font-weight: bold;font-size: 18px;border-left: 2px solid#fff;border-bottom: 1px solid#fff">Existencia Total:</td>
        <td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo number_format($flt_kg,2); ?>&nbsp;</td>
      </tr>	  
  </tbody>
   <tfoot>
	</tfoot>
</table>
</div>
	</center>	
	</div>
	
	<?php include "../../generales/pie_pagina_formato.php";?>
</body>
</html>