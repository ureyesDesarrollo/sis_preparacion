<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
//include "../../funciones/funciones_procesos.php";
$cnx =  Conectarse();


extract($_POST); 

$sqlf2g = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_g as pf2g INNER JOIN procesos as p  on(pf2g.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysql_error()."Error: en consultar procesos_fase_2c_g ");
$regf2g= mysqli_fetch_assoc($sqlf2g);

$sqlProAux2 = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$idx_pro' AND pe_id = '4'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux2 = mysqli_fetch_assoc($sqlProAux2);


$sqlProLib2 = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$idx_pro'  AND pe_id = '4'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib2 = mysqli_fetch_assoc($sqlProLib2);



$tbHtml2c = "";

$tbHtml2c.= '
 <table width="100%" style="margin:20px 0px 20px 0px;background:#F5F4F4">
      <tr>
        <td><table width="1026" border="1" style="background: #FCEFF2;font-size: 12px">
          <tr>
            <td height="45" colspan="10" style="background: #FCEFF2;font-size: 12px;width: 100%">BLANQUEO. Este proceso se puede hacer con aguar recuperada limpia (pila 1)</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="1">
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Fecha inicia lavados</td>
            <td >'.fnc_formato_fecha($regProAux2['proa_fe_ini']).'</td>
            <td style="font-weight: bold;background: #e6e6e6">Hora inicio</td>
            <td>'.$regProAux2['proa_hr_ini'].'</td>
            <td style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
            <td>'.$regf2g['pfg2_temp_ag'] .'</td>
            <td style="font-weight: bold;background: #e6e6e6"><span>Ph antes de  ajuste</span></td>
            <td>'.$regf2g['pfg2_ph_ant'] .'</td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Ce</td>
            <td>'.$regf2g['pfg2_ce'] .'</td>
            <td style="font-weight: bold;background: #e6e6e6">Ajuste con sosa</td>
            <td>'.$regf2g['pfg2_sosa'] .'</td>
            <td style="font-weight: bold;background: #e6e6e6">Ph ajustado</td>
            <td>'.$regf2g['pfg2_ph_aju'] .'</td>
            <td style="font-weight: bold;background: #e6e6e6">Peróxido</td>
            <td>'.$regf2g['pfg2_peroxido'] .'</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width="100%" border="1">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td style="font-size: small; text-align: center;">No.</td>
            <td style="font-size: small; text-align: center;">Hora</td>
            <td style="font-size: small; text-align: center;">Ph</td>
            <td style="font-size: small; text-align: center;">Sosa</td>
            <td style="font-size: small; text-align: center;">Ácido</td>
            <td style="font-size: small; text-align: center;">Peróxido</td>
            <td style="font-size: small; text-align: center;">Temp</td>
            <td style="font-size: small; text-align: center;">Redox</td>
            <td style="font-size: small; text-align: center;">Capturo</td>
          </tr>';
         
           $sqlf2d = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_d  WHERE pfg2_id = '$regf2g[pfg2_id]'") or die(mysql_error()."Error: en consultar el tipo de material 3");
          $regf2d= mysqli_fetch_assoc($sqlf2d);

          $txtOpe = fnc_nom_usu($regf2d['usu_id']);
do{

         
            
  $tbHtml2c.= '<tr>
             <td>'.$regf2d['pfd2_ren'].'</td>
             <td>'.$regf2d['pfd2_hr'].'</td>
             <td>'.$regf2d['pfd2_ph'].'</td>
             <td>'.fnc_formato_val($regf2d['pfd2_sosa']).'</td>
             <td>'.fnc_formato_val($regf2d['pfd2_acido']).'</td>
             <td>'.$regf2d['pfd2_peroxido'].'</td>
             <td>'.$regf2d['pfd2_temp'].'</td>
             <td>'.fnc_formato_val($regf2d['pfd2_redox']).'</td>
             <td>'.$txtOpe.'</td>
           </tr>';
           } while($regf2d= mysqli_fetch_assoc($sqlf2d));
  $tbHtml2c.= '</table></td>
     </tr>
     <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table border="1">';

        $usu_aux = $regProAux2['usu_sup'];
        $usu_proLib = $regProLib2['usu_id'];
         
  $tbHtml2c.= '<tr style="font-weight: bold;">
          <td style="background: #e6e6e6">Fecha termina</td>
          <td  style="background: #e6e6e6">Hora termina</td>
          <td  style="background: #e6e6e6">Usuario</td>
          <td  rowspan="2"  style="border:1px solid#fff;background: #e6e6e6">'.fnc_hora_de(4).' a '.fnc_hora_a(4).'</td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td rowspan="5" style="font-size:50px">2c</td>
          <td colspan="3" style="background: #e6e6e6;font-weight: bold;">LIBERACION pH '. fnc_hora_de(4).'  a '. fnc_hora_a(4).' </td>
        </tr>
        <tr>
          <td>'.fnc_formato_fecha($regProAux2['proa_fe_fin']).'</td>
          <td>'.$regProAux2['proa_hr_fin'].'</td>
          <td>'.fnc_nom_usu($usu_aux).'</td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td>Color</td>
          <td colspan="2">'.$regProLib2['prol_color'].'</td>
        </tr>
        <tr>
          <td colspan="4"></td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td>Ph de liberación</td>
          <td colspan="2">'.$regProLib2['prol_ph'].'</td>
        </tr>
        <tr>
          <td colspan="4" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td>Horas totales </td>
          <td colspan="2">'.$regProLib2['prol_hr_totales'].'</td>
        </tr>
        <tr>
          <td colspan="4">'.$regProAux2['proa_observaciones'].'</td>
          <td style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td>Nombre LCP </td>
          <td colspan="2">'.fnc_nom_usu($usu_proLib).'</td>
        </tr>
      </table></td>
    </tr>
  </table>
  <table>
  <tr>
      <td>&nbsp;</td>
    </tr>
</table>
<?php';

  echo $tbHtml2c;
 ?>

