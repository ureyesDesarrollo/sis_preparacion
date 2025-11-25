<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
//extract($_POST); 

$fechaIni = $_GET['fechaIni'];
$fechaFin = $_GET['fechaFin'];


#LOCAL CARNAZA_TABLA 1
$cad_carnaza = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE inv_fecha >= '$fechaIni' AND inv_fecha <= '$fechaFin' AND prv_tipo = 'L' AND mt_id IN(1,6,9)") or die(mysql_error()."Error: en consultar el inventario 2");
$reg_carnaza = mysqli_fetch_assoc($cad_carnaza);

#LOCA RECORTE/CERDO_TABLA 2
$cad_cerdo = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE inv_fecha >= '$fechaIni' AND inv_fecha <= '$fechaFin' AND prv_tipo = 'L' AND mt_id = '2'") or die(mysql_error()."Error: en consultar el inventario 2");
$reg_cerdo = mysqli_fetch_assoc($cad_cerdo);

#PEDACERA AMERICANA DEPILADA POR MAQUILAS_TABLA 3
$cad_dep_maq = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE inv_fe_recibe >= '$fechaIni' AND inv_fe_recibe <= DATE_ADD('$fechaFin', INTERVAL 1 DAY) and i.inv_enviado = 2 AND p.prv_ban = '0'" ) or die(mysql_error()."Error: en consultar el inventario 2");
$reg_dep_maq = mysqli_fetch_assoc($cad_dep_maq);

#PEDACERA AMERICANA DEPILADA POR MAQUILAS_TABLA 4
$cad_dep_loc = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE inv_fe_recibe >= '$fechaIni' AND inv_fe_recibe <= DATE_ADD('$fechaFin', INTERVAL 1 DAY) and i.inv_enviado = 2 AND p.prv_ban = '1'") or die(mysql_error()."Error: en consultar el inventario 2");
$reg_dep_loc = mysqli_fetch_assoc($cad_dep_loc);

$cadena3 = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE inv_fe_recibe >= '$fechaIni' AND inv_fe_recibe <= DATE_ADD('$fechaFin', INTERVAL 1 DAY) and i.inv_enviado = 2") or die(mysql_error()."Error: en consultar el inventario 2");
$registros3 = mysqli_fetch_assoc($cadena3);



#COMPRA PEDACERA AMERICANA(CAMIONES A BODEGA Y/O MAQUILA)_TABLA 5_OK
$cadena = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE inv_fecha >= '$fechaIni' AND inv_fecha <= '$fechaFin' AND prv_tipo = 'E' AND inv_id_key is NULL") or die(mysql_error()."Error: en consultar el inventario 1");
$registros = mysqli_fetch_assoc($cadena);


#COMPRA DE CUERO ENTERO CON PELO(DIRECTO A MAQUILA)_TABLA 6
$cad_di_maq = mysqli_query($cnx, "SELECT i.*, p.prv_nombre,p.prv_tipo, m.mat_nombre
 FROM inventario as i
 INNER JOIN proveedores AS p ON (i.prv_id = p.prv_id)
 INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
 WHERE inv_fecha >= '$fechaIni' AND inv_fecha <= '$fechaFin' AND prv_tipo = 'L' AND prv_ban = '1' ") or die(mysql_error()."Error: en consultar el inventario 1");
$reg_di_maq = mysqli_fetch_assoc($cad_di_maq);

//objetivo de material por día
$cad_scat = mysqli_query($cnx, "SELECT DISTINCT t.mt_id, t.mt_descripcion
  FROM materiales_tipo AS t
  INNER JOIN materiales as m ON(t.mt_id = m.mt_id)
  INNER JOIN inventario AS i ON ( m.mat_id = i.mat_id ) 
  WHERE i.inv_fecha = '$fechaIni'  "); 
$reg_scat = mysqli_fetch_array($cad_scat);

//obetivo de material por semana
$cad_scat2 = mysqli_query($cnx, "SELECT DISTINCT t.mt_id, t.mt_descripcion
  FROM materiales_tipo AS t
  INNER JOIN materiales as m ON(t.mt_id = m.mt_id)
  INNER JOIN inventario AS i ON ( m.mat_id = i.mat_id ) 
  WHERE i.inv_fecha >= '$fechaIni' AND inv_fecha <= '$fechaFin' "); 
$reg_scat2 = mysqli_fetch_array($cad_scat2);

 header('Content-type: application/vnd.ms-excel');
 header("Content-Disposition: attachment; filename=inventario_".$fechaIni." al ".$fechaFin.".".".xls");
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
         <td style="border: 1px solid #fff"><p>FECHA:<?php echo $fechaIni.' al '.$fechaFin; ?></p>
          <p>PRE F 001-REV.002</p></td>
        </tr>
      </table>
      <p>&nbsp;</p>
    </div>


     <!--TABLA LOCAL SE SEPARO POR TIPO DE MATERIAL CARNAZA Y RECORTE DE CERDO-->
       <!--TABLA CARNAZA 1-->
       <p>&nbsp;</p>
        <table  style="border: 1px solid #000;width: 1200px">
         <thead>
         </thead>
         <thead>
          <tr style="background: #32383e;color: #fff;text-align: center;">
            <th colspan="12">CARNAZA</th>
          </tr>
          <tr>
           <th width="24">No.</th>
           <th width="64">No. Ticket</th>
           <th width="119">Placas/Camioneta</th>
           <th width="134">Proveedor</th>
           <th width="77">Fecha entrada</th>
           <th width="73">Material</th>
           <th width="73">Kgs entrada</th>
           <th width="89">Prueba secador</th>
           <th width="79">Desc por agua</th>
           <th width="73">Descuento por descarne</th>
           <th width="91">Desc por rendimi.</th>
           <th width="94">Kg a pagar c/desc</th>

         </tr>
       </thead>
       <tbody>
        <?php 
        $ren = 1;
        $cont=1;
        do{?>
          <tr style="border: 1px solid">
            <td><?php echo $cont++?></td>
            <td><?php echo $reg_carnaza['inv_no_ticket'] ?></td>
            <td><?php echo $reg_carnaza['inv_placas']."-".$reg_carnaza['inv_camioneta'] ?></td>
            <td><?php echo $reg_carnaza['prv_nombre'] ?></td>
            <td align="center"><?php echo fnc_formato_fecha($reg_carnaza['inv_fecha']) ?></td>
            <td><?php echo $reg_carnaza['mat_nombre']?></td>
            <td><?php echo $reg_carnaza['inv_kilos']?></td>
            <td><?php echo $reg_carnaza['inv_prueba']?></td>
            <td><?php echo $reg_carnaza['inv_desc_ag']?></td>
            <td><?php echo $reg_carnaza['inv_desc_d']?></td>
            <td><?php echo $reg_carnaza['inv_desc_ren']?></td>
            <td align="right"><?php echo $reg_carnaza['inv_kg_totales']?></td>
          </tr>
          <?php 
          $ren += 1;
          $flt_kg_inv += $reg_carnaza['inv_kg_totales'];
        }while($reg_carnaza = mysqli_fetch_assoc($cad_carnaza));?>
        <tr>
          <td colspan="10" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff;border-right: 1px solid#fff"></td>
          <td style="text-align: right;font-weight: bold;font-size: 18px;border-bottom: 1px solid#fff;">Total</td>
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

    <!--TABLA RECORTE/CERDO-->
    <p>&nbsp;</p>
    <table  style="border: 1px solid #000;width: 1200px">
     <thead>
     </thead>
     <thead>
      <tr style="background: #32383e;color: #fff;text-align: center;">
        <th colspan="12">RECORTE</th>
      </tr>
      <tr>
       <th width="24">No.</th>
       <th width="64">No. Ticket</th>
       <th width="119">Placas/Camioneta</th>
       <th width="134">Proveedor</th>
       <th width="77">Fecha entrada</th>
       <th width="73">Material</th>
       <th width="73">Kgs entrada</th>
       <th width="89">Prueba secador</th>
       <th width="79">Desc por agua</th>
       <th width="73">Descuento por descarne</th>
       <th width="91">Desc por rendimi.</th>
       <th width="94">Kg a pagar c/desc</th>
     </tr>
   </thead>
   <tbody>
    <?php 
    $ren = 1;
    $cont=1;
    do{?>
      <tr style="border: 1px solid">
        <td><?php echo $cont++?></td>
        <td><?php echo $reg_cerdo['inv_no_ticket'] ?></td>
        <td><?php echo $reg_cerdo['inv_placas']."-".$reg_cerdo['inv_camioneta'] ?></td>
        <td><?php echo $reg_cerdo['prv_nombre'] ?></td>
        <td align="center"><?php echo fnc_formato_fecha($reg_cerdo['inv_fecha']) ?></td>
        <td><?php echo $reg_cerdo['mat_nombre']?></td>
        <td><?php echo $reg_cerdo['inv_kilos']?></td>
        <td><?php echo $reg_cerdo['inv_prueba']?></td>
        <td><?php echo $reg_cerdo['inv_desc_ag']?></td>
        <td><?php echo $reg_cerdo['inv_desc_d']?></td>
        <td><?php echo $reg_cerdo['inv_desc_ren']?></td>
        <td align="right"><?php echo $reg_cerdo['inv_kg_totales']?></td>
      </tr>
      <?php 
      $ren += 1;
      $flt_kg_inv2 += $reg_cerdo['inv_kg_totales'];
    }while($reg_cerdo = mysqli_fetch_assoc($cad_cerdo));?>
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

<!--PEDACERA AMERICANA DEPILADA POR MAQUILAS 3-->
<p>&nbsp;</p>
<table  style="border: 1px solid #000;width: 1200px">
  <thead>
  </thead>
  <thead>
    <tr style="background: #32383e;color: #fff;text-align: center;">
      <th colspan="14">PEDACERA AMERICANA DEPILADA POR MAQUILAS</th>
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
     <th>Desc  agua</th>
     <th>Descuento por descarne</th>
     <th>Desc  rendimiento</th>
     <th>Kg a pagar c/desc</th>
   </tr>
 </thead>
 <tbody>
  <?php 
  $ren = 1;
  $cont = 1;
  do{

    if ($reg_dep_maq['inv_id'] != '') {
      $cad_inv3 = mysqli_query($cnx, "SELECT inv_kg_totales FROM `inventario` WHERE inv_id = ".$reg_dep_maq['inv_id']."") or die(mysqli_error());
      $reg_inv3 = mysqli_fetch_assoc($cad_inv3);

      $cad_sum3 = mysqli_query($cnx, "SELECT SUM(inv_kg_totales) as inv_kg_totales  FROM `inventario` WHERE  inv_id_key = ".$reg_dep_maq['inv_id']."") or die(mysqli_error());
      $reg_sum3= mysqli_fetch_assoc($cad_sum3);

      $kg_pagar3 = $reg_inv3['inv_kg_totales'] + $reg_sum3['inv_kg_totales'];
    }

    ?>
    <tr style="border: 1px solid">
      <td><?php echo $cont++?></td>
      <td><?php echo $reg_dep_maq['inv_fecha'] ?></td>
      <td><?php echo $reg_dep_maq['inv_no_ticket'];//."-".$reg_dep_maq['inv_id'] ?></td>
      <td><?php echo $reg_dep_maq['prv_nombre'] ?></td>
      <td align="center"><?php echo fnc_formato_fecha($reg_dep_maq['inv_fe_recibe']); ?></td>
      <td><?php echo $reg_dep_maq['mat_nombre']?></td>
      <td><?php echo $reg_dep_maq['inv_kilos']?></td>
      <td><?php echo $reg_dep_maq['inv_kg_lavador']?></td>
      <td><?php echo $reg_dep_maq['inv_kg_entrada_maq']?></td>
      <td><?php echo $reg_dep_maq['inv_prueba'] ?></td>
      <td><?php echo $reg_dep_maq['inv_desc_ag'] ?></td>
      <td><?php echo $reg_dep_maq['inv_desc_d'] ?></td>
      <td><?php echo $reg_dep_maq['inv_desc_ren'] ?></td>
      <td><?php echo  $kg_pagar3 ?></td>
    </tr>
    <?php 
    $ren += 1;
    $flt_kg_inv3 += $kg_pagar3;
  }while($reg_dep_maq = mysqli_fetch_assoc($cad_dep_maq));?>
  <tr>
    <td colspan="12" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff;border-right: 1px solid#fff"><p>&nbsp;</p></td>
    <td valign="top" style="text-align: right;font-weight: bold;font-size: 18px;border-bottom: 1px solid#fff;">Total</td>
    <td valign="top" style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo $flt_kg_inv3; ?></td>
  </tr>
</tbody>
<tfoot>
  <?php for($i=$ren; $i <= 40; $i++){?>
  <?php }?>
</tfoot>
<thead>
</thead>
</table>


<!--CUERO ENTERO DEPILADO POR MAQUILA TABLA 4-->
<p>&nbsp;</p>
<table  style="border: 1px solid #000;width: 1200px">
  <thead>
  </thead>
  <thead>
    <tr style="background: #32383e;color: #fff;text-align: center;">
      <th colspan="14">CUERO ENTERO DEPILADO POR MAQUILA</th>
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
     <th>Desc  agua</th>
     <th>Descuento por descarne</th>
     <th>Desc  rendimiento</th>
     <th>Kg a pagar c/desc</th>
   </tr>
 </thead>
 <tbody>
  <?php 
  $ren = 1;
  $cont = 1;
  do{

    if ($reg_dep_loc['inv_id'] != '') {
      $cad_inv4 = mysqli_query($cnx, "SELECT inv_kg_totales FROM `inventario` WHERE inv_id = ".$reg_dep_loc['inv_id']."") or die(mysqli_error());
      $reg_inv4 = mysqli_fetch_assoc($cad_inv4);

      $cad_sum4 = mysqli_query($cnx, "SELECT SUM(inv_kg_totales) as inv_kg_totales  FROM `inventario` WHERE  inv_id_key = ".$reg_dep_loc['inv_id']."") or die(mysqli_error());
      $reg_sum4= mysqli_fetch_assoc($cad_sum4);

      $kg_pagar4 = $reg_inv4['inv_kg_totales'] + $reg_sum4['inv_kg_totales'];
    }

    ?>
    <tr style="border: 1px solid">
      <td><?php echo $cont++?></td>
      <td><?php echo $reg_dep_loc['inv_fecha'] ?></td>
      <td><?php echo $reg_dep_loc['inv_no_ticket'];//."-".$reg_dep_loc['inv_id'] ?></td>
      <td><?php echo $reg_dep_loc['prv_nombre'] ?></td>
      <td align="center"><?php echo fnc_formato_fecha($reg_dep_loc['inv_fe_recibe']); ?></td>
      <td><?php echo $reg_dep_loc['mat_nombre']?></td>
      <td><?php echo $reg_dep_loc['inv_kilos']?></td>
      <td><?php echo $reg_dep_loc['inv_kg_lavador']?></td>
      <td><?php echo $reg_dep_loc['inv_kg_entrada_maq']?></td>
      <td><?php echo $reg_dep_loc['inv_prueba'] ?></td>
      <td><?php echo $reg_dep_loc['inv_desc_ag'] ?></td>
      <td><?php echo $reg_dep_loc['inv_desc_d'] ?></td>
      <td><?php echo $reg_dep_loc['inv_desc_ren'] ?></td>
      <td><?php echo  $kg_pagar4 ?></td>
    </tr>
    <?php 
    $ren += 1;
    $flt_kg_inv4 += $kg_pagar4;
  }while($reg_dep_loc = mysqli_fetch_assoc($cad_dep_loc));?>
  <tr>
    <td colspan="12" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff;border-right: 1px solid#fff"><p>&nbsp;</p></td>
    <td valign="top" style="text-align: right;font-weight: bold;font-size: 18px;border-bottom: 1px solid#fff;">Total</td>
    <td valign="top" style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo $flt_kg_inv4; ?></td>
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
<table style="width: 1200px;">
  <tr style="font-weight: bold;font-size: 20px;text-align: right;">
    <td colspan="10" style="background: #FEFBD5">&nbsp;</td>
    <td width="400"  style="background: #FEFBD5" >Total de entrada del día a procesar en planta</td>
    <td width="95" style="background: #FEFBD5"  align="right" ><?php echo $flt_kg_inv + $flt_kg_inv2 + $flt_kg_inv3 + $flt_kg_inv4 + $flt_kg_inv5 + $flt_kg_inv6 ; ?></td>
  </tr>
</table>
<p>


  <!--TABLA 5 TABLA EXTRANJERO-->
  <table  style="border: 1px solid #000;width: 1200px">
    <thead>
    </thead>
    <thead>
      <tr style="background: #32383e;color: #fff;text-align: center;">
        <th colspan="12">COMPRA PEDACERA AMERICANA(CAMIONES A BODEGA Y/O MAQUILA)</th>
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
        <th width="80">% Merma max 5.0 %</th>
        <th width="91">No. tarimas/sacos</th>
        <th width="91">Prueba de secador</th>
        <!--<th width="100">Kilos a pagar c/desc</th>-->
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
          <td width="23" align="center"><?php echo fnc_formato_fecha($registros['inv_fecha']) ?></td>
          <td><?php echo $registros['mat_nombre']?></td>
          <td><?php echo $registros['inv_peso_factura']?></td>
          <td align="right"><?php echo $registros['inv_kilos']?></td>
          <td align="right"><?php echo $registros['inv_por_merma']?></td>
          <td><?php echo $registros['inv_no_tarimas']." / ".$registros['inv_no_sacos']?></td>        
          <td><?php echo $registros['inv_prueba']?></td>
        </tr>
        <?php 
        $ren += 1;
        $flt_kg_inv5 += $registros['inv_kilos'];
      }while($registros = mysqli_fetch_assoc($cadena));?>
      <tr>
        <td colspan="10" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff"></td>
        <td style="text-align: right;font-weight: bold;font-size: 18px;border-left: 2px solid#fff;border-bottom: 1px solid#fff">Total</td>
        <td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo $flt_kg_inv5; ?></td>
      </tr>
    </tbody>
    <tfoot>
      <?php for($i=$ren; $i <= 40; $i++){?>
      <?php }?>
    </tfoot>
    <thead>
    </thead>
  </table>

  <!--TABLA 6 ESTE MATERIAL ENTRA DIRECTO A MAQUILA, NO A ALMACEN-->
  <table  style="border: 1px solid #000;width: 1200px">
    <thead>
    </thead>
    <thead>
      <tr style="background: #32383e;color: #fff;text-align: center;">
        <th colspan="12">COMPRA DE CUERO ENTERO CON PELO(DIRECTO A MAQUILA)</th>
      </tr>
      <tr>
        <th width="24">No.</th>
        <th width="68">No. Ticket</th>
        <th width="119">Placas/Camioneta</th>
        <th width="124">Proveedor</th>
        <th width="60">Material</th>
        <th width="80">Fe. Ent. Bodega</th>
        <th width="81">Clave compra</th>
        <th width="60">Toneladas entrada</th>
        <th width="60">Descto por agua</th>
        <th width="80">Descto por rendim</th>
        <th width="91">Total de cueros</th>
        <th width="91">Prueba de secador</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $ren = 1;
      $cont=1;

      do{?>
        <tr style="border: 1px solid">
          <td><?php echo $cont++?></td>
          <td><?php echo $reg_di_maq['inv_no_ticket'] ?></td>
          <td><?php echo $reg_di_maq['inv_placas']."-".$reg_di_maq['inv_camioneta'] ?></td>
          <td><?php echo $reg_di_maq['prv_nombre'] ?></td>
          <td><?php echo $reg_di_maq['mat_nombre']?></td>
          <td width="23" align="center"><?php echo fnc_formato_fecha($reg_di_maq['inv_fecha']) ?></td>
          <td><?php echo $reg_di_maq['int_cve_compra']?></td>
          <td><?php echo $reg_di_maq['inv_kilos']?></td>
          <td align="right"><?php echo $reg_di_maq['inv_desc_ag']?></td>
          <td align="right"><?php echo $reg_di_maq['inv_desc_ren']?></td>
          <td><?php echo $reg_di_maq['inv_total_cueros'] ?></td>        
          <td><?php echo $reg_di_maq['inv_prueba']?></td>
        </tr>
        <?php 
        $ren += 1;
        $flt_kg_inv6 += $reg_di_maq['inv_kilos'];
      }while($reg_di_maq = mysqli_fetch_assoc($cad_di_maq));?>
      <tr>
        <td colspan="10" style="border-left: 1px solid#fff;border-bottom: 1px solid#fff"></td>
        <td style="text-align: right;font-weight: bold;font-size: 18px;border-left: 2px solid#fff;border-bottom: 1px solid#fff">Total</td>
        <td style="text-align: right;font-weight: bold;font-size: 18px;"><?php echo $flt_kg_inv6; ?></td>
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
  <table style="width: 1200px">
    <tr style="font-weight: bold;font-size: 20px;text-align: right;">
      <td colspan="10" style="background: #FEFBD5">&nbsp;</td>
      <td width="400" style="background: #FEFBD5"  >Total de compra del día de cuero a maquilar</td>
      <td width="95"  style="background: #FEFBD5" align="right" ><?php echo $flt_kg_inv5 + $flt_kg_inv6 ; ?></td>
    </tr>
  </table>
  <p>

    <!--TABLA TOTALES POR TIPO DE PRODUCTO-->

    <table style="width: 1200px;margin-bottom: 100px;border: 0px">
      <td valign="top" style="border: 0px">
        <table>
          <tr>
            <td colspan="4" align="center" valign="top" style="background: #32383e;color: #fff">Entrada cuero del día</td>
          </tr>
          <tr style="background: #32383e;color: #fff">
            <td width="6%">Tipo de material</td>
            <td width="11%">Objetivo(Entrada)</td>
            <td width="6%">Real(A pagar)</td>
            <td width="7%">Diferencia</td>
          </tr>
          <?php do { 

      //Obtiene el objetivo por tipo de material por dia
            $inv_obj = mysqli_query($cnx, "SELECT mto_kilos as res
              FROM materiales_tipo_obj
              WHERE mt_id = '$reg_scat[mt_id]' and mto_fecha = '$fechaIni'
              ") or die(mysqli_error()."Error: en consultar el inventario 3");
            $reginv_obj = mysqli_fetch_assoc($inv_obj);


            $inv = mysqli_query($cnx, "SELECT sum(i.inv_kg_totales)as res2 
              FROM inventario as i 
              INNER JOIN materiales as m  ON(i.mat_id = m.mat_id)
              WHERE m.mt_id = '$reg_scat[mt_id]' and inv_fecha = '$fechaIni'
              ") or die(mysqli_error()."Error: en consultar el inventario 3");
            $reginv = mysqli_fetch_assoc($inv);

            ?>
            <tr>
              <td><?php echo $reg_scat['mt_descripcion']; ?></td>
             <td><?php 
              if ($reginv_obj['res'] != '') {
                echo $reginv_obj['res'];
              }else{
                echo "0"; 
              } ?></td>
              <td><?php echo $reginv['res2'];?></td>
              <td><?php echo $reginv_obj['res'] - $reginv['res2']; ?></td>
            </tr>
            <?php
            $flt_kilos += $reginv_obj['res'];
            $flt_kilos2 += $reginv['res2'];
            ?>
          <?php }while($reg_scat = mysqli_fetch_array($cad_scat));?>
          <tr style="font-weight: bold;background: #32383e;color: #fff">
            <td>Totales</td>
            <td><?php echo $flt_kilos; ?></td>
            <td><?php echo $flt_kilos2; ?></td>
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
      <td style="border: 0px">
        <table>
          <tr>
            <td colspan="4" align="center" valign="top" style="background: #32383e;color: #fff">Entrada cuero de la semana</td>
          </tr>
          <tr style="background: #32383e;color: #fff">
            <td width="6%">Tipo de material</td>
            <td width="11%">Objetivo(Entrada)</td>
            <td width="6%">Real(A pagar)</td>
            <td width="7%">Diferencia</td>
          </tr>
          <?php do { 

      //Obtiene el objetivo por tipo de material
            $inv_obj2 = mysqli_query($cnx, "SELECT SUM(mto_kilos) as res
              FROM materiales_tipo_obj
              WHERE mt_id = '$reg_scat2[mt_id]' and mto_fecha >= '$fechaIni' AND mto_fecha <= '$fechaFin' 
              ") or die(mysqli_error()."Error: en consultar el inventario 3");
            $reginv_obj2 = mysqli_fetch_assoc($inv_obj2);


            $inv2 = mysqli_query($cnx, "SELECT sum(i.inv_kg_totales)as res2 
              FROM inventario as i 
              INNER JOIN materiales as m  ON(i.mat_id = m.mat_id)
              WHERE m.mt_id = '$reg_scat2[mt_id]' and inv_fecha >= '$fechaIni' AND inv_fecha <= '$fechaFin' 
              ") or die(mysqli_error()."Error: en consultar el inventario 3");
            $reginv2 = mysqli_fetch_assoc($inv2);

            ?>
            <tr>
              <td><?php echo $reg_scat2['mt_descripcion']; ?></td>
              <td><?php 
              if ($reginv_obj2['res'] != '') {
                echo $reginv_obj2['res'];
              }else{
                echo "0"; 
              } ?></td>
              <td><?php echo $reginv2['res2'];?></td>
              <td><?php echo $reginv_obj2['res'] - $reginv2['res2']; ?></td>
            </tr>
            <?php
            $flt_kilos3 += $reginv_obj2['res'];
            $flt_kilos4 += $reginv2['res2'];
            ?>
          <?php }while($reg_scat2 = mysqli_fetch_array($cad_scat2));?>
          <tr style="font-weight: bold;background: #32383e;color: #fff">
            <td>Totales</td>
            <td><?php echo $flt_kilos3; ?></td>
            <td><?php echo $flt_kilos4; ?></td>
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
      <td style="border: 0px" valign="top">
        <table>
          <tr>
            <td colspan="6" align="center" valign="top" style="background: #32383e;color: #fff">Parametros de secador</td>
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
      </td>
    </table>
<p>&nbsp;</p>
</body>
</html>