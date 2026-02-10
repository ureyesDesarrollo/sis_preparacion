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

$sqlG = mysqli_query($cnx, "SELECT * FROM procesos WHERE pro_id = '$idx_pro'") or die(mysql_error()."Error: en consultar el proceso");
$regG = mysqli_fetch_assoc($sqlG);


/*$sqlPal = mysqli_query($cnx, "SELECT p.pp_id FROM procesos_paletos as p INNER JOIN procesos_paletos_d as d ON (p.prop_id = d.prop_id) WHERE d.pro_id = '$idx_pro'") or die(mysql_error()."Error: en consultar el proceso");
$regPal = mysqli_fetch_assoc($sqlPal);*/

$sqlPal = mysqli_query($cnx, "SELECT p.pp_id, p.pt_id, p.prop_id FROM procesos_paletos as p INNER JOIN procesos_paletos_d as d ON (p.prop_id = d.prop_id) WHERE d.pro_id = '$idx_pro'") or die(mysql_error()."Error: en consultar el proceso");
$regPal = mysqli_fetch_assoc($sqlPal);

/*$sqlNomPal = mysqli_query($cnx, "SELECT pp_descripcion FROM preparacion_paletos  WHERE pp_id= '$regPal[pp_id]'") or die(mysql_error()."Error: en consultar el proceso");
$regNomPal = mysqli_fetch_assoc($sqlNomPal);*/

$sqlNomPal = mysqli_query($cnx, "SELECT pp_id,pp_descripcion FROM preparacion_paletos  WHERE pp_id= '$regPal[pp_id]'") or die(mysql_error()."Error: en consultar el proceso");
$regNomPal = mysqli_fetch_assoc($sqlNomPal);


$sqlPreTipo = mysqli_query($cnx, "SELECT pt_descripcion FROM preparacion_tipo where pt_id ='$regG[pt_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
$regPreTip= mysqli_fetch_assoc($sqlPreTipo);

$cadena = mysqli_query($cnx, "SELECT * FROM procesos_paletos_d WHERE prop_id = '$idx_prop' ") or die(mysql_error()."Error: en consultar el procesos");
$registros = mysqli_fetch_assoc($cadena);

$txtSup = fnc_nom_usu($regG['pro_supervisor']);
$txtOpe = fnc_nom_usu($regG['pro_operador']);

$encabezado = "";
if($regG['pro_id'] != ''){
$encabezado.= '
  <table width="1140" height="200" border="0" style="position:inherit">
  <tr>
  <td colspan="3">
  <table width="730" border="1" >
  <tr style="background: #e6e6e6;font-weight: bold;">
  <td width="119">Proceso</td>
  <td width="119">Paleto</td>
  <td width="567">Tipo de preparación</td>
  <td>Operador</td>
  <td>Supervisor</td>
  </tr>';

  if ($regNomPal['pp_id'] == 1 or $regNomPal['pp_id'] == 2) { $doble =  $regG['pro_id'].' - '.$regPal['prop_id']; } else{  $uno = $regG['pro_id'];}

  $encabezado.= '<tr>
  <td>'.$doble.$uno.'</td>

  <td>'. $regNomPal['pp_descripcion'].'</td>
  <td>'. $regPreTip['pt_descripcion'].'</td>
  <td>'.$txtOpe.'</td>
  <td>'.$txtSup.'</td>
  </tr>
  </table>
  </td>
  </tr>
  <tr>
  <td width="557">
  <table width="556" border="1" style="height:100%;margin-top:10px">
  <tr style="font-weight: bold;background: #e6e6e6">
  <td width="25%" style="font-size: small; text-align: center;">Tipo de material</td>
  <td width="34%" style="font-size: small; text-align: center;">Toneladas - kg</td>
  <td width="41%" style="font-size: small; text-align: center;">Fecha de entrada</td>
  </tr>';
  $sqlProMat = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, pm.pma_fe_entrada 
  FROM materiales as m 
  INNER JOIN procesos_materiales as pm on(m.mat_id=pm.mat_id) 
  WHERE pm.pro_id ='$regG[pro_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
  $regProMat= mysqli_fetch_assoc($sqlProMat);

  do{
    
   
$encabezado.=  '<tr>
    <td style="font-size: small">'. $regProMat['mat_nombre'] .'</td>
    <td style="font-size: small">'. $regProMat['pma_kg'] .'</td>
    <td style="font-size: small">'. fnc_formato_fecha($regProMat['pma_fe_entrada']) .'</td>
    </tr>';
    } while($regProMat= mysqli_fetch_assoc($sqlProMat));
    
$encabezado.= '</table>
    </td>
    <td width="361">
    <table width="356" border="1" style="height:100%;margin-top:10px">
    <tr>
    <td colspan="5" style="font-weight: bold;background: #e6e6e6;text-align: center;">Tipo de corte</td>
    </tr>
    <tr style="font-weight: bold;background: #e6e6e6">
    <td>Molino 1</td>
    <td>Molino 2</td>
    <td>Molino 3</td>
    <td>Molino 4</td>
    <td>Molino 5</td>
    </tr>';
    //VARIABLE DE MOLINOS
if($regG['pro_molino1'] == 1){$strMolino1 = "X";}
if($regG['pro_molino2'] == 1){$strMolino2 = "X";}
if($regG['pro_molino3'] == 1){$strMolino3 = "X";}
if($regG['pro_molino4'] == 1){$strMolino4 = "X";}
if($regG['pro_molino5'] == 1){$strMolino5 = "X";}

$encabezado.= '<tr align="center">';
$encabezado.= '<td>'.$strMolino1.'</td>';
$encabezado.= '<td>'.$strMolino2.'</td>';
$encabezado.= '<td>'.$strMolino3.'</td>';
$encabezado.= '<td>'.$strMolino4.'</td>';
$encabezado.= '<td>'.$strMolino5.'</td>';
$encabezado.= '</tr>  
    </table></td>
    <td width="200"><table width="200" border="1" style="height:100%;margin-top:10px">
    <tr>
    <td width="139" rowspan="2" style="font-weight: bold;background: #e6e6e6">Coladores limpios</td>
    <td width="28" style="font-weight: bold;background: #e6e6e6">Si</td>';
     
     if($regG['pro_col_limp'] == 1){$strColL = "X";}
      if($regG['pro_col_limp'] == 0){$strColS = "X";}
      
      if($regG['pro_pila'] == 3){$StrPila = "Limpia";}else{$StrPila = $regG['pro_pila'];}
      if($regG['pro_pila2'] == 3){$StrPila2 = "Limpia";}else{$StrPila2 = $regG['pro_pila2'];}

$encabezado.='
      <td width="11">'.$strColL.'</td>
    </tr>
    <tr>
    <td style="font-weight: bold;background: #e6e6e6">No</td>
    <td>'.$strColS.'</td>
    </tr>
    </table></td>
    </tr>
    <tr>
    <td><table width="555" border="1" style="height:100%;margin-top:20px">
    <tr style="font-weight: bold;background: #e6e6e6">
    <td width="80">Total kgs</td>
    <td width="190">Fecha que carga paleto</td>
    <td width="114">Hora Inicia</td>
    <td width="120">Hora Termina</td>
    </tr>
    <tr>
    <td>'. $regG['pro_total_kg'] .'</td>
    <td>'. fnc_formato_fecha($regG['pro_fe_carga']) .'</td>
    <td>'. $regG['pro_hr_inicio'] .'</td>
    <td>'. $regG['pro_hr_fin'] .'</td>
    </tr>
    </table></td>
    <td><table width="357" border="1" style="height:100%;margin-top:20px">
    <tr style="font-weight: bold;background: #e6e6e6">
    <td>Pila</td>
    <td>Ph</td>
    <td>Temp</td>
    <td>Ce</td>
    </tr>
    <tr align="center">
    <td>'.$StrPila.'</td>
    <td>'. $regG['pro_ph'] .'</td>
    <td>'. $regG['pro_temp'] .'</td>
    <td>'. $regG['pro_ce'] .'</td>
    </tr>
    <tr align="center">
    <td>'.$StrPila.'</td>
    <td>'. $regG['pro_ph2'] .'</td>
    <td>'. $regG['pro_temp2'] .'</td>
    <td>'. $regG['pro_ce2'] .'</td>
    </tr>
    </table></td>
    <td><table width="200" border="1" style="height:100%;margin-top:20px;">
    <tr style="font-weight: bold;background: #e6e6e6;">
    <td colspan="4">Cuero</td>
    </tr>
   <tr>
      <td style="font-weight: bold;background: #e6e6e6">CE</td>
      <td>12</td>
      ';
      
      if($regG['pro_cuero'] == "S"){$strCueroS = "X";}
      if($regG['pro_cuero'] == "N"){$strCueroN = "X";}
      if($regG['pro_cuero'] == "L"){$strCueroL = "X";}
      
      $encabezado.='
      <td>MUY SUCIO</td>
      <td>'.$strCueroS.'</td>
    </tr>
    <tr>
      <td style="font-weight: bold;background: #e6e6e6">CE</td>
      <td>9</td>
      <td>NORMAL</td>
      <td>'.$strCueroN.'</td>
    </tr>
    <tr>
      <td style="font-weight: bold;background: #e6e6e6">CE</td>
      <td>7</td>
      <td>LIMPIO</td>
      <td>'.$strCueroL.'</td>
    </tr>

    </table></td>
    </tr>
    </table>';
  }else{
    
$encabezado.= '<div style="height: 40px;width: 350px;text-align: left;z-index: 10;margin-top:10px;float: right;margin-bottom: 0px;background: #e6e6;border-radius: 5px;padding: 10px;color: #BD223E;font-weight: bold;font-style: italic;float: left;">
      No se ha capturado ningun tipo de preparación
    </div>';
    }
  
  if($idx_directo == 0){
   
$encabezado.=  '<div class="row" style="margin-top:20px; margin-right:20px;" align="right">';
       do{
       
$encabezado.=  '<a href="formatos/bitacora_consulta.php?idx_pro='.$registros['pro_id'].'" target="_blank"><img src="../iconos/buscar.png" alt="Consulta"> Consulta '.$registros['pro_id'].'</a';
      }while($registros = mysqli_fetch_assoc($cadena));
   
$encabezado.=  '</div>';
    }
    ?>

  </body>
  </html>