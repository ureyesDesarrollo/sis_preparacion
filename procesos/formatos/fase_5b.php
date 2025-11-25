<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/ 
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 

$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_5_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro' AND pfg.pe_id = 11") or die(mysql_error()."Error: en consultar procesos_fase_5_g ");
$regfg= mysqli_fetch_assoc($sqlfg);

if ($regfg['tpa_id'] != '') {
$sqlTagua = mysqli_query($cnx, "SELECT * FROM tipos_agua  WHERE tpa_id= '$regfg[tpa_id]'") or die(mysql_error()."Error: en consultar procesos_fase_5_g ");
$regTagua= mysqli_fetch_assoc($sqlTagua);
}

if ($regfg['taa_id'] != '') {
$sqltaa = mysqli_query($cnx, "SELECT taa_descripcion FROM tipos_agua_a  WHERE taa_id = '$regfg[taa_id]'") or die(mysql_error()."Error: en consultar tipos_agua_a ");
$regtaa= mysqli_fetch_assoc($sqltaa);
}

if ($regfg['pro_id'] != '') {
$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '11'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);
}

if ($regfg['pro_id'] != '') {
$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '11'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);
}

?>

<style>
/*td{
  padding-left: 5px;padding-right: 5px
}*/
@media print {
    .liberacion{
        width:220px;
    }
    
    .general{
        width:1015px;
    }
}
</style>


<div class="divProcesos">
  <table width="100%" style="margin:20px 0px 20px 0px">
    <tr>
      <td colspan="2"><table class="general" width="100%" border="1" style="background: #FCEFF2;font-size: 12px;">
        <tr>
          <td height="45" colspan="10">LAVADOS 1er ACIDO. Este proceso se puede hacer con agua limpia. El agua de este proceso se manda a agua recuperada semilimpia(PILA 1)
         </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2"><table class="general" width="100%" border="1">
        <tr>
          <td width="20%"  style="font-weight: bold;background: #e6e6e6"><span>Fecha inicio</span></td>
          <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
          <td width="12%"  style="font-weight: bold;background: #e6e6e6">Tipo de agua</td>
          <td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
          </tr>
        <tr>
          <td><?php echo fnc_formato_fecha($regProAux['proa_fe_ini'])?></td>
          <td><?php echo $regProAux['proa_hr_ini'] ?></td>
          <td><?php echo $regTagua['tpa_descripcion']?></td>
          <td><?php echo fnc_nom_usu($regfg['usu_id']) ?></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><p>&nbsp;</p></td>
    </tr>
    <tr>
      <td width="60%" valign="top">
        <table width="830" border="1" style="margin-top: 10px">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="5%" style="font-size: small; text-align: center;">Lav</td>
            <td width="5%" style="font-size: small; text-align: center;">Tipo agua</td>
            <td  style="font-size: small; text-align: center;">Hora ini llenado</td>
            <td  style="font-size: small; text-align: center;">Hora term llenado</td>
            <td  style="font-size: small; text-align: center;">Hora ini mov</td>
            <td  style="font-size: small; text-align: center;">Hora ter mov</td>
            <td width="5%" style="font-size: small; text-align: center;">Ce</td>
            <td width="5%" style="font-size: small; text-align: center;">Ph</td>
            <td width="5%" style="font-size: small; text-align: center;">Temp</td>
            <td width="12%"  style="font-size: small; text-align: center;">Agua a</td>
            <td width="5%" style="font-size: small; text-align: center;">Observaciones</td>
            <td width="10%" style="font-size: small; text-align: center;">Capturo</td>

          </tr>
          <?php
          $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_5_d  WHERE pfg5_id = '$regfg[pfg5_id]'") or die(mysql_error()."Error: en consultar procesos_fase_5_d");
          $regfd= mysqli_fetch_assoc($sqlfd);

          do{
 $agua_a = mysqli_query($cnx, "SELECT * FROM tipos_agua_a WHERE taa_id = '$regfd[taa_id]'") or die(mysql_error()."Error: en consultar el tipo de agua a");
          $reg_aa= mysqli_fetch_assoc($agua_a);

 $tipo_agua = mysqli_query($cnx, "SELECT * FROM tipos_agua WHERE tpa_id = '$regfd[tpa_id]'") or die(mysql_error()."Error: en consultar el tipo de agua a");
          $reg_tipo_agua= mysqli_fetch_assoc($tipo_agua);
            ?>
            <tr>
              <td><?php echo $regfd['pfd5_ren']?></td>
              <td><?php echo $reg_tipo_agua['tpa_descripcion']?></td>
              <td><?php echo $regfd['pfd5_hr_ini']?></td>
              <td><?php echo $regfd['pfd5_hr_fin']?></td>
              <td><?php echo $regfd['pfd5_hr_ini_mov']?></td>
              <td><?php echo $regfd['pfd5_hr_fin_mov']?></td>
              <td><?php echo $regfd['pfd5_ce']?></td>
              <td><?php echo $regfd['pfd5_ph']?></td>
              <td><?php echo $regfd['pfd5_temp']?></td>
              <td><?php echo $reg_aa['taa_descripcion']?></td>
              <td><?php echo $regfd['pfd5_observaciones']?></td>
              <td><?php echo fnc_nom_usu($regfd['usu_id'])?></td>
            </tr>
          <?php } while($regfd= mysqli_fetch_assoc($sqlfd));?>
        </table>

      </td>
      <td width="10%" rowspan="3" valign="top"><p>
        <?php 
       $usu_aux = $regProAux['usu_sup'];
       $usu_proLib = $regProLib['usu_id'];
       ?>
      </p>
        <table class="liberacion" width="280" border="1">
          <tr>
            <td width="75" rowspan="5" style="font-size:50px">5b</td>
            <td colspan="2" style="background: #e6e6e6;font-weight: bold;">LIBERACION CE A <?php echo fnc_rango_de(11) ?> a <?php echo fnc_rango_a(11) ?> hora) MAX</td>
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
            <td><?php echo fnc_nom_usu($usu_proLib)?></td>
          </tr>
      </table></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><table border="1" width="670">
          <tr style="font-weight: bold;">
            <td width="240" style="background: #e6e6e6">Fecha termina</td>
            <td width="170" style="background: #e6e6e6">Hora termina </td>
            <!--<td width="202" style="background: #e6e6e6">Agua a</td>-->
            <td width="202" style="background: #e6e6e6">Usuario</td>
            <td width="190" rowspan="2" style="background: #e6e6e6">(<?php echo fnc_hora_de(11) ?> a <?php echo fnc_hora_a(11) ?> horas)</td>
         
          </tr>
          <tr>
            <td><?php echo fnc_formato_fecha($regProAux['proa_fe_fin'])?></td>
            <td><?php echo $regProAux['proa_hr_fin']?></td>
           <!-- <td><?php echo $regtaa['taa_descripcion']?></td>-->
            <td><?php echo fnc_nom_usu($usu_aux)?></td>

           
            <tr>
              <td colspan="7" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
            </tr>
            <tr>
              <td colspan="9"><?php echo $regProAux['proa_observaciones']?></td>
            </tr>
         </table></td>
    </tr>
    </table>
  </div>
