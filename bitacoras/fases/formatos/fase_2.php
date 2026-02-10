<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST);

$sqlf2g = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_g as pf2g INNER JOIN procesos as p  on(pf2g.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro' AND pe_id = '2'") or die(mysqli_error($cnx) . "Error: en consultar procesos_fase_2_g ");
$regf2g = mysqli_fetch_assoc($sqlf2g);

$sqlProAux2 = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regf2g[pro_id]' AND pe_id = '2'") or die(mysqli_error($cnx) . "Error: en consultar procesos auxiliar");
$regProAux2 = mysqli_fetch_assoc($sqlProAux2);


$sqlProLib2 = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regf2g[pro_id]' AND pe_id = '2'") or die(mysqli_error($cnx) . "Error: en consultar procesos de liberacion");
$regProLib2 = mysqli_fetch_assoc($sqlProLib2);

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
      <td>
        <table border="1" style="background: #FCEFF2;font-size: 12px;width: 100%">
          <tr>
            <td height="45" colspan="10">BLANQUEO</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="1">
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Fecha inicia lavados</td>
            <td><?php echo fnc_formato_fecha($regProAux2['proa_fe_ini']) ?></td>
            <td style="font-weight: bold;background: #e6e6e6">Hora inicio</td>
            <td><?php echo $regProAux2['proa_hr_ini'] ?></td>

            <td style="font-weight: bold;background: #e6e6e6"><span>Ph antes de ajuste</span></td>
            <td><?php echo $regf2g['pfg2_ph_ant'] ?></td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Ce</td>
            <td><?php echo $regf2g['pfg2_ce'] ?></td>
            <td style="font-weight: bold;background: #e6e6e6">Ajuste con sosa</td>
            <td><?php echo $regf2g['pfg2_sosa'] ?></td>
            <td style="font-weight: bold;background: #e6e6e6">Ph ajustado</td>
            <td><?php echo $regf2g['pfg2_ph_aju'] ?></td>
            <td style="font-weight: bold;background: #e6e6e6">Peróxido</td>
            <td><?php echo $regf2g['pfg2_peroxido'] ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="1">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="4%" style="font-size: small; text-align: center;">No.</td>
            <td width="18%" style="font-size: small; text-align: center;">Hora</td>
            <td width="16%" style="font-size: small; text-align: center;">Ph</td>
            <td width="13%" style="font-size: small; text-align: center;">Redox</td>
            <td width="5%" style="background-color: #fff;" rowspan="6">MIN 340 PPM</td>
            <td width="20%" style="font-size: small; text-align: center;">Sosa</td>
            <td width="14%" style="font-size: small; text-align: center;">Temp</td>
            <td width="15%" style="font-size: small; text-align: center;">Capturo</td>
          </tr>
          <?php
          $sqlf2d = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_d as pf2d WHERE pf2d.pfg2_id = '$regf2g[pfg2_id]'") or die(mysqli_error($cnx) . "Error: en consultar el tipo de material");
          $regf2d = mysqli_fetch_assoc($sqlf2d);
          do {


          ?>
            <tr>
              <td><?php echo $regf2d['pfd2_ren'] ?></td>
              <td><?php echo $regf2d['pfd2_hr'] ?></td>
              <td><?php echo $regf2d['pfd2_ph'] ?></td>
              <td style="background: #FF6493"><?php echo fnc_formato_val($regf2d['pfd2_redox']) ?></td>
              <td><?php echo fnc_formato_vacio($regf2d['pfd2_sosa']) ?></td>
              <td><?php echo fnc_formato_vacio($regf2d['pfd2_temp']) ?></td>
              <td><?php echo fnc_nom_usu($regf2d['usu_id']); ?></td>
            </tr>
          <?php } while ($regf2d = mysqli_fetch_assoc($sqlf2d)); ?>
        </table>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
        <table border="1" width="100%">
          <?php
          $usu_aux = $regProAux2['usu_sup'];
          $usu_proLib = $regProLib2['usu_id'];
          ?>
          <tr style="font-weight: bold;">
            <td style="background: #e6e6e6">Fecha termina</td>
            <td style="background: #e6e6e6">Hora termina</td>
            <td style="background: #e6e6e6">Usuario</td>
            <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
            <td style="border:1px solid#fff;background: #e6e6e6"><?php echo fnc_hora_de(2) ?> a <?php echo fnc_hora_a(2) ?> horas</td>
            <td width="7%" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
            <td rowspan="6" width="30" style="font-size:50px">2</td>
            <td colspan="3" style="background: #e6e6e6;font-weight: bold;">LIBERACION pH 10.6 - 11</td>
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux2['proa_fe_fin']) ?></td>
            <td><?php echo $regProAux2['proa_hr_fin'] ?></td>
            <td><?php echo fnc_nom_usu($usu_aux) ?></td>
            <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
            <td style="border:1px solid#fff"></td>
            <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
            <td>Color</td>
            <td width="15%" colspan="2"><?php echo $regProLib2['prol_color'] ?></td>
          </tr>
          <tr>
            <td colspan="3"></td>
            <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
            <td style="border:1px solid#fff"></td>
            <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
            <td>Ph de liberación</td>
            <td width="15%" colspan="2"><?php echo $regProLib2['prol_ph'] ?></td>
          </tr>

          <tr>
            <td colspan="3" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
            <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
            <td style="border:1px solid#fff"></td>
            <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
            <td>Horas totales</td>
            <td width="15%" colspan="2"><?php echo $regProLib2['prol_hr_totales'] ?></td>
          </tr>
          <tr>
            <td colspan="3"></td>
            <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
            <td style="border:1px solid#fff"></td>
            <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          </tr>
          <tr>
            <td colspan="3"><?php echo $regProAux2['proa_observaciones'] ?></td>
            <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
            <td style="border:1px solid#fff"></td>
            <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
            <td>Nombre LCP </td>
            <td width="15%" colspan="2"><?php echo fnc_nom_usu($usu_proLib) ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>