<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE p.prv_tipo = 'L' and inv_enviado = 1 and p.prv_ban = 1 and inv_tomado = 0") or die(mysql_error()."Error: en consultar el inventario 1");
$registros = mysqli_fetch_assoc($cadena);
?>
<!DOCTYPE html>
<html lang="en" >

<head>
	<meta charset="UTF-8">
	<title>Listado de Inventario <?php echo date("d-m-Y"); ?></title>
	<link rel="stylesheet" href="../../css/estilos_formatos.css">
  <style type="text/css">
  td{
    border: 1px solid #000
  }
</style>
</head>

<body>
	<div class="container">
		<center>
      <div class="tablehead" style="width: 1200px">
       <table style="border: 1px solid #fff">
        <tr style="border: 1px solid #fff">
         <td style="border: 1px solid #fff"><img src="../../imagenes/logo_progel_v3.png"></td>
         <td style="border: 1px solid #fff"><h1>Inventario en maquila al día <?php echo fnc_formato_fecha(date("Y-m-d")); ?></h1></td>
       </tr>
       <tr></tr>
     </table>
   </div>


   <!--TABLA EXTRANJERO-->
   <table  style="border: 1px solid #000;width: 1200px">
  <thead>
    </thead>
    <thead>
      <tr style="background: #fff;color: #000;text-align: center;">
        <th colspan="15">ENTRADA DE CAMIONES LOCALES A MAQUILA</th>
      </tr>
      <tr>
        <th width="24">No.</th>
        <th width="68">No. Ticket</th>
        <th width="119">Placas/Camioneta</th>
        <th width="124">Proveedor</th>
        <th width="60">No. factura</th>
        <th width="80">Fecha entrada</th>
        <th width="81">Material</th>
        <th width="60">Peso factura</th>
        <th width="60">Kgs de entrada</th>
        <th width="80">% Merma max</th>
        <th width="91">No. tarimas/sacos</th>
        <th width="91">Prueba de secador</th>
        <th width="100">Kilos a pagar c/desc</th>
        <th width="86">Calidad material</th>
      </tr>
    </thead>
    <tbody>
      <?php 
  $ren = 1;
  $cont=1;
  $flt_kg =	$flt_kg_t = 0;
  do{?>
      <tr style="border: 1px solid">
        <td><?php echo $cont++?></td>
        <td><?php echo $registros['inv_no_ticket'] ?></td>
        <td><?php echo $registros['inv_placas']."-".$registros['inv_camioneta'] ?></td>
        <td><?php echo $registros['prv_nombre'] ?></td>
        <td><?php echo $registros['inv_no_factura']?></td>
        <td width="23" align="center"><?php echo fnc_formato_fecha($registros['inv_fecha']); ?></td>
        <td><?php echo $registros['mat_nombre']?></td>
        <td><?php echo $registros['inv_peso_factura']?></td>
        <td align="right"><?php echo $registros['inv_kilos']?></td>
        <td align="right"><?php echo $registros['inv_por_merma']?></td>
        <td><?php echo $registros['inv_no_tarimas']." / ".$registros['inv_no_sacos']?></td>        
        <td><?php echo $registros['inv_prueba']?></td>
        <td><?php echo $registros['inv_kg_totales']?></td>
         <?php 
        if($registros['inv_calidad']  == 'P'){?>
        <td><?php echo "Poco" ?></td>
        <?php
           }if($registros['inv_calidad']  == 'N'){?>
        <td><?php echo "Nada" ?></td>
        <?php }
        if($registros['inv_calidad']  == 'M'){?>
        <td><?php echo "Mucho" ?></td>
        <?php }?>
      </tr>
      <?php 
    $ren += 1;
    $flt_kg += $registros['inv_kilos'];
	$flt_kg_t += $registros['inv_kg_totales'];
    }while($registros = mysqli_fetch_assoc($cadena));?>
      <tr>
        <td colspan="7" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff"></td>
		<td style="text-align: right;font-weight: bold;font-size: 18px;border-left: 2px solid#fff;border-bottom: 1px solid#fff">Total</td>
		<td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo $flt_kg; ?></td>
		<td colspan="2" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff">&nbsp;</td>
        <td style="text-align: right;font-weight: bold;font-size: 18px;border-left: 2px solid#fff;border-bottom: 1px solid#fff">Total</td>
        <td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo $flt_kg_t; ?></td>
      </tr>
    </tbody>
    <tfoot>
      <?php for($i=$ren; $i <= 40; $i++){?>
      <?php }?>
    </tfoot>
    <thead>
    </thead>
</table>

    <!--TABLA LOCAL-->
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </center>
</div>
<p>&nbsp;</p>

<?php include "../../generales/pie_pagina_formato.php";?>
</body>
</html>