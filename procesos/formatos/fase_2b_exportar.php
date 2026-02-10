<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/ 
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 

$sqlf2bg = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_g as pf2bg INNER JOIN procesos as p  on(pf2bg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysql_error()."Error: en consultar procesos_fase_2b_g ");
$regf2bg= mysqli_fetch_assoc($sqlf2bg);


/*$sqlProAux2b = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$idx_pro' AND pe_id = $regf2bg[pe_id]") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux2b = mysqli_fetch_assoc($sqlProAux2b);*/

$sqlProAux2b = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regf2bg[pro_id]'  AND pe_id = '3'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux2b = mysqli_fetch_assoc($sqlProAux2b);


$sqlProLib2b = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regf2bg[pro_id]'  AND pe_id = '3'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib2b = mysqli_fetch_assoc($sqlProLib2b);


$tbHtml2b = "";

$tbHtml2b.= '  <table width="155%"  style="margin:20px 0px 20px 0px;background:#F5F4F4">
<tr>
<td colspan="12"><table border="1" style="background: #FCEFF2;font-size: 12px;width: 100%">
<tr>
<td height="45" colspan="10">ENZIMA. Este proceso es de 10 horas en movimiento continuo</td>
</tr>
</table></td>
</tr>
<tr>
  <td colspan="12"><table width="100%" border="1">
  <tr>
  <td width="17%" style="font-weight: bold;background: #e6e6e6">Enzima</td>
  <td width="9%"  style="font-weight: bold;background: #e6e6e6"><span>Fecha de inicio</span></td>
  <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
  <td width="12%"  style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
  <td width="14%" style="font-weight: bold;background: #e6e6e6">Usuario</td>
  </tr>
  <tr>
  <td>'.$regf2bg['pfg2_enzima'].'</td>
  <td>'.fnc_formato_fecha($regProAux2b['proa_fe_ini']).'</td>
    <td>'.$regProAux2b['proa_hr_ini'].'</td>
    <td>'.$regf2bg['pfg2_temp_ag'].'</td>
    <td>'.fnc_nom_usu($regf2bg['usu_id']).'</td>
    </tr>
    </table></td>
</tr>
	<tr>
	  <td colspan="12">&nbsp;</td>
  </tr>
	<tr>
	<td colspan="12"><table width="96%" border="1">
	  <tr style="font-weight: bold;background: #e6e6e6">
	    <td width="29%" style="font-size: small; text-align: center;">Hora</td>
	    <td width="31%" style="font-size: small; text-align: center;">Ph</td>
	    <td width="40%" style="font-size: small; text-align: center;">Sosa</td>
	    <td width="40%" style="font-size: small; text-align: center;">Ácido</td>
	    <td width="40%" style="font-size: small; text-align: center;">Usuario</td>
	    <td width="40%" rowspan="4" style="font-size: small; text-align: center;background:#F5F4F4;border-top:1px #FFF;border-bottom:1px #FFF;">&nbsp;</td>
	    <td width="40%" rowspan="2" style="font-size: small; text-align: center;"><table border="1" width="435">
	      ';
	      $usu_opg1 = fnc_nom_usu($regf2bg['pfg2_usu1']);
	      $usu_opg2 = fnc_nom_usu($regf2bg['pfg2_usu2']);
	      $tbHtml2b.= '
	      <tr>
	        <td style="font-weight: bold;background: #e6e6e6">Ph solución</td>
	        <td>'.$regf2bg['pfg2_ph1'].'</td>
	        <td style="font-weight: bold;background: #e6e6e6">Horas</td>
	        <td>'.$regf2bg['pfg2_hr1'].'</td>
	        </tr>
	      <tr>
	        <td style="font-weight: bold;background: #e6e6e6">Nombre LCP</td>
	        <td>'.$usu_opg1.'</td>
	        <td>&nbsp;</td>
	        <td>&nbsp;</td>
	        </tr>
	      <tr>
	        <td style="font-weight: bold;background: #e6e6e6">Ph solución</td>
	        <td>'.fnc_formato_val($regf2bg['pfg2_ph2']).'</td>
	        <td style="font-weight: bold;background: #e6e6e6">Horas</td>
	        <td>'.fnc_formato_val($regf2bg['pfg2_hr2']).'</td>
	        </tr>
	      <tr>
	        <td style="font-weight: bold;background: #e6e6e6">Nombre LCP</td>
	        <td>'.$usu_opg2.'</td>
	        <td>&nbsp;</td>
	        <td>&nbsp;</td>
	        </tr>
	      </table></td>
	    </tr>
	  ';
	  
	  $sqlf2d = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_d as pf2bd WHERE pf2bd.pfg2_id = '$regf2bg[pfg2_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
	  $regf2bd= mysqli_fetch_assoc($sqlf2d);
	  $txtOpe = fnc_nom_usu($regf2bd['usu_id']);
	  do{        
	  $tbHtml2b.= ' <tr>
	    <td>'.$regf2bd['pfd2_hr'].'</td>
	    <td>'.$regf2bd['pfd2_ph'].'</td>
	    <td>'.fnc_formato_val($regf2bd['pfd2_sosa']).'</td>
	    <td>'.fnc_formato_val($regf2bd['pfd2_acido']).'</td>
	    <td>'.$txtOpe.'</td>
	    </tr>
	  ';
	  } while($regf2bd= mysqli_fetch_assoc($sqlf2d));
	  $tbHtml2b.= '</table>
	  
	  </td>
	</tr>
	<tr>
	<td>Este proceso es de 15 a 30 minutos de movimiento por cada 2 o 3 horas de reposo
	  <br /></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Nota: Seguir anotando los chequeos después de que cumpla su tiempo (32-36)
o cuando se inicia abara
normalidad</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td colspan="12">
	<table width="100%" border="1">
	<tr style="font-weight: bold;background: #e6e6e6">
	<td width="6%" style="font-size: small; text-align: center;">No.</td>
	<td width="12%" style="font-size: small; text-align: center;">Horas</td>
	<td width="12%" style="font-size: small; text-align: center;">Hora</td>
	<td width="12%" style="font-size: small; text-align: center;">Min. movimiento</td>
	<td width="12%" style="font-size: small; text-align: center;">Reposo</td>
	<td width="12%" style="font-size: small; text-align: center;">Ph</td>
	<td width="12%" style="font-size: small; text-align: center;">Temp</td>
	<td width="12%"  style="font-weight: bold;background: #e6e6e6">Sosa</td>
            <td width="12%"  style="font-weight: bold;background: #e6e6e6">Ácido</td>
	<td width="12%" style="font-size: small; text-align: center;">Capturo</td>
	</tr>';
	$sqlf2d2 = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_d2 as pf2bd WHERE pf2bd.pfg2_id = '$regf2bg[pfg2_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
	$regf2bd2= mysqli_fetch_assoc($sqlf2d2);

	$usu_opg4 = fnc_nom_usu($regf2bd2['usu_id']);
	do{

		$tbHtml2b.= '<tr>
		<td>'.$regf2bd2['pfd22_ren'].'</td>
		<td>'.$regf2bd2['pfd22_ren'].'</td>
		<td>'.$regf2bd2['pfd22_hr'].'</td>
		<td>'.fnc_formato_val($regf2bd2['pfd22_min']).'</td>
		<td>'.fnc_formato_val($regf2bd2['pfd22_reposo']).'</td>
		<td>'.$regf2bd2['pfd22_ph'].'</td>
		<td>'.$regf2bd2['pfd22_temp'].'</td>
		<td>'.$regf2bd2['pfd22_sosa'].'</td>
		<td>'.$regf2bd2['pfd22_acido'].'</td>
		<td>'.$usu_opg4.'</td>';
	} while($regf2bd2= mysqli_fetch_assoc($sqlf2d2));

	$tbHtml2b.='</table>
	</td>
	<tr>
	<td colspan="12">
	El agua de este proceso se manda a agua recuperada semilimpia (Pila 2)
	</td>
	</tr>
	<tr>
    <td width="61%" colspan="9"></tr>
	<tr>
	<td colspan="12"><table border="1" width="100%">';
	$usu_aux = fnc_nom_usu($regProAux2b['usu_sup']);
	$usu_proLib = fnc_nom_usu($regProLib2b['usu_id']);
	$tbHtml2b.='<tr style="font-weight: bold;">
	<td width="266" style="background: #e6e6e6">Fecha termina enzima</td>
	<td width="168" style="background: #e6e6e6">Hora termina enzima</td>
	<td width="175" style="background: #e6e6e6">Usuario</td>
	<td width="8" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
	<td width="112"  style="border:1px solid#fff;background: #e6e6e6">32  a 36 horas</td>
	<td width="77" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
	<td rowspan="5" width="36" style="font-size:50px">2b</td>
	<td colspan="3" style="background: #e6e6e6;font-weight: bold;">Liberación'.fnc_rango_de(10).' a '.fnc_rango_a(10).' horas</td>
	</tr>
	<tr>
	<td>'.fnc_formato_fecha($regProAux2b['proa_fe_fin']).'</td>
	<td>'.$regProAux2b['proa_hr_fin'].'</td>
	<td>'.$usu_aux.'</td>
	<td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
	<td style="border:1px solid#fff"></td>
	<td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
	<td width="119"></td>
	<td width="172" colspan="2"></td>
	</tr>
	<tr>
	<td colspan="3" style="border-right:1px solid#fff"></td>
	<td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
	<td style="border:1px solid#fff"></td>
	<td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
	<td>Horas totales </td>
	<td width="172" colspan="2">'.$regProLib2b['prol_hr_totales'].'</td>
	</tr>
	<tr>
	<td colspan="3" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
	<td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
	<td style="border:1px solid#fff"></td>
	<td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
	<td>Nombre LCP </td>
	<td width="172" colspan="2">'.$usu_proLib.'</td>
	</tr>
	<tr>
	<td colspan="3">'.$regProAux2b['proa_observaciones'].'</td>
	<td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
	<td style="border:1px solid#fff"></td>
	<td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
	<td></td>
	<td width="172" colspan="2"></td>
	</tr>
	<tr>
	<td colspan="3" style="border-right:1px solid#fff">&nbsp;</td>
	</tr>
	<tr>
	<td  style="font-weight: bold;background: #e6e6e6"><label for="inputPassword4" >Horas totales de todo el proceso</label></td>
	<td  style="font-weight: bold;background: #e6e6e6">Revisó</td>
	<td  style="font-weight: bold;background: #e6e6e6">'.fnc_hora_de(3).' a '.fnc_hora_a(3).' Horas</td>

	</tr>
	<tr>
	<td>'.$regProLib2b['prol_hr_totales'].'</td>
	<td>'.$usu_proLib.'</td>
	<td></td>
	</tr>
	</table></td>
	</tr>
	</table>
	<table>
	<tr>
	<td>
	</td>
	</tr>
	</table>
	<?php';

	echo $tbHtml2b;
	?>