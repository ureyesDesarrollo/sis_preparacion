<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();


extract($_POST); 

$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_8_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysql_error()."Error: en consultar procesos_fase_8_g ");
$regfg= mysqli_fetch_assoc($sqlfg);

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '21'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);

$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion_b WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '21'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

$sql_lib_coc = mysqli_query($cnx, "SELECT * FROM procesos_liberacion_b_cocidos WHERE prol_id = '$regProLib[prol_id]'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$reg_lib_coc = mysqli_fetch_assoc($sql_lib_coc);

$tbHtml8b = "";

$tbHtml8b.= '
<table width="100%" style="margin:20px 0px 20px 0px;background:#F5F4F4">
<tr>
<td colspan="2"><table width="97%" border="1" style="background: #FCEFF2;font-size: 12px;width: 93%">
<tr>
<td height="45" colspan="9">LAVADOS FINALES. Este proceso se utilizará: Agua recuperada de los ultimos lavados finales de otro paleto y/o agua limpia
<div></div></td>
</tr>
</table></td>
</tr>
<tr>
<td colspan="2"><table width="98%" border="1">
<tr>
<td width="20%"  style="font-weight: bold;background: #e6e6e6"><span>Fecha inicio</span></td>
<td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
<td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
</tr>
<tr>
<td>'.fnc_formato_fecha($regProAux['proa_fe_ini']).'</td>
<td>'.$regProAux['proa_hr_ini'].'</td>
<td>'.fnc_nom_usu($regfg['usu_id']) .'</td>
</tr>
</table></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td colspan="2">
<table width="1095" border="1" style="margin-top: 10px">
<tr style="font-weight: bold;background: #e6e6e6">
<td width="9%" style="font-size: small; text-align: center;">Tipo agua</td>
<td width="9%" style="font-size: small; text-align: center;">Movimiento</td>
<td width="12%" style="font-size: small; text-align: center;">Hora ini. llenado</td>
<td width="12%" style="font-size: small; text-align: center;">Hora fin. llenado</td>
<td width="12%" style="font-size: small; text-align: center;">Hora ini. drenado</td>
<td width="12%" style="font-size: small; text-align: center;">Hora fin. drenado</td>
<td width="6%" style="font-size: small; text-align: center;">Ph</td>
<td width="6%" style="font-size: small; text-align: center;">Ce</td>
<td width="7%" style="font-size: small; text-align: center;">Temp</td>
<td style="font-size: small; text-align: center;">Agua a</td>
<td width="7%" style="font-size: small; text-align: center;">Observaciones</td>
<td width="12%" style="font-size: small; text-align: center;">Capturo</td>
</tr>';

$sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_8_d WHERE pfg8_id = '$regfg[pfg8_id]'") or die(mysql_error()."Error: en consultar procesos_fase_8_d");
$regfd= mysqli_fetch_assoc($sqlfd);

do{

  $agua_a = mysqli_query($cnx, "SELECT * FROM tipos_agua_a WHERE taa_id = '$regfd[taa_id]'") or die(mysql_error()."Error: en consultar el tipo de agua a");
  $reg_aa= mysqli_fetch_assoc($agua_a);

  $tipo_agua = mysqli_query($cnx, "SELECT * FROM tipos_agua WHERE tpa_id = '$regfd[tpa_id]'") or die(mysql_error()."Error: en consultar el tipo de agua a");
  $reg_tipo_agua= mysqli_fetch_assoc($tipo_agua);
  $tbHtml8b.= '
  <tr>
  <td>'.$reg_tipo_agua['tpa_descripcion'].'</td>
  <td>'.$regfd['pfd8_mov'].'</td>
  <td>'.$regfd['pfd8_ini_llenado'].'</td>
  <td>'.$regfd['pfd8_fin_llenado'].'</td>
  <td>'.$regfd['pfd8_ini_dren'].'</td>
  <td>'.$regfd['pfd8_fin_dren'].'</td>
  <td>'.$regfd['pfd8_ph'].'</td>
  <td>'.$regfd['pfd8_ce'].'</td>
  <td>'.$regfd['pfd8_temp'].'</td>
  <td>'.$reg_aa['taa_descripcion'].'</td>
  <td>'.$regfd['pfd8_observaciones'].'</td>
  <td>'.fnc_nom_usu($regfd['usu_id']).'</td>
  </tr>';
} while($regfd= mysqli_fetch_assoc($sqlfd));
$tbHtml8b.= '  </table>
<p>';
$usu_aux = $regProAux['usu_sup'];
$usu_proLib = $regProLib['usu_id'];
$tbHtml8b.= '
</p></td>
</tr>
<tr>
<td>&nbsp;</td>
<td width="37%" rowspan="6">&nbsp;</td>
</tr>
<tr>
<td width="63%"><table border="1" width="699">
<tr style="font-weight: bold;">
<td width="205" style="background: #e6e6e6">Fecha termina lavados finales</td>
<td width="94" style="background: #e6e6e6">Hora termina </td>
<td width="111" style="background: #e6e6e6">Horas totales</td>
<td width="109" style="background: #e6e6e6">Usuario</td>
<td width="143" rowspan="2" style="background: #e6e6e6">('. fnc_hora_de(21).' a '. fnc_hora_a(21).' horas)</td>
<td width="143" rowspan="2" style="background: #e6e6e6">Para hacer los lavados<br /> finales deben tardar <br />de ('. fnc_hora_de(21).' a '. fnc_hora_a(21).' horas)</td>
</tr>
<tr>
<td>'.fnc_formato_fecha($regProAux['proa_fe_fin']).'</td>
<td>'.$regProAux['proa_hr_fin'].'</td>
<td>'.$regfg['pfg8_hr_totales'].'</td>
<td>'.fnc_nom_usu($usu_aux).'</td>

</tr>
</table></td>
</tr>
<tr>
<td>El agua de este proceso se manda a (Pila 1) agua recuperada limpia</td>
</tr>
<tr>
<td><table border="1" width="701">
<tr style="font-weight: bold;">
<td width="210" style="background: #e6e6e6">Horas totales de todo el proceso</td>
<td width="96" style="background: #e6e6e6">Usuario</td>
<td width="124" rowspan="2" style="background: #e6e6e6">('. fnc_hora_de(21).' a '. fnc_hora_a(21).' horas)</td>
</tr>
<tr>
<td>'.$regfg['pfg8_hr_totales'].'</td>
<td>'.fnc_nom_usu($usu_aux).'</td>
</tr>
</table></td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td><table border="1" width="830">
<tr style="font-weight: bold;">
<td width="142" style="background: #e6e6e6">Fecha sale a producción</td>
<td width="95" style="background: #e6e6e6">Hora </td>
<td width="107" style="background: #e6e6e6">Usuario</td>
<td width="226" rowspan="2" style="background: #e6e6e6">('. fnc_hora_de(21).' a '. fnc_hora_a(21).' horas)</td>
<td width="226" rowspan="5"><table border="1">
<tr>
<td rowspan="13" style="font-size:50px">8b</td>
<td colspan="4" style="background: #e6e6e6;font-weight: bold;">LIBERACION PH LIBERACIÓN (PH '. fnc_rango_de(21).' a '. fnc_rango_a(21).')</td>
</tr>
<tr>
<td width="50">Fecha</td>
<td width="50">'. fnc_formato_fecha($regProLib['prol_fecha']) .'</td>
<td width="50">Hora</td>
<td width="50">'.$regProLib['prol_hora'] .'</td>
</tr>
<tr>
<td>Cocido ph</td>
<td>Ce</td>
<td>Cuero sob</td>
<td>% ext</td>
</tr>';

do {
  $tbHtml8b.= '<tr>
  <td style="font-weight:bold">L'.$reg_lib_coc['prol_ren'].' '.fnc_formato_val($reg_lib_coc['prol_cocido']).'</td>
  <td style="text-align: left">'.fnc_formato_val($reg_lib_coc['prol_ce']).'</td>
  <td style="text-align: left">'.fnc_formato_val($reg_lib_coc['prol_cuero_sob']).'</td>
  <td style="text-align: left">'.fnc_formato_val($reg_lib_coc['prol_por_extrac']).'</td>
  </tr>';
} while ($reg_lib_coc = mysqli_fetch_assoc($sql_lib_coc));
$tbHtml8b.= '<td>Color</td>
<td colspan="3">'. $regProLib['prol_color'].'</td>
</tr>
<tr>
<td>Color caldo</td>
<td colspan="3">'. $regProLib['prol_color_caldo'].'</td>
</tr>
<tr>
<td>% de solidos</td>
<td colspan="3" style="text-align:left">'. $regProLib['prol_solides'].'</td>
</tr>
<tr>
<td>Observaciones</td>
<td colspan="3">'. $regProLib['prol_observaciones'].'</td>
</tr>
<tr>
<td>Nombre LCP</td>
<td colspan="3">'. fnc_nom_usu($usu_proLib).'</td>
</tr>
</table></td>
</tr>
<tr>
<td>'.fnc_formato_fecha($regfg['pfg8_fe_lib_prod']).'</td>
<td>'.$regfg['pfg8_hr_lib_prod'].'</td>

<td>'.fnc_nom_usu($usu_aux).'</td>
</tr>
<tr>
<td style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
<td style="font-weight: bold;background: #e6e6e6">&nbsp;</td>
<td style="font-weight: bold;background: #e6e6e6">&nbsp;</td>
<td style="font-weight: bold;background: #e6e6e6">&nbsp;</td>
</tr>
<tr>
<td>'.$regProAux['proa_observaciones'].'</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table>
<p>&nbsp;</p></td>
</tr>
</table>
<table>
<tr>
<td>
</td>
</tr>
</table>';
echo $tbHtml8b;
?>

