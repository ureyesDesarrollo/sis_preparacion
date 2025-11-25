<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST);
$sqlf2bg = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_g as pf2bg INNER JOIN procesos as p  on(pf2bg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'  AND pe_id = '3'") or die(mysqli_error($cnx) . "Error: en consultar procesos_fase_2b_g ");
$regf2bg = mysqli_fetch_assoc($sqlf2bg);

$sqlProAux2b = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regf2bg[pro_id]'  AND pe_id = '3'") or die(mysqli_error($cnx) . "Error: en consultar procesos auxiliar");
$regProAux2b = mysqli_fetch_assoc($sqlProAux2b);


$sqlProLib2b = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regf2bg[pro_id]'  AND pe_id = '3'") or die(mysqli_error($cnx) . "Error: en consultar procesos de liberacion");
$regProLib2b = mysqli_fetch_assoc($sqlProLib2b);

?>


<!--<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">-->
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
            <td height="45" colspan="10">ENZIMA. Las primeras 6 horas en movimiento continuo y según como se vea el material se le dan reposos</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table width="100%" border="1">
          <tr>
            <td width="17%" style="font-weight: bold;background: #e6e6e6">Enzima</td>
            <td width="12%" style="font-weight: bold;background: #e6e6e6">PH</td>
            <td width="12%" style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
            <td width="9%" style="font-weight: bold;background: #e6e6e6"><span>Fecha de inicio</span></td>
            <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
            <td width="14%" style="font-weight: bold;background: #e6e6e6">Capturo</td>
          </tr>
          <tr>
            <td><?php echo $regf2bg['pfg2_enzima'] ?></td>
            <td><?php echo $regf2bg['pfg2_ph1'] ?></td>
            <td><?php echo $regf2bg['pfg2_temp_ag'] ?></td>
            <td><?php echo fnc_formato_fecha($regProAux2b['proa_fe_ini']) ?></td>
            <td><?php echo $regProAux2b['proa_hr_ini'] ?></td>

            <td><?php echo fnc_nom_usu($regf2bg['usu_id']) ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="63%" valign="top">
        <table width="96%" border="1" style="margin-top: 10px">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="8%" style="font-size: small; text-align: center;">No.</td>
            <td width="30%" style="font-size: small; text-align: center;">Ph</td>
            <td width="27%" style="font-size: small; text-align: center;">Sosa</td>
            <td width="27%" style="font-size: small; text-align: center;">Temp</td>
            <td width="35%" style="font-size: small; text-align: center;">Capturo</td>
          </tr>
          <?php
          $sqlf2d = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_d as pf2bd WHERE pf2bd.pfg2_id = '$regf2bg[pfg2_id]'") or die(mysqli_error($cnx) . "Error: en consultar el tipo de material");
          $regf2d = mysqli_fetch_assoc($sqlf2d);
          do {


          ?>
            <tr>
              <td><?php echo $regf2d['pfd2_ren'] ?></td>
              <td><?php echo $regf2d['pfd2_ph'] ?></td>
              <td><?php echo fnc_formato_vacio($regf2d['pfd2_sosa']) ?></td>
              <td><?php echo fnc_formato_vacio($regf2d['pfd2_temp']) ?></td>
              <td><?php echo fnc_nom_usu($regf2d['usu_id']) ?></td>
            </tr>
          <?php } while ($regf2d = mysqli_fetch_assoc($sqlf2d)); ?>
        </table>
      </td>
      <td width="37%" valign="center">
        <table>
          <tr>
            <td>
              <table border="1" style="width: 400px;margin-top:1rem">
                <tr>
                  <td rowspan="4" style="font-size:50px;border-bottom:1px solid#000"> 2b</td>
                  <td colspan="2" style="background: #e6e6e6;font-weight: bold;">Liberación de extractibilidad entre 90-95%</td>
                </tr>
                <tr>
                  <td>Extractibilidad</td>
                  <td><?php echo $regProLib2b['extractibilidad'] ?></td>
                </tr>
                <tr>
                  <td>Horas</td>
                  <td><?php echo $regProLib2b['prol_hr_totales'] ?></td>
                </tr>
                <tr>
                  <td>Nombre LCP</td>
                  <td>
                    <?php $usu_proLib = $regProLib2b['usu_id'];
                    echo fnc_nom_usu($usu_proLib) ?>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>

        <table style="margin-top: 1rem;">
          <tr>
            <td>
              <table border="1" style="width: 400px;">
                <?php
                //$usu_aux = $regProAux2b['usu_sup'];

                ?>
                <tr>
                  <td colspan="2" style="font-weight: bold;background: #e6e6e6;text-align:center">(<?php echo fnc_hora_de(3) ?> a <?php echo fnc_hora_a(3) ?> Horas)</td>
                </tr>
                <tr>
                  <td width="266" style="background: #e6e6e6;font-weight:bold">Fecha termina enzima</td>
                  <td><?php echo fnc_formato_fecha($regProAux2b['proa_fe_fin']) ?></td>
                </tr>
                <tr>
                  <td width="168" style="background: #e6e6e6;font-weight:bold">Hora termina enzima</td>
                  <td><?php echo $regProAux2b['proa_hr_fin'] ?></td>
                </tr>
                <tr>
                  <td style="background: #e6e6e6;font-weight:bold">Horas totales de proceso</td>
                  <td><?php echo $regf2bg['pfg2_hr_totales'] ?></td>
                </tr>
                <tr>
                  <td style="background: #e6e6e6;font-weight:bold">Observaciones</td>
                  <td><?php echo $regProAux2b['proa_observaciones'] ?></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
  </table>
  </tr>
  </table>
</div>