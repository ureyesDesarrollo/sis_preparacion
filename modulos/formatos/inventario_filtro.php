<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
extract($_POST);


$cadena = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE inv_fecha = '$fechaIni' AND prv_tipo = 'E' AND inv_id_key is NULL") or die(mysqlI_error($cnx) . "Error: en consultar el inventario 1");
$registros = mysqli_fetch_assoc($cadena);


$cadena2 = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE inv_fecha = '$fechaIni' AND prv_tipo = 'L'") or die(mysqlI_error($cnx) . "Error: en consultar el inventario 2");
$registros2 = mysqli_fetch_assoc($cadena2);


$cadena3 = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE substring(inv_fe_recibe,1,10) = '$fechaIni' and i.inv_enviado = 2") or die(mysqlI_error($cnx) . "Error: en consultar el inventario 2");
$registros3 = mysqli_fetch_assoc($cadena3);




$cad_scat = mysqli_query($cnx, "SELECT DISTINCT t.mt_id, t.mt_descripcion
  FROM materiales_tipo AS t
  INNER JOIN materiales as m ON(t.mt_id = m.mt_id)
  INNER JOIN inventario AS i ON ( m.mat_id = i.mat_id ) 
  WHERE i.inv_fecha = '$fechaIni' ");
$reg_scat = mysqli_fetch_array($cad_scat);
?>

<div class="tablehead" style="width: 1200px">
  <table width="1187" style="border: 1px solid #fff">
    <tr style="border: 1px solid #fff">
      <td align="center" style="border: 1px solid #fff"><img src="../../imagenes/logo_progel_v3.png"></td>
      <td align="center" style="border: 1px solid #fff">
        <h1>ENTRADAS DE MATERIA PRIMA</h1>
      </td>
      <td align="center" style="border: 1px solid #fff">
        <p>PRE F 001-REV.002</p>
        <p style="font-size: 20px">FECHA : <?php echo $fechaIni; ?></p>
      </td>
    </tr>
  </table>
</div>


<!--TABLA EXTRANJERO-->
<table style="border: 1px solid #000;width: 1200px">
  <thead>
  </thead>
  <thead>
    <tr style="background: #fff;color: #000;text-align: center;">
      <th colspan="15">ENTRADA DE CAMIONES AMERICANOS A BODEGA</th>
    </tr>
    <tr>
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
    $cont = 1;
    $flt_kg_inv = 0;
    do {
      if (isset($registros['inv_id'])) {

    ?>
        <tr style="border: 1px solid">
          <td><?php echo $cont++ ?></td>
          <td><?php echo $registros['inv_no_ticket'] ?></td>
          <td><?php echo $registros['inv_placas'] . "-" . $registros['inv_camioneta'] ?></td>
          <td><?php echo $registros['prv_nombre'] ?></td>
          <td><?php echo $registros['inv_no_factura'] ?></td>
          <td width="23" align="center"><?php echo fnc_formato_fecha($registros['inv_fecha']) ?></td>
          <td><?php echo $registros['mat_nombre'] ?></td>
          <td><?php echo $registros['inv_peso_factura'] ?></td>
          <td align="right"><?php echo $registros['inv_kilos'] ?></td>
          <td align="right"><?php echo $registros['inv_por_merma'] ?></td>
          <td><?php echo $registros['inv_no_tarimas'] . " / " . $registros['inv_no_sacos'] ?></td>
          <td><?php echo $registros['inv_prueba'] ?></td>
          <!-- <td><?php //echo $registros['inv_kg_totales']
                    ?></td>-->
          <?php
          if ($registros['inv_calidad']  == 'P') { ?>
            <td><?php echo "Poco" ?></td>
          <?php
          }
          if ($registros['inv_calidad']  == 'N') { ?>
            <td><?php echo "Nada" ?></td>
          <?php }
          if ($registros['inv_calidad']  == 'M') { ?>
            <td><?php echo "Mucho" ?></td>
          <?php } ?>
          <?php if ($registros['inv_calidad'] == 'X') { ?>
            <td><?php echo "N/A" ?></td>
          <?php } ?>
        </tr>
    <?php
        $ren += 1;
        $flt_kg_inv += $registros['inv_kilos'];
      }
    } while ($registros = mysqli_fetch_assoc($cadena)); ?>
    <tr>
      <td colspan="10" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff"></td>
      <td style="text-align: right;font-weight: bold;font-size: 18px;border-left: 2px solid#fff;border-bottom: 1px solid#fff">Total</td>
      <td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo $flt_kg_inv; ?></td>
    </tr>
  </tbody>
  <tfoot>
    <?php for ($i = $ren; $i <= 40; $i++) { ?>
    <?php } ?>
  </tfoot>
  <thead>
  </thead>
</table>


<!--TABLA LOCAL-->
<p>&nbsp;</p>
<table style="border: 1px solid #000;width: 1200px">
  <thead>
  </thead>
  <thead>
    <tr style="background: #fff;color: #000;text-align: center;">
      <th colspan="15">ENTRADA PROVEEDORES LOCALES</th>
    </tr>
    <tr>
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
    $cont = 1;
    $flt_kg_inv2 = 0;
    do {
      if (isset($registros2['inv_id'])) {

    ?>
        <tr style="border: 1px solid">
          <td><?php echo $cont++ ?></td>
          <td><?php echo $registros2['inv_no_ticket'] ?></td>
          <td><?php echo $registros2['inv_placas'] . "-" . $registros2['inv_camioneta'] ?></td>
          <td><?php echo $registros2['prv_nombre'] ?></td>
          <td align="center"><?php echo fnc_formato_fecha($registros2['inv_fecha']) ?></td>
          <td><?php echo $registros2['mat_nombre'] ?></td>
          <td><?php echo $registros2['inv_kilos'] ?></td>
          <td><?php echo $registros2['inv_prueba'] ?></td>
          <td><?php echo $registros2['inv_desc_ag'] ?></td>
          <td><?php echo $registros2['inv_desc_d'] ?></td>
          <td><?php echo $registros2['inv_desc_ren'] ?></td>
          <td align="right"><?php echo $registros2['inv_kg_totales'] ?></td>
          <?php
          if ($registros2['inv_calidad']  == 'P') { ?>
            <td><?php echo "Poco" ?></td>
          <?php
          }
          if ($registros2['inv_calidad']  == 'N') { ?>
            <td><?php echo "Nada" ?></td>
          <?php }
          if ($registros2['inv_calidad']  == 'M') { ?>
            <td><?php echo "Mucho" ?></td>
          <?php } ?>
          <?php if ($registros2['inv_calidad'] == 'X') { ?>
            <td><?php echo "N/A" ?></td>
          <?php } ?>
        </tr>
    <?php
        $ren += 1;
        $flt_kg_inv2 += $registros2['inv_kg_totales'];
      }
    } while ($registros2 = mysqli_fetch_assoc($cadena2)); ?>
    <tr>
      <td colspan="10" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff;border-right: 1px solid#fff"></td>
      <td style="text-align: right;font-weight: bold;font-size: 18px;border-bottom: 1px solid#fff;">Total</td>
      <td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo $flt_kg_inv2; ?></td>
    </tr>
  </tbody>
  <tfoot>
    <?php for ($i = $ren; $i <= 40; $i++) { ?>
    <?php } ?>
  </tfoot>
  <thead>
  </thead>
</table>
<!--TABLA DEPILADOS A BODEGA-->
<p>&nbsp;</p>
<table style="border: 1px solid #000;width: 1200px">
  <thead>
  </thead>
  <thead>
    <tr style="background: #fff;color: #000;text-align: center;">
      <th colspan="15">ENTRADA A PLANTA DE CUERO DEPILADO DE BODEGA</th>
    </tr>
    <tr>
      <th width="24">No.</th>
      <th>Fe ingreso</th>
      <th width="110">No. Ticket</th>
      <th width="354">Proveedor</th>
      <th width="220">Fe. Ent. de Maquila</th>
      <th width="372">Material</th>
      <th width="120">Kgs de entrada</th>
      <th width="120">Kgs cargas lavador</th>
      <th width="120">Kgs entrada maq.</th>
      <th>Pruebas secador</th>
      <th>Desc agua</th>
      <th>% Descarne</th>
      <th>Desc rendimiento</th>
      <th>Kg a pagar c/desc</th>
      <th>Calidad material</th>
      <!--<th width="89">Prueba de secador</th>
         <th width="79">Desc por agua</th>
         <th width="73">% Descarne</th>
         <th width="91">Desc por rendimi.</th>
         <th width="94">Kg a pagar c/desc</th>
         <th width="80">Calidad material</th>-->

    </tr>
  </thead>
  <tbody>
    <?php
    $ren = 1;
    $cont = 1;
    $flt_kg_inv3 = 0;
    do {
      if (isset($registros3['inv_id'])) {

        $cad_inv3 = mysqli_query($cnx, "SELECT inv_kg_totales FROM `inventario` WHERE inv_id = " . $registros3['inv_id'] . "") or die(mysqlI_error($cnx));
        $reg_inv3 = mysqli_fetch_assoc($cad_inv3);

        $cad_sum3 = mysqli_query($cnx, "SELECT SUM(inv_kg_totales) as inv_kg_totales  FROM `inventario` WHERE  inv_id_key = " . $registros3['inv_id'] . "") or die(mysqlI_error($cnx));
        $reg_sum3 = mysqli_fetch_assoc($cad_sum3);

        $kg_pagar = $reg_inv3['inv_kg_totales'] + $reg_sum3['inv_kg_totales'];
    ?>
        <tr style="border: 1px solid">
          <td><?php echo $cont++ ?></td>
          <td><?php echo $registros3['inv_fecha'] ?></td>
          <td><?php echo $registros3['inv_no_ticket']; //."-".$registros3['inv_id'] 
              ?></td>
          <td><?php echo $registros3['prv_nombre'] ?></td>
          <td align="center"><?php echo fnc_formato_fecha($registros3['inv_fe_recibe']); ?></td>
          <td><?php echo $registros3['mat_nombre'] ?></td>
          <td><?php echo $registros3['inv_kilos'] ?></td>
          <td><?php echo $registros3['inv_kg_lavador'] ?></td>
          <td><?php echo $registros3['inv_kg_entrada_maq'] ?></td>
          <td><?php echo $registros3['inv_prueba'] ?></td>
          <td><?php echo $registros3['inv_desc_ag'] ?></td>
          <td><?php echo $registros3['inv_desc_d'] ?></td>
          <td><?php echo $registros3['inv_desc_ren'] ?></td>
          <td><?php echo  $kg_pagar ?></td>
          <td><?php echo $registros3['inv_calidad'] ?></td>
        </tr>
    <?php
        $ren += 1;
        $flt_kg_inv3 += $kg_pagar;
      }
    } while ($registros3 = mysqli_fetch_assoc($cadena3)); ?>
    <tr>
      <td colspan="12" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff;border-right: 1px solid#fff">
        <p>&nbsp;</p>
      </td>
      <td valign="top" style="text-align: right;font-weight: bold;font-size: 18px;border-bottom: 1px solid#fff;">Total</td>
      <td valign="top" style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo $flt_kg_inv3; ?></td>
    </tr>
  </tbody>
  <tfoot>
    <?php for ($i = $ren; $i <= 40; $i++) { ?>
    <?php } ?>
  </tfoot>
  <thead>
  </thead>
</table>


<p>&nbsp;</p>
<table style="width: 1200px">
  <tr style="font-weight: bold;font-size: 20px;text-align: right;">
    <td width="759" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff;border-top: 1px solid#fff">&nbsp;</td>
    <td width="200">Total de entrada a planta</td>
    <td width="95" align="left"><?php echo $flt_kg_inv + $flt_kg_inv2 + $flt_kg_inv3; ?></td>
  </tr>
</table>

<!--TABLA TOTALES POR TIPO DE PRODUCTO-->
<table style="width: 1200px;">
  <tr style="background: #32383e;color: #fff">
    <td colspan="4" align="center">Entrada cuero del día</td>
    <td width="59%" rowspan="5" style="background: #fff;border-right: 1px solid#fff;border-bottom: 1px solid#fff;border-top: 1px solid#fff" align="center">
    <td width="59%" rowspan="5" style="background: #fff;border-right: 1px solid#fff;border-bottom: 1px solid#fff;border-top: 1px solid#fff" align="center">
      <table border="1" cellspacing="0" cellpadding="0" style="font-size:14px; color:#666666" width="554">
        <tr>
          <td colspan="6" align="center" style="background: #32383e;color: #fff">PARAMETROS DE SECADOR </td>
        </tr>
        <tr>
          <td width="100">Material</td>
          <td width="100">Carnaza</td>
          <td width="100">Recorte</td>
          <td width="100">Entero Propio/Reven.</td>
          <td width="100">Ped. Local </td>
          <td width="100">Ped. Americana </td>
        </tr>
        <tr>
          <td>Rendimiento</td>
          <td>MIN. 28 </td>
          <td>MIN. 28 </td>
          <td>MIN. 30 </td>
          <td>MIN. 27 </td>
          <td>MIN. 50 </td>
        </tr>
      </table>
  </tr>
  <tr style="background: #32383e;color: #fff">
    <td width="11%">Tipo de material</td>
    <td width="10%">Objetivo(Entrada)</td>
    <td width="10%">Real(A pagar)</td>
    <td width="10%">Diferencia</td>
  </tr>
  <?php
  $flt_kilos = 0;
  $flt_kilos2 = 0;
  do {
    if (isset($reg_scat['mt_id'])) {

      //Obtiene el objetivo por tipo de material
      $inv_obj = mysqli_query($cnx, "SELECT mto_kilos as res
                FROM materiales_tipo_obj
                WHERE mt_id = '$reg_scat[mt_id]' and mto_fecha = '$fechaIni'
                ") or die(mysqlI_error($cnx) . "Error: en consultar el inventario 3");
      $reginv_obj = mysqli_fetch_assoc($inv_obj);


      $inv = mysqli_query($cnx, "SELECT sum(i.inv_kg_totales)as res2 
                FROM inventario as i 
                INNER JOIN materiales as m  ON(i.mat_id = m.mat_id)
                WHERE m.mt_id = '$reg_scat[mt_id]' and inv_fecha = '$fechaIni'
                ") or die(mysqlI_error($cnx) . "Error: en consultar el inventario 3");
      $reginv = mysqli_fetch_assoc($inv);

      if (isset($reginv_obj['res'])) {
        $reginv_obj_res = $reginv_obj['res'];
      } else {
        $reginv_obj_res = 0;
      }
      if (isset($reginv['res2'])) {
        $reginv_res = $reginv['res2'];
      } else {
        $reginv_res = 0;
      }
  ?>
      <tr>
        <td><?php echo $reg_scat['mt_descripcion']; ?></td>
        <td><?php echo $reginv_obj_res; ?></td>
        <td><?php echo $reginv['res2']; ?></td>
        <td><?php echo $reginv_obj_res - $reginv_res; ?></td>
      </tr>
      <?php
      $flt_kilos += $reginv_obj_res;
      $flt_kilos2 += $reginv_res;
      ?>
  <?php }
  } while ($reg_scat = mysqli_fetch_array($cad_scat)); ?>
  <tr style="font-weight: bold;">
    <td>Totales</td>
    <td><?php echo $flt_kilos; ?></td>
    <td><?php echo $flt_kilos2; ?></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<script src="../../js/jspdf.js"></script>
<script src="../../js/pdfFromHTML.js"></script>