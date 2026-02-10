<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT DISTINCT lote_mes, YEAR(lote_fecha) as fecha  FROM lotes") or die(mysql_error()."Error: en consultar el inventario");
$registros = mysqli_fetch_assoc($cadena);

?>
<!DOCTYPE html>
<html lang="en" >

<head>
	<meta charset="UTF-8">
	<title>Listado movimientos de inventario</title>
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
			<td><h1>Resumen de reporte general</h1></td>
		</tr>
		<tr></tr>
	</table>

</div>

<div class="tablecuerpo" style="margin-bottom: 60px">
<table >
	<thead>
	  <tr>
		<th>Año</th>
		<th>Més</th>
		<th>Total de lotes</th>
	  </tr>
	</thead>
	<tbody>
	<?php 
	$ren = 1;
	
	do{
 $cad_sum = mysqli_query($cnx, "SELECT  lote_folio FROM lotes WHERE lote_mes = ".$registros['lote_mes']." ") or die(mysql_error()."Error: en consultar el inventario");
        $regSum = mysqli_fetch_assoc($cad_sum);
        $regSum = mysqli_num_rows($cad_sum);
		?>
	  <tr>
	   <td><?php echo $registros['fecha'] ?></td>
	   <td><?php echo $registros['lote_mes'] ?></td>
	   <td><?php echo $regSum  ?></td>
	  </tr>
	  <?php 
	  $ren += 1;
	  }while($registros = mysqli_fetch_assoc($cadena));  ?>	 
  </tbody>
   <tfoot>
   <?php for($i=$ren; $i <= 40; $i++){?>

	<?php }?>
	</tfoot>
</table>
</div>
<!--	</center>	
	</div>-->
	
	<?php include "../../generales/pie_pagina_formato.php";?>
</body>
</html>