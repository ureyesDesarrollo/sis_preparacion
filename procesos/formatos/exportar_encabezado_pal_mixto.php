<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
  <?php 

/*include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
include "../funciones_procesos.php";*/
$cnx =  Conectarse();

//$Lav_id = 1;

$sqlPal = mysqli_query($cnx, "SELECT p.pp_id, p.pt_id FROM procesos_paletos as p INNER JOIN procesos_paletos_d as d ON (p.prop_id = d.prop_id) WHERE d.pro_id = '$idx_pro'") or die(mysql_error()."Error: en consultar el proceso");
$regPal = mysqli_fetch_assoc($sqlPal);

$sqlNomPal = mysqli_query($cnx, "SELECT pp_descripcion FROM preparacion_paletos  WHERE pp_id= '$regPal[pp_id]'") or die(mysql_error()."Error: en consultar el proceso");
$regNomPal = mysqli_fetch_assoc($sqlNomPal);

$sqlPreTipo = mysqli_query($cnx, "SELECT pt_descripcion FROM preparacion_tipo where pt_id ='$regPal[pt_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
$regPreTip= mysqli_fetch_assoc($sqlPreTipo);

$cadena = mysqli_query($cnx, "SELECT * FROM procesos_paletos_d WHERE prop_id = '$idx_prop' ") or die(mysql_error()."Error: en consultar el procesos");
$registros = mysqli_fetch_assoc($cadena);

$txtSup = fnc_nom_usu($regG['pro_supervisor']);
$txtOpe = fnc_nom_usu($regG['pro_operador']);

$encabezado = "";
  if($idx_pro != ''){

$encabezado.= '<table width="1140" height="200" border="0" style="position:inherit">
    <tr>
      <td colspan="3">
        <table width="730" border="1" >
          <tr style="background: #e6e6e6;font-weight: bold;">
            <td width="119">S-Proceso</td>
            <td width="119">Paleto</td>
            <td width="567">Tipo de preparación</td>
            <!--<td>Operador</td>
            <td>Supervisor</td>-->
          </tr>
          <tr>
            <td align="center">'.$idx_prop .'</td>
            <td align="center">'.$regNomPal['pp_descripcion'] .'</td>
            <td>'.$regPreTip['pt_descripcion'] .'</td>
            <!--<td>'.$txtOpe.'</td>
            <td>'.$txtSup.'</td>-->
          </tr>
        </table>      </td>
    </tr>
    <tr>
      <td width="557">
        <table width="556" border="1" style="height:100%;margin-top:10px">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="25%" style="font-size: small; text-align: center;"><p>Procesos</p>
            </td>
            <td width="25%" style="font-size: small; text-align: center;">Lavador</td>
            <td width="25%" style="font-size: small; text-align: center;">Tipo de material</td>
            <td width="34%" style="font-size: small; text-align: center;">Toneladas - kg</td>
          </tr>';
          do{
      
      $sqlG = mysqli_query($cnx, "SELECT * FROM procesos WHERE pro_id = '$registros[pro_id]'") or die(mysql_error()."Error: en consultar el proceso");
     $regG = mysqli_fetch_assoc($sqlG);

      $sqlProMat = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, pm.pma_fe_entrada 
            FROM materiales as m 
            INNER JOIN procesos_materiales as pm on(m.mat_id=pm.mat_id) 
            WHERE pm.pro_id = '$registros[pro_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regProMat= mysqli_fetch_assoc($sqlProMat);
            
          $encabezado.= '<tr>
              <td style="font-size: small" align="center"><a href="formatos/bitacora_consulta.php?idx_pro='.$registros['pro_id'].'" target="_blank"><!--<img src="../iconos/buscar.png" alt="Consulta"> Consulta -->'.$registros['pro_id'].'</a></td>
              <td style="font-size: small" align="center">'.$regG['pl_id'] .'</td>
              <td style="font-size: small">'.$regProMat['mat_nombre'] .'</td>
              <td style="font-size: small">'.$regG['pro_total_kg'] .'</td>
            </tr>';
          } while($registros = mysqli_fetch_assoc($cadena));
      $encabezado.= '</table>      </td>
    </tr>
    </table>';
     }else{
   $encabezado.= ' <div style="height: 40px;width: 350px;text-align: left;z-index: 10;margin-top:10px;float: right;margin-bottom: 0px;background: #e6e6;border-radius: 5px;padding: 10px;color: #BD223E;font-weight: bold;font-style: italic;float: left;">
    No se ha capturado ningun tipo de preparación
    </div>';
     }
  
    ?>

  </body>
  </html>