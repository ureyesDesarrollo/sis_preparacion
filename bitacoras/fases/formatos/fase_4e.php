<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST);

$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_4b_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro' AND pfg.pe_id = 27") or die(mysql_error() . "Error: en consultar procesos_fase_4b_g ");
$regfg = mysqli_fetch_assoc($sqlfg);


$sqltaa = mysqli_query($cnx, "SELECT taa_descripcion FROM tipos_agua_a  WHERE taa_id = '$regfg[taa_id]'") or die(mysql_error() . "Error: en consultar tipos_agua_a ");
$regtaa = mysqli_fetch_assoc($sqltaa);


$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '27'") or die(mysql_error() . "Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '27'") or die(mysql_error() . "Error: en consultar procesos de liberacion");
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
        <table border="1" style="background: #FCEFF2;font-size: 12px;width: 100%">
          <tr>
            <td height="45" colspan="10">LAVADOS DE BLANQUEO. Este proceso se puede hacer con aguar recuperada limpia (pila 1). Lavados finales, 1er ácido, paleto a paleto</td>.
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table width="100%" border="1">
          <tr>
            <td width="20%" style="font-weight: bold;background: #e6e6e6"><span>Fecha de inicia lavados</span></td>
            <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
            <td width="12%" style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
            <td width="14%" rowspan="2" style="font-weight: bold;background: #e6e6e6">BAJAR CE A 3.0 MAXIMO</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_ini']) ?></td>
            <td><?php echo $regProAux['proa_hr_ini'] ?></td>
            <td><?php echo $regfg['pfg4_temp_ag'] ?></td>
            <td><?php echo fnc_nom_usu($regfg['usu_id']) ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="61%" valign="top">
        <table border="1" style="margin-top: 10px;width:98%">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="5%" style="font-size: small; text-align: center;">Lav</td>
            <td width="20%" style="font-size: small; text-align: center;">Tipo de agua</td>
            <td width="5%" style="font-size: small; text-align: center;">Temp</td>
            <td width="10%" style="font-size: small; text-align: center;">Hora ini llenado</td>
            <td width="10%" style="font-size: small; text-align: center;">Hora term llenado</td>
            <td width="10%" style="font-size: small; text-align: center;">Hora ini mov</td>
            <td width="10%" style="font-size: small; text-align: center;">Hora ter mov</td>
            <td width="5%" style="font-size: small; text-align: center;">Ce</td>
            <td width="5%" style="font-size: small; text-align: center;">Ph</td>
            <td width="5%" style="font-size: small; text-align: center;">PPM</td>
            <td width="8%" style="font-size: small; text-align: center;">Agua a</td>
            <td width="5%" style="font-size: small; text-align: center;">Observaciones</td>
            <td width="20%" style="font-size: small; text-align: center;">Capturo</td>

          </tr>
          <?php
          $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_4b_d as pfd inner join tipos_agua as ta on(pfd.tpa_id= ta.tpa_id) WHERE pfd.pfg4_id = '$regfg[pfg4_id]'") or die(mysql_error() . "Error: en consultar procesos_fase_4_d");
          $regfd = mysqli_fetch_assoc($sqlfd);

          do {

            $agua_a = mysqli_query($cnx, "SELECT * FROM tipos_agua_a WHERE taa_id = '$regfd[taa_id]'") or die(mysql_error() . "Error: en consultar el tipo de agua a");
            $reg_aa = mysqli_fetch_assoc($agua_a);

          ?>
            <tr>
              <td><?php echo $regfd['pfd4_ren'] ?></td>
              <td><?php echo $regfd['tpa_descripcion'] ?></td>
              <td><?php echo $regfd['pfd4_temp'] ?></td>
              <td><?php echo $regfd['pfd4_hr_ini'] ?></td>
              <td><?php echo $regfd['pfd4_hr_fin'] ?></td>
              <td><?php echo $regfd['pfd4_hr_ini_mov'] ?></td>
              <td><?php echo $regfd['pfd4_hr_fin_mov'] ?></td>
              <td><?php echo $regfd['pfd4_ce'] ?></td>
              <td><?php echo $regfd['pfd4_ph'] ?></td>
              <td><?php echo $regfd['pfd4_ppm'] ?></td>
              <td><?php echo $reg_aa['taa_descripcion'] ?></td>
              <td><?php echo $regfd['pfd4_observaciones'] ?></td>
              <td><?php echo fnc_nom_usu($regfd['usu_id']) ?></td>
            </tr>
          <?php } while ($regfd = mysqli_fetch_assoc($sqlfd)); ?>
        </table>

      </td>
      <td width="20%" rowspan="3" valign="top">
        <p>
          <?php
          $usu_aux = $regProAux['usu_sup'];
          $usu_proLib = $regProLib['usu_id'];
          ?>
        </p>
        <table border="1">
          <tr>
            <td width="75" rowspan="5" style="font-size:50px">4e</td>
            <td colspan="2" style="background: #e6e6e6;font-weight: bold;">LIBERACION CE A 3 MAX</td>
          </tr>
          <tr>
            <td width="169">Ce liberación</td>
            <td width="170"><?php echo $regProLib['prol_ce'] ?></td>
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
        <p>&nbsp;</p>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">
        <table border="1">
          <tr style="font-weight: bold;">
            <td width="170" style="background: #e6e6e6">Fecha termina lavados</td>
            <td width="100" style="background: #e6e6e6">Hora termina </td>
            <!--<td width="150" style="background: #e6e6e6">Agua a </td>-->
            <td width="20" colspan="2" style="background: #e6e6e6">Usuario</td>
            <td width="30" colspan="2" style="background: #e6e6e6" rowspan="2">(<?php echo fnc_hora_de(27) ?> a <?php echo fnc_hora_a(27) ?> horas)</td>
            <td colspan="2" width="30" style="background: #e6e6e6">Horas reales</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin']) ?></td>
            <td><?php echo $regProAux['proa_hr_fin'] ?></td>
            <!--<td><?php echo $regtaa['taa_descripcion'] ?></td>-->
            <td colspan="2"><?php echo fnc_nom_usu($usu_aux) ?></td>
            <td><?php echo $regfg['pfg4_horas_reales'] ?></td>

          <tr>
            <td colspan="8" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          </tr>
          <tr>
            <td colspan="8"><?php echo $regProAux['proa_observaciones'] ?></td>
          </tr>
          <tr>
            <td colspan="8">
              Para pasar de lavados de 1er ácido a 2do ácido el tiempo debe ser de (1 a 3 Horas). El agua de este proceso se manda a agua recuperada similimpia(pila 2)
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>