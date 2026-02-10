<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
//include "../conexion/conexion.php";
//include "../funciones/funciones.php";
$cnx =  Conectarse();

//$id_l = $_GET['id_l'];

/*$sqlG = mysqli_query($cnx, "SELECT pro_id,pl_id,pt_id,pro_total_kg,pro_fe_carga,pro_hr_inicio,pro_hr_fin,pro_molino1,pro_molino2 ,pro_molino3,pro_molino4,pro_molino5,pro_pila,pro_ph,pro_temp,pro_ce,coladores_limpios(pro_col_limp) as res,pro_cuero,pro_estatus FROM procesos") or die(mysql_error()."Error: en consultar el tipo de material");
$regG= mysqli_fetch_assoc($sqlG);*/

$sqlG = mysqli_query($cnx, "SELECT * FROM procesos WHERE pro_id = '$idx_pro'") or die(mysql_error()."Error: en consultar el proceso");
$regG = mysqli_fetch_assoc($sqlG);

$sqlPal = mysqli_query($cnx, "SELECT p.pp_id, p.pt_id, p.prop_id FROM procesos_paletos as p INNER JOIN procesos_paletos_d as d ON (p.prop_id = d.prop_id) WHERE d.pro_id = '$idx_pro'") or die(mysql_error()."Error: en consultar el proceso");
$regPal = mysqli_fetch_assoc($sqlPal);

$sqlPal = mysqli_query($cnx, "SELECT p.pp_id FROM procesos_paletos as p INNER JOIN procesos_paletos_d as d ON (p.prop_id = d.prop_id) WHERE d.pro_id = '$idx_pro'") or die(mysql_error()."Error: en consultar el proceso");
$regPalante = mysqli_fetch_assoc($sqlPal);

$sqlNomPal = mysqli_query($cnx, "SELECT pp_id,pp_descripcion FROM preparacion_paletos  WHERE pp_id= '$regPal[pp_id]'") or die(mysql_error()."Error: en consultar el proceso");
$regNomPal = mysqli_fetch_assoc($sqlNomPal);

$sqlNomPal2 = mysqli_query($cnx, "SELECT a.pp_descripcion FROM procesos_paletos_hist as h inner join preparacion_paletos as a on (h.pp_id = a.pp_id) WHERE  h.prop_id =  '$regPal[prop_id]'") or die(mysql_error()."Error: en consultar el proceso");
$regNomPal2 = mysqli_fetch_assoc($sqlNomPal2);

$sqlNomPalante = mysqli_query($cnx, "SELECT pp_descripcion FROM preparacion_paletos  WHERE pp_id= '$regPal[pp_id]'") or die(mysql_error()."Error: en consultar el proceso");
$regNomPalante = mysqli_fetch_assoc($sqlNomPal);

$sqlPreTipo = mysqli_query($cnx, "SELECT pt_descripcion FROM preparacion_tipo where pt_id ='$regG[pt_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
$regPreTip= mysqli_fetch_assoc($sqlPreTipo);

$cadena = mysqli_query($cnx, "SELECT * FROM procesos_paletos_d WHERE prop_id = '$idx_prop' ") or die(mysql_error()."Error: en consultar el procesos");
$registros = mysqli_fetch_assoc($cadena);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Encabezado</title>
</head>
<body>


  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
  <style>
</style>
<p></p>
<?php if($regG['pro_id'] != ''){?>
<table width="1140" height="200" border="0" style="position:inherit">
    <tr>
      <td colspan="3">
        <table width="730" border="1" >
          <tr style="background: #e6e6e6;font-weight: bold;">
            <td width="119">Proceso</td>
            <td width="119">Paleto</td>
            <td width="119">Paleto Anterior </td>
            <td width="567">Tipo de preparación</td>
			<td>Operador</td>
			<td>Supervisor</td>
          </tr>
          <tr>
            <td>
<?php 
if ($regNomPal['pp_id'] == 1 or $regNomPal['pp_id'] == 2) {
  echo $regG['pro_id'].' - '.$regPal['prop_id'];
 }
   else{
    echo $regG['pro_id'];
   }
  ?>

            </td>
            
            <td><?php echo $regNomPal['pp_descripcion'] ?></td>
            <td><?php echo $regNomPal2['pp_descripcion'] ?></td>
            <td><?php echo $regPreTip['pt_descripcion'] ?></td>
			<td><?php echo fnc_nom_usu($regG['pro_operador']); ?></td>
			<td><?php echo fnc_nom_usu($regG['pro_supervisor']); ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="557">
        <table width="556" border="1" style="height:100%;margin-top:10px">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="25%" style="font-size: small; text-align: center;">Tipo de material</td>
            <td width="20%" style="font-size: small; text-align: center;">Toneladas - kg</td>
            <td width="25%" style="font-size: small; text-align: center;">Fecha de entrada</td>
             <td  style="font-size: small; text-align: center;">Fe. entrada de maquila</td>
          </tr>
          <?php 

          $sqlProMat = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, pm.pma_fe_entrada, pm.pma_fe_entrada_maquila
            FROM materiales as m 
            INNER JOIN procesos_materiales as pm on(m.mat_id=pm.mat_id) 
            WHERE pm.pro_id ='$regG[pro_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regProMat= mysqli_fetch_assoc($sqlProMat);

          do{
            ?>
            <tr>
              <td style="font-size: small"><?php echo $regProMat['mat_nombre'] ?></td>
              <td style="font-size: small"><?php echo $regProMat['pma_kg'] ?></td>
              <td style="font-size: small"><?php echo fnc_formato_fecha($regProMat['pma_fe_entrada']) ?></td>
               <td style="font-size: small"><?php echo fnc_formato_fecha($regProMat['pma_fe_entrada_maquila']) ?></td>
            </tr>
          <?php } while($regProMat= mysqli_fetch_assoc($sqlProMat));?>
        </table>
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
          </tr>
          <tr align="center">
            <td><?php if($regG['pro_molino1'] == 1){echo "X";} ?></td>
            <td><?php if($regG['pro_molino2'] == 1){echo "X";} ?></td>
            <td><?php if($regG['pro_molino3'] == 1){echo "X";} ?></td>
            <td><?php if($regG['pro_molino4'] == 1){echo "X";} ?></td>
            <td><?php if($regG['pro_molino5'] == 1){echo "X";} ?></td>
          </tr>
      </table></td>
        <td width="200"><table width="200" border="1" style="height:100%;margin-top:10px">
          <tr>
            <td width="139" rowspan="2" style="font-weight: bold;background: #e6e6e6">Coladores limpios</td>
            <td width="28" style="font-weight: bold;background: #e6e6e6">Si</td>
            <td width="11"><?php if($regG['pro_col_limp'] == 1){echo "X";}?></td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">No</td>
            <td><?php if($regG['pro_col_limp'] == '0'){echo "X";}?></td>
          </tr>
      </table></td>
      </tr>
      <tr>
        <td valign="top"><table width="555" border="1" style="height:100%;margin-top:20px">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="80">Total kgs</td>
            <td width="190">Fecha que carga paleto</td>
            <td width="114">Hora Inicia</td>
            <td width="120">Hora Termina</td>
          </tr>
          <tr>
            <td><?php echo $regG['pro_total_kg'] ?></td>
            <td><?php echo fnc_formato_fecha($regG['pro_fe_carga']) ?></td>
            <td><?php echo $regG['pro_hr_inicio'] ?></td>
            <td><?php echo $regG['pro_hr_fin'] ?></td>
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
            <td><?php if($regG['pro_pila'] == 3){echo "Limpia";}else{echo $regG['pro_pila'];} ?></td>
            <td><?php echo $regG['pro_ph'] ?></td>
            <td><?php echo $regG['pro_temp'] ?></td>
            <td><?php echo $regG['pro_ce'] ?></td>
          </tr>
           <tr align="center">
            <td><?php if($regG['pro_pila2'] == 3){echo "Limpia";}else{echo $regG['pro_pila'];} ?></td>
            <td><?php echo $regG['pro_ph2'] ?></td>
            <td><?php echo $regG['pro_temp2'] ?></td>
            <td><?php echo $regG['pro_ce2'] ?></td>
          </tr>
        </table></td>
        <td rowspan="3" valign="top"><table width="200" border="1" style="height:100%;margin-top:20px;">
          <tr style="font-weight: bold;background: #e6e6e6;">
            <td colspan="4">Cuero</td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Ce</td>
            <td>12</td>
            <td>Muy sucio</td>
            <td><?php if($regG['pro_cuero'] == 'S'){echo "X";}?></td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Ce</td>
            <td>9</td>
            <td>Normal</td>
            <td><?php if($regG['pro_cuero'] == 'N'){echo "X";}?></td>
          </tr>
          <tr>
            <td style="font-weight: bold;background: #e6e6e6">Ce</td>
            <td>7</td>
            <td>Limpio</td>
            <td><?php if($regG['pro_cuero'] == 'L'){echo "X";}?></td>
          </tr>

        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><table width="916" border="1" style="height:100%;margin-top:20px">
          <tr>
            <td width="80" style="font-weight: bold;background: #e6e6e6">Observaciones</td>
            <td valign="top"><?php echo $regG['pro_observaciones'] ?></td>
          </tr>
        </table></td>
      </tr>
    </table>
	<?php }else{?>
		<div style="height: 40px;width: 350px;text-align: left;z-index: 10;margin-top:10px;float: right;margin-bottom: 0px;background: #e6e6;border-radius: 5px;padding: 10px;color: #BD223E;font-weight: bold;font-style: italic;float: left;">
		No se ha capturado ningun tipo de preparación
		</div>
	<?php }?>
	
	<?php if($idx_directo == 0){?>
	<div class="row" style="margin-top:20px; margin-right:20px;" align="right">
		<?php do{?>
		<a href="formatos/bitacora_consulta.php?idx_pro=<?php echo $registros['pro_id']; ?>" target="_blank"><img src="../iconos/buscar.png" alt="Consulta"> Consulta <?php echo $registros['pro_id']; ?></a>
		<?php }while($registros = mysqli_fetch_assoc($cadena));?>
	</div>
	<?php }?>
</body>
</html>