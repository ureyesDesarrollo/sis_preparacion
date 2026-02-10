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

$sqlPreTipo = mysqli_query($cnx, "SELECT pt_descripcion FROM preparacion_tipo where pt_id ='$regG[pt_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
$regPreTip= mysqli_fetch_assoc($sqlPreTipo);
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
  @media print {
    .encabezado{
        width:430px;
    }
    
    .tipoCorte{
        width:200px;
    }
    
    .coladores{
        width:170px;
    }

}
</style>
<p></p>
<?php if($regG['pro_id'] != ''){?>
<table  width="1140"  border="0">
    <tr>
      <td colspan="3">
        <table width="630" border="1" >
          <tr style="background: #e6e6e6;font-weight: bold;">
            <td width="119">Proceso</td>
            <td width="119">Lavador</td>
            <td width="467">Tipo de preparación</td>
			<td>Operador</td>
			<td>Supervisor</td>
          </tr>
          <tr>
            <td><?php echo $regG['pro_id']?></td>
            <td><?php echo $regG['pl_id'] ?></td>
            <td><?php echo $regPreTip['pt_descripcion'] ?></td>
			<td><?php echo fnc_nom_usu($regG['pro_operador']); ?></td>
			<td><?php echo fnc_nom_usu($regG['pro_supervisor']); ?></td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td valign="top" class="encabezado">&nbsp;</td>
      <td valign="top"  class="tipoCorte">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td width="516" valign="top" class="encabezado">
        <table class="encabezado" border="1"  width="516">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="25%" style="font-size: small; text-align: center;">Tipo de material</td>
            <td width="20%" style="font-size: small; text-align: center;">Toneladas - kg</td>
            <td  style="font-size: small; text-align: center;">Fecha de entrada</td>
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
      <td  width="357" valign="top"  class="tipoCorte">
        <table width="356" border="1">
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
        <td width="251" valign="top"><table class="coladores" width="200" border="1">
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
        <td valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td valign="top"><p>&nbsp;</p></td>
      </tr>
      <tr>
        <td valign="top"><table class="encabezado" width="518" border="1">
          <tr style="font-weight: bold;background: #e6e6e6">
            <td width="80">Total kgs</td>
            <td width="190">Fecha carga lavador</td>
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
        <td valign="top"><table width="357" border="1">
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
        </table></td>
        <td rowspan="3" valign="top"><table class="coladores" width="200" border="1">
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
        <td valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><table width="855" border="1">
          <tr>
            <td width="124" style="font-weight: bold;background: #e6e6e6">Observaciones:</td>
            <td width="715" valign="top"><?php echo $regG['pro_observaciones'] ?></td>
          </tr>
        </table></td>
      </tr>
    </table>
	<?php }else{?>
		<div style="height: 40px;width: 350px;text-align: left;z-index: 10;margin-top:10px;float: right;margin-bottom: 0px;background: #e6e6;border-radius: 5px;padding: 10px;color: #BD223E;font-weight: bold;font-style: italic;float: left;">
		No se ha capturado ningun tipo de preparaci贸n
</div>
	<?php }?>
</body>
</html>