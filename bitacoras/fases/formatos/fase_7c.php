<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST);


$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysqli_error($cnx) . "Error: en consultar procesos_fase_7b_g ");
$regfg = mysqli_fetch_assoc($sqlfg);

$sqltaa = mysqli_query($cnx, "SELECT taa_descripcion FROM tipos_agua_a  WHERE taa_id = '$regfg[taa_id]'") or die(mysqli_error($cnx) . "Error: en consultar tipos_agua_a ");
$regtaa = mysqli_fetch_assoc($sqltaa);

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '19'") or die(mysqli_error($cnx) . "Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '19'") or die(mysqli_error($cnx) . "Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);
?>

<style>
  /*td{
  padding-left: 5px;padding-right: 5px
}*/
</style>


<div class="divProcesos">
  <table width="100%" style="margin:1rem">
    <tr>
      <td colspan="2">
        <table border="1" style="background: #FCEFF2;font-size: 12px;width: 98%">
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
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Temp agua inicial</td>
            <td style="font-weight: bold;background: #e6e6e6">Ácido diluido</td>
            <td style="font-weight: bold;background: #e6e6e6"><span>Temp</span></td>
            <td style="font-weight: bold;background: #e6e6e6">Ácido</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Normalidad</td>
            <td style="font-weight: bold;background: #e6e6e6">Ph</td>
            <td style="font-weight: bold;background: #e6e6e6">Ce</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_ini']) ?></td>
            <td><?php echo $regProAux['proa_hr_ini'] ?></td>
            <td><?php echo $regfg['pfg7_temp_ag'] ?></td>
            <td><?php echo $regfg['pfg7_acido_diluido'] ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg7_temp']) ?></td>
            <td><?php echo $regfg['pfg7_acido'] ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg7_norm']) ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg7_ph']) ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg7_ce']) ?></td>
            <td><?php echo fnc_nom_usu($regfg['usu_id']) ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table width="98%" border="1" style="margin-top: 10px">
          <tr style="font-weight: bold;background: #e6e6e6;">
            <td width="5%" style="font-size: small; text-align: center;">Ajust</td>
            <td width="15%" style="font-size: small; text-align: center;">Normalidad</td>
            <td width="20%" style="font-size: small; text-align: center;">Ph</td>
            <td width="15%" style="font-size: small; text-align: center;">Ce</td>
            <td width="18%" style="font-size: small; text-align: center;">Temp</td>
            <td width="18%" style="font-size: small; text-align: center;">Acido</td>
            <td width="20%" style="font-size: small; text-align: center;">Capturo</td>

          </tr>
          <?php
          $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d WHERE pfg7_id = '$regfg[pfg7_id]'") or die(mysqli_error($cnx) . "Error: en consultar procesos_fase_7b_d");
          $regfd = mysqli_fetch_assoc($sqlfd);

          do {


          ?>
            <tr>
              <td>
                <?php

                if ($regfd['pfd7_ren']  == 1) {
                  $val = '0:30';
                }
                if ($regfd['pfd7_ren']  == 2) {
                  $val = '1:00';
                }
                if ($regfd['pfd7_ren']  == 3) {
                  $val = '1:30';
                }
                if ($regfd['pfd7_ren']  == 4) {
                  $val = '2:00';
                }
                if ($regfd['pfd7_ren']  == 5) {
                  $val = '2:30';
                }
                if ($regfd['pfd7_ren']  == 6) {
                  $val = '3:00';
                }
                if ($regfd['pfd7_ren']  == 7) {
                  $val = '3:30';
                }
                if ($regfd['pfd7_ren']  == 8) {
                  $val = '4:00';
                }

                if ($regfd['pfd7_ren'] == 9) {
                  $val = '5:00';
                }
                if ($regfd['pfd7_ren'] == 10) {
                  $val = '6:00';
                }
                if ($regfd['pfd7_ren'] == 11) {
                  $val = '7:00';
                }
                if ($regfd['pfd7_ren'] == 12) {
                  $val = '8:00';
                }
                if ($regfd['pfd7_ren'] == 13) {
                  $val = '9:00';
                }
                if ($regfd['pfd7_ren'] == 14) {
                  $val = '10:00';
                }
                if ($regfd['pfd7_ren'] == 15) {
                  $val = '11:00';
                }
                if ($regfd['pfd7_ren'] == 16) {
                  $val = '12:00';
                }

                echo $val ?>
              </td>
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
        <table width="98%" border="1">
          <tr style="font-weight: bold;">
            <td width="225" style="background: #e6e6e6">Fecha termina 2da acidificación</td>
            <td width="282" style="background: #e6e6e6">Hora termina </td>
            <td width="208" style="background: #e6e6e6">Horas totales</td>
            <td width="156" style="background: #e6e6e6">Usuario</td>
            <td width="203" rowspan="2" style="background: #e6e6e6">(12 horas continuas)</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regfg['pfg7_fe_fin']) ?></td>
            <td><?php echo $regfg['pfg7_hr_fin'] ?></td>
            <td><?php echo $regfg['pfg7_hr_totales'] ?></td>
            <td><?php echo fnc_nom_usu($regfg['usu_id']) ?></td>

          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="65%">&nbsp;</td>
      <td width="35%">&nbsp;</td>
    </tr>

    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="73%" valign="top">
        <table width="95%" border="1">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td style="font-size: small; text-align: center;">Ph</td>
            <td style="font-size: small; text-align: center;">Ce</td>
            <td style="font-size: small; text-align: center;">Temp</td>
            <td rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(19) ?> a <?php echo fnc_hora_a(19) ?> horas)</td>
          </tr>
          <tr>
            <td width="134"><?php echo fnc_formato_val($regfg['pfg7_agua_ph']) ?></td>
            <td width="122"><?php echo fnc_formato_val($regfg['pfg7_agua_ce']) ?></td>
            <td width="113"><?php echo fnc_formato_val($regfg['pfg7_tem_final']) ?></td>
          </tr>
        </table>
      </td>
      <td width="20%" rowspan="5" valign="top">
        <table width="90%" border="1">
          <tr>
            <td width="44" rowspan="4" style="font-size:50px;">7c</td>
            <td colspan="2" style="background: #e6e6e6;font-weight: bold;">.<!-- LIBERACION CE A <?php echo fnc_rango_de(19) ?> a <?php echo fnc_rango_a(19) ?> hora) MAX --></td>
          </tr>
          <!--  <tr>
      <td width="115">Cocido ph (1.7-2.1)</td>
      <td width="75"><?php echo $regProLib['prol_ph'] ?></td>
    </tr> -->
          <tr>
            <td>Horas totales</td>
            <td><?php echo $regProLib['prol_hr_totales'] ?></td>
          </tr>
          <tr>
            <td>Extractibilidad 2do Acido</td>
            <td><?php echo $regProLib['prol_extrac2doacido'] ?></td>
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
      <td valign="top">
        <table border="1">
          <tr>
            <td colspan="9" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          </tr>
          <tr>
            <td colspan="11"><?php echo $regProAux['proa_observaciones'] ?></td>
          </tr>
        </table>
      </td>

    </tr>
    <tr>


    </tr>
  </table>
</div>