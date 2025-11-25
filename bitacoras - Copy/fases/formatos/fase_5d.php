<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST);

/*$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_5b_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro' AND pfg.pe_id = 13") or die(mysql_error()."Error: en consultar procesos_fase_5b_g ");
$regfg= mysqli_fetch_assoc($sqlfg);*/

$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_5b_g  WHERE pro_id  = '$idx_pro' AND pe_id = 13") or die(mysqli_error($cnx) . "Error: en consultar procesos_fase_5b_g ");
$regfg = mysqli_fetch_assoc($sqlfg);

if ($regfg['taa_id'] != '') {
  $sqltaa = mysqli_query($cnx, "SELECT taa_descripcion FROM tipos_agua_a  WHERE taa_id = '$regfg[taa_id]'") or die(mysql_errori($cnx) . "Error: en consultar tipos_agua_a ");
  $regtaa = mysqli_fetch_assoc($sqltaa);
}

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '13'") or die(mysqli_error($cnx) . "Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '13'") or die(mysqli_error($cnx) . "Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

?>

<style>
  /*td{
  padding-left: 5px;padding-right: 5px
}*/

  @media print {
    .liberacion {
      width: 280px;
    }
  }
</style>


<div class="divProcesos">
  <table width="100%" style="margin:20px 0px 20px 0px">
    <tr>
      <td colspan="3">
        <table border="1" style="background: #FCEFF2;font-size: 12px;width: 100%">
          <tr>
            <td height="45" colspan="10">
              <div>PRIMER ÁCIDO. Este proceso se puede hacer con agua de depositos de agua acida (Recuperada de 2do ácido)</div>
              <div></div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">
        <table width="100%" border="1">
          <tr>
            <td width="20%" style="font-weight: bold;background: #e6e6e6"><span>Fecha inicio</span></td>
            <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
            <td width="12%" style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_ini']) ?></td>
            <td><?php echo $regProAux['proa_hr_ini'] ?></td>
            <td><?php echo $regfg['pfg5_temp_ag'] ?></td>
            <td><?php echo fnc_nom_usu($regfg['usu_id']) ?></td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6"><span>Temp</span></td>
            <td style="font-weight: bold;background: #e6e6e6">Ácido</td>
            <td style="font-weight: bold;background: #e6e6e6">Termina</td>
            <td style="font-weight: bold;background: #e6e6e6">Temp</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_val($regfg['pfg5_temp']) ?></td>
            <td><?php echo $regfg['pfg5_acido'] ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg5_termina']) ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg5_temp2']) ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="55%" valign="top">
        <table border="1" style="margin-top: 10px;width: 100%">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="5%" style="font-size: small; text-align: center;">Ajus</td>
            <td width="18%" style="font-size: small; text-align: center;">Ácido</td>
            <td width="20%" style="font-size: small; text-align: center;">Ph lado A</td>
            <td width="15%" style="font-size: small; text-align: center;">Ph lado B</td>
            <td width="15%" style="font-size: small; text-align: center;">Temp</td>
            <td width="20%" style="font-size: small; text-align: center;">Capturo</td>
          </tr>
          <?php
          $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_5b_d  WHERE pfg5_id = '$regfg[pfg5_id]'") or die(mysql_error() . "Error: en consultar procesos_fase_5b_d");
          $regfd = mysqli_fetch_assoc($sqlfd);

          do {


          ?>
            <tr>
              <td><?php echo $regfd['pfd5_ren'] ?></td>
              <td><?php echo $regfd['pfd5_acido'] ?></td>
              <td><?php echo fnc_formato_val($regfd['pfd5_ph']) ?></td>
              <td><?php echo fnc_formato_val($regfd['pfd5_ph_b']) ?></td>
              <td><?php echo fnc_formato_val($regfd['pfd5_temp']) ?></td>
              <td><?php echo fnc_nom_usu($regfd['usu_id']) ?></td>
            </tr>
          <?php } while ($regfd = mysqli_fetch_assoc($sqlfd)); ?>
        </table>
      </td>
      <td width="10%" rowspan="3" valign="center"><label for="inputPassword3">Mantener PH</label>
        <?php echo fnc_rango_de(13) ?> - <?php echo fnc_rango_a(13) ?></td>
      <td width="20%" rowspan="3" valign="top">
        <p>&nbsp;</p>
        <?php
        $usu_aux = $regProAux['usu_sup'];
        $usu_proLib = $regProLib['usu_id'];
        ?>
        <table width="344" border="1" class="liberacion">
          <tr>
            <td width="61" rowspan="6" style="font-size:50px">5d</td>
            <td colspan="2" style="background: #e6e6e6;font-weight: bold;">
              <p><br />
                Nota: La adición de ácido se agrega por los dos lados del lavador y mantener el PH <?php echo fnc_rango_de(13) ?> - <?php echo fnc_rango_a(13) ?> durante todo el proceso de 1er ácido</p>
            </td>
          </tr>
          <tr>
            <td width="161">Adelgazamiento</td>
            <td width="100"><?php echo $regProLib['prol_adelgasamiento'] ?></td>
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
            <td style="background: #e6e6e6">Ph del agua</td>
            <td style="background: #e6e6e6">Ce del agua</td>
            <td style="background: #e6e6e6"><label for="inputPassword2">Cocido del cuero PH(5.0)</label></td>
            <td width="126" style="background: #e6e6e6">Usuario</td>
          </tr>
          <tr>
            <td><?php echo $regfg['pfg5_ph_agua'] ?></td>
            <td><?php echo $regfg['pfg5_ce_agua'] ?></td>
            <td><?php echo fnc_formato_val($regfg['pfg5_cocido_ph']) ?></td>
            <td><?php echo fnc_nom_usu($usu_aux) ?></td>
          </tr>
          <tr style="font-weight: bold;">
            <td width="200" style="background: #e6e6e6">Fecha termina 1er acidificación</td>
            <td width="141" style="background: #e6e6e6">Hora termina </td>
            <td width="209" style="background: #e6e6e6">Agua a</td>
            <td width="126" rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(13) ?> a <?php echo fnc_hora_a(13) ?> horas)</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin']) ?></td>
            <td><?php echo $regProAux['proa_hr_fin'] ?></td>
            <td><?php echo $regtaa['taa_descripcion'] ?></td>
          </tr>
          <tr>
            <td colspan="6" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          </tr>
          <tr>
            <td colspan="8"><?php echo $regProAux['proa_observaciones'] ?></td>
          </tr>
          <tr>
            <td colspan="8"> El agua de este proceso se manda a agua recuperada semilimpia (PILA 2)</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
</div>