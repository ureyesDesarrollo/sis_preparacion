<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 

$sqlfg = mysqli_query($cnx, "SELECT * FROM procesos_fase_5_g as pfg INNER JOIN procesos as p  on(pfg.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro' AND pfg.pe_id = 10") or die(mysql_error()."Error: en consultar procesos_fase_5_g ");
$regfg= mysqli_fetch_assoc($sqlfg);

if ($regfg['tpa_id'] != '') {
$sqlTagua = mysqli_query($cnx, "SELECT * FROM tipos_agua  WHERE tpa_id= $regfg[tpa_id]") or die(mysql_error()."Error: en consultar procesos_fase_5_g ");
$regTagua= mysqli_fetch_assoc($sqlTagua);
}

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '10'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regfg[pro_id]'  AND pe_id = '10'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

$tbHtml5 = "";

$tbHtml5.= '
 <table width="100%" style="background:#F5F4F4">
    <tr>
      <td width="61%" ><table border="1" style="background: #FCEFF2;font-size: 12px;width: 100%">
        <tr>
          <td height="45" colspan="8">LAVADOS 1er ACIDO. Este proceso se puede hacer con agua limpia. El agua de este proceso se manda a agua recuperada semilimpia(PILA 1)
         </td>.
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
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">
        <table width="830" border="1" style="margin-top: 10px">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="5%" style="font-size: small; text-align: center;">Lav</td>
            <td width="18%" style="font-size: small; text-align: center;">Hora ini llenado</td>
            <td width="20%" style="font-size: small; text-align: center;">Hora term llenado</td>
            <td width="15%" style="font-size: small; text-align: center;">Hora ini mov</td>
            <td width="15%" style="font-size: small; text-align: center;">Hora ter mov</td>
            <td width="5%" style="font-size: small; text-align: center;">Ce</td>
            <td width="5%" style="font-size: small; text-align: center;">Ph</td>
            <td width="5%" style="font-size: small; text-align: center;">Temp</td>
               <td width="5%" style="font-size: small; text-align: center;">Observaciones</td>
            <td width="20%" style="font-size: small; text-align: center;">Capturo</td>

          </tr>';

          $sqlfd = mysqli_query($cnx, "SELECT * FROM procesos_fase_5_d  WHERE pfg5_id = '$regfg[pfg5_id]'") or die(mysql_error()."Error: en consultar procesos_fase_5_d");
          $regfd= mysqli_fetch_assoc($sqlfd);

          do{


$tbHtml5.= '
            <tr>
              <td>'.$regfd['pfd5_ren'].'</td>
              <td>'.$regfd['pfd5_hr_ini'].'</td>
              <td>'.$regfd['pfd5_hr_fin'].'</td>
              <td>'.$regfd['pfd5_hr_ini_mov'].'</td>
              <td>'.$regfd['pfd5_hr_fin_mov'].'</td>
              <td>'.$regfd['pfd5_ce'].'</td>
              <td>'.$regfd['pfd5_ph'].'</td>
              <td>'.$regfd['pfd5_temp'].'</td>
              <td>'.$regf1d['pfd5_observaciones'].'</td>
              <td>'.fnc_nom_usu($regfd['usu_id']).'</td>
            </tr>';
          } while($regfd= mysqli_fetch_assoc($sqlfd));
$tbHtml5.= '        </table></td>
    </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td><table width="1205" border="1">
          <tr style="font-weight: bold;">
            <td width="240" style="background: #e6e6e6">Fecha termina</td>
            <td width="170" style="background: #e6e6e6">Hora termina </td>
            <td width="202" style="background: #e6e6e6">Usuario</td>
            <td width="190" rowspan="2" style="background: #e6e6e6">('.fnc_hora_de(10).' a '.fnc_hora_a(10).' horas)</td>
            <td width="190" rowspan="4" style="background: #e6e6e6"><p>';
       $usu_aux = $regProAux['usu_sup'];
       $usu_proLib = $regProLib['usu_id'];
$tbHtml5.= '</p>
              <table width="280" border="1">
              <tr>
                <td width="75" rowspan="5" style="font-size:50px">5</td>
                <td colspan="2" style="background: #e6e6e6;font-weight: bold;">LIBERACION CE A '.fnc_rango_de(10).' a '.fnc_rango_a(10).' hora) MAX</td>
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
         </table></td>
         <td width="39%">&nbsp;</td>
    </tr>
    </table>
    <table>
    <tr><td></td></tr>
</table>
<?php';
echo $tbHtml5;
?>

