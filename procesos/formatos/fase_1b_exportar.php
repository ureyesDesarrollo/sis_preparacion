<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/ 
//include "../../conexion/conexion.php";
//include "../../funciones/funciones.php";
$cnx =  Conectarse();

//extract($_POST); 

$id_l = $_GET['id_l'];
//$sqlf1g = mysqli_query($cnx, "SELECT * FROM procesos_fase_1_g as pf1g INNER JOIN procesos as p on(pf1g.pro_id=p.pro_id) AND p.pl_id='1' AND p.pro_estatus='1'") or die(mysql_error()."Error: en consultar procesos_fase_1_g ");

$sqlf1g = mysqli_query($cnx, "SELECT * FROM procesos_fase_1_g as pf1g INNER JOIN procesos as p  on(pf1g.pro_id=p.pro_id) WHERE p.pro_id  = '$idx_pro' AND pf1g.pe_id = 22") or die(mysql_error()."Error: en consultar procesos_fase_1_g ");
$regf1g= mysqli_fetch_assoc($sqlf1g);


//$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar as pa INNER JOIN usuarios as u on(pa.usu_sup=u.usu_id) WHERE pro_id = '$regf1g[pro_id]'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$sqlProAux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$regf1g[pro_id]' AND pe_id = '22'") or die(mysql_error()."Error: en consultar procesos auxiliar");
$regProAux = mysqli_fetch_assoc($sqlProAux);


//$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion as prolib INNER JOIN usuarios as u on(prolib.usu_id=u.usu_id) WHERE pro_id = '$regf1g[pro_id]'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$sqlProLib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$regf1g[pro_id]' AND pe_id = '22'") or die(mysql_error()."Error: en consultar procesos de liberacion");
$regProLib = mysqli_fetch_assoc($sqlProLib);

$tbHtml1b = "";

$tbHtml1b.= '
 <table width="770" style="margin:20px 0px 20px 0px;background:#F5F4F4">
      <tr>
        <td><table width="771" border="1"  style="background: #FCEFF2;font-size: 12px">
          <tr>
            <td height="45" colspan="10"> Lavados inicales. Nota: Si se junta agua recuperada con baja conductividad (Ce) con un cuero muy sucio, agregar más ácido limpio por cada lavado (20 -30 lts)</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="770" border="1">
          <tr>
            <td width="25%" style="font-weight: bold;background: #e6e6e6">Fecha inicia lavados</td>
            <td width="19%" style="font-weight: bold;background: #e6e6e6">Hora inicio</td>
            <td width="27%" style="font-weight: bold;background: #e6e6e6">Ph agua inicio</td>
            <td width="27%" style="font-weight: bold;background: #e6e6e6">Ce</td>
            <td width="15%" style="border-top: 1px solid#fff;border-bottom: 1px solid#fff;"></td>
            <td width="14%" rowspan="2" style="background: #e6e6e6;font-weight: bold;">Bajar CE A 3.0</td>
          </tr>
          <tr>
            <td>'.fnc_formato_fecha($regProAux['proa_fe_ini']).'</td>
            <td>'.$regProAux['proa_hr_ini'].'</td>
            <td>'.$regf1g['pfg1_ph_agua'] .'</td>
            <td>'.$regf1g['pfg1_ce_agua'] .'</td>
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
            <td width="12%" style="font-size: small; text-align: center;">Lav</td>
            <td width="12%" style="font-size: small; text-align: center;">Lav tipo agua</td>
            <td width="12%" style="font-size: small; text-align: center;">TEMP</td>
            <td width="12%" style="font-size: small; text-align: center;">Hra Ini. lldo</td>
            <td width="12%" style="font-size: small; text-align: center;">Hra term. lav</td>
            <td width="15%" style="font-size: small; text-align: center;">Hra ini. mov</td>
            <td width="20%" style="font-size: small; text-align: center;">Hra term. mov</td>
            <td width="9%"  style="font-size: small; text-align: center;">Ph</td>
            <td width="8%"  style="font-size: small; text-align: center;">Ce</td>
            <td style="font-size: small; text-align: center;">Agua a</td>
             <td width="8%"  style="font-size: small; text-align: center;">Observaciones</td>
            <td width="8%"  style="font-size: small; text-align: center;">Capturo</td>
          </tr>';
        
           $sqlf1d = mysqli_query($cnx, "SELECT * FROM procesos_fase_1_d as pf1d  inner join tipos_agua as ta on(pf1d.tpa_id= ta.tpa_id) WHERE pf1d.pfg1_id = '$regf1g[pfg1_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regf1d= mysqli_fetch_assoc($sqlf1d);

do{

         $agua_a = mysqli_query($cnx, "SELECT * FROM tipos_agua_a WHERE taa_id = '$regf1d[taa_id]'") or die(mysql_error()."Error: en consultar el tipo de agua a");
          $reg_aa= mysqli_fetch_assoc($agua_a);
       
           $tbHtml1b.= ' <tr>
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
       $tbHtml1b.= '</table></td>
     </tr>
     <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table border="1" width="1420">';
       $usu_aux = $regProAux['usu_sup'];
        $usu_proLib = $regProLib['usu_id'];

       $tbHtml1b.= '<tr style="font-weight: bold;">
          <td style="background: #e6e6e6">Fecha termina</td>
          <td style="background: #e6e6e6">Hora termina</td>
          <td style="background: #e6e6e6">Usuario</td>
          <td rowspan="5" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td  style="border:1px solid#fff;background: #e6e6e6">'.fnc_hora_de(10).' a '.fnc_hora_a(10).'</td>
          <td width="7%" rowspan="4" style="border-bottom:1px solid#fff;border-top:1px solid#fff"></td>
          <td width="30" rowspan="4" style="font-size:50px">1b</td>
          <td colspan="2" style="background: #e6e6e6;font-weight: bold;">Liberacion CE a 3MAX</td>
        </tr>
        <tr>
          <td>'. fnc_formato_fecha($regProAux['proa_fe_fin']).'</td>
          <td>'. $regProAux['proa_hr_fin'].'</td>
          <td>'.fnc_nom_usu($usu_aux).'</td>
          <td style="border:1px solid#fff"></td>
          <td>CE de liberacion</td>
          <td width="15%">'. $regProLib['prol_ce'] .'</td>
        </tr>
        <tr>
          <td colspan="3" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
          <td style="border:1px solid#fff"></td>
          <td>Horas totales </td>
          <td width="15%">'. $regProLib['prol_hr_totales'] .'</td>
        </tr>
        <tr>
          <td colspan="3">'.$regProAux['proa_observaciones'].'</td>
          <td style="border:1px solid#fff"></td>
          <td>Nombre LCP </td>
          <td>'.fnc_nom_usu($usu_proLib).'</td>
        </tr>
      </table></td>
    </tr>
  </table>
</div>

  <table>
    <tr><td></td></tr>
</table>
<?php';

echo $tbHtml1b;
 ?>

