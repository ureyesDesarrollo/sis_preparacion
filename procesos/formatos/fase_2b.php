<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/ 
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 
$sqlf2bg = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_g as pf2bg INNER JOIN procesos as p  on(pf2bg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysql_error()."Error: en consultar procesos_fase_2b_g ");
$regf2bg= mysqli_fetch_assoc($sqlf2bg);


/*$sqlProAux2b = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$idx_pro' AND pe_id = $regf2bg[pe_id]") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux2b = mysqli_fetch_assoc($sqlProAux2b);*/

$sqlProAux2b = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regf2bg[pro_id]'  AND pe_id = '3'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux2b = mysqli_fetch_assoc($sqlProAux2b);


$sqlProLib2b = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regf2bg[pro_id]'  AND pe_id = '3'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib2b = mysqli_fetch_assoc($sqlProLib2b);

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
        <td colspan="2"><table border="1" style="background: #FCEFF2;font-size: 12px;width: 100%">
          <tr>
            <td height="45" colspan="10">ENZIMA. Este proceso es de 10 horas en movimiento continuo</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><table width="100%" border="1">
          <tr>
            <td width="17%" style="font-weight: bold;background: #e6e6e6">Enzima</td>
            <td width="9%"  style="font-weight: bold;background: #e6e6e6"><span>Fecha de inicio</span></td>
            <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
            <td width="12%"  style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Capturo</td>
          </tr>
          <tr>
            <td><?php echo $regf2bg['pfg2_enzima']?></td>
            <td><?php echo fnc_formato_fecha($regProAux2b['proa_fe_ini'])?></td>
            <td><?php echo $regProAux2b['proa_hr_ini']?></td>
            <td><?php echo $regf2bg['pfg2_temp_ag'] ?></td>
            <td><?php echo fnc_nom_usu($regf2bg['usu_id']) ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="63%" valign="top">
          <table width="96%" border="1" style="margin-top: 10px">
            <tr style="font-weight: bold;background: #e6e6e6">
              <td width="8%" style="font-size: small; text-align: center;">No.</td>
              <td width="30%" style="font-size: small; text-align: center;">Ph</td>
              <td width="27%" style="font-size: small; text-align: center;">Sosa</td>
              <td width="27%" style="font-size: small; text-align: center;">Ácido</td>
              <td width="35%" style="font-size: small; text-align: center;">Capturo</td>
            </tr>
            <?php 
           $sqlf2d = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_d as pf2bd WHERE pf2bd.pfg2_id = '$regf2bg[pfg2_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regf2d= mysqli_fetch_assoc($sqlf2d);
do{

         
            ?>
            <tr>
              <td><?php echo $regf2d['pfd2_ren']?></td>
              <td><?php echo $regf2d['pfd2_ph']?></td>
              <td><?php echo fnc_formato_val($regf2d['pfd2_sosa'])?></td>
              <td><?php echo fnc_formato_val($regf2d['pfd2_acido'])?></td>
              <td><?php echo fnc_nom_usu($regf2d['usu_id'])?></td>
            </tr>
            <?php } while($regf2d= mysqli_fetch_assoc($sqlf2d));?>
          </table>
        </td>
        <td width="37%" valign="top"><table border="1" width="435" style="margin-top:10px">
          <tr>
            <td width="124" style="font-weight: bold;background: #e6e6e6">Ph solución</td>
            <td width="199"><?php echo $regf2bg['pfg2_ph1'] ?></td>
            <td width="89" style="font-weight: bold;background: #e6e6e6">Horas</td>
            <td width="132"><?php echo $regf2bg['pfg2_hr1'] ?></td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Nombre LCP</td>
            <td><?php echo fnc_nom_usu($regf2bg['pfg2_usu1']) ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Ph solución</td>
            <td><?php echo fnc_formato_val($regf2bg['pfg2_ph2'])?></td>
            <td style="font-weight: bold;background: #e6e6e6">Horas</td>
            <td><?php echo fnc_formato_val($regf2bg['pfg2_hr2'])?></td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Nombre LCP</td>
            <td><?php echo fnc_nom_usu($regf2bg['pfg2_usu2']) ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><div>Este proceso es de 15 a 30 minutos de movimiento por cada 2 o 3 horas de reposo</div>
        <br /></td>
        <td>Nota: Seguir anotando los chequeos después de que cumpla su tiempo (32-36) o cuando se inicia abara normalidad.</td>
      </tr>
      <tr>
      <td colspan="2" valign="top">
        <table width="100%" border="1">
        <tr style="font-weight: bold;background: #e6e6e6">
          <td width="6%" style="font-size: small; text-align: center;">No.</td>
          <td width="15%" style="font-size: small; text-align: center;">Hora</td>
          <td width="18%" style="font-size: small; text-align: center;">Min. movimiento</td>
          <td width="19%" style="font-size: small; text-align: center;">Reposo</td>
          <td width="14%" style="font-size: small; text-align: center;">Ph</td>
          <td width="14%" style="font-size: small; text-align: center;">Temp</td>
           <td width="12%"  style="font-weight: bold;background: #e6e6e6">Sosa</td>
            <td width="12%"  style="font-weight: bold;background: #e6e6e6">Ácido</td>
          <td width="14%" style="font-size: small; text-align: center;">Capturo</td>
        </tr>
        <?php 
           $sqlf2d2 = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_d2 as pf2bd WHERE pf2bd.pfg2_id = '$regf2bg[pfg2_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regf2d2= mysqli_fetch_assoc($sqlf2d2);
do{

         
            ?>
        <tr>
          <td><?php echo $regf2d2['pfd22_ren']?></td>
          <td><?php echo $regf2d2['pfd22_hr']?></td>
          <td><?php echo fnc_formato_val($regf2d2['pfd22_min'])?></td>
          <td><?php echo fnc_formato_val($regf2d2['pfd22_reposo'])?></td>
          <td><?php echo fnc_formato_val($regf2d2['pfd22_ph'])?></td>
          <td><?php echo fnc_formato_val($regf2d2['pfd22_temp'])?></td>
          <td><?php echo fnc_formato_val($regf2d2['pfd22_sosa'])?></td>
          <td><?php echo fnc_formato_val($regf2d2['pfd22_acido'])?></td>
          <td><?php echo fnc_nom_usu($regf2d2['usu_id'])?></td>
        </tr>
        <?php } while($regf2d2= mysqli_fetch_assoc($sqlf2d2));?>
      </table>
        </td>
          <tr>
          <td>
            El agua de este proceso se manda a agua recuperada semilimpia (Pila 2)
          </td>
        </tr>
    </tr>
    <tr>
      <td colspan="2"><table border="1" width="100%">
        <?php 
        $usu_aux = $regProAux2b['usu_sup'];
        $usu_proLib = $regProLib2b['usu_id'];
         ?>
        <tr style="font-weight: bold;">
          <td width="266" style="background: #e6e6e6">Fecha termina enzima</td>
          <td width="168" style="background: #e6e6e6">Hora termina enzima</td>
          <td width="175" style="background: #e6e6e6">Usuario</td>
          <td width="8" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <!--<td width="112"  style="border:1px solid#fff;background: #e6e6e6">32 a 36 horas</td>-->
          <td width="77" style="border-top:1px solid#fff;border-left:1px solid#fff"></td>
          <td rowspan="4" width="36" style="font-size:50px;border-bottom:1px solid#000">2b</td>
          <td colspan="3" style="background: #e6e6e6;font-weight: bold;">Liberación <?php echo fnc_rango_de(3) ?> a <?php echo fnc_rango_a(3) ?> horas</td>
        </tr>
        <tr>
          <td><?php echo fnc_formato_fecha($regProAux2b['proa_fe_fin'])?></td>
          <td><?php echo $regProAux2b['proa_hr_fin']?></td>
          <td><?php echo fnc_nom_usu($usu_aux)?></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff;border-left:1px solid#fff"></td>
         
          <td width="119"></td>
          <td width="172" colspan="2"></td>
        </tr>
        <tr>
          <td colspan="3"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff;border-left:1px solid#fff"></td>
          
          <td>Horas totales </td>
          <td width="172" colspan="2"><?php echo $regProLib2b['prol_hr_totales'] ?></td>
        </tr>
        <tr>
          <td colspan="3" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff;border-left:1px solid#fff"></td>
          
          <td>Nombre LCP </td>
          <td width="172" colspan="2"><?php echo fnc_nom_usu($usu_proLib)?></td>
        </tr>
        <tr>
          <td colspan="3"><?php echo $regProAux2b['proa_observaciones']?></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff;border-right:2px solid#fff;"></td>
         
         
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
          </tr>
        <tr>
          <td  style="font-weight: bold;background: #e6e6e6"><label for="inputPassword4" >Horas totales de todo el proceso</label></td>
          <td  style="font-weight: bold;background: #e6e6e6">Revisó</td>
          <td  style="font-weight: bold;background: #e6e6e6">(<?php echo fnc_hora_de(3) ?> a <?php echo fnc_hora_a(3) ?> Horas)</td>
         
          </tr>
        <tr>
          <td><?php echo $regProLib2b['prol_hr_totales']?></td>
          <td><?php echo fnc_nom_usu($regProLib2b['usu_id'])?></td>
          <td></td>
          </tr>
      </table></td>
    </tr>
  </table>
</div>
