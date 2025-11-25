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

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '18'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '18'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

?>

<style>
/*td{
  padding-left: 5px;padding-right: 5px
}*/

@media print {
    
    .segunAc{
        width:89%;
    }
    
    .feIni{
        width:1024px;
    }
    
    .tablaPPRO{
        width:1024px;
    }
    
    
    
}
</style>


<div class="divProcesos">
  <table  width="95%" style="margin:20px 0px 20px 0px">
    <tr>
      <td colspan="2"><table class="segunAc" width="95%" border="1" style="background: #FCEFF2;font-size: 12px">
        <tr>
          <td height="45" colspan="10">SEGUNDO ÁCIDO. Este proceso se utiliza el agua de los lavadores de 1er ácido o solo de ácido fuerte</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><table class="feIni" width="95%" border="1">
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
          <td><?php echo fnc_formato_val($regfg['pfg7_ph']) ?></td>
          <td><?php echo fnc_formato_val($regfg['pfg7_ce']) ?></td>
          <td><?php echo fnc_formato_val($regfg['pfg7_norm']) ?></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top">
        <table class="tablaPPRO" width="1096" border="1" style="margin-top: 10px" >
          <tr style="font-weight: bold;border-top:2px solid#fff;border-left:2px solid#fff;border-right:2px solid#fff;">
            <td style="border-right:1px solid#fff;font-size: small; text-align: center;">&nbsp;</td>
            <td style="border-right:1px solid#fff;font-size: small; text-align: center;">&nbsp;</td>
            <td style="border-right:1px solid#fff;font-size: small; text-align: center;">&nbsp;</td>
            <td style="border-right:1px solid#fff;font-size: small; text-align: center;">PPRO</td>
            <td style="border-right:1px solid#fff;font-size: small; text-align: center;">&nbsp;</td>
            <td style="border-right:1px solid#fff;font-size: small; text-align: center;">&nbsp;</td>
            <td style="border-right:1px solid#fff;font-size: small; text-align: center;">&nbsp;</td>
          </tr>
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="5%" style="font-size: small; text-align: center;">Ajust</td>
            <td width="18%" style="font-size: small; text-align: center;">Ac</td>
            <td width="18%" style="font-size: small; text-align: center;">Temp</td>
            <td width="20%" style="font-size: small; text-align: center;">Ph</td>
            <td width="15%" style="font-size: small; text-align: center;">Ce</td>
            <td width="15%" style="font-size: small; text-align: center;">Normalidad</td>
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
              <td><?php echo fnc_formato_val($regfd['pfd7_temp'])?></td>
              <td style="background:#FF0000;color: #fff"><?php echo fnc_formato_val($regfd['pfd7_ph'])?></td>
              <td><?php echo fnc_formato_val($regfd['pfd7_ce'])?></td>
              <td><?php echo fnc_formato_val($regfd['pfd7_norm'])?></td>
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
        <td colspan="2" valign="top"><table border="1" width="1017">
          <tr style="font-weight: bold;">
            <td width="249" style="background: #e6e6e6">Fecha termina 2da acidificación</td>
            <td width="181" style="background: #e6e6e6">Hora termina </td>
            <td width="171" style="background: #e6e6e6">Horas totales</td>
            <td width="142" style="background: #e6e6e6">Usuario</td>
            <td width="246" rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(18) ?> a <?php echo fnc_hora_a(18) ?> horas)</td>
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
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="54%" valign="top"><table width="677" border="1">
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
            <td><?php echo fnc_formato_val($regfd2['pfd7_ph'])?></td>
            <td><?php echo fnc_formato_val($regfd2['pfd7_temp'])?></td>
            <td><?php echo fnc_formato_val($regfd2['pfd7_norm'])?></td>
            <td><?php echo fnc_nom_usu($regfd2['usu_id'])?></td>
          </tr>
          <?php } while($regfd2= mysqli_fetch_assoc($sqlfd2));?>
        </table></td>
        <td width="46%" rowspan="5" valign="top"><table width="327" border="1">
          <tr>
            <td width="44" rowspan="4" style="font-size:50px">7b</td>
            <td colspan="2" style="background: #e6e6e6;font-weight: bold;">LIBERACION CE A <?php echo fnc_rango_de(18) ?> a <?php echo fnc_rango_a(18) ?> hora) MAX</td>
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
        </table></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><table width="678" border="1">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td colspan="3" style="font-size: small; ">Cocido a las 8 a 12 horas de la 2da acidificación</td>
          </tr>
          <tr>
            <td>Agua ph (1.3-1.7) Buscar rango inferior</td>
            <td>Cocido ph(1.7-2.1) buscar rango inferior</td>
            <td>&nbsp;</td>
          </tr>
          <tr style="font-weight: bold;background: #e6e6e6">
            <td style="font-size: small; text-align: center;">Ph</td>
            <td style="font-size: small; text-align: center;">Ph</td>
            <td style="font-size: small; text-align: center;">Hora inicio cocido</td>
          </tr>
          <tr>
            <td width="270"><?php echo fnc_formato_val($regfg['pfg7_agua_ph'])?></td>
            <td width="253"><?php echo fnc_formato_val($regfg['pfg7_cocido_ph']) ?></td>
            <td width="133"><?php echo $regfg['pfg7_hr_ini_co'] ?></td>
          </tr>
        </table></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><table border="1" width="676">
          <tr style="font-weight: bold;">
            <td width="220" style="background: #e6e6e6">Fecha termina mov. y reposo</td>
            <td width="124" style="background: #e6e6e6">Hora termina </td>
            <td width="88" style="background: #e6e6e6">Agua</td>
            <td width="124" style="background: #e6e6e6">Usuario</td>
            <td width="86" rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(18) ?> a <?php echo fnc_hora_a(18) ?> horas)</td>
         
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
              <td colspan="11">El agua de este proceso se manda a depositos de agua acida</td>
            </tr>
        </table></td>
    </tr>
  </table>
</div>
