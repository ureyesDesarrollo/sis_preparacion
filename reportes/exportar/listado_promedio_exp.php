<!--<link rel="stylesheet" href="../../css/promedio_formatos.css">-->
<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
<?php 

include '../formatos/funciones_reporte.php';
include '../../funciones/funciones.php';
include('../../seguridad/user_seguridad.php');

$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
	FROM lotes WHERE  lote_mes = ".$_GET['mes']." ") or die(mysql_error()."Error: en consultar el lotes");
$registros = mysqli_fetch_assoc($cadena);

header('Content-type: application/vnd.ms-excel');

header("Content-Disposition: attachment; filename=reporte_promedio_".$_GET['mes'].".xls");


header("Pragma: no-cache");
header("Expires: 0");

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>

<div class="rol">
	<table border="1" class="table table-striped">
		<thead>
			<tr id="encabezado" valign="top">
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Lote</p></th>
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Material</p></th>
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Fecha</p></th>
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Hora</p></th>
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Turno</p></th>
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Tem p. Ent.</p></th>
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Tem p. Salida</p></th>
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Solides</p></th>
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Ce</p></th>
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Dureza</p></th>
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Color</p></th>
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >NTUs</p></th>
				<th style="background: #08DFF9;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Proc   Viscosidad</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Tem p Agua</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Ce Lav Ini</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Hora Hidrolisis</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Kg Enzima</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Ph Enzima</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Temp Enzima</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >sosa  Normalidad</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Temp sosa</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Color blanqueo</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >L Peroxido</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Blanqueo Ce Lav </p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Blanqueo ppm Lav </p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Adel. 1er ac</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >pH 1er ac</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Ce Lav 1er ac</p></th>
				<th style="background: #FCE5F7;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >2do ac Normalidad</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >PALETO</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >pal Num. proceso</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Lavadores</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Lav  No. procesos </p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Preparación</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Ce</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Ph</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >% Sol</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >% Ext</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Color cuero</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Color caldo</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >(G) Cuero sobrante </p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Fecha</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Hora</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Observaciones</p></th>
				<th style="background: #EBFC74;color: #5F5E5E;font-weight: bold;text-align: center; height: 130px;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Analista</p></th>
				<th style="background: #EAB7FD;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Tarimas</p></th>
				<th style="background: #EAB7FD;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Bloom</p></th>
				<th style="background: #EAB7FD;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Visc</p></th>
				<th style="background: #EAB7FD;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Ph final</p></th>
				<th style="background: #EAB7FD;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Trans</p></th>
				<th style="background: #EAB7FD;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Cenizas</p></th>
				<th style="background: #EAB7FD;color: #5F5E5E;font-weight: bold;"><p style="writing-mode: vertical-lr;transform: rotate(220deg);margin-top: 10px;margin-bottom:10px;text-align: left; height: 130px;" >Color</p></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			do { 

			//Obtiene el tipo de material, 
				//sleecciona lotes procesos donde procesos_paletos_d sea igual a prop_id y juntala con procesos_materiales donde
				//pro_id se igual

				$str_cad = 	mysqli_query($cnx, "SELECT DISTINCT m.mat_id ,m.mat_nombre 
					FROM lotes_procesos AS l
					INNER JOIN procesos_paletos_d AS ppd on(l.prop_id = ppd.prop_id)
					INNER JOIN procesos_materiales as pm on(ppd.pro_id = pm.pro_id)
					INNER JOIN materiales as m on(pm.mat_id = m.mat_id) 
					where l.lote_id = ".$registros['lote_id']." limit 5");
				$tot_cad = mysqli_num_rows($str_cad);
				$reg_cad = mysqli_fetch_assoc($str_cad);

				$str_tar = 	mysqli_query($cnx, "SELECT * FROM tarimas
					where lote_id = ".$registros['lote_id']." AND tarima_lim_param <> 0 ");
				$reg_tar = mysqli_fetch_assoc($str_tar);
				$tot_tar = mysqli_num_rows($str_tar);

				?>
				<tr id="cuerpo">
					<td align="center"><?php echo $registros['lote_folio'];//."-".$registros['lote_id'] ?></td>
					<td><?php 
					if($tot_cad == 0){echo "-";}
					if($tot_cad == 1){ ?>
						<a style="font-size: 14px;" href="#"  
						onclick="javascript:AbreModalEditar(<?=$registros['lote_id']; ?>);" ><?php echo $reg_cad['mat_nombre']; ?> </a>


					<?php } 
					if($tot_cad >= 2){ ?>

						<a style="font-size: 14px;" href="#"  
						onclick="javascript:AbreModalEditar(<?=$registros['lote_id']; ?>);" ><?php echo "Mezcla" ?> </a>


					<?php } ?>
				</td>
				<td><?php echo $registros['lote_fecha'] ?></td>
				<td><?php echo $registros['lote_hora'] ?></td>
				<td><?php echo $registros['lote_turno'] ?></td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td><?php echo fnc_temp_agua($registros['lote_id']); ?></td>
				<td><?php echo fnc_lav_ini($registros['lote_id']); ?></td>
				<td></td>
				<td></td>
				<td><?php echo fnc_ph_enzima($registros['lote_id']); ?></td>
				<td><?php echo fnc_temp_enzima($registros['lote_id']); ?></td>
				<td><?php echo fnc_normalidad_sosa($registros['lote_id']); ?></td>
				<td><?php echo fnc_temp_sosa($registros['lote_id']); ?></td>
				<td><?php echo fnc_color_blanqueo($registros['lote_id']); ?></td>
				<td><?php echo fnc_l_peroxido($registros['lote_id']); ?></td>
				<td><?php echo fnc_c_lav_blanqueo($registros['lote_id']); ?></td>
				<td><?php echo fnc_ppm_lav_blanqueo($registros['lote_id']); ?></td>
				<td><?php echo fnc_adel_1er_ac($registros['lote_id']); ?></td>
				<td><?php echo fnc_ph_1er_ac($registros['lote_id']); ?></td>
				<td><?php echo fnc_ce_1er_ac($registros['lote_id']); ?></td>
				<td><?php echo fnc_normalidad_2ac($registros['lote_id']); ?></td>
				<td><?php echo fnc_paleto($registros['lote_id']); ?></td>
				<td><?php $idx = fnc_sproceso($registros['lote_id']); echo $idx ?></td>
				<td><?php echo fnc_lavador($registros['lote_id']); ?></td>
				<td><?php echo fnc_procesos($registros['lote_id']);?></td>
				<td><?php echo fnc_tipo($registros['lote_id']); ?></td>
				<td><?php echo fnc_lib_b_ce($registros['lote_id']); ?></td>
				<td><?php echo fnc_lib_b_ph2($registros['lote_id']); ?></td>
				<td><?php echo fnc_lib_b_sol($registros['lote_id']); ?></td>
				<td><?php echo fnc_lib_b_ext($idx); ?></td>
				<td><?php echo fnc_lib_b_color($idx); ?></td>
				<td>-</td>
				<td>-</td>
				<td><?php echo fnc_lib_b_fecha($idx); ?></td>
				<td><?php echo fnc_lib_b_hora($idx); ?></td>
				<td>-</td>
				<td><?php echo fnc_nom_usuario(fnc_lib_b_user($idx)); ?></td>
				<td>
					<a style="font-size: 14px;" href="#"  
					onclick="javascript:AbreModalTarimas(<?=$registros['lote_id']; ?>);" ><?php echo $tot_tar ?> </a>
				</td>
				<td><?php echo $registros['lote_bloom'] ?></td>
				<td><?php echo $registros['lote_viscocidad'] ?></td>
				<td><?php echo $registros['lote_ph_final'] ?></td>
				<td><?php echo $registros['lote_transparencia'] ?></td>
				<td><?php echo $registros['lote_cenizas'] ?></td>
				<td><?php echo $registros['lote_color'] ?></td>
			</tr>

		<?php } while($registros = mysqli_fetch_assoc($cadena)); ?>
	</tbody>
</table>
</div>

	
</body>
</html>