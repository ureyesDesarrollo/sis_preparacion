<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/ 
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 


$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysql_error()."Error: en consultar procesos_fase_7b_g ");
$regfg= mysqli_fetch_assoc($sqlfg);

$sqltaa = mysqli_query($cnx, "SELECT taa_descripcion FROM tipos_agua_a  WHERE taa_id = '$regfg[taa_id]'") or die(mysql_error()."Error: en consultar tipos_agua_a ");
$regtaa= mysqli_fetch_assoc($sqltaa);

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '19'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '19'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

?>

<style>
/*td{
  padding-left: 5px;padding-right: 5px
}*/
</style>


<div class="divProcesos">
  <table width="100%" style="margin:20px 0px 20px 0px">
    <tr>
      <td colspan="2"><table width="84%" border="1" style="background: #FCEFF2;font-size: 12px;width: 90%">
        <tr>
          <td height="45" colspan="10">SEGUNDO ÁCIDO. Este proceso se utiliza el agua de los lavadores de 1er ácido o solo de ácido fuerte</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2"><table width="90%" border="1">
        <tr>
          <td width="20%"  style="font-weight: bold;background: #e6e6e6"><span>Fecha inicio</span></td>
          <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
          <td width="14%" style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
          <td width="14%" style="font-weight: bold;background: #e6e6e6">Ácido diluido</td>
          <td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
          </tr>
        <tr>
          <td><?php echo fnc_formato_fecha($regProAux['proa_fe_ini'])?></td>
          <td><?php echo $regProAux['proa_hr_ini'] ?></td>
          <td><?php echo $regfg['pfg7_temp_ag'] ?></td>
          <td><?php echo $regfg['pfg7_acido_diluido'] ?></td>
          <td><?php echo fnc_nom_usu($regfg['usu_id']) ?></td>
          </tr>
        <tr>
          <td  style="font-weight: bold;background: #e6e6e6"><span>Temp</span></td>
          <td style="font-weight: bold;background: #e6e6e6">Ácido</td>
          <td style="font-weight: bold;background: #e6e6e6">Ph</td>
          <td style="font-weight: bold;background: #e6e6e6">Ce</td>
          <td width="14%" style="font-weight: bold;background: #e6e6e6">Normalidad</td>
          </tr>
        <tr>
          <td><?php echo fnc_formato_val($regfg['pfg7_temp'])?></td>
          <td><?php echo $regfg['pfg7_acido'] ?></td>
          <td><?php echo fnc_formato_val($regfg['pfg7_norm']) ?></td>
          <td><?php echo fnc_formato_val($regfg['pfg7_temp2']) ?></td>
          <td><?php echo fnc_formato_val($regfg['pfg7_norm']) ?></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2">
        <table width="1088" border="1" style="margin-top: 10px">
          <tr style="font-weight: bold;border-top:2px solid#fff;border-left:2px solid#fff;border-right:2px solid#fff;">
            <td style="font-size: small; text-align: center;border:1px solid#fff">&nbsp;</td>
            <td style="font-size: small; text-align: center;border:1px solid#fff">&nbsp;</td>
            <td style="font-size: small; text-align: center;border:1px solid#fff">&nbsp;</td>
            <td style="font-size: small; text-align: center;border:1px solid#fff">PPRO</td>
            <td style="font-size: small; text-align: center;border:1px solid#fff">&nbsp;</td>
            <td style="font-size: small; text-align: center;border:1px solid#fff">&nbsp;</td>
            <td style="font-size: small; text-align: center;border:1px solid#fff">&nbsp;</td>
          </tr>
          <tr style="font-weight: bold;background: #e6e6e6;">
            <td width="5%" style="font-size: small; text-align: center;border">Ajust</td>
            <td width="18%" style="font-size: small; text-align: center;border">Ac</td>
            <td width="18%" style="font-size: small; text-align: center;border">Temp</td>
            <td width="20%" style="font-size: small; text-align: center;border">Ph</td>
            <td width="15%" style="font-size: small; text-align: center;border">Ce</td>
            <td width="15%" style="font-size: small; text-align: center;border">Normalidad</td>
            <td width="20%" style="font-size: small; text-align: center;">Capturo</td>

          </tr>
          <?php
          $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d WHERE pfg7_id = '$regfg[pfg7_id]'") or die(mysql_error()."Error: en consultar procesos_fase_7b_d");
          $regfd= mysqli_fetch_assoc($sqlfd);

          do{


            ?>
            <tr>
              <td><?php echo $regfd['pfd7_ren']?></td>
              <td><?php echo $regfd['pfd7_acido']?></td>
              <td><?php echo $regfd['pfd7_temp']?></td>
              <td style="background:#FF0000;color: #fff"><?php echo $regfd['pfd7_ph']?></td>
              <td><?php echo $regfd['pfd7_ce']?></td>
              <td><?php echo $regfd['pfd7_norm']?></td>
              <td><?php echo fnc_nom_usu($regfd['usu_id'])?></td>
            </tr>
          <?php } while($regfd= mysqli_fetch_assoc($sqlfd));?>
        </table>
        <p>
          <?php 
       $usu_aux = $regProAux['usu_sup'];
       $usu_proLib = $regProLib['usu_id'];
       ?>
      </p></td>
    </tr>
      <tr>
        <td colspan="2"><table border="1" width="1087">
          <tr style="font-weight: bold;">
            <td width="225" style="background: #e6e6e6">Fecha termina 2da acidificación</td>
            <td width="282" style="background: #e6e6e6">Hora termina </td>
            <td width="208" style="background: #e6e6e6">Horas totales</td>
            <td width="156" style="background: #e6e6e6">Usuario</td>
            <td width="203" rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(19) ?> a <?php echo fnc_hora_a(19) ?> horas)</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin'])?></td>
            <td><?php echo $regProAux['proa_hr_fin']?></td>
            <td><?php echo $regfg['pfg7_hr_totales']?></td>
            <td><?php echo fnc_nom_usu($usu_aux)?></td>
      
          </tr>
          <tr>
            <td colspan="10">En esta parte son 15 minutos de movimiento y 1:45 horas de reposo durante 8 horas</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="65%">&nbsp;</td>
        <td width="35%">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><table width="825" border="1" style="margin-top: 10px">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="14%" style="font-size: small; text-align: center;">Inicia mov.</td>
            <td width="16%" style="font-size: small; text-align: center;">Inicia reposo</td>
            <td width="10%" style="font-size: small; text-align: center;">Ph</td>
            <td width="13%" style="font-size: small; text-align: center;">Temp</td>
            <td width="13%" style="font-size: small; text-align: center;">Normalidad</td>
            <td width="19%" style="font-size: small; text-align: center;">Operador</td>
          </tr>
          <?php
          $sqlfd2 = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d2 as pfd  WHERE pfg7_id = '$regfg[pfg7_id]'") or die(mysql_error()."Error: en consultar procesos_fase_7b_d2");
          $regfd2= mysqli_fetch_assoc($sqlfd2);

          do{


            ?>
          <tr>
            <td><?php echo $regfd2['pfd7_ini_mov']?></td>
            <td><?php echo $regfd2['pfd7_ini_reposo']?></td>
            <td><?php echo $regfd2['pfd7_ph']?></td>
            <td><?php echo $regfd2['pfd7_temp']?></td>
            <td><?php echo $regfd2['pfd7_norm']?></td>
            <td><?php echo fnc_nom_usu($regfd2['usu_id'])?></td>
          </tr>
          <?php } while($regfd2= mysqli_fetch_assoc($sqlfd2));?>
        </table></td>
        <td rowspan="5" valign="top">
            <table width="249" border="1">
          <tr>
            <td width="44" rowspan="4" style="font-size:50px;">7c</td>
            <td colspan="2" style="background: #e6e6e6;font-weight: bold;">LIBERACION CE A <?php echo fnc_rango_de(19) ?> a <?php echo fnc_rango_a(19) ?> hora) MAX</td>
          </tr>
          <tr>
            <td width="115">Cocido ph (1.7-2.1)</td>
            <td width="75"><?php echo $regProLib['prol_ph'] ?></td>
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
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><table width="820" border="1">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td colspan="5" style="font-size: small; ">Cocido a las 8 a 12 horas de la 2da acidificación</td>
          </tr>
          <tr>
            <td colspan="2">Cocido ph(1.7-2.1) buscar rango inferior</td>
            <td colspan="2">Agua ph (1.3-1.7) Buscar rango inferior</td>
            <td>&nbsp;</td>
          </tr>
          <tr style="font-weight: bold;background: #e6e6e6">
            <td style="font-size: small; text-align: center;">Ph</td>
            <td style="font-size: small; text-align: center;">Ce</td>
            <td style="font-size: small; text-align: center;">Ph</td>
            <td style="font-size: small; text-align: center;">Ce</td>
            <td style="font-size: small; text-align: center;">Hora inicio cocido</td>
          </tr>
          <tr>
            <td width="134"><?php echo fnc_formato_val($regfg['pfg7_cocido_ph']) ?></td>
            <td width="134"><?php echo fnc_formato_val($regfg['pfg7_cocido_ce']) ?></td>
            <td width="141"><?php echo fnc_formato_val($regfg['pfg7_agua_ph']) ?></td>
            <td width="122"><?php echo fnc_formato_val($regfg['pfg7_agua_ce']) ?></td>
            <td width="113"><?php echo fnc_formato_val($regfg['pfg7_hr_ini_co']) ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><table border="1" width="824">
          <tr style="font-weight: bold;">
            <td width="232" style="background: #e6e6e6">Fecha termina mov. y reposo</td>
            <td width="153" style="background: #e6e6e6">Hora termina </td>
            <td width="158" style="background: #e6e6e6">Agua</td>
            <td width="158" style="background: #e6e6e6">Usuario</td>
            <td width="159" rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(19) ?> a <?php echo fnc_hora_a(19) ?> horas)</td>
         
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin'])?></td>
            <td><?php echo $regProAux['proa_hr_fin']?></td>
            <td><?php echo $regtaa['taa_descripcion']?></td>
            <td><?php echo fnc_nom_usu($usu_aux)?></td>
            <tr>
              <td colspan="9" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
            </tr>
            <tr>
              <td colspan="11"><?php echo $regProAux['proa_observaciones']?></td>
            </tr>
            <tr>
              <td colspan="11">Para pasar de lavados de 1er ácido a 2do ácido el tiempo debe ser de (3 a 5 horas). El agua de este procesos se manda a depositos de agua acida</td>
            </tr>
        </table></td>
    </tr>
    </table>
</div>
