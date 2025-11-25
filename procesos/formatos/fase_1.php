<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/ 
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

//extract($_POST); 

//$id_l = $_GET['id_l'];
//$sqlf1g = mysqli_query($cnx, "SELECT * FROM procesos_fase_1_g as pf1g INNER JOIN procesos as p on(pf1g.pro_id=p.pro_id) AND p.pl_id='1' AND p.pro_estatus='1'") or die(mysql_error()."Error: en consultar procesos_fase_1_g ");
$sqlf1g = mysqli_query($cnx, "SELECT * FROM procesos_fase_1_g as pf1g INNER JOIN procesos as p  on(pf1g.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro' AND pf1g.pe_id = 1") or die(mysql_error()."Error: en consultar procesos_fase_1_g ");
$regf1g= mysqli_fetch_assoc($sqlf1g);


//$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar as pa INNER JOIN usuarios as u on(pa.usu_sup=u.usu_id) WHERE pro_id = '$regf1g[pro_id]'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regf1g[pro_id]' AND pe_id = '1'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


//$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion as prolib INNER JOIN usuarios as u on(prolib.usu_id=u.usu_id) WHERE pro_id = '$regf1g[pro_id]'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regf1g[pro_id]' AND pe_id = '1'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

?>
<!--<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>-->


<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
<style>
/*td{
  padding-left: 5px;padding-right: 5px
}*/
</style>


  <div class="divProcesos">
    <table width="100%" style="margin:20px 0px 20px 0px">
      <tr>
        <td><table border="1" style="background: #FCEFF2;font-size: 12px;width: 100%">
          <tr>
            <td height="45" colspan="10"> Lavados inicales. Este proceso se puede hacer con aguar recuperada limpia (pila 1).
            Lavados finales de paleto a paleto y en el ultimo lavado utilizar agua limpia si se necesita bajar CE</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="1">
          <tr>
            <td width="25%" style="font-weight: bold;background: #e6e6e6">Fecha inicia lavados</td>
            <td width="19%" style="font-weight: bold;background: #e6e6e6">Hora inicio</td>
            <td width="27%" style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
            <td width="15%" style="border-top: 1px solid#fff;border-bottom: 1px solid#fff;"></td>
            <td width="14%" rowspan="2" style="background: #e6e6e6;font-weight: bold;">Bajar CE A 3.0</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_ini'])?></td>
            <td><?php echo $regProAux['proa_hr_ini']?></td>
            <td><?php echo $regf1g['pfg1_temp_ag'] ?></td>
            <td style="border-top: 1px solid#fff;border-bottom: 1px solid#fff;border-right: 1px solid#fff;font-weight: bold;"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width="100%" border="1">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="5%" style="font-size: small; text-align: left;">Lav</td>
            <td width="12%" style="font-size: small; text-align: center;"> Tipo agua</td>
            <td width="12%" style="font-size: small; text-align: center;">TEMP</td>
            <td width="12%" style="font-size: small; text-align: center;">Hora Ini. llenado</td>
            <td width="12%" style="font-size: small; text-align: center;">Hora term. lavado</td>
            <td width="10%" style="font-size: small; text-align: center;">Hora ini. mov</td>
            <td width="10%" style="font-size: small; text-align: center;">Hora term. mov</td>
            <td width="9%"  style="font-size: small; text-align: center;">Ph</td>
            <td width="8%"  style="font-size: small; text-align: center;">Ce</td>
            <td width="8%"  style="font-size: small; text-align: center;">Agua a</td>
            <td width="8%"  style="font-size: small; text-align: center;">Observaciones</td>
            <td width="18%"  style="font-size: small; text-align: left;">Capturo</td>
          </tr>
          <?php 
            $sqlf1d = mysqli_query($cnx, "SELECT * FROM procesos_fase_1_d as pf1d  inner join tipos_agua as ta on(pf1d.tpa_id= ta.tpa_id) WHERE pf1d.pfg1_id = '$regf1g[pfg1_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regf1d= mysqli_fetch_assoc($sqlf1d);


do{

   $agua_a = mysqli_query($cnx, "SELECT * FROM tipos_agua_a WHERE taa_id = '$regf1d[taa_id]'") or die(mysql_error()."Error: en consultar el tipo de agua a");
          $reg_aa= mysqli_fetch_assoc($agua_a);

         
            ?>
            <tr>
             <td><?php echo $regf1d['pfd1_ren']?></td>
             <td><?php echo $regf1d['tpa_descripcion']?></td>
             <td><?php echo $regf1d['pfd1_temp']?></td>
             <td><?php echo $regf1d['pfd1_hr_ini']?></td>
             <td><?php echo $regf1d['pfd1_hr_fin']?></td>
             <td><?php echo $regf1d['pfd1_hr_ini_mov']?></td>
             <td><?php echo $regf1d['pfd1_hr_fin_mov']?></td>
             <td><?php echo $regf1d['pfd1_ph']?></td>
             <td><?php echo $regf1d['pfd1_ce']?></td>
             <td><?php echo $reg_aa['taa_descripcion']?></td>
             <td><?php echo $regf1d['pfd1_observaciones']?></td>
             <td><?php echo fnc_nom_usu($regf1d['usu_id'])?></td>
           </tr>
         <?php } while($regf1d= mysqli_fetch_assoc($sqlf1d));?>
       </table></td>
     </tr>
     <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table border="1" width="100%">
        <tr style="font-weight: bold;">
          <td style="background: #e6e6e6">Fecha termina</td>
          <td style="background: #e6e6e6">Hora termina</td>
          <td style="background: #e6e6e6">Usuario</td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td  style="border:1px solid#fff;background: #e6e6e6"><?php echo fnc_hora_de(1) ?> a <?php echo fnc_rango_a(1) ?></td>
          <td width="7%" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td rowspan="5" width="30" style="font-size:50px">1</td>
          <td colspan="3" style="background: #e6e6e6;font-weight: bold;">Liberación CE a 3MAX</td>
        </tr>
        <tr>
          <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin'])?></td>
          <td><?php echo $regProAux['proa_hr_fin']?></td>
          <td><?php echo fnc_nom_usu($regProAux['usu_sup'])?></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td style="border:1px solid#fff"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td>CE de liberación</td>
          <td width="15%" colspan="2"><?php echo $regProLib['prol_ce'] ?></td>
        </tr>
        <tr>
          <td colspan="3"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td style="border:1px solid#fff"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td>Horas totales </td>
          <td width="15%" colspan="2"><?php echo $regProLib['prol_hr_totales'] ?></td>
        </tr>
        <tr>
          <td colspan="3" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td style="border:1px solid#fff"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td>Nombre LCP </td>
          <td width="15%" colspan="2"><?php echo fnc_nom_usu($regProLib['usu_id']) ?></td>
        </tr>
        <tr>
          <td colspan="3"><?php echo $regProAux['proa_observaciones']?></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td style="border:1px solid#fff"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td></td>
          <td width="15%" colspan="2"></td>
        </tr>
      </table></td>
    </tr>
  </table>






</div>
