<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
//extract($_POST); 

$fechaIni = $_GET['fechaIni'];

$cadena = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE inv_fecha = '$fechaIni' AND prv_tipo = 'E' AND inv_id_key is NUL") or die(mysql_error()."Error: en consultar el inventario 1");
$registros = mysqli_fetch_assoc($cadena);


$cadena2 = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE inv_fecha = '$fechaIni' AND prv_tipo = 'L'") or die(mysql_error()."Error: en consultar el inventario 2");
$registros2 = mysqli_fetch_assoc($cadena2);

$cadena3 = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE inv_fe_recibe = '$fechaIni' and i.inv_enviado = 2") or die(mysql_error()."Error: en consultar el inventario 2");
$registros3 = mysqli_fetch_assoc($cadena3);

$cad_scat = mysqli_query($cnx, "SELECT DISTINCT t.mt_id, t.mt_descripcion
  FROM materiales_tipo AS t
  INNER JOIN materiales as m ON(t.mt_id = m.mt_id)
  INNER JOIN inventario AS i ON ( m.mat_id = i.mat_id ) 
  WHERE i.inv_fecha = '$fechaIni' "); 
$reg_scat = mysqli_fetch_array($cad_scat);

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=inventario_".$fechaIni.".xls");
header("Pragma: no-cache");
header("Expires: 0");


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
       <table width="" style="border: 1px solid #fff; background:#e6e6e6">
        <tr style="border: 1px solid #e6e6e6">
         <td style="border: 1px solid #e6e6e6"><img src="../../imagenes/logo_progel_v3.png"></td>
         <td colspan="11" align="center" style="border: 1px solid #e6e6e6"><h1>ENTRADAS DE MATERIA PRIMA</h1></td>
         <td style="border: 1px solid #fff"><p>FECHA:<?php echo $fechaIni; ?></p>
          <p>REF F 001-REV.000</p></td>
        </tr>
      </table>
      <p>&nbsp;</p>
    </div>


    <!--TABLA EXTRANJERO-->
    <table width="1497"  style="border: 1px solid #000;width: ">
      <thead>
      </thead>
      <thead>
        <tr  style="background: #fff;color: #000;text-align: center;">
          <th colspan="13">ENTRADA DE CAMIONES AMERICANOS A BODEGA</th>
        </tr>
        <tr style="background: #32383e;color: #fff">
          <th width="24">No.</th>
          <th width="68">No. Ticket</th>
          <th width="119">Placas/Camioneta</th>
          <th width="124">Proveedor</th>
          <th width="60">No. factura</th>
          <th width="80">Fe. Ent. Bodega</th>
          <th width="81">Material</th>
          <th width="60">Peso factura</th>
          <th width="60">Kgs de entrada</th>
          <th width="80">% Merma max</th>
          <th width="91">No. tarimas/sacos</th>
          <th width="91">Prueba de secador</th>
          <!--<th width="100">Kilos a pagar c/desc</th>-->
          <th width="86">Calidad material</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $ren = 1;
        $cont=1;

        do{?>
          <tr style="border: 1px solid">
            <td><?php echo $cont++?></td>
            <td><?php echo $registros['inv_no_ticket'] ?></td>
            <td><?php echo $registros['inv_placas']."-".$registros['inv_camioneta'] ?></td>
            <td><?php echo $registros['prv_nombre'] ?></td>
            <td><?php echo $registros['inv_no_factura']?></td>
            <td width="80" align="center"><?php echo fnc_formato_fecha($registros['inv_fecha']) ?></td>
            <td><?php echo $registros['mat_nombre']?></td>
            <td><?php echo $registros['inv_peso_factura']?></td>
            <td align="right"><?php echo $registros['inv_kilos']?></td>
            <td align="right"><?php echo $registros['inv_por_merma']?></td>
            <td><?php echo $registros['inv_no_tarimas']." / ".$registros['inv_no_sacos']?></td>        
            <td><?php echo $registros['inv_prueba']?></td>
            <!-- <td><?php //echo $registros['inv_kg_totales']?></td>-->
            <td>
             <?php 
             if($registros['inv_calidad']  == 'P'){
              echo "Poco";
            }

            if($registros['inv_calidad']  == 'N'){
              echo "Nada";
            }

            if($registros['inv_calidad']  == 'M'){
              echo "Mucho";
            }

            if($registros['inv_calidad']  == 'X'){
              echo "N/A";
            }
            ?>
          </td>

        </tr>
        <?php 
        $ren += 1;
        $flt_kg_inv += $registros['inv_kilos'];
      }while($registros = mysqli_fetch_assoc($cadena));?>
      <tr>
        <td colspan="10" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff"></td>
        <td style="text-align: right;font-weight: bold;font-size: 18px;border-left: 2px solid#fff;border-bottom: 1px solid#fff">Total</td>
        <td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo $flt_kg_inv; ?></td>
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
  <table  style="border: 1px solid #000;width: ">
   <thead>
   </thead>
   <thead>
    <tr style="background: #fff;color: #000;text-align: center;">
      <th colspan="13">ENTRADA PROVEEDORES LOCALES</th>
    </tr>
    <tr style="background: #32383e;color: #fff">
     <th width="24">No.</th>
     <th width="64">No. Ticket</th>
     <th width="119">Placas/Camioneta</th>
     <th width="134">Proveedor</th>
     <th width="77">Fecha entrada</th>
     <th width="73">Material</th>
     <th width="73">Kgs de entrada</th>
     <th width="89">Prueba de secador</th>
     <th width="79">Desc por agua</th>
     <th width="73">% Descarne</th>
     <th width="91">Desc por rendimi.</th>
     <th width="94">Kg a pagar c/desc</th>
     <th width="80">Calidad material</th>

   </tr>
 </thead>
 <tbody>
  <?php 
  $ren = 1;
  $cont=1;
  do{?>
    <tr style="border: 1px solid">
      <td><?php echo $cont++?></td>
      <td><?php echo $registros2['inv_no_ticket'] ?></td>
      <td><?php echo $registros2['inv_placas']."-".$registros2['inv_camioneta'] ?></td>
      <td><?php echo $registros2['prv_nombre'] ?></td>
      <td align="center"><?php echo fnc_formato_fecha($registros2['inv_fecha']) ?></td>
      <td><?php echo $registros2['mat_nombre']?></td>
      <td><?php echo $registros2['inv_kilos']?></td>
      <td><?php echo $registros2['inv_prueba']?></td>
      <td><?php echo $registros2['inv_desc_ag']?></td>
      <td><?php echo $registros2['inv_desc_d']?></td>
      <td><?php echo $registros2['inv_desc_ren']?></td>
      <td align="right"><?php echo $registros2['inv_kg_totales']?></td>
      <td>
       <?php 
       if($registros2['inv_calidad']  == 'P'){
        echo "Poco";
      }

      if($registros2['inv_calidad']  == 'N'){
        echo "Nada";
      }

      if($registros2['inv_calidad']  == 'M'){
        echo "Mucho";
      }

      if($registros2['inv_calidad']  == 'X'){
        echo "N/A";
      }
      ?>
    </td>
  </tr>
  <?php 
  $ren += 1;
  $flt_kg_inv2 += $registros2['inv_kg_totales'];
}while($registros2 = mysqli_fetch_assoc($cadena2));?>
<tr>
  <td colspan="10" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff;border-right: 1px solid#fff"></td>
  <td style="text-align: right;font-weight: bold;font-size: 18px;border-bottom: 1px solid#fff;">Total</td>
  <td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo $flt_kg_inv2; ?></td>
</tr>
</tbody>
<tfoot>
  <?php for($i=$ren; $i <= 40; $i++){?>
  <?php }?>
</tfoot>
<thead>
</thead>
</table>
    <!--TABLA DEPILADOS A BODEGA-->
    <p>&nbsp;</p>
    <table  style="border: 1px solid #000;width: ">
    	<thead>
      </thead>
      <thead>
        <tr style="background: #fff;color: #000;text-align: center;">
          <th colspan="13">ENTRADA A PLANTA DE CUERO DEPILADO DE BODEGA</th>
        </tr>
        <tr style="background: #32383e;color: #fff">
         <th width="24">No.</th>
         <th width="110">No. Ticket</th>
         <th colspan="3" width="354">Proveedor</th>
         <th  colspan="2" width="220">Fe. Ent. de Maquila</th>
         <th colspan="2" width="372">Material</th>
         <th colspan="2" width="120">Kgs de salida</th>
         <th colspan="2" width="120">Kgs de entrada</th>
       </tr>
     </thead>
     <tbody>
      <?php 
      $ren = 1;
      $cont = 1;
      do{?>
        <tr style="border: 1px solid">
          <td><?php echo $cont++?></td>
          <td><?php echo $registros3['inv_no_ticket'] ?></td>
          <td colspan="3"><?php echo $registros3['prv_nombre'] ?></td>
          <td colspan="2" align="center"><?php echo fnc_formato_fecha($registros3['inv_fe_recibe']); ?></td>
          <td colspan="2"><?php echo $registros3['mat_nombre']?></td>
          <td colspan="2"><?php echo $registros3['inv_kilos']?></td>
          <td colspan="2"><?php echo $registros3['inv_kg_totales']?></td>
            </tr>
            <?php 
            $ren += 1;
            $flt_kg_inv3 += $registros3['inv_kg_totales'];
          }while($registros3 = mysqli_fetch_assoc($cadena3));?>
          <tr>
            <td colspan="11" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff;border-right: 1px solid#fff"></td>
            <td style="text-align: right;font-weight: bold;font-size: 18px;border-bottom: 1px solid#fff;">Total</td>
            <td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo $flt_kg_inv3; ?></td>
          </tr>
        </tbody>
        <tfoot>
          <?php for($i=$ren; $i <= 40; $i++){?>
          <?php }?>
        </tfoot>
        <thead>
        </thead>
      </table>
      <p>&nbsp;</p>
      <table width="2299" border="1">
        <tr style="font-weight: bold;font-size: 20px;text-align: right;border-solid:2px solid#000 ">
          <td  colspan="7">&nbsp;</td>
          <td colspan="4" >Total de entrada a planta</td>
          <td ><?php echo $flt_kg_inv + $flt_kg_inv2 + $flt_kg_inv3; ?></td>
          <td width="90" style="border-right: 1px solid#fff;border-bottom: 1px solid#fff;border-top: 1px solid#fff"></td>
        </tr>
      </table>
      <p>&nbsp;</p>


      <!--TABLA TOTALES POR TIPO DE PRODUCTO-->
      <table width="100%">
        <tr>
          <td width="41%" valign="top">
          <table>
            <tr style="background: #32383e;color: #fff"><td colspan="5" align="center">Entrada cuero del día</td></tr>
            <tr style="background: #32383e;color: #fff">
              <td width="11%" colspan="2">Tipo de material</td>
              <td width="10%">Objetivo(Entrada)</td>
              <td width="10%">Real(A pagar)</td>
              <td width="10%">Diferencia</td>
            </tr>

            <?php do { 
              //Obtiene el objetivo por tipo de material
              $inv_obj = mysqli_query($cnx, "SELECT mto_kilos as res
                FROM materiales_tipo_obj
                WHERE mt_id = '$reg_scat[mt_id]' and mto_fecha = '$fechaIni'
                ") or die(mysql_error()."Error: en consultar el inventario 3");
              $reginv_obj = mysqli_fetch_assoc($inv_obj);


              $inv = mysqli_query($cnx, "SELECT sum(i.inv_kg_totales)as res2 
                FROM inventario as i 
                INNER JOIN materiales as m  ON(i.mat_id = m.mat_id)
                WHERE m.mt_id = '$reg_scat[mt_id]' and inv_fecha = '$fechaIni'
                ") or die(mysql_error()."Error: en consultar el inventario 3");
              $reginv = mysqli_fetch_assoc($inv);
              ?>
              <tr>
                <td colspan="2"><?php echo $reg_scat['mt_descripcion']; ?></td>
                <td><?php echo $reginv_obj['res'];?></td>
                <td><?php echo $reginv['res2'];?></td>
                <td><?php echo $reginv_obj['res'] - $reginv['res2']; ?></td>
              </tr>
              <?php
              $flt_kilos += $reginv_obj['res'];
              $flt_kilos2 += $reginv['res2'];
              ?>
            <?php }while($reg_scat = mysqli_fetch_array($cad_scat));?>
            <tr style="font-weight: bold;">
              <td colspan="2">Totales</td>
              <td><?php echo $flt_kilos; ?></td>
              <td><?php echo $flt_kilos2; ?></td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </td>
          <td width="11%" colspan="2"></td>          


        <td width="48%" valign="top"><table border="1" cellspacing="0" cellpadding="0" style="font-size:14px; color:#666666" width="554">
            <tr style="background: #32383e;color: #fff">
              <td colspan="6" align="center">PARAMETROS DE SECADOR </td>
            </tr>
            <tr style="background: #32383e;color: #fff">
              <td width="100">Material</td>
              <td width="100">Carnaza</td>
              <td width="100">Recorte</td>
              <td width="100">Cerdo</td>
              <td width="100">Ped. Local </td>
              <td width="100">Ped. Americana </td>
            </tr>
            <tr>
              <td rowspan="2">Rendimiento</td>
              <td rowspan="2">MIN. 27 </td>
              <td rowspan="2">MIN. 27 </td>
              <td rowspan="2">MIN. 26 </td>
              <td rowspan="2">MIN. 27 </td>
              <td rowspan="2">MIN. 50 </td>
            </tr>
          </table>
        </table>

      </td>





    </center>
  </div>
  <p>&nbsp;</p>
  <table style="border:1px solid #fff">
    <tr style="border:1px solid #fff">
      <td style="border:1px solid #fff" colspan="13" align="center" ><?php include "../../generales/pie_pagina_formato.php";?></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</body>
</html>