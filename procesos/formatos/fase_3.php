<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/ 
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 

/*$sqlProAux3 = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$idx_pro' AND pe_id = $regf3d[pe_id]") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux3 = mysqli_fetch_assoc($sqlProAux3);*/

$sqlProAux3 = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$idx_pro'  AND pe_id = '5'") or die(mysqli_error()."Error: en consultar procesos auxiliar");
$regProAux3 = mysqli_fetch_assoc($sqlProAux3);

$sqlf3d = mysqli_query($cnx, "SELECT * FROM procesos_fase_3_d as pf3d INNER JOIN procesos as p  on(pf3d.pro_id = p.pro_id) WHERE p.pro_id  = '$regProAux3[pro_id]'") or die(mysqli_error($cnx)."Error: en consultar el tipo de material");
$regf3d= mysqli_fetch_assoc($sqlf3d);

$sqlf3g = mysqli_query($cnx, "SELECT * FROM procesos_fase_3_g WHERE pro_id = '$idx_pro'") or die(mysqli_error($cnx)."Error: en consultar procesos_fase_3_g ");
$regf3= mysqli_fetch_assoc($sqlf3g);

$sqlProLib3 = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regProAux3[pro_id]'  AND pe_id = '5'") or die(mysqli_error($cnx)."Error: en consultar procesos de liberacion");
$regProLib3 = mysqli_fetch_assoc($sqlProLib3);

$usu_auxx = $regProAux3['usu_op'];
?>


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
          <td height="45" colspan="10"> LAVADOS DE BLANQUEO Este proceso se puede hacer con aguar recuperada limpia (pila 1)Lavados finales, 1er ACIDO, PALETO A PALETO
          </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><table width="100%" border="1">
        <tr>
          <td width="17%" style="font-weight: bold;background: #e6e6e6">Fecha inicia</td>
          <td width="9%" ><?php echo fnc_formato_fecha($regProAux3['proa_fe_ini'])?></td>
          <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora inicio</td>
          <td width="12%"><?php echo $regProAux3['proa_hr_ini']?></td>
          <td width="12%" style="font-weight: bold;background: #e6e6e6">Enzima liquida</td>
          <td width="12%"><?php echo $regf3['pfg3_enzima'] ?></td>
          <td width="12%" style="font-weight: bold;background: #e6e6e6">Operador</td>
          <td width="12%"><?php echo fnc_nom_usu($usu_auxx)?></td>
          <td width="14%" style="font-weight: bold;background: #e6e6e6">BUSCAR CE A 3.0 MAXIMO</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table width="100%" border="1">
        <tr style="font-weight: bold;background: #e6e6e6">
          <td width="5%" style="font-size: small; text-align: center;">Lav</td>
          <td width="16%" style="font-size: small; text-align: center;">Tipo agua</td>
          <td width="12%" style="font-size: small; text-align: center;">Tem</td>
          <td width="12%" style="font-size: small; text-align: center;">Hr inica llenado</td>
          <td width="12%" style="font-size: small; text-align: center;">Hr termina llenado</td>
          <td width="12%" style="font-size: small; text-align: center;">Hr inicia movimiento</td>
          <td width="8%"  style="font-size: small; text-align: center;">Hr termina movimiento</td>
          <td style="font-size: small; text-align: center;">Ph</td>
          <td style="font-size: small; text-align: center;">Ce</td>
          <td style="font-size: small; text-align: center;">PPM</td>
          <td width="12%"  style="font-size: small; text-align: center;">Agua a</td>
          <td width="8%"  style="font-size: small; text-align: center;">Observaciones</td>
          <td width="8%"  style="font-size: small; text-align: center;">Capturo</td>
        </tr>
        <?php 
        $txtOpe = fnc_nom_usu($regf2d['usu_id']);
        do{

          $tagua = mysqli_query($cnx, "SELECT * FROM tipos_agua  WHERE tpa_id  = '$regf3d[tpa_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regtagua= mysqli_fetch_assoc($tagua);

          $agua_a = mysqli_query($cnx, "SELECT * FROM tipos_agua_a WHERE taa_id = '$regf3d[taa_id]'") or die(mysql_error()."Error: en consultar el tipo de agua a");
          $reg_aa= mysqli_fetch_assoc($agua_a);

          ?>
          <tr>
           <td><?php echo $regf3d['pfd3_ren']?></td>
           <td><?php echo $regtagua['tpa_descripcion']?></td>
           <td><?php echo fnc_formato_val($regf3d['pfd3_temp'])?></td>
           <td><?php echo $regf3d['pfd3_hr_ini']?></td>
           <td><?php echo $regf3d['pfd3_hr_fin']?></td>
           <td><?php echo $regf3d['pfd3_hr_ini_mov']?></td>
           <td><?php echo $regf3d['pfd3_hr_fin_mov']?></td>
           <td><?php echo fnc_formato_val($regf3d['pfd3_ph'])?></td>
           <td><?php echo fnc_formato_val($regf3d['pfd3_ce'])?></td>
           <td><?php echo fnc_formato_val($regf3d['pfd3_ppm'])?></td>
           <td><?php echo $reg_aa['taa_descripcion']?></td>
           <td><?php echo $regf3d['pfd3_observaciones']?></td>
           <td><?php echo fnc_nom_usu($regf3d['usu_id']); ?></td>
         </tr>
       <?php } while($regf3d= mysqli_fetch_assoc($sqlf3d));?>
     </table></td>
   </tr>
   <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table border="1" width="100%">
      <?php 
      $usu_aux = $regProAux3['usu_sup'];
      
      $usu_proLib = $regProLib3['usu_id'];
      ?>
      <tr style="font-weight: bold;">
        <td style="background: #e6e6e6">Fecha termina</td>
        <td style="background: #e6e6e6">Hora termina</td>
        <td style="background: #e6e6e6">Usuario</td>
        <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
        <td  style="border:1px solid#fff;background: #e6e6e6"><?php echo fnc_hora_de(5) ?> a <?php echo fnc_hora_a(5) ?></td>
        <td width="7%" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
        <td rowspan="5" width="30" style="font-size:50px">3</td>
        <td colspan="3" style="background: #e6e6e6;font-weight: bold;">Liberación CE 3MAX</td>
      </tr>
      <tr>
        <td><?php echo fnc_formato_fecha($regProAux3['proa_fe_fin'])?></td>
        <td><?php echo $regProAux3['proa_hr_fin']?></td>
        <td><?php echo fnc_nom_usu($usu_aux)?></td>
        <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
        <td style="border:1px solid#fff"></td>
        <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
        <td>Ce de liberación</td>
        <td width="15%" colspan="2"><?php echo $regProLib3['prol_ce'] ?></td>
      </tr>
        <!--<tr>
          <td colspan="3"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td style="border:1px solid#fff"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td>Color</td>
          <td width="15%" colspan="2"><?php echo $regProLib3['prol_color'] ?></td>
        </tr>-->
        <tr>
          <td colspan="3" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td style="border:1px solid#fff"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td>Horas totales</td>
          <td width="15%" colspan="2"><?php echo $regProLib3['prol_hr_totales'] ?></td>
        </tr>
        <tr>
          <td colspan="3"><?php echo $regProAux3['proa_observaciones']?></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td style="border:1px solid#fff"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td>Nombre LCP </td>
          <td width="15%" colspan="2"><?php echo fnc_nom_usu($usu_proLib)?></td>
        </tr>
      </table></td>
    </tr>
  </table>
</div>
