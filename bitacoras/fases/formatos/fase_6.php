<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST);

$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_6_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysqli_error($cnx) . "Error: en consultar procesos_fase_6_g ");
$regfg = mysqli_fetch_assoc($sqlfg);


$sqltaa = mysqli_query($cnx, "SELECT taa_descripcion FROM tipos_agua_a  WHERE taa_id = '$regfg[taa_id]'") or die(mysqli_error($cnx) . "Error: en consultar tipos_agua_a ");
$regtaa = mysqli_fetch_assoc($sqltaa);

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '14'") or die(mysqli_error($cnx) . "Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '14'") or die(mysqli_error($cnx) . "Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

?>

<style>
  /*td{
  padding-left: 5px;padding-right: 5px
}*/
  @media print {
    .liberacion {
      width: 100px;
    }

    .general {
      width: 1019px;
    }

  }
</style>


<div class="divProcesos">
  <table width="100%" style="margin:1rem">
    <tr>
      <td colspan="3">
        <table width="98%" border="1" style="background: #FCEFF2;font-size: 12px;">
          <tr>
            <td height="45" colspan="10">SEGUNDO ÁCIDO</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table width="98%" border="1">
          <tr>
            <td width="20%" style="font-weight: bold;background: #e6e6e6"><span>Fecha inicio</span></td>
            <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Ácido diluido</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_ini']) ?></td>
            <td><?php echo $regProAux['proa_hr_ini'] ?></td>
            <td><?php echo $regfg['pfg6_temp_ag'] ?></td>
            <td><?php echo $regfg['pfg6_acido_diluido'] ?></td>
            <td><?php echo fnc_nom_usu($regfg['usu_id']) ?></td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6"><span>Temp</span></td>
            <td style="font-weight: bold;background: #e6e6e6">Ácido</td>
            <td style="font-weight: bold;background: #e6e6e6">Ph</td>
            <td style="font-weight: bold;background: #e6e6e6">Temp</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Normalidad</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_val($regfg['pfg6_temp']) ?></td>
            <td><?php echo $regfg['pfg6_acido'] ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg6_ph']) ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg6_temp2']) ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg6_norm']) ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table width="98%" border="1" style="margin-top: 10px">
          <tr style="font-weight: bold;border-top:2px solid#fff;border-left:2px solid#fff;border-right:2px solid#fff;">
            <td style="font-size: small; text-align: center; border-right:1px solid#FFF">&nbsp;</td>
            <td style="font-size: small; text-align: center;border-right:1px solid#FFF">&nbsp;</td>
            <td style="font-size: small; text-align: center;border-right:1px solid#FFF">&nbsp;</td>
            <td style="font-size: small; text-align: center;border-right:1px solid#FFF">PPRO</td>
            <td style="font-size: small; text-align: center;border-right:1px solid#FFF">&nbsp;</td>
            <td style="font-size: small; text-align: center;border-right:1px solid#FFF">&nbsp;</td>
            <td style="font-size: small; text-align: center;border-right:1px solid#FFF">&nbsp;</td>
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
          $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_6_d WHERE pfg6_id = '$regfg[pfg6_id]'") or die(mysqli_error($cnx) . "Error: en consultar procesos_fase_6_d");
          $regfd = mysqli_fetch_assoc($sqlfd);

          do {


          ?>
            <tr>
              <td><?php echo $regfd['pfd6_ren'] ?></td>
              <td><?php echo $regfd['pfd6_acido'] ?></td>
              <td><?php echo fnc_formato_val($regfd['pfd6_temp']) ?></td>
              <td style="background:#FF0000;color: #fff"><?php echo fnc_formato_val($regfd['pfd6_ph']) ?></td>
              <td><?php echo fnc_formato_val($regfd['pfd6_ce']) ?></td>
              <td><?php echo fnc_formato_val($regfd['pfd6_norm']) ?></td>
              <td><?php echo fnc_nom_usu($regfd['usu_id']) ?></td>
            </tr>
          <?php } while ($regfd = mysqli_fetch_assoc($sqlfd)); ?>
        </table>
        <p>
          <?php
          $usu_aux = $regProAux['usu_sup'];
          $usu_proLib = $regProLib['usu_id'];
          ?>
        </p>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table border="1" width="98%">
          <tr style="font-weight: bold;">
            <td width="210" style="background: #e6e6e6">Fecha termina 2da acidificación</td>
            <td width="105" style="background: #e6e6e6">Hora termina </td>
            <td width="129" style="background: #e6e6e6">Horas totales</td>
            <td width="96" style="background: #e6e6e6">Usuario</td>
            <td width="124" rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(14) ?> a <?php echo fnc_hora_a(14) ?> horas)</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin']) ?></td>
            <td><?php echo $regProAux['proa_hr_fin'] ?></td>
            <td><?php echo $regfg['pfg6_hr_totales'] ?></td>
            <td><?php echo fnc_nom_usu($usu_aux) ?></td>

          </tr>
          <tr>
            <td colspan="10">En esta parte son 15 minutos de movimiento y 1:45 horas de reposo durante 18 horas - 22</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td width="32%" valign="top">
        <table border="1" style="margin-top: 10px;width:100%">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="14%" style="font-size: small; text-align: center;">Inicia mov.</td>
            <td width="16%" style="font-size: small; text-align: center;">Inicia reposo</td>
            <td width="10%" style="font-size: small; text-align: center;">Ph</td>
            <td width="13%" style="font-size: small; text-align: center;">Ce</td>
            <td width="13%" style="font-size: small; text-align: center;">Normalidad</td>
            <td width="15%" style="font-size: small; text-align: center;">Temp</td>
            <td width="19%" style="font-size: small; text-align: center;">Operador</td>
          </tr>
          <?php
          $sqlfd2 = mysqli_query($cnx, "SELECT * FROM procesos_fase_6_d2 as pfd  WHERE pfg6_id = '$regfg[pfg6_id]'") or die(mysqli_error($cnx) . "Error: en consultar procesos_fase_6_d2");
          $regfd2 = mysqli_fetch_assoc($sqlfd2);

          do {


          ?>
            <tr>
              <td><?php echo $regfd2['pfd6_ini_mov'] ?></td>
              <td><?php echo $regfd2['pfd6_ini_reposo'] ?></td>
              <td><?php echo $regfd2['pfd6_ph'] ?></td>
              <td><?php echo $regfd2['pfd6_ce'] ?></td>
              <td><?php echo $regfd2['pfd6_norm'] ?></td>
              <td><?php echo $regfd2['pfd6_temp'] ?></td>
              <td><?php echo fnc_nom_usu($regfd2['usu_id']) ?></td>
            </tr>
          <?php } while ($regfd2 = mysqli_fetch_assoc($sqlfd2)); ?>
        </table>
      </td>
      <td width="16%" rowspan="5" valign="top">
        <table class="liberacion" border="1" style="margin-left: 1rem;margin-top:1rem">
          <tr>
            <td width="53" rowspan="5" style="font-size:50px">6</td>
            <!--  <td colspan="2" style="background: #e6e6e6;font-weight: bold;">LIBERACION CE A <?php echo fnc_rango_de(14) ?> a <?php echo fnc_rango_a(14) ?> hora) MAX</td> -->
          </tr>
          <tr>
            <td width="171">Cocido ph (1.7-2.1)</td>
            <td width="120"><?php echo $regProLib['prol_ph'] ?></td>
          </tr>
          <tr>
          <tr>
            <td>Extractibilidad 2do Acido</td>
            <td><?php echo $regProLib['prol_extrac2doacido'] ?></td>
          </tr>
            <td>Horas totales</td>
            <td><?php echo $regProLib['prol_hr_totales'] ?></td>
          </tr>
          <tr>
            <td>Nombre LCP</td>
            <td><?php echo fnc_nom_usu($usu_proLib) ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td  width="32%" valign="top">
        <table border="1">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td style="font-size: small; text-align: center;">Ph</td>
            <td style="font-size: small; text-align: center;">Ce</td>
            <td style="font-size: small; text-align: center;">Ph</td>
            <td style="font-size: small; text-align: center;">Ce</td>
          </tr>
          <tr>
            <td width="215"><?php echo fnc_formato_val($regfg['pfg6_ph2']) ?></td>
            <td width="200"><?php echo fnc_formato_val($regfg['pfg6_ce2']) ?></td>
            <td width="224"><?php echo fnc_formato_val($regfg['pfg6_ph3']) ?></td>
            <td width="160"><?php echo fnc_formato_val($regfg['pfg6_ce3']) ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">
        <table  width="100%" border="1">
          <tr style="font-weight: bold;">
            <td width="232" style="background: #e6e6e6">Fecha termina mov. y reposo</td>
            <td width="153" style="background: #e6e6e6">Hora termina </td>
            <td width="158" style="background: #e6e6e6">Supervisor</td>
            <td width="159" rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(14) ?> a <?php echo fnc_hora_a(14) ?> horas)</td>

          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin']) ?></td>
            <td><?php echo $regProAux['proa_hr_fin'] ?></td>
            <td><?php echo fnc_nom_usu($usu_aux) ?></td>
          <tr>
            <td colspan="8" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          </tr>
          <tr>
            <td colspan="10"><?php echo $regProAux['proa_observaciones'] ?></td>
          </tr>
          <!--  <tr>
            <td colspan="10">El agua de este proceso se manda a depositos de agua acida</td>
          </tr> -->
        </table>
      </td>
    </tr>
  </table>
</div>