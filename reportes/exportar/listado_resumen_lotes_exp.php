<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
extract($_POST); 

$cadena = mysqli_query($cnx, "SELECT DISTINCT lote_mes, YEAR(lote_fecha) as fecha  FROM lotes") or die(mysql_error()."Error: en consultar el inventario");
$registros = mysqli_fetch_assoc($cadena);

header('Content-type: application/vnd.ms-excel');

header("Content-Disposition: attachment; filename=resumen mensual de reporte general ".date("Y-m-d")."'.xls");

header("Pragma: no-cache");
header("Expires: 0");

?>
<!DOCTYPE html>
<html lang="en" >

<head>
	<meta charset="UTF-8">
	<title>Listado de Inventario <?php echo date("d-m-Y"); ?></title>
	<!--<link rel="stylesheet" href="../../css/estilos_formatos.css">-->
  <style type="text/css">
    td{
      border: 1px solid #e6e6e6;
    }
  </style>
</head>

<body>
  <div class="container">
   <table >
	<thead style="background: #F4F3F3;">
	  <tr style="background: #F4F3F3;">
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
   <table style="border:1px solid #e6e6e6">
    <tr style="border:1px solid #e6e6e6">
      <td style="border:1px solid #fff;background: #F4F3F3" colspan="3" align="center" >
        <footer style="background: #F4F3F3; width: 100%; text-align: center; position: fixed; bottom: 0; color:#fff;font-weight: bold;font-size: 9px">
          Copyright 2018 by <b>Ca & Ce Technologies</b>. All Rights Reserved. 
        </footer>
      </tr>
    </table>
    <p>&nbsp;</p>
  </div>
</body>
</html>