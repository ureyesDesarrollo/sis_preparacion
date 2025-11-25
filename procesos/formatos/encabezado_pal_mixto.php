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

/*$sqlG = mysqli_query($cnx, "SELECT * FROM procesos WHERE pro_id = '$idx_pro'") or die(mysql_error()."Error: en consultar el proceso");
$regG = mysqli_fetch_assoc($sqlG);*/

$sqlPal = mysqli_query($cnx, "SELECT p.pp_id, p.pt_id, p.prop_id FROM procesos_paletos as p INNER JOIN procesos_paletos_d as d ON (p.prop_id = d.prop_id) WHERE d.pro_id = '$idx_pro'") or die(mysql_error()."Error: en consultar el proceso");
$regPal = mysqli_fetch_assoc($sqlPal);

$sqlNomPal = mysqli_query($cnx, "SELECT pp_descripcion FROM preparacion_paletos  WHERE pp_id= '$regPal[pp_id]'") or die(mysql_error()."Error: en consultar el proceso");
$regNomPal = mysqli_fetch_assoc($sqlNomPal);

$sqlNomPal2 = mysqli_query($cnx, "SELECT a.pp_descripcion FROM procesos_paletos_hist as h inner join preparacion_paletos as a on (h.pp_id = a.pp_id) WHERE  h.prop_id =  '$regPal[prop_id]'") or die(mysql_error()."Error: en consultar el proceso");
$regNomPal2 = mysqli_fetch_assoc($sqlNomPal2);

$sqlPreTipo = mysqli_query($cnx, "SELECT pt_descripcion FROM preparacion_tipo where pt_id ='$regPal[pt_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
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
<?php if($idx_pro != ''){?>
<table width="1140" height="200" border="0" style="position:inherit">
    <tr>
      <td colspan="3">
        <table width="952" border="1" >
          <tr style="background: #e6e6e6;font-weight: bold;">
            <td width="119">S-Proceso</td>
            <td width="119">Paleto</td>
            <td width="119">Paleto Anterior </td>
            <td width="567">Tipo de preparación</td>
<!--			<td>Operador</td>
			<td>Supervisor</td>-->
          </tr>
          <tr>
            <td align="center"><?php echo $idx_prop; ?></td>
            <td align="center"><?php echo $regNomPal['pp_descripcion'] ?></td>
            <td align="center"><?php echo $regNomPal2['pp_descripcion'] ?></td>
            <td><?php echo $regPreTip['pt_descripcion'] ?></td>
<!--			<td><?php /*echo fnc_nom_usu($regG['pro_operador']); ?></td>
			<td><?php echo fnc_nom_usu($regG['pro_supervisor']);*/ ?></td>-->
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
          </tr>
          <?php 
          do{
		  
		  $sqlG = mysqli_query($cnx, "SELECT * FROM procesos WHERE pro_id = '$registros[pro_id]'") or die(mysql_error()."Error: en consultar el proceso");
		 $regG = mysqli_fetch_assoc($sqlG);

		  $sqlProMat = mysqli_query($cnx, "SELECT m.mat_nombre, pm.pma_kg, pm.pma_fe_entrada 
            FROM materiales as m 
            INNER JOIN procesos_materiales as pm on(m.mat_id=pm.mat_id) 
            WHERE pm.pro_id = '$registros[pro_id]'") or die(mysql_error()."Error: en consultar el tipo de material");
          $regProMat= mysqli_fetch_assoc($sqlProMat);
            ?>
            <tr>
              <td style="font-size: small" align="center"><a href="../procesos/formatos/bitacora_consulta.php?idx_pro=<?php echo $registros['pro_id']; ?>" target="_blank"><!--<img src="../iconos/buscar.png" alt="Consulta"> Consulta --><?php echo $registros['pro_id']; ?></a></td>
              <td style="font-size: small" align="center"><?php echo $regG['pl_id']; ?></td>
              <td style="font-size: small"><?php echo $regProMat['mat_nombre'] ?></td>
              <td style="font-size: small"><?php echo $regG['pro_total_kg'] ?></td>
            </tr>
          <?php } while($registros = mysqli_fetch_assoc($cadena));?>
        </table>      </td>
    </tr>
    </table>
	<?php }else{?>
		<div style="height: 40px;width: 350px;text-align: left;z-index: 10;margin-top:10px;float: right;margin-bottom: 0px;background: #e6e6;border-radius: 5px;padding: 10px;color: #BD223E;font-weight: bold;font-style: italic;float: left;">
		No se ha capturado ningun tipo de preparación
		</div>
	<?php }?>
	
	<?php /* if($idx_directo == 0){?>
	<div class="row" style="margin-top:20px; margin-right:20px;" align="right">
		<?php do{?>
		<a href="formatos/bitacora_consulta.php?idx_pro=<?php echo $registros['pro_id']; ?>" target="_blank"><img src="../iconos/buscar.png" alt="Consulta"> Consulta <?php echo $registros['pro_id']; ?></a>
		<?php }while($registros = mysqli_fetch_assoc($cadena));?>
	</div>
	<?php }*/?>
</body>
</html>