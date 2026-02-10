<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 

$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysql_error()."Error: en consultar procesos_fase_7b_g ");
$regfg= mysqli_fetch_assoc($sqlfg);

$sqltaa = mysqli_query($cnx, "SELECT taa_descripcion FROM tipos_agua_a  WHERE taa_id = '$regfg[taa_id]'") or die(mysql_error()."Error: en consultar tipos_agua_a ");
$regtaa= mysqli_fetch_assoc($sqltaa);

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '18'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '18'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);


$tbHtml7b = "";
$tbHtml7b.= '
  <table width="100%" style="margin:20px 0px 20px 0px;background:#F5F4F4">
    <tr>
      <td colspan="2"><table width="84%" border="1" style="background: #FCEFF2;font-size: 12px;width: 93%">
        <tr>
          <td height="45" colspan="7">SEGUNDO ÁCIDO. Este proceso se utiliza el agua de los lavadores de 1er ácido o solo de ácido fuerte</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2"><table width="93%" border="1">
        <tr>
          <td width="20%"  style="font-weight: bold;background: #e6e6e6"><span>Fecha inicio</span></td>
          <td width="20%" style="font-weight: bold;background: #e6e6e6">Hora de incio</td>
          <td width="14%" style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
          <td width="14%" style="font-weight: bold;background: #e6e6e6">Ácido diluido</td>
          <td width="14%" style="font-weight: bold;background: #e6e6e6">Operador</td>
          </tr>
        <tr>
          <td>'.fnc_formato_fecha($regProAux['proa_fe_ini']).'</td>
          <td>'.$regProAux['proa_hr_ini'] .'</td>
          <td>'.$regfg['pfg7_temp_ag'] .'</td>
          <td>'.$regfg['pfg7_acido_diluido'] .'</td>
          <td>'.fnc_nom_usu($regfg['usu_id']) .'</td>
        </tr>
        <tr>
          <td  style="font-weight: bold;background: #e6e6e6"><span>Temp</span></td>
          <td style="font-weight: bold;background: #e6e6e6">Ácido</td>
          <td style="font-weight: bold;background: #e6e6e6">Ph</td>
          <td style="font-weight: bold;background: #e6e6e6">Ce</td>
          <td width="14%" style="font-weight: bold;background: #e6e6e6">Normalidad</td>
          </tr>
        <tr>
          <td>'.fnc_formato_val($regfg['pfg7_temp']).'</td>
          <td>'.$regfg['pfg7_acido'] .'</td>
          <td>'.fnc_formato_val($regfg['pfg7_ph']).'</td>
          <td>'.fnc_formato_val($regfg['pfg7_ce']).'</td>
          <td>'.fnc_formato_val($regfg['pfg7_norm']).'</td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">
        <table width="1106" border="1" style="margin-top: 10px">
          <tr style="font-weight: bold;border-top:2px solid#fff;border-left:2px solid#fff;border-right:2px solid#fff;">
            <td style="font-size: small; text-align: center;">&nbsp;</td>
            <td style="font-size: small; text-align: center;">&nbsp;</td>
            <td style="font-size: small; text-align: center;">&nbsp;</td>
            <td style="font-size: small; text-align: center;">PPRO</td>
            <td style="font-size: small; text-align: center;">&nbsp;</td>
            <td style="font-size: small; text-align: center;">&nbsp;</td>
            <td style="font-size: small; text-align: center;">&nbsp;</td>
          </tr>
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="5%" style="font-size: small; text-align: center;">Ajust</td>
            <td width="18%" style="font-size: small; text-align: center;">Ac</td>
            <td width="18%" style="font-size: small; text-align: center;">Temp</td>
            <td width="20%" style="font-size: small; text-align: center;">Ph</td>
            <td width="15%" style="font-size: small; text-align: center;">Ce</td>
            <td width="15%" style="font-size: small; text-align: center;">Normalidad</td>
            <td width="20%" style="font-size: small; text-align: center;">Capturo</td>

          </tr>';
          $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d WHERE pfg7_id = '$regfg[pfg7_id]'") or die(mysql_error()."Error: en consultar procesos_fase_7b_d");
          $regfd= mysqli_fetch_assoc($sqlfd);

          do{
            $tbHtml7b.= '
            <tr>
              <td>'.$regfd['pfd7_ren'].'</td>
              <td>'.$regfd['pfd7_acido'].'</td>
              <td>'.fnc_formato_val($regfd['pfd7_temp']).'</td>
              <td style="background:#FF0000">'.fnc_formato_val($regfd['pfd7_ph']).'</td>
              <td>'.fnc_formato_val($regfd['pfd7_ce']).'</td>
              <td>'.fnc_formato_val($regfd['pfd7_norm']).'</td>
              <td>'.fnc_nom_usu($regfd['usu_id']).'</td>
            </tr>';} while($regfd= mysqli_fetch_assoc($sqlfd));
      $tbHtml7b.= '
        </table>
        <p>';
       $usu_aux = $regProAux['usu_sup'];
       $usu_proLib = $regProLib['usu_id'];
$tbHtml7b.= '
      </p></td>
    </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td ><table border="1" width="1108">
          <tr style="font-weight: bold;">
            <td width="225" style="background: #e6e6e6">Fecha termina 2da acidificación</td>
            <td width="282" style="background: #e6e6e6">Hora termina </td>
            <td width="208" style="background: #e6e6e6">Horas totales</td>
            <td width="156" style="background: #e6e6e6">Usuario</td>
            <td width="203" rowspan="2" style="background: #e6e6e6">'.fnc_hora_de(18).' a '.fnc_hora_a(18).' horas)</td>
          </tr>
          <tr>
            <td>'.fnc_formato_fecha($regProAux['proa_fe_fin']).'</td>
            <td>'.$regProAux['proa_hr_fin'].'</td>
            <td>'.$regfg['pfg7_hr_totales'].'</td>
            <td>'.fnc_nom_usu($usu_aux).'</td>
      
          </tr>
          <tr>
            <td colspan="5">En esta parte son 15 minutos de movimiento y 1:45 horas de reposo durante 8 horas</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="67%">&nbsp;</td>
        <td width="33%">&nbsp;</td>
      </tr>
      <tr>
        <td><table width="825" border="1" style="margin-top: 10px">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="14%" style="font-size: small; text-align: center;">Inicia mov.</td>
            <td width="16%" style="font-size: small; text-align: center;">Inicia reposo</td>
            <td width="10%" style="font-size: small; text-align: center;">Ph</td>
            <td width="13%" style="font-size: small; text-align: center;">Temp</td>
            <td width="13%" style="font-size: small; text-align: center;">Normalidad</td>
            <td width="19%" style="font-size: small; text-align: center;">Operador</td>
          </tr>';
          $sqlfd2 = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d2 as pfd  WHERE pfg7_id = '$regfg[pfg7_id]'") or die(mysql_error()."Error: en consultar procesos_fase_7b_d2");
          $regfd2= mysqli_fetch_assoc($sqlfd2);

          do{
$tbHtml7b.= '
          <tr>
            <td>'.$regfd2['pfd7_ini_mov'].'</td>
            <td>'.$regfd2['pfd7_ini_reposo'].'</td>
            <td>'.fnc_formato_val($regfd2['pfd7_ph']).'</td>
            <td>'.fnc_formato_val($regfd2['pfd7_temp']).'</td>
            <td>'.fnc_formato_val($regfd2['pfd7_norm']).'</td>
            <td>'.fnc_nom_usu($regfd2['usu_id']).'</td>
          </tr>';
        } while($regfd2= mysqli_fetch_assoc($sqlfd2));
        
        $tbHtml7b.= '
      </table></td>
        <td><table width="270" border="1">
          <tr>
            <td width="10" rowspan="5" style="font-size:50px">7b</td>
            <td colspan="2" style="background: #e6e6e6;font-weight: bold;">LIBERACION CE A '.fnc_rango_de(18).' a '.fnc_rango_a(18).' hora) MAX</td>
          </tr>
          <tr>
            <td width="10">Cocido ph (1.7-2.1)</td>
            <td width="10">'.$regProLib['prol_ph'] .'</td>
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
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width="678" border="1">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td colspan="3" style="font-size: small; ">Cocido a las 8 a 12 horas de la 2da acidificación</td>
          </tr>
          <tr>
            <td>Agua ph (1.3-1.7) Buscar rango inferior</td>
            <td>Cocido ph(1.7-2.1) buscar rango inferior</td>
            <td>&nbsp;</td>
          </tr>
          <tr style="font-weight: bold;background: #e6e6e6">
            <td style="font-size: small; text-align: center;">Ph</td>
            <td style="font-size: small; text-align: center;">Ph</td>
            <td style="font-size: small; text-align: center;">Hora inicio cocido</td>
          </tr>
          <tr>
            <td width="270">'.fnc_formato_val($regfg['pfg7_agua_ph']).'</td>
            <td width="253">'.fnc_formato_val($regfg['pfg7_cocido_ph']).'</td>
            <td width="133">'.$regfg['pfg7_hr_ini_co'].'</td>
          </tr>
        </table></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td rowspan="3"><table border="1" width="824">
          <tr style="font-weight: bold;">
            <td width="232" style="background: #e6e6e6">Fecha termina mov. y reposo</td>
            <td width="153" style="background: #e6e6e6">Hora termina </td>
            <td width="158" style="background: #e6e6e6">Agua</td>
            <td width="158" style="background: #e6e6e6">Usuario</td>
            <td width="159" rowspan="2" style="background: #e6e6e6">'.fnc_hora_de(18).' a '.fnc_hora_a(18).' horas)</td>
         
          </tr>
          <tr>
            <td>'.fnc_formato_fecha($regProAux['proa_fe_fin']).'</td>
            <td>'.$regProAux['proa_hr_fin'].'</td>
            <td>'.$regtaa['taa_descripcion'].'</td>
            <td>'.fnc_nom_usu($usu_aux).'</td>
            <tr>
              <td colspan="5" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
            </tr>
            <tr>
              <td colspan="5">'.$regProAux['proa_observaciones'].'</td>
            </tr>
            <tr>
              <td colspan="5">El agua de este proceso se manda a depositos de agua acida</td>
            </tr>
        </table></td>
         <td>&nbsp;</td>
    </tr>
       <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
    <table>
    <tr>
    <td>
    </td>
    </tr>
    </table>
';
echo $tbHtml7b;
?>

