<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 

$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_6b_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro' AND pfg.pe_id = 16") or die(mysql_error()."Error: en consultar procesos_fase_6b_g ");
$regfg= mysqli_fetch_assoc($sqlfg);

if ($regfg['tpa_id'] != '') {
$sqlTagua = mysqli_query($cnx, "SELECT * FROM tipos_agua  WHERE tpa_id= $regfg[tpa_id]") or die(mysql_error()."Error: en consultar procesos_fase_6b_g ");
$regTagua= mysqli_fetch_assoc($sqlTagua);
}

if ($regfg['pro_id'] != '') {
$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '16'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);
}

if ($regfg['pro_id'] != '') {
$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '16'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);
}


$tbHtml6c = "";

$tbHtml6c.= '
 <table width="100%" style="margin:20px 0px 20px 0px;background:#F5F4F4">
    <tr>
      <td colspan="2"><table border="1" style="background: #FCEFF2;font-size: 12px;width: 100%">
        <tr>
          <td height="45" colspan="10">LAVADOS 1er ACIDO. Este proceso se puede hacer con agua limpia. El agua de este proceso se manda a agua recuperada semilimpia(PILA 1)
         </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2"><table width="100%" border="1">
        <tr>
          <td width="20%"  style="font-weight: bold;background: #e6e6e6"><span>Fecha inicio</span></td>
          <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
          <td width="12%"  style="font-weight: bold;background: #e6e6e6">Tipo de agua</td>
          <td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
          </tr>
        <tr>
          <td>'.fnc_formato_fecha($regProAux['proa_fe_ini']).'</td>
          <td>'.$regProAux['proa_hr_ini'] .'</td>
          <td>'.$regTagua['tpa_descripcion'].'</td>
          <td>'.fnc_nom_usu($regfg['usu_id']) .'</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="61%" valign="top">
        <table width="830" border="1" style="margin-top: 10px">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="5%" style="font-size: small; text-align: center;">Lav</td>
             <td width="5%" style="font-size: small; text-align: center;">Tipo agua</td>
            <td width="5%" style="font-size: small; text-align: center;">Temp</td>
            <td width="18%" style="font-size: small; text-align: center;">Hora ini llenado</td>
            <td width="20%" style="font-size: small; text-align: center;">Hora term llenado</td>
            <td width="15%" style="font-size: small; text-align: center;">Hora ini mov</td>
            <td width="15%" style="font-size: small; text-align: center;">Hora ter mov</td>
            <td width="5%" style="font-size: small; text-align: center;">Ce</td>
            <td width="5%" style="font-size: small; text-align: center;">Ph</td>
            <td width="8%"  style="font-size: small; text-align: center;">Agua a</td>
             <td width="5%" style="font-size: small; text-align: center;">Observaciones</td>
            <td width="20%" style="font-size: small; text-align: center;">Capturo</td>

          </tr>';
          $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_6b_d  WHERE pfg6_id = '$regfg[pfg6_id]'") or die(mysql_error()."Error: en consultar procesos_fase_6b_d");
          $regfd= mysqli_fetch_assoc($sqlfd);

          do{
             $agua_a = mysqli_query($cnx, "SELECT * FROM tipos_agua_a WHERE taa_id = '$regfd[taa_id]'") or die(mysql_error()."Error: en consultar el tipo de agua a");
          $reg_aa= mysqli_fetch_assoc($agua_a);

           $tipo_agua = mysqli_query($cnx, "SELECT * FROM tipos_agua WHERE tpa_id = '$regfd[tpa_id]'") or die(mysql_error()."Error: en consultar el tipo de agua a");
          $reg_tipo_agua= mysqli_fetch_assoc($tipo_agua);
$tbHtml6c.= '
            <tr>
              <td>'.$regfd['pfd6_ren'].'</td>
              <td>'.$reg_tipo_agua['tpa_descripcion'].'</td>
              <td>'.$regfd['pfd6_temp'].'</td>
              <td>'.$regfd['pfd6_hr_ini'].'</td>
              <td>'.$regfd['pfd6_hr_fin'].'</td>
              <td>'.$regfd['pfd6_hr_ini_mov'].'</td>
              <td>'.$regfd['pfd6_hr_fin_mov'].'</td>
              <td>'.$regfd['pfd6_ce'].'</td>
              <td>'.$regfd['pfd6_ph'].'</td>
              <td>'.$reg_aa['taa_descripcion'].'</td>
              <td>'.$regfd['pfd6_observaciones'].'</td>
              <td>'.fnc_nom_usu($regfd['usu_id']).'</td>
            </tr>';
            } while($regfd= mysqli_fetch_assoc($sqlfd));
$tbHtml6c.= '        </table>

      </td>
      <td width="39%"><p>&nbsp;</p>
      <p>&nbsp;</p></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table border="1" width="1331">
          <tr style="font-weight: bold;">
            <td width="296" style="background: #e6e6e6">Fecha termina</td>
            <td width="174" style="background: #e6e6e6">Hora termina </td>
            <td width="167" style="background: #e6e6e6">Usuario</td>
            <td width="133" rowspan="2" style="background: #e6e6e6">('.fnc_hora_de(16).' a '.fnc_hora_a(16).' horas)</td>
            <td width="26" rowspan="5">&nbsp;</td>
            <td width="335" rowspan="5" ><p>';
       $usu_aux = $regProAux['usu_sup'];
       $usu_proLib = $regProLib['usu_id'];
$tbHtml6c.= '</p>
              <table width="280" border="1">
              <tr>
                <td width="75" rowspan="4" style="font-size:50px">6c</td>
                <td colspan="2" style="background: #e6e6e6;font-weight: bold;">LIBERACION CE A '.fnc_rango_de(16).' a '.fnc_rango_a(16).' hora) MAX</td>
              </tr>
              <tr>
                <td width="169">Ce liberación</td>
                <td width="170">'.$regProLib['prol_ce'] .'</td>
              </tr>
              <tr>
                <td>Horas totales</td>
                <td>'.$regProLib['prol_hr_totales'] .'</td>
              </tr>
              <tr>
                <td>Nombre LCP</td>
                <td>'.fnc_nom_usu($usu_proLib).'</td>
              </tr>
            </table></td>
         
          </tr>
          <tr>
            <td>'.fnc_formato_fecha($regProAux['proa_fe_fin']).'</td>
            <td>'.$regProAux['proa_hr_fin'].'</td>
            <td>'.fnc_nom_usu($usu_aux).'</td>

           
          <tr>
              <td colspan="5" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          </tr>
            <tr>
              <td height="23" colspan="5">'.$regProAux['proa_observaciones'].'</td>
            </tr>
        </table></td>
         <td>&nbsp;</td>
    </tr>
    </table>
    	  <table>
  <tr>
      <td>&nbsp;</td>
    </tr>
</table>
';
echo $tbHtml6c;
?>

