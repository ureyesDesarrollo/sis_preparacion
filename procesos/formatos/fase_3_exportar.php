<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();


extract($_POST); 

 $sqlf3d = mysqli_query($cnx, "SELECT * FROM procesos_fase_3_d as pf3d INNER JOIN procesos as p  on(pf3d.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro'") or die(mysqli_error()."Error:en consultar procesos_fase_2c_d");
          $regf3d= mysqli_fetch_assoc($sqlf3d);

$sqlf3 = mysqli_query($cnx, "SELECT * FROM procesos_fase_3_g WHERE pro_id  = '$idx_pro'") or die(mysqli_error()."Error:en consultar procesos_fase_3g");
          $regf3= mysqli_fetch_assoc($sqlf3);

$sqlProAux3 = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar  WHERE pro_id = '$idx_pro'  AND pe_id = '5'") or die(mysqli_error()."Error: en consultar procesos auxiliar");
$regProAux3 = mysqli_fetch_assoc($sqlProAux3);


$sqlProLib3 = mysqli_query($cnx, "SELECT * FROM procesos_liberacion  WHERE pro_id = '$idx_pro'  AND pe_id = '5'") or die(mysqli_error()."Error: en consultar procesos de liberacion");
$regProLib3 = mysqli_fetch_assoc($sqlProLib3);

$usu_auxx = fnc_nom_usu ($regProAux3['usu_op']);

$tbHtml3 = "";

$tbHtml3.= '
 <table width="770" style="background:#F5F4F4">
      <tr>
        <td><table width="1468" border="1" style="background: #FCEFF2;font-size: 12px">
          <tr>
            <td height="45" colspan="10">
LAVADOS DE BLANQUEO Este proceso se puede hacer con aguar recuperada limpia (pila 1)Lavados finales, 1er ACIDO, PALETO A PALETO</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="770" border="1">
            <tr>
            <td style="font-weight: bold;background: #e6e6e6">Fecha inicia lavados</td>
            <td style="font-weight: bold;background: #e6e6e6">Hora inicio</td>
            <td style="font-weight: bold;background: #e6e6e6">Enzima liquida</td>
            <td style="font-weight: bold;background: #e6e6e6">Operador</td>
            <td style="border-top: 1px solid#fff;border-bottom: 1px solid#fff;"></td>
            <td rowspan="2" style="background: #e6e6e6;font-weight: bold;">Bajar CE A 3.0</td>
          </tr>
          <tr>
            <td>'.fnc_formato_fecha($regProAux3['proa_fe_ini']).'</td>
            <td>'.$regProAux3['proa_hr_ini'].'</td>
            <td>'.$regf3['pfg3_enzima'].'</td>
             <td>'.$usu_auxx.'</td>
            <td style="border-top: 1px solid#fff;border-bottom: 1px solid#fff;border-right: 1px solid#fff;font-weight: bold;"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width="770" border="1">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td style="font-size: small; text-align: center;">Lav</td>
            <td style="font-size: small; text-align: center;">tipo agua</td>
            <td style="font-size: small; text-align: center;">TEMP</td>
            <td style="font-size: small; text-align: center;">Hra Ini. lldo</td>
            <td style="font-size: small; text-align: center;">Hra term. lav</td>
            <td style="font-size: small; text-align: center;">Hra ini. mov</td>
            <td style="font-size: small; text-align: center;">Hra term. mov</td>
            <td  style="font-size: small; text-align: center;">Ph</td>
            <td style="font-size: small; text-align: center;">Ce</td>
            <td style="font-size: small; text-align: center;">PPM</td>
            <td style="font-size: small; text-align: center;">Agua a</td>
            <td style="font-size: small; text-align: center;">Observaciones</td>
            <td style="font-size: small; text-align: center;">Capturo</td>
          </tr>';
        

          $txtOpe = fnc_nom_usu($regf3d['usu_id']);
do{
 $tagua = mysqli_query($cnx, "SELECT * FROM tipos_agua  WHERE tpa_id  = '$regf3d[tpa_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regtagua= mysqli_fetch_assoc($tagua);


          $agua_a = mysqli_query($cnx, "SELECT * FROM tipos_agua_a WHERE taa_id = '$regf3d[taa_id]'") or die(mysqli_error()."Error: en consultar el tipo de agua a");
          $reg_aa= mysqli_fetch_assoc($agua_a);
       
           $tbHtml3.= ' <tr>
             <td>'.$regf3d['pfd3_ren'].'</td>
             <td>'.$regtagua['tpa_descripcion'].'</td>
             <td>'.$regf3d['pfd3_temp'].'</td>
             <td>'.$regf3d['pfd3_hr_ini'].'</td>
             <td>'.$regf3d['pfd3_hr_fin'].'</td>
             <td>'.$regf3d['pfd3_hr_ini_mov'].'</td>
             <td>'.$regf3d['pfd3_hr_fin_mov'].'</td>
             <td>'.$regf3d['pfd3_ph'].'</td>
             <td>'.$regf3d['pfd3_ce'].'</td>
             <td>'.$regf3d['pfd3_ppm'].'</td>
             <td>'.$reg_aa['taa_descripcion'].'</td>
             <td>'.$regf3d['pfd3_observaciones'].'</td>
             <td>'.$txtOpe.'</td>
           </tr>';
          } while($regf3d= mysqli_fetch_assoc($sqlf3d));
       $tbHtml3.= '</table></td>
     </tr>
     <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table border="1" width="1420">';
      $usu_aux = $regProAux3['usu_sup'];
      $usu_proLib = $regProLib3['usu_id'];
       $tbHtml3.= '<tr style="font-weight: bold;">
          <td style="background: #e6e6e6">Fecha termina</td>
          <td style="background: #e6e6e6">Hora termina</td>
          <td style="background: #e6e6e6">Usuario</td>
          <td rowspan="5" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td  style="border:1px solid#fff;background: #e6e6e6">'.fnc_hora_de(5).' a '.fnc_hora_a(5).'</td>
          <td width="7%" rowspan="5" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td rowspan="5" width="30" style="font-size:50px">3</td>
          <td colspan="2" style="background: #e6e6e6;font-weight: bold;">Liberacion CE 3MAX</td>
        </tr>
        <tr>
          <td>'.fnc_formato_fecha($regProAux3['proa_fe_fin']).'</td>
          <td>'. $regProAux3['proa_hr_fin'].'</td>
          <td>'.fnc_nom_usu($usu_aux).'</td>
          <td style="border:1px solid#fff"></td>
          <td>CE de liberacion</td>
          <td>'. $regProLib3['prol_ce'] .'</td>
        </tr>
        <tr>
          <td colspan="3"></td>
          <td style="border:1px solid#fff"></td>
          <td>Horas totales </td>
          <td>'. $regProLib3['prol_hr_totales'] .'</td>
        </tr>
        <tr>
          <td colspan="3" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          <td style="border:1px solid#fff"></td>
          <td>Nombre LCP </td>
          <td>'.fnc_nom_usu($usu_proLib).'</td>
        </tr>
        <tr>
          <td colspan="3">'.      $regProAux3['proa_observaciones'].'</td>
          <td style="border:1px solid#fff"></td>
        </tr>
      </table></td>
    </tr>
</table>
</div>
  <table>
    <tr><td></td></tr>
  </table>';
  echo $tbHtml3;
 ?>

