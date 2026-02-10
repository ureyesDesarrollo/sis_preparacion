<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST); 

$sqlf1g = mysqli_query($cnx, "SELECT * FROM procesos_fase_1_g as pf1g INNER JOIN procesos as p  on(pf1g.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro' and pf1g.pe_id = 1") or die(mysql_error()."Error: en consultar procesos_fase_1_g ");
$regf1g= mysqli_fetch_assoc($sqlf1g);

$sqlf2g = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_g WHERE pro_id = '$regf1g[pro_id]'") or die(mysql_error()."Error: en consultar procesos_fase_2_g ");
$regf2g= mysqli_fetch_assoc($sqlf2g);

$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regf1g[pro_id]' AND pe_id='1'") or die(mysql_error()."Error: en consultar procesos auxiliar 1");
$regProAux = mysqli_fetch_assoc($sqlProAux);

$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion  WHERE pro_id = '$regf1g[pro_id]' AND pe_id='1'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

$tbHtml1 = "";

$tbHtml1.= '

 <table width="770"  style="background:#F5F4F4">
      <tr>
        <td><table width="1564" border="1" style="background: #FCEFF2;font-size: 12px">
          <tr>
            <td width="1483" height="45" colspan="10"> Lavados inicales. Este proceso se puede hacer con aguar recuperada limpia (pila 1).
            Lavados finales de paleto a paleto y en el ultimo lavado utilizar agua limpia si se necesita bajar CE</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width="1572" border="1">
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Fecha inicia lavados</td>
            <td style="font-weight: bold;background: #e6e6e6">Hora inicio</td>
            <td style="font-weight: bold;background: #e6e6e6">Temp agua utilizada</td>
            <td width="15%" style="border-top: 1px solid#fff;border-bottom: 1px solid#fff;"></td>
            <td rowspan="2" style="background: #e6e6e6;font-weight: bold;">Bajar CE A 3.0</td>
          </tr>
          <tr>
            <td>'.fnc_formato_fecha($regProAux['proa_fe_ini']).'</td>
            <td>'.$regProAux['proa_hr_ini'].'</td>
            <td>'.$regf1g['pfg1_temp_ag'] .'</td>
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
            <td  style="font-size: small; text-align: center;">Lav</td>
            <td style="font-size: small; text-align: center;">Lav tipo agua</td>
            <td style="font-size: small; text-align: center;">TEMP</td>
            <td  style="font-size: small; text-align: center;">Hra Ini. lldo</td>
            <td  style="font-size: small; text-align: center;">Hra term. lav</td>
            <td style="font-size: small; text-align: center;">Hra ini. mov</td>
            <td  style="font-size: small;">Hra term. mov</td>
            <td style="font-size: small; text-align: center;">Ph</td>
            <td style="font-size: small; text-align: center;">Ce</td>
            <td style="font-size: small; text-align: center;">Agua a</td>
            <td style="font-size: small; text-align: center;">Observaciones</td>
            <td style="font-size: small; text-align: center;">Capturo</td>
          </tr>';
          
           $sqlf1d = mysqli_query($cnx, "SELECT * FROM procesos_fase_1_d as pf1d  inner join tipos_agua as ta on(pf1d.tpa_id= ta.tpa_id) WHERE pf1d.pfg1_id = '$regf1g[pfg1_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regf1d= mysqli_fetch_assoc($sqlf1d);

do{
  $agua_a = mysqli_query($cnx, "SELECT * FROM tipos_agua_a WHERE taa_id = '$regf1d[taa_id]'") or die(mysql_error()."Error: en consultar el tipo de agua a");
          $reg_aa= mysqli_fetch_assoc($agua_a);

           $tbHtml1.= ' <tr>
             <td>'.$regf1d['pfd1_ren'].'</td>
             <td>'.$regf1d['tpa_descripcion'].'</td>
             <td>'.$regf1d['pfd1_temp'].'</td>
             <td>'.$regf1d['pfd1_hr_ini'].'</td>
             <td>'.$regf1d['pfd1_hr_fin'].'</td>
             <td>'.$regf1d['pfd1_hr_ini_mov'].'</td>
             <td>'.$regf1d['pfd1_hr_fin_mov'].'</td>
             <td>'.$regf1d['pfd1_ph'].'</td>
             <td>'.$regf1d['pfd1_ce'].'</td>
             <td>'.$reg_aa['taa_descripcion'].'</td>
             <td>'.$regf1d['pfd1_observaciones'].'</td>
             <td>'.$regf1d['usu_nombre'].'</td>
           </tr>';
          } while($regf1d= mysqli_fetch_assoc($sqlf1d));
       $tbHtml1.= '</table></td>
     </tr>
     <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table border="1" width="1420">';
       $usu_aux = $regProAux['usu_sup'];
        $usu_proLib = $regProLib['usu_id'];

       $tbHtml1.= '<tr style="font-weight: bold;">
          <td style="background: #e6e6e6">Fecha termina</td>
          <td style="background: #e6e6e6">Hora termina</td>
          <td style="background: #e6e6e6">Usuario</td>
          <td rowspan="5" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td  style="border:1px solid#fff;background: #e6e6e6">'.fnc_hora_de(1).' a '.fnc_hora_a(1).'</td>
          <td rowspan="5" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td rowspan="5"  style="font-size:50px">1</td>
          <td colspan="2" style="background: #e6e6e6;font-weight: bold;">Liberacion CE a 3MAX</td>
        </tr>
        <tr>
          <td>'. fnc_formato_fecha($regProAux['proa_fe_fin']).'</td>
          <td>'. $regProAux['proa_hr_fin'].'</td>
          <td>'.fnc_nom_usu($usu_aux).'</td>
          <td style="border:1px solid#fff"></td>
          <td>CE de liberacion</td>
          <td>'. $regProLib['prol_ce'] .'</td>
        </tr>
        <tr>
          <td colspan="3"></td>
          <td style="border:1px solid#fff"></td>
          <td>Horas totales </td>
          <td>'. $regProLib['prol_hr_totales'] .'</td>
        </tr>
        <tr>
          <td colspan="3" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          <td style="border:1px solid#fff"></td>
          <td>Nombre LCP </td>
          <td>'.fnc_nom_usu($usu_proLib).'</td>
        </tr>
        <tr>
          <td colspan="3">'.
          $regProAux['proa_observaciones'].'</td>
          <td style="border:1px solid#fff"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
</div>
  <table>
    <tr><td></td></tr>
  </table>
<?php';

echo $tbHtml1;
?>