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

$sqlG = mysqli_query($cnx, "SELECT * FROM procesos where pro_id='$idx_pro'") or die(mysql_error()."Error: en consultar el tipo de proceso");
$regG = mysqli_fetch_assoc($sqlG);

$sqlPreTipo = mysqli_query($cnx, "SELECT pt_descripcion FROM preparacion_tipo where pt_id ='$regG[pt_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
$regPreTip= mysqli_fetch_assoc($sqlPreTipo);

$txtSup = fnc_nom_usu($regG['pro_supervisor']);
$txtOpe = fnc_nom_usu($regG['pro_operador']);

$encabezado = "";

$encabezado.= '<div class="col-md-12" style="margin-bottom: 30px">
<table width="100%" height="103" style="margin:20px 0px 20px 0px;background:#F5F4F4">
<tr>
<td colspan="4">
<table width="100%" style="margin-bottom: 20px">
<tr style="background: #e6e6e6;font-weight: bold;">
<td colspan="2" width="119" style="border:1px solid #838181;">Proceso</td>
<td colspan="2" width="148" style="border:1px solid #838181;">Lavador</td>
<td colspan="2" width="270" style="border:1px solid #838181;">Tipo de preparación</td>
<td colspan="2" width="55" style="border:1px solid #838181;">Supervisor</td>
<td colspan="2" width="93" style="border:1px solid #838181;">Operador</td>
</tr>
<tr>
<td colspan="2" style="border:1px solid #838181; ">'.$regG['pro_id'].'</td>
<td colspan="2" style="border:1px solid #838181; ">'.$regG['pl_id'].'</td>
<td colspan="2" style="border:1px solid #838181; ">'.$regPreTip['pt_descripcion'].'</td>
<td colspan="2" style="border:1px solid #838181; ">'.$txtSup.'</td>
<td colspan="2" style="border:1px solid #838181; ">'.$txtOpe.'</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="570">
<table width="100%" border="1">
<tr style="font-weight: bold;background: #e6e6e6">
<td width="28%" style="font-size: small; text-align: center;">Tipo material</td>
<td width="25%" style="font-size: small; text-align: center;">Toneladas-kg</td>
<td width="47%" style="font-size: small; text-align: center;">Fecha entrada</td>
</tr>';

$sqlProMat = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, pm.pma_fe_entrada 
  FROM materiales as m 
  INNER JOIN procesos_materiales as pm on(m.mat_id=pm.mat_id) 
  WHERE pm.pro_id =$regG[pro_id]") or die(mysql_error()."Error: en consultar el tipo de material");
$regProMat= mysqli_fetch_assoc($sqlProMat);

do{
  $encabezado.= '<tr>
  <td style="font-size: small">'.$regProMat['mat_nombre'].'</td>
  <td style="font-size: small">'.$regProMat['pma_kg'].'</td>
  <td style="font-size: small">'.$regProMat['pma_fe_entrada'].'</td>
  </tr>';
} while($regProMat= mysqli_fetch_assoc($sqlProMat));
$encabezado.='</table>
</td>
<td width="345">
<table width="100%" border="1" >
<tr>
<td colspan="5" style="font-weight: bold;background: #e6e6e6;text-align: center;">Tipo corte</td>
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
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
  <td><table width="200" border="1" >
    <tr>
      <td width="139" rowspan="2" style="font-weight: bold;background: #e6e6e6">Coladores limpios</td>
      <td width="28" style="font-weight: bold;background: #e6e6e6">SI</td>
      ';
      
      if($regG['pro_col_limp'] == 1){$strColL = "X";}
      if($regG['pro_col_limp'] == 0){$strColS = "X";}
      
      if($regG['pro_pila'] == 3){$StrPila = "Limpia";}else{$StrPila = $regG['pro_pila'];}
      
      $encabezado.='
      <td width="11">'.$strColL.'</td>
    </tr>
    <tr>
      <td style="font-weight: bold;background: #e6e6e6">NO</td>
      <td>'.$strColS.'</td>
    </tr>
  </table></td>
  <td><table width="200" border="1">
    <tr style="font-weight: bold;background: #e6e6e6">
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
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
<td><table width="532" border="1">
<tr style="font-weight: bold;background: #e6e6e6">
<td width="80">Total kgs</td>
<td width="190">Fecha que carga lavador</td>
<td width="114">Hora Inicia</td>
<td width="120">Hora Termina</td>
</tr>
<tr>
<td>'.$regG['pro_total_kg'].'</td>
<td>'.$regG['pro_fe_carga'].'</td>
<td>'.$regG['pro_hr_inicio'].'</td>
<td>'.$regG['pro_hr_fin'].'</td>
</tr>
</table></td>
<td><table width="335" border="1">
<tr style="font-weight: bold;background: #e6e6e6">
<td>PILA</td>
<td>PH</td>
<td>TEMP</td>
<td>CE</td>
</tr>
<tr align="center">
<td>'.$StrPila.'</td>
<td>'.$regG['pro_ph'].'</td>
<td>'.$regG['pro_temp'].'</td>
<td>'.$regG['pro_ce'].'</td>
</tr>
</table></td>
</tr>
</table>
<p></p>
</div>';
?>

</body>
</html>