<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
$cnx =  Conectarse();

extract($_POST);


$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysqli_error($cnx) . "Error: en consultar procesos_fase_7b_g ");
$regfg = mysqli_fetch_assoc($sqlfg);

$sqltaa = mysqli_query($cnx, "SELECT taa_descripcion FROM tipos_agua_a  WHERE taa_id = '$regfg[taa_id]'") or die(mysqli_error($cnx) . "Error: en consultar tipos_agua_a ");
$regtaa = mysqli_fetch_assoc($sqltaa);

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '29'") or die(mysqli_error($cnx) . "Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '29'") or die(mysqli_error($cnx) . "Error: en consultar procesos de liberacion");
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
      <td colspan="2">
        <table width="100%" border="1" style="background: #FCEFF2;font-size: 12px;width: 100%">
          <tr>
            <td height="45" colspan="10">SEGUNDO ÁCIDO</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table width="100%" border="1">
          <tr>
            <td width="20%" style="font-weight: bold;background: #e6e6e6"><span>Fecha inicio</span></td>
            <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
            <td style="font-weight: bold;background: #e6e6e6"><span>Temp</span></td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_ini']) ?></td>
            <td><?php echo $regProAux['proa_hr_ini'] ?></td>
            <td><?php echo $regfg['pfg7_temp_ag'] ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg7_temp']) ?></td>
            <td><?php echo fnc_nom_usu($regfg['usu_id']) ?></td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Ácido</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Normalidad</td>
            <td style="font-weight: bold;background: #e6e6e6">Ph</td>
            <td style="font-weight: bold;background: #e6e6e6">Ce</td>
            <td rowspan="2" style="font-weight: bold;background: #e6e6e6;text-align:center">4 horas continuas</td>
          </tr>
          <tr>
            <td><?php echo $regfg['pfg7_acido'] ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg7_norm']) ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg7_ph']) ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg7_ce']) ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table width="100%" border="1" style="margin-top: 10px">
          <tr style="font-weight: bold;background: #e6e6e6;">
            <td width="5%" style="font-size:small; text-align:center;border:1px solid#fff">Ajust</td>
            <td width="15%" style="font-size:small; text-align:center;border:1px solid#fff">Normalidad</td>
            <td width="20%" style="font-size:small; text-align:center;border:1px solid#fff">Ph</td>
            <td width="15%" style="font-size:small; text-align:center;border:1px solid#fff">Ce</td>
            <td width="18%" style="font-size:small; text-align:center;border:1px solid#fff">Temp</td>
            <td width="18%" style="font-size:small; text-align:center;border:1px solid#fff">Acido</td>



            <td width="20%" style="font-size:small; text-align: center;">Capturo</td>

          </tr>
          <?php
          $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d WHERE pfg7_id = '$regfg[pfg7_id]'") or die(mysqli_error($cnx) . "Error: en consultar procesos_fase_7b_d");
          $regfd = mysqli_fetch_assoc($sqlfd);

          do {


          ?>
            <tr>
              <td><?php echo $regfd['pfd7_ren'] ?></td>
              <td><?php echo $regfd['pfd7_norm'] ?></td>
              <td style="background:#FF0000;color: #fff"><?php echo $regfd['pfd7_ph'] ?></td>
              <td><?php echo $regfd['pfd7_ce'] ?></td>
              <td><?php echo fnc_formato_vacio($regfd['pfd7_temp']) ?></td>
              <td><?php echo fnc_formato_vacio($regfd['pfd7_acido']) ?></td>
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
        <table border="1" width="100%">
          <tr style="font-weight: bold;">
            <td width="225" style="background: #e6e6e6">Fecha termina 2da acidificación</td>
            <td width="282" style="background: #e6e6e6">Hora termina </td>
            <td width="208" style="background: #e6e6e6">Horas totales</td>
            <td width="156" style="background: #e6e6e6">Usuario</td>
            <td width="203" rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(29) ?> a <?php echo fnc_hora_a(29) ?> horas)</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin']) ?></td>
            <td><?php echo $regProAux['proa_hr_fin'] ?></td>
            <td><?php echo $regfg['pfg7_hr_totales'] ?></td>
            <td><?php echo fnc_nom_usu($usu_aux) ?></td>

          </tr>
          <tr>
            <td colspan="10">En esta parte son 15 minutos de movimiento y 1:45 horas de reposo durante 8 horas</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="70%">&nbsp;</td>
      <td width="35%">&nbsp;</td>
    </tr>
    <tr>
      <td width="65%" valign="top">
        <table width="100%" border="1" style="margin-top: 10px">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="14%" style="font-size: small; text-align: center;">Inicia mov.</td>
            <td width="16%" style="font-size: small; text-align: center;">Inicia reposo</td>
            <td width="10%" style="font-size: small; text-align: center;">Ph</td>
            <td width="13%" style="font-size: small; text-align: center;">Temp</td>
            <td width="13%" style="font-size: small; text-align: center;">Normalidad</td>
            <td width="29%" style="font-size: small; text-align: center;">Operador</td>
          </tr>
          <?php
          $sqlfd2 = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d2 as pfd  WHERE pfg7_id = '$regfg[pfg7_id]'") or die(mysqli_error($cnx) . "Error: en consultar procesos_fase_7b_d2");
          $regfd2 = mysqli_fetch_assoc($sqlfd2);

          do {


          ?>
            <tr>
              <td><?php echo $regfd2['pfd7_ini_mov'] ?></td>
              <td><?php echo $regfd2['pfd7_ini_reposo'] ?></td>
              <td><?php echo $regfd2['pfd7_ph'] ?></td>
              <td><?php echo $regfd2['pfd7_temp'] ?></td>
              <td><?php echo $regfd2['pfd7_norm'] ?></td>
              <td><?php echo fnc_nom_usu($regfd2['usu_id']) ?></td>
            </tr>
          <?php } while ($regfd2 = mysqli_fetch_assoc($sqlfd2)); ?>
        </table>
      </td>
      <td width="20%" rowspan="5" valign="top">
        <table width="95%" border="1" style="margin-top: 1rem;margin-left:1rem">
          <tr>
            <td width="44" rowspan="4" style="font-size:50px;">7d</td>
            <td colspan="2" style="background: #e6e6e6;font-weight: bold;">LIBERACION CE A <?php echo fnc_rango_de(29) ?> a <?php echo fnc_rango_a(29) ?> hora) MAX</td>
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
            <td><?php echo fnc_nom_usu($usu_proLib) ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="70%" valign="top">
        <table width="100%" border="1">
          <tr style="font-weight: bold;">
            <td style="background: #e6e6e6">Fecha termina lavados</td>
            <td style="background: #e6e6e6">Hora termina </td>
            <td style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
            <td style="background: #e6e6e6">Usuario</td>
            <td rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(29) ?> a <?php echo fnc_hora_a(29) ?> horas)</td>

          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin']) ?></td>
            <td><?php echo $regProAux['proa_hr_fin'] ?></td>
            <td><?php echo $regProAux['proa_observaciones'] ?></td>
            <td><?php echo fnc_nom_usu($usu_aux) ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>