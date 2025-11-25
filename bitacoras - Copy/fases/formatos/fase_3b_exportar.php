<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
//include "../../funciones/funciones_procesos.php";
$cnx =  Conectarse();

extract($_POST); 

$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_3b_g as pf3bg INNER JOIN procesos as p  on(pf3bg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysql_error()."Error: en consultar procesos_fase_3b_g ");
$regfg= mysqli_fetch_assoc($sqlfg);

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '6'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '6'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

$usu_aux = $regProAux['usu_sup'];
$usu_proLib = $regProLib['usu_id'];

$tbHtml3b = "";

$tbHtml3b.= '<table width="156%" style="margin:20px 0px 20px 0px;background:#F5F4F4">
<tr style="background: #FCEFF2;font-size: 12px;width: 100%">
<td colspan="3"><table border="1" style="background: #FCEFF2;font-size: 12px;width: 100%">
<tr>
<td width="97%" height="45" colspan="8">ADICION A SOSANota:Estar revisando los chequeos durante las 32 horas</td>
</tr>
</table></td>
</tr>
<tr>
<td valign="top"><table width="100%" border="1">
  <tr>
    <td width="9%"  style="font-weight: bold;background: #e6e6e6"><span>Fecha de inicio</span></td>
    <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
    <td width="12%"  style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
    <td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
  </tr>
  <tr>
    <td>'.fnc_formato_fecha($regProAux['proa_hr_ini']).'</td>
    <td>'.$regProAux['proa_hr_ini'].'</td>
    <td>'.$regfg['pfg3_temp_ag'].'</td>
    <td>'.fnc_nom_usu($regfg['usu_id']).'</td>
  </tr>
  <tr>
    <td  style="font-weight: bold;background: #e6e6e6"><span>Agrega lts sosa</span></td>
    <td style="font-weight: bold;background: #e6e6e6">Ph</td>
    <td  style="font-weight: bold;background: #e6e6e6">Temp </td>
    <td style="font-weight: bold;background: #e6e6e6">Norm</td>
  </tr>
  <tr>
    <td>'.$regfg['pfg3_lts'].'</td>
    <td>'.$regfg['pfg3_ph'].'</td>
    <td>'.$regfg['pfg3_temp'].'</td>
    <td>'.$regfg['pfg3_norm'].'</td>
  </tr>
</table></td>

</tr>
<tr>
<td>Nota: Seguir anotando los chequeos después de que cumpla su tiempo (32-36) o cuando se inicia abara normalida</td>
</tr>
<tr>
<td valign="top">
<table width="96%" border="1" style="margin-top: 10px">
<tr style="font-weight: bold;background: #e6e6e6">
<td width="29%" style="font-size: small; text-align: center;">Chequeo</td>
<td width="31%" style="font-size: small; text-align: center;">Fecha</td>
<td width="40%" style="font-size: small; text-align: center;">Hora</td>
<td width="40%" valign="middle" style="font-size: small; text-align: center;">Temp</td>
<td width="40%" style="font-size: small; text-align: center;">Norm</td>
<td width="40%" style="font-size: small; text-align: center;">Sosa</td>
<td width="40%" style="font-size: small; text-align: center;">Movimeinto</td>
<td width="40%" style="font-size: small; text-align: center;">Reposo</td>
<td width="40%" style="font-size: small; text-align: center;">Capturo</td>
</tr>';

$sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_3b_d as pf3bd WHERE pf3bd.pfg3_id = '$regfg[pfg3_id]'") or die(mysql_error()."Error: en consultar procesos_fase_3b_d");
$regfd= mysqli_fetch_assoc($sqlfd);
do{

 $tbHtml3b.= '<tr>
 <td>'.$regfd['pfd3_ren'].'</td>
 <td>'.fnc_formato_fecha($regfd['pfd3_fecha']).'</td>
 <td>'.$regfd['pfd3_hr'].'</td>
 <td>'.fnc_formato_val($regfd['pfd3_temp']).'</td>
 <td>'.fnc_formato_val($regfd['pfd3_norm']).'</td>
 <td>'.fnc_formato_val($regfd['pfd3_sosa']).'</td>
 <td>'.fnc_formato_val($regfd['pfd3_movimiento']).'</td>
 <td>'.fnc_formato_val($regfd['pfd3_reposo']).'</td>
 <td>'.fnc_nom_usu($regfd['usu_id']).'</td>
 </tr>';
} while($regfd= mysqli_fetch_assoc($sqlfd));

$tbHtml3b.= '</table>
</td>
</tr>
<tr>
<td><div>Revisar estado de material.Si ya esta LIBERAR</div>
<br /></td>

</tr>
<tr>
  <td><table border="1" width="514">
    <tr>
      <td colspan="4" align="center" style="font-weight: bold;background: #e6e6e6;">CP CHEQUEOS DE PH 10.0 - 10.8</td>
      <td width="37" rowspan="5" align="center"><table width="436" border="1">
        <tr>
          <td width="75" rowspan="4" align="left" style="font-size:50px">3b</td>
          <td colspan="2" style="background: #e6e6e6;font-weight: bold;">Liberación'.fnc_rango_de(6).' a'.fnc_rango_a(6) .'horas</td>
          </tr>
        <tr>
          <td width="169"></td>
          <td width="170"></td>
          </tr>
        <tr>
          <td>Horas totales</td>
          <td>'.$regProLib['prol_hr_totales'].'</td>
          </tr>
        <tr>
          <td>Nombre LCP</td>
          <td>'.fnc_nom_usu($usu_proLib).'</td>
          </tr>
        </table></td>
      </tr>
    <tr>
      <td width="58" style="font-weight: bold;background: #e6e6e6">Norm. solución</td>
      <td width="221">'.$regfg['pfg3_ph1'].'</td>
      <td width="41" style="font-weight: bold;background: #e6e6e6">Horas</td>
      <td width="123">'.$regfg['pfg3_hr1'].'</td>
      </tr>
    <tr>
      <td style="font-weight: bold;background: #e6e6e6">Nombre LCP</td>
      <td>'.fnc_nom_usu($regfg['pfg3_usu1']).'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td style="font-weight: bold;background: #e6e6e6">Norm. solución</td>
      <td>'.fnc_formato_val($regfg['pfg3_ph2']).'</td>
      <td style="font-weight: bold;background: #e6e6e6">Horas</td>
      <td>'.fnc_formato_val($regfg['pfg3_hr2']).'</td>
      </tr>
    <tr>
      <td style="font-weight: bold;background: #e6e6e6">Nombre LCP</td>
      <td>'.fnc_nom_usu($regfg['pfg3_usu2']).'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
  </table></td>
</tr>
<tr>
  <td>Nota: Seguir anotando los chequeos después de que cumpla su tiempo (32-36) o cuando se inicia abara normalida</td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
<tr>
  <td width="36%" valign="top"><table border="1" width="108%">';      
    $tbHtml3b.='<tr style="font-weight: bold;">
      <td width="266" style="background: #e6e6e6">Fecha termina sosa</td>
      <td width="168" style="background: #e6e6e6">Hora termina sosa</td>
      <td width="175" style="background: #e6e6e6">Usuario</td>
      <td width="175" rowspan="5">&nbsp;</td>
      <td width="175" rowspan="5"><table width="434" border="1">
        <tr>
          <td  style="font-weight: bold;background: #e6e6e6"><label for="inputPassword2" >Horas totales de<br /> todo el proceso</label></td>
          <td  style="font-weight: bold;background: #e6e6e6">Revisó</td>
          <td  style="font-weight: bold;background: #e6e6e6">'.fnc_hora_de(6).' a '.fnc_hora_a(6).' Horas</td>
        </tr>
        <tr>
          <td>'.$regProLib['prol_hr_totales'].'</td>
          <td>'.fnc_nom_usu($regProLib['usu_id']).'</td>
          <td></td>
        </tr>
      </table></td>
      </tr>
    <tr>
      <td>'.fnc_formato_fecha($regProAux['proa_fe_fin']).'</td>
      <td>'.$regProAux['proa_hr_fin'].'</td>
      <td>'.fnc_nom_usu($usu_aux).'</td>
      <tr>
        <td>El agua de este proceso se manda a agua recuperada semilimpia (pila 2)</td>
        <td colspan="2">&nbsp;</td>
        </tr>
    <tr>
      <td style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
      <td colspan="2" style="font-weight: bold;background: #e6e6e6">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="3">'.$regProAux['proa_observaciones'].'</td>
      </tr>
    </table></td>
  </tr>
</table>
  <table>
  <tr>
      <td>&nbsp;</td>
    </tr>
</table>';

	echo $tbHtml3b;
?>

