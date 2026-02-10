<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT b.*, p.pep_nombre, tipo_alerta(b.ba_tipo) as tipox from bitacora_alertas as b
	 INNER JOIN preparacion_etapas_param as p on (b.pep_id = p.pep_id)") or die(mysql_error()."Error: en consultar el inventario");
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
			<td><h1>Listado alertas</h1></td>
		</tr>
		<tr></tr>
	</table>

</div>

<div class="tablecuerpo" style="margin-bottom: 60px">
<table >
	<thead>
	  <tr>
		<th>&nbsp;Clave&nbsp;</th>
		<th>&nbsp;Usuario&nbsp;</th>
		<th>&nbsp;Fecha&nbsp;</th>
		<th>&nbsp;Parametro&nbsp;</th>
		<th>&nbsp;Valor&nbsp;</th>
		<th>&nbsp;Proceso&nbsp;</th>
		<th>&nbsp;Etapa&nbsp;</th>
		<th>&nbsp;Tipo&nbsp;</th>
		<th>&nbsp;Observaciones&nbsp;</th>
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
	   <td><?php echo $registros['pep_tipo'] ?></td>
	   <td><?php echo $registros['ba_valor']; ?></td>
	   <td><?php echo $registros['pro_id'] ?></td>
	   <td><?php echo $registros['pep_descripcion']." (".$registros['pep_nombre'].")" ?></td>
	   <td><?php echo $registros['tipox'] ?></td>
	   <td><?php echo $registros['ba_comentarios'] ?></td>
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

	<?php include "../../generales/pie_pagina_formato.php";?>
</body>
</html>