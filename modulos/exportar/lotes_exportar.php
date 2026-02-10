<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
extract($_POST); 

$mes = $_GET['mes'];

$fechaInicio = $_GET['fechaInicio'];

$fechaFinal = $_GET['fechaFinal'];


if ($mes == '' AND $fechaInicio == '' AND $fechaFinal == '') {
 $cadena = mysqli_query($cnx, "SELECT * FROM tarimas WHERE tarima_fecha =  '".date("Y-m-d")."'") or die(mysql_error()."Error: en consultar mes".$mes);
 $registros = mysqli_fetch_assoc($cadena);
}

if ($mes != '' AND $fechaInicio == '' AND $fechaFinal == '') {
 $cadena = mysqli_query($cnx, "SELECT * FROM tarimas as t INNER JOIN lotes as l
   WHERE t.lote_id = l.lote_id AND l.lote_mes = '$mes'") or die(mysql_error()."Error: en consultar mes".$mes);
 $registros = mysqli_fetch_assoc($cadena);
}

if ($mes == '' AND $fechaInicio != '' AND $fechaFinal == '') {
  $cadena = mysqli_query($cnx, "SELECT * FROM tarimas as t INNER JOIN lotes as l WHERE  t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio' ORDER BY tarima_fecha") or die(mysql_error()."Error: en consultar tarima por rango de fechas");
  $registros = mysqli_fetch_assoc($cadena);
}

if ($mes == '' AND $fechaInicio != '' AND $fechaFinal != '') {
  $cadena = mysqli_query($cnx, "SELECT * FROM tarimas as t INNER JOIN lotes as l WHERE  t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal' ORDER BY tarima_fecha") or die(mysql_error()."Error: en consultar tarima por fecha");
  $registros = mysqli_fetch_assoc($cadena);
}



$cont1 = mysqli_query($cnx, "SELECT COUNT(tarima_bloom) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_bloom > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont1 = mysqli_fetch_assoc ($cont1);
$cont1 = $reg_cont1['cont'];

$cont2 = mysqli_query($cnx, "SELECT COUNT(tarima_viscocidad) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_viscocidad > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont2 = mysqli_fetch_assoc ($cont2);
$cont2 = $reg_cont2['cont'];

$cont3 = mysqli_query($cnx, "SELECT COUNT(tarima_ph_final) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_ph_final > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont3 = mysqli_fetch_assoc ($cont3);
$cont3 = $reg_cont3['cont'];

$cont4 = mysqli_query($cnx, "SELECT COUNT(tarima_transparencia) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_transparencia > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont4 = mysqli_fetch_assoc ($cont4);
$cont4 = $reg_cont4['cont'];

$cont5 = mysqli_query($cnx, "SELECT COUNT(tarima_porcen_t) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_porcen_t > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont5 = mysqli_fetch_assoc ($cont5);
$cont5 = $reg_cont5['cont'];

$cont6 = mysqli_query($cnx, "SELECT COUNT(tarima_ntu) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_ntu > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont6 = mysqli_fetch_assoc ($cont6);
$cont6 = $reg_cont6['cont'];

$cont7 = mysqli_query($cnx, "SELECT COUNT(tarima_humedad) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_humedad > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont7 = mysqli_fetch_assoc ($cont7);
$cont7 = $reg_cont7['cont'];

$cont8 = mysqli_query($cnx, "SELECT COUNT(tarima_cenizas) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_cenizas > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont8 = mysqli_fetch_assoc ($cont8);
$cont8 = $reg_cont8['cont'];

$cont9 = mysqli_query($cnx, "SELECT COUNT(tarima_redox) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_redox > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont9 = mysqli_fetch_assoc ($cont9);
$cont9 = $reg_cont9['cont'];

$cont10 = mysqli_query($cnx, "SELECT COUNT(tarima_color) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_color > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont10 = mysqli_fetch_assoc ($cont10);
$cont10 = $reg_cont10['cont'];

$cont11 = mysqli_query($cnx, "SELECT COUNT(tarima_grano) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_grano > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont11 = mysqli_fetch_assoc ($cont11);
$cont11 = $reg_cont11['cont'];

$cont12 = mysqli_query($cnx, "SELECT COUNT(tarima_part_ext) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_part_ext > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont12 = mysqli_fetch_assoc ($cont12);
$cont12 = $reg_cont12['cont'];

$cont13 = mysqli_query($cnx, "SELECT COUNT(tarima_part_ind) as cont FROM tarimas as t INNER JOIN lotes as l WHERE  ((t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR (t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR (t.lote_id = l.lote_id AND lote_mes = '$mes')  OR (t.lote_id = l.lote_id AND tarima_fecha = '".date("Y-m-d")."')) AND tarima_part_ind > 0") or die(mysql_error()."Error: en consultar lotes");
$reg_cont13 = mysqli_fetch_assoc ($cont13);
$cont13 = $reg_cont13['cont'];



$cad_tar =  mysqli_query($cnx, "SELECT SUM(tarima_lim_param) AS limite, SUM(tarima_bloom) as bloom, SUM(tarima_viscocidad) as viscosidad,SUM(tarima_ph_final) as ph_f, SUM(tarima_transparencia) as trans,SUM(tarima_porcen_t) AS porc, sum(tarima_ntu) as ntu, sum(tarima_humedad) as humedad,sum(tarima_cenizas) as cenizas,sum(tarima_redox) as redox,sum(tarima_color) as color, sum(tarima_grano) as grano,sum(tarima_olor) as olor, sum(tarima_part_ext) as partE,sum(tarima_part_ind) as partI FROM tarimas as t  INNER JOIN lotes as l WHERE (t.lote_id = l.lote_id AND tarima_fecha >= '$fechaInicio' AND tarima_fecha <= '$fechaFinal') OR ( t.lote_id = l.lote_id AND tarima_fecha = '$fechaInicio') OR ( t.lote_id = l.lote_id AND l.lote_mes = '$mes') OR ( t.lote_id = l.lote_id AND t.tarima_fecha = '".date("Y-m-d")."') ") or die(mysql_error()."Error: en consultar tarimas");
$reg_tar =  mysqli_fetch_array($cad_tar);



header('Content-type: application/vnd.ms-excel');

if ($mes == '' AND $fechaInicio == '' AND $fechaFinal == '') {
 header("Content-Disposition: attachment; filename=reporte_diario_día_'".date("Y-m-d")."'.xls");
}

if ($mes != '' AND $fechaInicio == '' AND $fechaFinal == '') {
 header("Content-Disposition: attachment; filename=reporte_diario_mes_".$mes.".xls");
}

if ($mes == '' AND $fechaInicio != '' AND $fechaFinal == '') {
  header("Content-Disposition: attachment; filename=reporte_diario_mes_".$fechaInicio.".xls");
}

if ($mes == '' AND $fechaInicio != '' AND $fechaFinal != '') {
 header("Content-Disposition: attachment; filename=reporte_diario_del_".$fechaInicio." al ".$fechaFinal.".xls");
}


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
   <table border="1" class="table table-striped" style="font-size: 12px;width: 100%;margin-bottom: 100px;">
    <thead>
      <tr style="text-align: center;background: #32383e;color: #fff">
        <th width="3%">FOLIO LOTE</th>
        <th width="3%">LOTE</th>
        <th width="7%">TARIMA</th>
        <th width="7%">FECHA</th>
        <th>HORA</th>
        <th>MES</th>
        <th>TURNO</th>
        <th></th>
        <th>BLOOM</th>
        <th>VISC.</th>
        <th>PH FINAL</th>
        <th>TRANS</th>
        <th>%T(620)</th>
        <th>NTU</th>
        <th>HUMEDAD</th>
        <th>CENIZAS</th>
        <th>REDOX</th>
        <th>COLOR</th>
        <th>GRANO MALLA #45</th>
        <th>OLOR</th>
        <th>PART. EXTRAÑAS</th>
        <th>PART. IND 6,66%</th>
        <th>HIDRATACIÓN</th>
          <th>ACEPT. / RECH.</th>
        </tr>
        <tr style="background: #F4F3F3;color: #000;text-align: center;">
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>LIM PARAM</td>
          <td></td>
          <td>MIN 100</td>
          <td>MIN .15-16 MAX</td>
          <td>5.5-6.0</td>
          <td>MIN 15</td>
          <td>70% MIN.</td>
          <td>60 MIN</td>
          <td>12%MAX</td>
          <td>1.5%MAX</td>
          <td>30 PPM MAX</td>
          <td>3 MAX</td>
          <td>40% MIN</td>
          <td>SIN OLOR EXTRAÑO</td>
          <td>0-25 MAX</td>
        <td>MAXIMO 6 GRANOS</td>
        <td>MAL-BIEN</td>
      </tr>
    </thead>
    <tbody>
      <?php  do { 

        $lotes = mysqli_query($cnx, "SELECT * FROM lotes WHERE lote_id = '$registros[lote_id]' ") or die(mysql_error()."Error: en consultar lotes");
        $reg_lotes = mysqli_fetch_assoc($lotes);

        if ($reg_lotes['lote_id'] == '') {?>
          <tr>
            <td style="text-align: center;font-size: 18px;color: #FD98B8" colspan="22">No hay registros del día</td>
          </tr>
        <?php } else{ ?>
          <tr style="text-align: center;">
            <td><?php echo $reg_lotes['lote_id'] ?></td>
            <td><?php echo $reg_lotes['lote_folio'] ?></td>
            <td><?php echo $registros['tarima_id'] ?></td>
            <td><?php echo fnc_formato_fecha($registros['tarima_fecha']) ?></td>
            <td><?php echo $reg_lotes['lote_hora'] ?></td>
            <td><?php echo $reg_lotes['lote_mes'] ?></td>
            <td><?php echo $reg_lotes['lote_turno'] ?></td>
            <td><?php echo $registros['tarima_lim_param'] ?></td>
            <td><?php echo $registros['tarima_bloom'] ?></td>
            <td><?php echo $registros['tarima_viscocidad'] ?></td>
            <td><?php echo $registros['tarima_ph_final'] ?></td>
            <td><?php echo $registros['tarima_transparencia'] ?></td>
            <td><?php echo $registros['tarima_porcen_t'] ?></td>
            <td><?php echo $registros['tarima_ntu'] ?></td>
            <td><?php echo $registros['tarima_humedad'] ?></td>
            <td><?php echo $registros['tarima_cenizas'] ?></td>
            <td><?php echo $registros['tarima_redox'] ?></td>
            <td><?php echo $registros['tarima_color'] ?></td>
            <td><?php echo $registros['tarima_grano'] ?></td>
            <td><?php echo $registros['tarima_olor'] ?></td>
            <td><?php echo $registros['tarima_part_ext'] ?></td>
            <td><?php echo $registros['tarima_part_ind'] ?></td>
          <td><?php echo $registros['tarima_hidratacion'] ?></td>
            <td><?php echo $registros['tarima_aceptado'] ?></td>
          </tr>
        <?php } } while ($registros = mysqli_fetch_assoc($cadena)); ?>

      </tbody>
         <tfoot style="background: #F4F3F3;text-align: center;font-weight: bold;">
        <tr style="background: #F4F3F3;text-align: center;font-weight: bold;border: 1px solid#e6e6e6">
         <td colspan="6">Promedio:</td>
         <td></td>
         <td align="center">---</td>
         <td><?php if($reg_tar['bloom'] == '' or $reg_tar['bloom'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['bloom'] / $cont1), 2); } ?></td>
         <td><?php if($reg_tar['viscosidad'] == '' or $reg_tar['viscosidad'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['viscosidad'] / $cont1), 2); } ?></td>
         <td><?php if($reg_tar['ph_f'] == '' or $reg_tar['ph_f'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['ph_f'] / $cont3), 2); } ?></td>
         <td><?php if($reg_tar['trans'] == '' or $reg_tar['trans'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['trans'] / $cont4), 2); } ?></td>
         <td><?php if($reg_tar['porc'] == '' or $reg_tar['porc'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['porc'] / $cont5), 2); } ?></td>
         <td><?php if($reg_tar['ntu'] == '' or $reg_tar['ntu'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['ntu'] / $cont6), 2); }?></td>
         <td><?php if($reg_tar['humedad'] == '' or $reg_tar['humedad'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['humedad'] / $cont7), 2); } ?></td>
         <td><?php if($reg_tar['cenizas'] == '' or $reg_tar['cenizas'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['cenizas'] / $cont8), 2); } ?></td>
         <td><?php if($reg_tar['redox'] == '' or $reg_tar['redox'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['redox'] / $cont9), 2); } ?></td>
         <td><?php if($reg_tar['color'] == '' or $reg_tar['color'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['color'] / $cont10), 2); }?></td>
         <td><?php if($reg_tar['grano'] == '' or $reg_tar['grano'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['grano'] / $cont11), 2); }?></td>
         <td>---</td>
         <td><?php if($reg_tar['partE'] == '' or $reg_tar['partE'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['partE'] / $cont12), 2); }?></td>
         <td><?php if($reg_tar['partI'] == '' or $reg_tar['partI'] == '0'){ echo "0.00"; }else{ echo number_format(($reg_tar['partI'] / $cont13), 2); }?></td>
          <td align="center">---</td>
           <td align="center">---</td>
       </tr>
     </tfoot>
   </table>
   <table style="border:1px solid #e6e6e6">
    <tr style="border:1px solid #e6e6e6">
      <td style="border:1px solid #fff;background: #F4F3F3" colspan="24" align="center" >
        <footer style="background: #F4F3F3; width: 100%; text-align: center; position: fixed; bottom: 0; color:#fff;font-weight: bold;font-size: 9px">
          Copyright 2018 by <b>Ca & Ce Technologies</b>. All Rights Reserved. 
        </footer>
      </tr>
    </table>
    <p>&nbsp;</p>
  </div>
</body>
</html>