<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
/*$id_l = $_GET['id_l'];

$cad_pro = mysqli_query($cnx, "SELECT p.pro_id  
	FROM procesos as p
	WHERE p.pl_id = '$id_l' AND p.pro_estatus = 1");
$reg_pro = mysqli_fetch_array($cad_pro);*/

$reg_pro['pro_id'] = $idx_pro;

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 10");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_5_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 10");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 10");
$reg_lib = mysqli_fetch_array($cad_lib);

if($_SESSION['privilegio'] == 3 ){$strProp1 = 'disabled';$strProp6 = '';}else{$strProp1 = '';}//Operador
if($_SESSION['privilegio'] == 4 ){$strProp2 = 'readonly';}else{$strProp2 = '';}//Supervidor
if($_SESSION['privilegio'] == 6 ){$strProp3 = 'readonly';}else{$strProp3 = '';}//Laboratorio

//Para capturar primero los datos generales
if($reg_aux['proa_id'] == ''){$strProp4 = 'readonly';}else{$strProp4 = '';}

//Para bloquear las observaciones si capturo los datos el supervisor.
if($reg_aux['proa_fe_fin'] != ''){$strProp5 = 'readonly';}else{$strProp5 = '';}
?>
<script>
/*	e = 1;
	function agregarCampo5(){
		e++;
		var div = document.createElement('div');
		div.innerHTML = 
		'<div class="form-group col-md-1" style="width: 70px"><label>'+e+' Lav</label></div>'+
		'<div class="form-group col-md-1" style="width: 150px"><input class="form-control" name="txtHIni5'+e+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="width: 160px"><input class="form-control" name="txtHLle5'+e+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="width: 180px"><input class="form-control" name="txtHMov5'+e+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="width: 190px"><input class="form-control" name="txtHTerMov5'+e+'" type="text"/></div>'+
		'<div class="form-group col-md-1"><input class="form-control" name="txtCe5'+e+'" type="text"/></div>'+
		'<div class="form-group col-md-1"><input class="form-control" name="txtPh5'+e+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="margin-right:100px;"><input class="form-control" name="txtTemp5'+e+'" type="text"/></div>';
		document.getElementById('campos5').appendChild(div);document.getElementById('campos5').appendChild(div);
	}*/
	
	$(document).ready(function()
	{
		$("#formFase5").submit(function(){

			var formData = $(this).serialize();
			$.ajax({
				url: "fase_5_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase5Ope", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase5').each (function(){this.reset();}); 
					setTimeout("location.reload()", 2000); 

				}
			});
			return false;

		});
	});
	
	function AbreModalAgregarR(proceso, etapa, uren){
	var datos={
		"pro_id": proceso,
		"pe_id": etapa,
		"uren": uren
	}
		//alert($("hdd_pro_id").val());
		$.ajax({
			type:'post',
			url: 'modal_renglon.php',
			data: datos,
			//data: {nombre:n},
			success: function(result){
				$("#modalRenglon5").html(result);
				$('#modalRenglon5').modal('show')
			}	
		});
		return false;
	}

	function AbreModalPaleto(proceso, lavador){
	var datos={
		"pro_id": proceso,
		"lavador": lavador
	}
		//alert($("hdd_pro_id").val());
		$.ajax({
			type:'post',
			url: 'modal_paleto.php',
			data: datos,
			//data: {nombre:n},
			success: function(result){
				$("#modalPaleto5").html(result);
				$('#modalPaleto5').modal('show')
			}	
		});
		return false;
	}
	
	function AbreModalE5(proceso, etapa){
	var datos={
		"pro_id": proceso,
		"pe_id": etapa
	}
		$.ajax({
			type:'post',
			url: 'editar/fase_5.php',
			data: datos,
			success: function(result){
				$("#modalEditar5").html(result);
				$('#modalEditar5').modal('show')
			}	
		});
		return false;
	}

	//Bloquear boton modal de lavadores a paleto
		function confirmEnviar3() {

			formModalP.btn.disabled = true; 
			formModalP.btn.value = "Enviando...";

			setTimeout(function(){
				formModalP.btn.disabled = true;
				formModalP.btn.value = "Guardar";
			},2000);

			var statSend = false;
			return false;
		}
</script>
<div class="divProcesos">
<form id="formFase5" name="formFase5">
	<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id"/>
	<input name="hdd_pe_id" type="hidden" value="10" id="hdd_pe_id"/>
	<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>"/>
	<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg5_id']; ?>"/>

	<div class="headerdivProcesos">
		<div class="col-md-2" >LAVADOS 1er ACIDO </div>
		<div class="col-md-4" >Este proceso se puede hacer con agua limpia</div>
		<div class="col-md-5" >El agua de este proceso se manda a agua recuperada semilimpia(PILA 1)</div>
	</div>
	
	<!--tiempos-->
	<div class="row" style="margin-bottom: 10px">
		<label class="col-md-1" style="width: 200px;">Fecha que inicio</label>
		<div class="col-md-2 tiempos">
			<input type="date" class="form-control" id="txtFeIni" placeholder="" name="txtFeIni" value="<?php if($reg_aux['proa_fe_ini'] == ''){ echo date("Y-m-d"); $str_prop = '';}else{echo $reg_aux['proa_fe_ini']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; ?> required>
		</div>

		<label class="col-md-1" style="width: 110px">Hora inicio</label>
		<div class="col-md-2 tiempos">
			<input type="time" class="form-control" id="txtHrIni" placeholder="" name="txtHrIni" value="<?php if($reg_aux['proa_hr_ini'] == ''){echo date("H:i"); $str_prop = '';}else{echo $reg_aux['proa_hr_ini']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; ?> required>
		</div>

		<label class="col-md-1" style="width: 165px">Tipo de agua</label>
		<div class="col-md-2 tiempos">
			<select id="cbxTipAg" class="form-control" style="width: 250px" name="cbxTipAg" <?php if($reg_fa['tpa_id'] == ''){echo ""; $str_prop = '';}else{ $str_prop = 'disabled';} echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; ?> required>
				<option value="">Seleccionar</option>
				<?php 
				$cadena =  mysqli_query($cnx,"SELECT * from tipos_agua");
				$registros =  mysqli_fetch_array($cadena);

				do
				{
					?><option value="<?php echo $registros['tpa_id'] ?>" <?php if($registros['tpa_id'] == $reg_fa['tpa_id']){ ?>selected="selected"<?php }?>><?php echo $registros['tpa_descripcion'] ?></option><?php
				}while($registros =  mysqli_fetch_array($cadena));

				//mysqli_free_result($registros);

				?>
			</select>
		</div>
	</div>
	<!---->
	
	<div>
	<table border="0" cellspacing="5" cellpadding="5">
		<tr class="etiqueta_tbl">
		    <td width="15">&nbsp;</td>
			<td>Lav</td>
			<td>HR ini llenado</td>
			<td>HR ter llenado</td>
			<td>HR ini movimiento</td>
			<td>HR ter movimiento</td>
			<td>pH</td>
			<td>CE</td>
			<td>TEMP</td>
			<td>Observaciones</td>
		</tr>
		<?php 
		$strVal = 'NO';
		
		for($i = 1; $i <=10; $i++){ 
			$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_5_d WHERE pfg5_id = '$reg_fa[pfg5_id]' and pfd5_ren = '$i' ");
			$reg_fad = mysqli_fetch_array($cad_fad);

			if($i == 2 and $reg_fad['pfd5_ce'] == ''){$strVal = '';}/*else{$strVal = 'NO';}*/

			$fltVal = fnc_parametro_max(10, 'C');
			
			$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 10);
						
			if($i <= 4 or $bolVal == 'Si' ){
			?>
			<tr>
			    <td>&nbsp;</td>
				<td align="center"><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen".$i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen".$i ?>">
				<input type="hidden" class="form-control" id="<?php echo "hddRen".$i ?>" name="<?php echo "hddRen".$i ?>" value="<?php echo $reg_fad['pfd5_id'];?>" /></td>
				
				<td><input type="time" class="form-control" id="<?php echo "txtHraIni".$i ?>" name="<?php echo "txtHraIni".$i ?>" value="<?php if($reg_fad['pfd5_hr_ini'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd5_hr_ini']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> ></td>
				<td><input type="time" class="form-control" id="<?php echo "txtHraFin".$i ?>" name="<?php echo "txtHraFin".$i ?>" value="<?php if($reg_fad['pfd5_hr_fin'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd5_hr_fin']; $str_prop = 'disabled';} ?>" <?php echo $str_prop;  echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4;?>></td>
				<td><input type="time" class="form-control" id="<?php echo "txtHraIniMov".$i ?>" name="<?php echo "txtHraIniMov".$i ?>" value="<?php if($reg_fad['pfd5_hr_ini_mov'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd5_hr_ini_mov']; $str_prop = 'disabled';} ?>" <?php echo " ".$str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?>></td>
				<td><input type="time" class="form-control" id="<?php echo "txtHraFinMov".$i ?>" name="<?php echo "txtHraFinMov".$i ?>" value="<?php if($reg_fad['pfd5_hr_fin_mov'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd5_hr_fin_mov']; $str_prop = 'disabled';} ?>" <?php echo " ".$str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?>></td>
				<td><input type="text" class="form-control" id="<?php echo "txtPh".$i ?>" name="<?php echo "txtPh".$i ?>" value="<?php if($reg_fad['pfd5_ph'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd5_ph']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?> size="5" placeholder="pH"></td>
				<td align="center"><input 
					<?php if($fltVal >= $reg_fad['pfd5_ce'] and $reg_fad['pfd5_ce'] != ''){?>style="background-color:#66FF66;"<?php }?>
					type="text" class="form-control" id="<?php echo "txtCe".$i ?>" name="<?php echo "txtCe".$i ?>" value="<?php if($reg_fad['pfd5_ce'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd5_ce']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?> size="5" style="width:100px;" placeholder="Ce"></td>
					<td><input type="text" class="form-control" id="<?php echo "txtTemp".$i ?>" name="<?php echo "txtTemp".$i ?>" value="<?php if($reg_fad['pfd5_temp'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd5_temp']; $str_prop = 'disabled';} ?>" <?php echo $str_prop;  echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> size="5" placeholder="Temp"></td>
					<td><input type="text" class="form-control" id="<?php echo "txtObs".$i ?>" name="<?php echo "txtObs".$i ?>" value="<?php if($reg_fad['pfd5_observaciones'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd5_observaciones']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?> size="5" placeholder="Observaciones"></td>
					
				</tr>
			<?php 
			$valUltRen = $i;
			}//termina if
							
	}// termina for?>
		</table>

	</div>
	
	
  <?php 
/*$strVal = 'NO';
$i = 1;
 //echo "SELECT * FROM procesos_fase_5_d WHERE pfg5_id = '$reg_fa[pfg5_id]' and pfd5_ren = '$i' ";
$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_5_d WHERE pfg5_id = '$reg_fa[pfg5_id]' and pfd5_ren = '$i' ");
$reg_fad = mysqli_fetch_array($cad_fad);

if($i == 2 and $reg_fad['pfd5_ce'] == ''){$strVal = '';}/*else{$strVal = 'NO';}

$fltVal = fnc_parametro_max(10, 'C');
  ?>
	<div class="row">	
			<div class="form-group col-md-1" style="width: 70px">
				<label for="inputPassword3" style="margin-bottom: 10px;color: #fff">.</label>
				<label for="inputPassword3" style="margin-bottom: 10px">1 Lav</label>
			</div>
			<div class="form-group col-md-1" style="width: 150px">
				<label for="inputPassword4">HR inicia llenado </label>	
				<input type="hidden" class="form-control" id="<?php echo "txtRen".$i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen".$i ?>">			
				<input type="time" class="form-control" id="<?php echo "txtHraIni".$i ?>" name="<?php echo "txtHraIni".$i ?>" value="<?php if($reg_fad['pfd5_hr_ini'] == ''){echo date("H:i"); $str_prop = '';}else{echo $reg_fad['pfd5_hr_ini']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> >
			</div>		
			<div class="form-group col-md-1" style="width: 160px">
				<label for="inputPassword4">HR termina llenado </label>		
				<input type="time" class="form-control" id="<?php echo "txtHraFin".$i ?>" name="<?php echo "txtHraFin".$i ?>" value="<?php if($reg_fad['pfd5_hr_fin'] == ''){echo date("H:i"); $str_prop = '';}else{echo $reg_fad['pfd5_hr_fin']; $str_prop = 'disabled';} ?>" <?php echo $str_prop;  echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4;?>>
			</div>		
			<div class="form-group col-md-1" style="width: 180px">
				<label for="inputPassword4">HR inicia movimiento </label>		
				<input type="time" class="form-control" id="<?php echo "txtHraIniMov".$i ?>" name="<?php echo "txtHraIniMov".$i ?>" value="<?php if($reg_fad['pfd5_hr_ini_mov'] == ''){echo date("H:i"); $str_prop = '';}else{echo $reg_fad['pfd5_hr_ini_mov']; $str_prop = 'disabled';} ?>" <?php echo " ".$str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?>>
			</div>		
			<div class="form-group col-md-1" style="width: 190px">
				<label for="inputPassword4">HR termina movimiento </label>					
				<input type="time" class="form-control" id="<?php echo "txtHraFinMov".$i ?>" name="<?php echo "txtHraFinMov".$i ?>" value="<?php if($reg_fad['pfd5_hr_fin_mov'] == ''){echo date("H:i"); $str_prop = '';}else{echo $reg_fad['pfd5_hr_fin_mov']; $str_prop = 'disabled';} ?>" <?php echo " ".$str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?>>
			</div>		
			<div class="form-group col-md-1">
				<label for="inputPassword4">CE</label>					
				<input type="text" class="form-control" id="<?php echo "txtCe".$i ?>" name="<?php echo "txtCe".$i ?>" value="<?php if($reg_fad['pfd5_ce'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd5_ce']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?> size="5" style="width:80px;">
			</div>
			<div class="form-group col-md-1">
				<label for="inputPassword4">pH</label>				
				<input type="text" class="form-control" id="<?php echo "txtPh".$i ?>" name="<?php echo "txtPh".$i ?>" value="<?php if($reg_fad['pfd5_ph'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd5_ph']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?> size="5">
			</div>
			<div class="form-group col-md-1">
				<label for="inputPassword4">TEMP</label>				
				<input type="text" class="form-control" id="<?php echo "txtTemp".$i ?>" name="<?php echo "txtTemp".$i ?>" value="<?php if($reg_fad['pfd5_temp'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd5_temp']; $str_prop = 'disabled';} ?>" <?php echo $str_prop;  echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> size="5">
			</div>
	</div>
	<div class="row" id="campos5"></div><?php */?>


	<!--estilo general de estapas-->
	<div class="row">
		<div class="form-row">
			<div class="form-group col-md-2">
				<label for="inputPassword4">Fecha termina</label>
				<input type="date" class="form-control" id="txtFeTerm" placeholder="" name="txtFeTerm" value="<?php if($reg_aux['proa_fe_fin'] == ''){echo ""; $str_prop = '';}else{echo $reg_aux['proa_fe_fin']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3." ".$strProp4; ?>>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-2">
				<label for="inputPassword4">Hora termina</label>
				<input type="time" class="form-control" id="txtHrTerm" placeholder="" name="txtHrTerm" value="<?php if($reg_aux['proa_hr_fin'] == ''){echo ""; $str_prop = '';}else{echo $reg_aux['proa_hr_fin']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3." ".$strProp4; ?>>
			</div>
		</div>
		<!--<div class="form-row">
			<div class="form-group col-md-2">
				<label for="inputPassword4">Realizó</label>
				<select type="text" class="form-control" id="inputPassword4" placeholder=""></select>
			</div>
		</div>-->
		<!--<div class="form-row">
			<div class="form-group col-md-1">
				<label for="inputPassword4">Agua a:</label>
				<input type="text" class="form-control" id="inputPassword4" placeholder="">
			</div>
		</div>-->
		<div class="form-row">
			<div class="form-group col-md-2">
				<label for="inputPassword4">(<?php echo fnc_hora_de(10) ?> a <?php echo fnc_hora_a(10) ?> Horas)</label>
			</div>
		</div>
		<div class="col-md-3 divEtapas">
			<div class="col-md-1 etapa">
				<p class="numEtapa">5</p>
			</div>
			<div class="col-md-2 divEtapasInput">
				<label class="etiquetaEtapa">LIBERACION CE <?php echo fnc_rango_a(10) ?> MAX</label>
				<input  type="text" id="txtCeLib" class="form-control" placeholder="Ce liberacion" name="txtCeLib" value="<?php if($reg_lib['prol_ce'] == ''){ echo ""; $str_prop = '';}else{echo $reg_lib['prol_ce']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
				<input  type="text" id="txtHrTotales" class="form-control" placeholder="Horas totales" name="txtHrTotales" value="<?php if($reg_lib['prol_hr_totales'] == ''){ echo ""; $str_prop = '';}else{echo $reg_lib['prol_hr_totales']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
				<input  type="text" id="txtUsuario" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
				<!--<input  type="text" id="inputPassword" class="form-control" placeholder="Firma LCP">-->
			</div>	
		</div>
	</div>
	<!---->

	<!--textareaobservaciones-->
	<div class="row">
		<div class="col-md-7 textareaObservaciones">
			<label for="inputPassword4">Observaciones</label>
			<!--<label class="col-md-1"  style="width: 50px">1er</label>-->
			<textarea type="textarea" class="form-control" id="" placeholder="Observaciones..." name="txaObservaciones" value="<?php if($reg_aux['proa_observaciones'] == ''){$str_prop = '';}else{$str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3; echo " ".$strProp5." ".$strProp4;?>><?php echo $reg_aux['proa_observaciones']; ?></textarea>
		</div>
	</div>

 <!--barra botones-->
	<div class="row footerdivProcesos" style="margin-bottom: 10px;">

		<div class="form-group col-md-7">
			<div class="alert alert-info hide" id="alerta-errorFase5Ope" style="height: 40px;width: 270px;text-align: left;z-index: 10;font-size: 10px;margin-bottom: -10px">
				<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
				<strong>Titulo</strong> &nbsp;&nbsp;
				<span> Mensaje </span>
			</div>
		</div>

		<?php if($_SESSION['privilegio'] == 4){	?>

				<div class="col-md-1">
					<!--Nota: si no ocupa es-->
					<button type="button" class="btn btn-success" id="paleto" onClick="javascript:AbreModalPaleto(<?php echo $reg_pro['pro_id'] ?>, <?php echo $id_l ?>);"> 		<img src="../iconos/procesos2.png" alt="">Paleto
					</button>
				</div>
				<div class="col-md-1">
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE5(<?php echo $reg_pro['pro_id'] ?>, 10);"> <img src="../iconos/edit.png" alt="">Editar
					</button>
				</div>
				<div class="col-md-2" style="width: 110px">
					<button type="button" class="btn btn-success" id="permitir" onClick="javascript:AbreModalAgregarR(<?php echo $reg_pro['pro_id'] ?>, 10, <?php echo $valUltRen ?>);"> <img src="../iconos/add.png" alt="">Renglon
					</button>
				</div>
			<?php } ?>
			<div class="col-md-1" style="float: right;margin-right: 80px">
				<?php 
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 4)
				{
					 if($reg_fa['pfg5_id'] != '')
					 {
				?>
						<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>	
				<?php 
					}else{echo "N/A Guardar";}
				} else{?>
					<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>	
				<?php }?>	
			</div>
	</div>

</form>
<div class="modal" id="modalRenglon5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
<div class="modal" id="modalPaleto5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
<div class="modal" id="modalEditar5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

</div>