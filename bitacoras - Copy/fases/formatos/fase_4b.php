<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/ 
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 

$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_4_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro' AND pfg.pe_id = 8") or die(mysql_error()."Error: en consultar procesos_fase_4_g ");
$regfg= mysqli_fetch_assoc($sqlfg);




/*$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$idx_pro' AND pe_id = $regfg[pe_id]") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);*/

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '8'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '8'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

?>


<!--<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">-->
<style>
/*td{
  padding-left: 5px;padding-right: 5px
}*/</style>


  <div class="divProcesos">
    <table width="100%" style="margin:20px 0px 20px 0px">
      <tr>
        <td colspan="3"><table border="1" style="background: #FCEFF2;font-size: 12px;width: 100%">
          <tr>
            <td height="45" colspan="10">PRIMER ÁCIDO. Este proceso se puede hacer con agua de depositos de agua acida (Recupera de 2do ácido)</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="3"><table width="100%" border="1">
          <tr>
            <td width="18%"  style="font-weight: bold;background: #e6e6e6"><span>Fecha de inicio</span></td>
            <td colspan="2" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
            <td width="21%"  style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
            <td width="23%" style="font-weight: bold;background: #e6e6e6">Operador</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_ini'])?></td>
            <td colspan="2"><?php echo $regProAux['proa_hr_ini'] ?></td>
            <td><?php echo $regfg['pfg4_temp_ag']?></td>
            <td><?php echo fnc_nom_usu($regfg['usu_id']) ?></td>
          </tr>
          <tr>
            <td  style="font-weight: bold;background: #e6e6e6">Temp</td>
            <td width="17%" style="font-weight: bold;background: #e6e6e6">Acido</td>
            <td width="21%" style="font-weight: bold;background: #e6e6e6">Sol acida fuerte</td>
            <td  style="font-weight: bold;background: #e6e6e6">Termina </td>
            <td style="font-weight: bold;background: #e6e6e6">Temp</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_val($regfg['pfg4_temp'])?></td>
            <td><?php echo $regfg['pfg4_acido']?></td>
            <td><?php echo $regfg['pfg4_acido_fuerte']?></td>
            <td><?php echo fnc_formato_val($regfg['pfg4_termina'])?></td>
            <td><?php echo fnc_formato_val($regfg['pfg4_temp2'])?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="54%" valign="top"><table width="100%" border="1" style="margin-top: 10px;height:100%">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="20%" style="font-size: small; text-align: center;">Ajust</td>
            <td width="31%" style="font-size: small; text-align: center;">Acido</td>
            <td width="30%" style="font-size: small; text-align: center;">Ph</td>
            <td width="40%" style="font-size: small; text-align: center;">Temp</td>
            <td width="40%" style="font-size: small; text-align: center;">Capturo</td>
          </tr>
          <?php 
           $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_4_d as pfd WHERE pfd.pfg4_id = '$regfg[pfg4_id]'") or die(mysql_error()."Error: en consultar procesos_fase_4_d");
          $regfd= mysqli_fetch_assoc($sqlfd);
do{

         
            ?>
          <tr>
            <td><?php echo $regfd['pfd4_ren']?></td>
            <td><?php echo $regfd['pfd4_acido']?></td>
            <td><?php echo fnc_formato_val($regfd['pfd4_ph'])?></td>
            <td><?php echo fnc_formato_val($regfd['pfd4_temp'])?></td>
            <td><?php echo fnc_nom_usu($regfd['usu_id'])?></td>
          </tr>
          <?php } while($regfd= mysqli_fetch_assoc($sqlfd));?>
        </table></td>
          <td width="11%" valign="top"><table width="112" border="1" style="margin-top:10px">
          <tr>
            <td valign="top">MANTENER PH 3 - 3.5 (2 a 4 hrs. mov.cont)</td>
          </tr>
        </table></td>
        <td width="20%" rowspan="3" valign="top"><p>
          <?php 
        $usu_aux = $regProAux['usu_sup'];
        $usu_proLib = $regProLib['usu_id'];
         ?>
        </p>
          <table width="406" border="1" style="height:100%">
          <tr>
            <td width="64" rowspan="5" style="font-size:50px">4b</td>
            <td colspan="2" style="background: #e6e6e6;font-weight: bold;">Nota: la adición de ácido se agrega por los dos lados del lavador y mantener el ph 3.0 a 5 durante todo el proceso de 1er ácido.</td>
          </tr>
          <tr>
            <td width="181">Adelgazamiento</td>
            <td width="139"><?php echo $regProLib['prol_adelgasamiento'] ?></td>
          </tr>
          <tr>
            <td>Ph promedio</td>
            <td><?php echo $regProLib['prol_ph'] ?></td>
          </tr>
          <tr>
            <td>Horas totales</td>
            <td><?php echo $regProLib['prol_hr_totales'] ?></td>
          </tr>
          <tr>
            <td>Nombre LCP</td>
            <td><?php echo fnc_nom_usu($usu_proLib)?></td>
          </tr>
        </table>
        <p>&nbsp;</p></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td valign="top"><table border="1" width="99%">
        <tr style="font-weight: bold;">
          <td style="background: #e6e6e6">Cocido del cuero ph (6.0)</td>
          <td><?php echo fnc_formato_val($regfg['pfg4_cocido_ph'])?></td>
          <td width="105" style="background: #e6e6e6">Ce</td>
          <td width="111"><?php echo $regfg['pfg4_ce']?></td>
        </tr>
        <tr style="font-weight: bold;">
          <td width="246" style="background: #e6e6e6">Fecha termina 1er acidificación</td>
          <td width="199" style="background: #e6e6e6">Hora termina </td>
          <td colspan="2" style="background: #e6e6e6">Usuario</td>
        </tr>
        <tr>
          <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin'])?></td>
          <td><?php echo $regProAux['proa_hr_fin']?></td>
          <td colspan="2"><?php echo fnc_nom_usu($usu_aux)?></td>
        </tr>
        <tr>
          <td colspan="4" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
        </tr>
        <tr>
          <td colspan="4"><?php echo $regProAux['proa_observaciones']?></td>
        </tr>
      </table></td>
      <td>&nbsp;</td>
      </tr>
    </table>
</div>
