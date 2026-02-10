<link rel="stylesheet" href="../css/promedio.css">
<?php 
include "funciones_reporte.php";

$cnx =  Conectarse();

$cadena = mysqli_query($cnx, "SELECT *
	FROM lotes WHERE lote_mes = ".$_GET['mes']." ") or die(mysql_error()."Error: en consultar el lotes");
$registros = mysqli_fetch_assoc($cadena);
?>

<script>
	/*Abrir Modal material*/
	function AbreModalEditar(lote)
	{ 
		$.ajax({
			type : 'post',
			url : 'consulta_mat.php', 
		data : {"lote": lote}, //Pass $id//
		success : function(result){
			$("#modalEditar").html(result);
			$('#modalEditar').modal('show')
		}
	});
		return false;
	};


/*Abrir Modal tarimas*/
	function AbreModalTarimas(lote)
	{ 
		$.ajax({
			type : 'post',
			url : 'consulta_tarimas.php', 
		data : {"lote": lote}, //Pass $id//
		success : function(result){
			$("#modalTarimas").html(result);
			$('#modalTarimas').modal('show')
		}
	});
		return false;
	};

/*Abrir Modal procesos*/
	function AbreModalProceso(lote, pro)
	{ 
		$.ajax({
			type : 'post',
			url : 'consulta_proceso.php', 
		data : {"lote": lote, 
			   "pro": pro}, //Pass $id//
		success : function(result){
			$("#modalProceso").html(result);
			$('#modalProceso').modal('show')
		}
	});
		return false;
	};
</script>
<div class="rol">
	<table border="1" class="table table-striped">
		<thead>
			<tr id="encabezado" valign="top">
				<th id="bloque1"><p class="verticalText">Lote</p></th>
				<th id="bloque1"><p class="verticalText">Material</p></th>
				<th id="bloque1"><p class="verticalText">Fecha</p></th>
				<th id="bloque1"><p class="verticalText">Hora</p></th>
				<th id="bloque1"><p class="verticalText">Turno</p></th>
				<th id="bloque1"><p class="verticalText">Tem p. Ent.</p></th>
				<th id="bloque1"><p class="verticalText">Tem p. Salida</p></th>
				<th id="bloque1"><p class="verticalText">Solides</p></th>
				<th id="bloque1"><p class="verticalText">Ce</p></th>
				<th id="bloque1"><p class="verticalText">Dureza</p></th>
				<th id="bloque1"><p class="verticalText">Color</p></th>
				<th id="bloque1"><p class="verticalText">NTUs</p></th>
				<th id="bloque1"><p class="verticalText">Proc <br> Viscosidad</p></th>
				<th id="bloque2"><p class="verticalText">Tem p Agua</p></th>
				<th id="bloque2"><p class="verticalText">Ce Lav Ini</p></th>
				<th id="bloque2"><p class="verticalText">Hora Hidrolisis</p></th>
				<th id="bloque2"><p class="verticalText">Kg Enzima</p></th>
				<th id="bloque2"><p class="verticalText">Ph Enzima</p></th>
				<th id="bloque2"><p class="verticalText">Temp Enzima</p></th>
				<th id="bloque2"><p class="verticalText">sosa <br>Normalidad</p></th>
				<th id="bloque2"><p class="verticalText">Temp sosa</p></th>
				<th id="bloque2"><p class="verticalText">Color blanqueo</p></th>
				<th id="bloque2"><p class="verticalText">L Peroxido</p></th>
				<th id="bloque2"><p class="verticalText">Blanqueo<br>Ce Lav </p></th>
				<th id="bloque2"><p class="verticalText">Blanqueo<br>ppm Lav </p></th>
				<th id="bloque2"><p class="verticalText">Adel. 1er ac</p></th>
				<th id="bloque2"><p class="verticalText">pH 1er ac</p></th>
				<th id="bloque2"><p class="verticalText">Ce Lav 1er ac</p></th>
				<th id="bloque2"><p class="verticalText">2do ac<br>Normalidad</p></th>
				<th id="bloque3"><p class="verticalText">PALETO</p></th>
				<th id="bloque3"><p class="verticalText">pal<br>Num. proceso</p></th>
				<th id="bloque3"><p class="verticalText">Lavadores</p></th>
				<th id="bloque3"><p class="verticalText">Lav <br>No. procesos </p></th>
				<th id="bloque3"><p class="verticalText">Preparación</p></th>
				<th id="bloque3"><p class="verticalText">Ce</p></th>
				<th id="bloque3"><p class="verticalText">Ph</p></th>
				<th id="bloque3"><p class="verticalText">% Sol</p></th>
				<th id="bloque3"><p class="verticalText">% Ext</p></th>
				<th id="bloque3"><p class="verticalText">Color cuero</p></th>
				<th id="bloque3"><p class="verticalText">Color caldo</p></th>
				<th id="bloque3"><p class="verticalText">(G)<br>Cuero sobrante </p></th>
				<th id="bloque3"><p class="verticalText">Fecha</p></th>
				<th id="bloque3"><p class="verticalText">Hora</p></th>
				<th id="bloque3"><p class="verticalText">Observaciones</p></th>
				<th id="bloque3"><p class="verticalText">Analista</p></th>
				<th id="bloque4"><p class="verticalText">Tarimas</p></th>
				<th id="bloque4"><p class="verticalText">Bloom</p></th>
				<th id="bloque4"><p class="verticalText">Visc</p></th>
				<th id="bloque4"><p class="verticalText">Ph final</p></th>
				<th id="bloque4"><p class="verticalText">Trans</p></th>
				<th id="bloque4"><p class="verticalText">Cenizas</p></th>
				<th id="bloque4"><p class="verticalText">Color</p></th>
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
				<td><?php $idx = fnc_sproceso($registros['lote_id']);
					if($_SESSION['privilegio']  == 1 or $_SESSION['privilegio']  == 2 )
					{
					?>
					<a style="font-size: 14px;" href="#"  
						onclick="javascript:AbreModalProceso(<?=$registros['lote_id']; ?>, <?php echo $idx;?>);" >
					<?php  echo $idx ?></a> 
				<?php }else{echo $idx;}?>
				</td>
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

<div class="modal fade bd-example-modal-lg" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
</div>

<div class="modal fade bd-example-modal-lg" id="modalTarimas" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
</div>

<div class="modal fade bd-example-modal-lg" id="modalProceso" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
</div>