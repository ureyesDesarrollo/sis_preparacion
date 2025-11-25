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

	$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 17");
	$reg_aux = mysqli_fetch_array($cad_aux);

	$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_7_g WHERE pro_id = '$reg_pro[pro_id]'");
	$reg_fa = mysqli_fetch_array($cad_fa);

	$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion_b WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 17");
	$reg_lib = mysqli_fetch_array($cad_lib);

if($_SESSION['privilegio'] == 3 ){$strProp1 = 'disabled';$strProp6 = '';}else{$strProp1 = '';}//Operador
if($_SESSION['privilegio'] == 4 ){$strProp2 = 'disabled';}else{$strProp2 = '';}//Supervidor
if($_SESSION['privilegio'] == 6 ){$strProp3 = 'disabled';}else{$strProp3 = '';}//Laboratorio

//Para capturar primero los datos generales
if($reg_aux['proa_id'] == ''){$strProp4 = 'readonly';}else{$strProp4 = '';}

//Para bloquear las observaciones si capturo los datos el supervisor.
if($reg_aux['proa_fe_fin'] != ''){$strProp5 = 'readonly';}else{$strProp5 = '';}
?>
<script>
	a = 6;
	function agregarCampo7(){
		a++;
		var div = document.createElement('div');
		div.setAttribute('class', 'row');

		div.innerHTML = 
		'<div  class="form-group col-md-2" style="width: 110px;margin-right: -20px;margin-top:-15px"><label name="lblMov">Movimiento</label></div>'+

		'<div class="form-group col-md-2" style="width: 130px;margin-right: -20px;margin-top:-15px"><input class="form-control" name="txtMov'+a+'" type="text"/></div>'+
		'<div class="form-group col-md-1" style="width: 30px;padding-right: 50px;margin-top:-15px"><label>MIN</label></div>'+

		'<div class="form-group col-md-2" style="width: 140px;margin-right: -20px;margin-top:-15px"><label style="padding-bottom: 12px" name="lblHIniDrenado">Hr ini. drenado</label></div>'+

		'<div class="form-group col-md-1" style="width: 100px;margin-right: -20px;margin-top:-15px"><label class="form-control" name="txtHIniDrenado'+a+'" type="text"/></div>'+

		'<div class="form-group col-md-2" style="width: 140px;margin-right: -20px;margin-top:-15px"><label style="padding-bottom: 12px" name="lblHIniDrenado">Hr fin. drenado</label></div>'+

		'<div class="form-group col-md-2" style="width: 100px;margin-right: -20px;margin-top:-15px"><input class="form-control" name="txtFinDrenado'+a+'" type="text"/></div>';
		document.getElementById('campos7').appendChild(div);document.getElementById('campos7').appendChild(div);
	}
	
	$(document).ready(function()
	{	
		$("#formFase7").submit(function(){

			for(var i = 1; i <= 5; i++){
				cocido = $("#txtPhLib"+i).val();
				ce = $("#txtCeLib"+i).val();
				cuero = $("#txtCue_sob"+i).val();
				ext = $("#txtpor_ext"+i).val();

				if (cocido != '' && (ce == '' || cuero == '' || ext == '')) 
				{
					alert("Hay campo vacios en renglon "+i+" de cocidos");
					intban = 1;
					//break
					return false;
				}
				else{
					intban = 0;
				}
			}
			if(intban == 0)
			{
				var formData = $(this).serialize();
				$.ajax({
					url: "fase_7_insertar.php",
					type: 'POST',
					data: formData,
					success: function(result) {

						data = JSON.parse(result);
						alertas("#alerta-errorFase7Ope", 'Listo!', data["mensaje"], 1, true, 5000);
						$('#formFase7').each (function(){this.reset();}); 
						setTimeout("location.reload()", 2000); 
					}
				});
				return false;
			}

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
				$("#modalRenglon7").html(result);
				$('#modalRenglon7').modal('show')
			}	
		});
		return false;
	}

	function AbreModalE7(proceso, etapa){
		var datos={
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type:'post',
			url: 'editar/fase_7.php',
			data: datos,
			success: function(result){
				$("#modalEditar7").html(result);
				$('#modalEditar7').modal('show')
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

	function AbreModalPaletoB(proceso, lavador){
		var datos={
			"pro_id": proceso,
			"lavador": lavador
		}
		//alert($("hdd_pro_id").val());
		$.ajax({
			type:'post',
			//url: 'modal_paleto2.php',
			url: 'modal_paleto3.php',
			data: datos,
			//data: {nombre:n},
			success: function(result){
				$("#modalPaleto7").html(result);
				$('#modalPaleto7').modal('show')
			}	
		});
		return false;
	}
</script>
<div class="divProcesos">
	<form id="formFase7" name="formFase7">
		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id"/>
		<input name="hdd_pe_id" type="hidden" value="17" id="hdd_pe_id"/>
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>"/>
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg7_id']; ?>"/>
		<!--<input name="hdd_lav" type="hidden" id="hdd_lav" value="<?php echo $id_l; ?>"/>-->
		<input type="hidden" value="<?php //echo $_GET['id_e'] ?>" name="txt_equipo">
		<!--<input name="hdd_tipo" type="hidden" id="hdd_tipo" value="<?php //echo $reg_pro['pt_id']; ?>"/>-->

		<div class="headerdivProcesos">
			<div class="col-md-2" >LAVADOS FINALES</div>
			<div class="col-md-4" >Este proceso se utilizará: Agua recuperada de los ultimos lavados finales de otro paleto y/o agua limpia</div>
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
		</div>
		<!--Tabla-->

		<table border="0" cellspacing="5" cellpadding="5">
			<tr class="etiqueta_tbl">
				<td width="15">&nbsp;</td>
				<td>Lav</td>
				<td><span style="color: red;font-weight: bold;"> * </span> Tipo Agua</td>
				<td><span style="color: red;font-weight: bold;"> * </span> Movimiento</td>
				<td>&nbsp;</td>
				<td>Hr ini. drenado</td>
				<td>Hr fin. drenado</td>
				<td>Ph</td>
				<td>Ce</td>
				<td>TEMP</td>
				<td>Agua a</td>
				<td>Observaciones</td>
				<td rowspan="4">Para hacer los lavados finales deben tardar de (6 a 10 horas)</td>
			</tr>
			<?php for($i = 1; $i <=10; $i++){ 

				$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_7_d WHERE pfg7_id = '$reg_fa[pfg7_id]' and pfd7_ren = '$i' ");
				$reg_fad = mysqli_fetch_array($cad_fad);

				$fltVal = fnc_parametro_max(17, 'P');

				$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 17);

				if($i <= 5 or $bolVal == 'Si' ){
					?>
					<tr>
						<td>&nbsp;</td>
						<td><?php echo $i; ?></td>
						<td><input type="hidden" class="form-control" id="<?php echo "txtRen".$i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen".$i ?>">
							<input type="hidden" class="form-control" id="<?php echo "hddRen".$i ?>" name="<?php echo "hddRen".$i ?>" value="<?php echo $reg_fad['pfd7_id'];?>" />
							<select  id="cbxTipAg<?php echo $i  ?>" class="form-control" style="width: 150px" name="cbxTipAg<?php echo $i  ?>" <?php if($reg_fad['tpa_id'] == ''){echo ""; $str_prop = '';}else{ $str_prop = 'disabled';} echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?>>
								<option value="">Seleccionar</option>
								<?php 
								$cadena =  mysqli_query($cnx,"SELECT * from tipos_agua ORDER BY tpa_descripcion ");
								$registros =  mysqli_fetch_array($cadena);

								do
								{
									?><option value="<?php echo $registros['tpa_id'] ?>" <?php if($registros['tpa_id'] == $reg_fad['tpa_id']){ ?>selected="selected"<?php }?>><?php echo $registros['tpa_descripcion'] ?></option><?php
								}while($registros =  mysqli_fetch_array($cadena));


								?>
							</select></td>
							<td>
								<input type="text" class="form-control" id="<?php echo "txtMov".$i ?>" name="<?php echo "txtMov".$i ?>" value="<?php if($reg_fad['pfd7_mov'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd7_mov']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> placeholder="Mov"></td>
								<td>MIN</td>
								<td><input type="time" class="form-control" id="<?php echo "txtHIniDrenado".$i ?>" name="<?php echo "txtHIniDrenado".$i ?>" value="<?php if($reg_fad['pfd7_hr_ini_dren'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd7_hr_ini_dren']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?>></td>
								<td><input type="time" class="form-control" id="<?php echo "txtHFinDrenado".$i ?>" name="<?php echo "txtHFinDrenado".$i ?>" value="<?php if($reg_fad['pfd7_hr_fin_dren'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd7_hr_fin_dren']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?>></td>
								<?php //if($i <= 3){ ?>
									<td><input type="text" name="txtPh<?php echo $i; ?>" id="txtPh<?php echo $i; ?>" class="form-control" placeholder="PH"  value="<?php if($reg_fad['pfd7_ph'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd7_ph']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> size="7" <?php if($fltVal >= $reg_fad['pfd7_ph'] and $reg_fad['pfd7_ph'] != ''){?>style="background-color:#66FF66;"<?php }?>/></td>
									<td><input type="text" name="txtCe<?php echo $i; ?>" id="txtCe<?php echo $i; ?>" class="form-control" placeholder="CE" value="<?php if($reg_fad['pfd7_ce'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd7_ce']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> size="7"/></td>
									<td><input type="text" name="txtTemp<?php echo $i; ?>" id="txtTemp<?php echo $i; ?>" class="form-control" placeholder="TEMP" value="<?php if($reg_fad['pfd7_temp'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd7_temp']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo " ".$strProp4; ?> size="7" /></td>
									<td align="center">
										<select id="cbxAgua<?php echo $i  ?>" class="form-control" style="width: 180px" name="cbxAgua<?php echo $i  ?>" <?php if($reg_fad['taa_id'] == ''){echo ""; $str_prop = '';}else{ $str_prop = 'disabled';} echo $str_prop; echo " ".$strProp2; echo " ".$strProp3." ".$strProp4; ?> >
											<option value="">Seleccionar</option>
											<?php 
											$cadena =  mysqli_query($cnx,"SELECT * from tipos_agua_a ORDER BY taa_descripcion");
											$registros =  mysqli_fetch_array($cadena);

											do
											{
												?><option value="<?php echo $registros['taa_id'] ?>" <?php if($registros['taa_id'] == $reg_fad['taa_id']){ ?>selected="selected"<?php }?>><?php echo $registros['taa_descripcion'] ?></option><?php
											}while($registros =  mysqli_fetch_array($cadena));
											?>
										</select>
									</td>
									<td><input type="text" class="form-control" id="<?php echo "txtObs".$i ?>" name="<?php echo "txtObs".$i ?>" value="<?php if($reg_fad['pfd7_observaciones'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd7_observaciones']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;  echo " ".$strProp4;?> size="5" placeholder="Observaciones"></td>
	<?php /*}else{ ?>
	<td colspan="3">&nbsp;</td>
<?php }*/?>
</tr>
<?php 
$valUltRen = $i;
  }//termina if

}// termina for?>
</table>

<!--	
	<div class="row">
		<div class="form-row">
			<div class="form-group col-md-2" style="width: 130px;margin-right: -20px">
				<label style="padding-bottom: 12px" for="inputPassword3" name="lblMov">Movimiento</label>  			
				<?php /*for($i = 1; $i <=5; $i++){ ?>
					<input type="text" class="form-control" id="<?php echo "txtMov".$i ?>">
				<?php }?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 50px;margin-top: 30px">
				<?php for($i = 1; $i <=5; $i++){ ?>
					<label style="padding-bottom: 12px" for="inputPassword3" name="lblMin">MIN</label>
				<?php }?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-row">
				<div class="form-group col-md-2" style="margin-right: -20px">
					<label style="padding-bottom: 12px" for="inputPassword3" name="lblHIniDrenado">Hr ini. drenado</label>  			

					<?php for($i = 1; $i <=5; $i++){ ?>
						<input type="text" class="form-control" id="<?php echo "txtHIniDrenado".$i ?>">
					<?php }?>
				</div>
			</div>
		</div>
		<div class="form-row">

			<div class="form-row">
				<div class="form-group col-md-2" style="margin-right: -20px">
					<label style="padding-bottom: 12px" for="inputPassword3" name="lblHFinDrenado">Hr fin. drenado</label>  			

					<?php for($i = 1; $i <=5; $i++){ ?>
						<input type="text" class="form-control" id="<?php echo "txtHFinDrenado".$i ?>">
					<?php }?>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 30px;">
				<?php for($i = 1; $i <=3; $i++){ ?>
					<label style="padding-bottom: 20px;padding-top:10px" for="inputPassword3">PH</label>  			
				<?php }?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: ">
				<?php for($i = 1; $i <=3; $i++){ ?>
					<input style="margin-bottom: 25px;margin-top:10px" placeholder="____" type="text" class="form-control" id="<?php echo "txtLavTipAgua".$i ?>">
				<?php }?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 30px">
				<?php for($i = 1; $i <=3; $i++){ ?>
					<label style="padding-bottom: 20px;padding-top:10px" for="inputPassword3">CE</label>  			
				<?php }?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1">
				<?php for($i = 1; $i <=3; $i++){ ?>
					<input style="margin-bottom: 25px;margin-top:10px" placeholder="____" type="text" class="form-control" id="<?php echo "txtLavTipAgua".$i ?>">
				<?php }?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: 50px">
				<?php for($i = 1; $i <=3; $i++){ ?>
					<label style="padding-bottom: 20px;padding-top:10px" for="inputPassword3">TEMP</label>  			
				<?php }?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-1" style="width: ">
				<?php for($i = 1; $i <=3; $i++){ ?>
					<input style="margin-bottom: 25px;margin-top:10px" placeholder="____" type="text" class="form-control" id="<?php echo "txtLavTipAgua".$i ?>">
				<?php }*/?>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-2" style="text-align: justify;">
				Para hacer los lavados finales deben tardar de (6 a 10 horas)
			</div>
		</div>
	</div>
-->

<div  id="campos7">.</div>
<!--tiempos-->
<div class="col-md-8">
	<div class="col-md-4">
		<label>Fecha term lavados finales</label>
		<input type="date" class="form-control" id="txtFeTerm" placeholder="" name="txtFeTerm" value="<?php if($reg_aux['proa_fe_fin'] == ''){echo ""; $str_prop = '';}else{echo $reg_aux['proa_fe_fin']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3." ".$strProp4; ?>>
	</div>
	<div class="col-md-2">
		<label>Hora termina</label>
		<input type="time" class="form-control" id="txtHrTerm" placeholder="" name="txtHrTerm" value="<?php if($reg_aux['proa_hr_fin'] == ''){echo ""; $str_prop = '';}else{echo $reg_aux['proa_hr_fin']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3." ".$strProp4; ?>>
	</div>
	<div class="col-md-2">
		<label>Hora totales</label>
		<input  type="text" id="txtHrTotales1" class="form-control" placeholder="Horas totales" name="txtHrTotales1" value="<?php 
		$str_estilo = '';
		if($reg_fa['pfg7_hr_totales'] == '0'){ echo ""; $str_prop = ''; $str_estilo = "style='background-color:#FFFF99;'";}else{echo $reg_fa['pfg7_hr_totales']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp3; echo " ".$str_estilo;?>>
	</div>
	<div class="col-md-2">
		<label>Realizó</label>
		<input  type="text" id="txtRealizo" class="form-control" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
	</div>
	<label for="inputPassword4">(6 a 10 horas)</label>

	<!---->

	<div class="col-md-11" style="border: 1px solid#e6e6e6;border-radius: 5px;background: #e6e6e6;margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
		<div class="col-md-5 tiempos" style="margin-left: -25px;">
			<label>Hrs totales de todo el proceso</label>
			<input  type="text" id="txtHrasTotales" class="form-control" placeholder="Horas totales" name="txtHrasTotales" value="<?php if($reg_fa['pfg7_hr_totales2'] == ''){ echo fnc_horas($strFech, date("Y-m-d"), $strHr, date("H:i")); $str_prop = '';$str_estilo = "style='background-color:#FFFF99;'";}else{echo $reg_fa['pfg7_hr_totales2']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp3; echo " ".$str_estilo;?>>
		</div>
		<div class="col-md-2">
			<label>Revisó</label>
			<input  type="text" id="txtRealizo" class="form-control" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
		</div>
		<!--<div class="col-md-2">
			<label>Firma Revisó</label>
			<select type="time" class="form-control" id="inputPassword" placeholder=""></select>
		</div>-->
		<label for="inputPassword4">(<?php echo fnc_hora_de(17) ?> a <?php echo fnc_hora_a(17) ?> horas)</label>
	</div>


	<!--tiempos-->
	<div class="col-md-4">
		<label>Fecha liberación de paleto</label>
		<input type="date" class="form-control" id="txtFeLibPal" name="txtFeLibPal" placeholder="" value="<?php if($reg_fa['pfg7_fe_lib_pal'] == ''){echo date("Y-m-d"); $str_prop = '';$str_estilo = "style='background-color:#FFFF99;'";}else{echo $reg_fa['pfg7_fe_lib_pal']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp3;  echo " ".$str_estilo; ?>>
	</div>
	<div class="col-md-2">
		<label>Hora</label>
		<input type="time" class="form-control" id="txtHrLibPal" name="txtHrLibPal" placeholder="" value="<?php if($reg_fa['pfg7_hr_lib_pal'] == ''){echo ""; $str_prop = '';}else{echo $reg_fa['pfg7_hr_lib_pal']; $str_prop = 'disabled';$str_estilo = "style='background-color:#FFFF99;'";} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp3;  echo " ".$str_estilo;?>>
	</div>
	<div class="col-md-4">
		<label>Fecha sale a producción</label>
		<input type="date" class="form-control" id="txtFeLibProd" name="txtFeLibProd" placeholder="" value="<?php if($reg_fa['pfg7_fe_lib_prod'] == ''){echo date("Y-m-d"); $str_prop = '';}else{echo $reg_fa['pfg7_fe_lib_prod']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp3; ?>>
	</div>
	<div class="col-md-2">
		<label>Hora</label>
		<input type="time" class="form-control" id="txtHrLibProd" name="txtHrLibProd" placeholder="" value="<?php if($reg_fa['pfg7_hr_lib_prod'] == ''){echo date("H:i"); $str_prop = '';}else{echo $reg_fa['pfg7_hr_lib_prod']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp3; ?> />
	</div>
	<!---->



	<!--textareaobservaciones-->
	<div class="row col-md-10">
		<label for="inputPassword4">Observaciones</label>
		<textarea type="textarea" class="form-control" id="" placeholder="Observaciones..." name="txaObservaciones" value="<?php if($reg_aux['proa_observaciones'] == ''){$str_prop = '';}else{$str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp6; echo " ".$strProp3; echo " ".$strProp5." ".$strProp4; ?>><?php echo $reg_aux['proa_observaciones']; ?></textarea>
	</div>
</div>



<div class="col-md-4 divEtapas7_mod">
	<div class="col-md-1 etapa" style="height: 362px">
		<p class="numEtapa_mod">7</p>
	</div>
	<div class="col-md-12 divEtapasInput7_mod">
		<label class="etiquetaEtapa_mod">COCIDO PH LIBERACIÓN ( PH <?php echo fnc_rango_de(17) ?> - <?php echo fnc_rango_a(17) ?>)</label>
		<input type="date" class="form-control col-md-1" style="width: 140px" id="txtFeLib" name="txtFeLib" placeholder=""  value="<?php if($reg_lib['prol_fecha'] == ''){ echo ""; $str_prop = '';}else{echo $reg_lib['prol_fecha']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
		<input type="time" class="form-control col-md-1" style="width: 110px" id="txtHrLib" name="txtHrLib" placeholder=""  value="<?php if($reg_lib['prol_hora'] == ''){ echo ""; $str_prop = '';}else{echo $reg_lib['prol_hora']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>

		<?php
		for($i = 1; $i <=5; $i++){ ?>
			<input type="hidden" name="<?php echo "R".$i ?>" value="<?php echo $i ?>">
			<input onkeypress="return isNumberKey(event, this);" type="tex" class="form-control col-md-1" style="width: 100px" id="<?php echo "txtPhLib".$i ?>" name="<?php echo "txtPhLib".$i ?>" placeholder="(<?php echo "L".$i ?>) Cocido ph"  value="<?php if($reg_lib['prol_cocido_ph1'] == ''){ echo ""; $str_prop = '';}else{echo $reg_lib['prol_cocido_ph1']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?> />
			<input onkeypress="return isNumberKey(event, this);" type="text" class="form-control col-md-1" style="width: 55px" id="<?php echo "txtCeLib".$i ?>" name="<?php echo "txtCeLib".$i ?>" placeholder="Ce"  value="<?php if($reg_lib['prol_ce1'] == ''){ echo ""; $str_prop = '';}else{echo $reg_lib['prol_ce1']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
			<input onkeypress="return isNumberKey(event, this);" type="text" class="form-control col-md-1" style="width: 75px" id="<?php echo "txtCue_sob".$i ?>" name="<?php echo "txtCue_sob".$i ?>" placeholder="Cuero sob"  value="<?php if($reg_lib['prol_cu1'] == ''){ echo ""; $str_prop = '';}else{echo $reg_lib['prol_cu1']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
			<input onkeypress="return isNumberKey(event, this);" type="text" class="form-control col-md-1" style="width: 50px" id="<?php echo "txtpor_ext".$i ?>" name="<?php echo "txtpor_ext".$i ?>" placeholder="% ext"  value="<?php if($reg_lib['prol_ext1'] == ''){ echo ""; $str_prop = '';}else{echo $reg_lib['prol_ext1']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>

		<?php } ?>

				<!--quitar de la consulta
					<input type="text" id="txtCocidoLib" name="txtCocidoLib" class="form-control" placeholder="Cocido liberación % ext" value="<?php if($reg_lib['prol_cocido_lib'] == ''){ echo ""; $str_prop = '';}else{echo $reg_lib['prol_cocido_lib']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?> />-->

					<select  type="text" id="cbxColor" class="form-control" placeholder="Colores" name="cbxColor" <?php if($reg_lib['prol_color'] == ''){ echo ""; $str_prop = '';}else{echo "<option value='$reg_lib[prol_color]'>$reg_lib[prol_color]</option>"; $str_prop = 'disabled';} ?> <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
						<option value="">Color</option>
						<option value="1-A">1-A</option>
						<option value="1-B">1-B</option>
						<option value="1-C">1-C</option>
						<option value="1-D">1-D</option>
						<option value="2-A">2-A</option>
						<option value="2-B">2-B</option>
						<option value="2-C">2-C</option>
						<option value="2-D">2-D</option>
						<option value="3-A">3-A</option>
						<option value="3-B">3-B</option>
						<option value="3-C">3-C</option>
						<option value="3-D">3-D</option>
						<option value="4-A">4-A</option>
						<option value="4-B">4-B</option>
						<option value="4-C">4-C</option>
						<option value="4-D">4-D</option>
					</select>

					<!--Color de caldo agregar CC-->
					<select  type="text" id="cbxColor_caldo" class="form-control" placeholder="Colores" name="cbxColor_caldo" <?php if($reg_lib['prol_color_caldo'] == ''){ echo ""; $str_prop = '';}else{echo "<option value='$reg_lib[prol_color_caldo]'>$reg_lib[prol_color_caldo]</option>"; $str_prop = 'disabled';} ?> <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?>>
						<option value="">Color de caldo</option>
						<option value="1-A">1-A</option>
						<option value="1-B">1-B</option>
						<option value="1-C">1-C</option>
						<option value="1-D">1-D</option>
						<option value="2-A">2-A</option>
						<option value="2-B">2-B</option>
						<option value="2-C">2-C</option>
						<option value="2-D">2-D</option>
						<option value="3-A">3-A</option>
						<option value="3-B">3-B</option>
						<option value="3-C">3-C</option>
						<option value="3-D">3-D</option>
						<option value="4-A">4-A</option>
						<option value="4-B">4-B</option>
						<option value="4-C">4-C</option>
						<option value="4-D">4-D</option>
					</select>
					<input onkeypress="return isNumberKey(event, this);" type="text" id="txtSolides" name="txtSolides" class="form-control" placeholder="% de solidos" value="<?php if($reg_lib['prol_solides'] == ''){ echo ""; $str_prop = '';}else{echo $reg_lib['prol_solides']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?> />
					<textarea type="text" id="txta_obs" name="txta_obs" class="form-control" placeholder="Observaciones" value="<?php if($reg_lib['prol_observaciones'] == ''){ echo ""; $str_prop = '';}else{echo $reg_lib['prol_observaciones']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;?> /></textarea>
					<input  type="text" id="txtUsuario" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
				</div>	
			</div>

			<!--barra botones-->
			<div class="row footerdivProcesos" style="margin-bottom: 10px;">
				<div class="col-md-7">
					<label style="font-weight:bold; margin-left:20px;">El agua de este proceso se manda a (Pila 1) agua recuperada limpia</label>
				</div>

				<div class="row" style="margin-bottom: 10px">
	<i><label style="margin-left:20px;color: red"> * Los campos "Tipo Agua" y "Movimiento" deben ser capturados juntos</label></i>
</div>	

				<!--<div class="col-md-2" style="margin-bottom: 10px;"><input type="button" class="btn btn-success" id="add_cancion()" onClick="agregarCampo7()" value="+ Agregar campo" /></div>-->
				<div class="form-group col-md-7">
					<div class="alert alert-info hide" id="alerta-errorFase7Ope" style="height: 40px;width: 420px;text-align: left;z-index: 10;font-size: 10px;margin-bottom: -10px">
						<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
						<strong>Titulo</strong> &nbsp;&nbsp;
						<span> Mensaje </span>
					</div>
				</div>

				<?php if($_SESSION['privilegio'] == 4){	?>

					<div class="col-md-1">
						<button type="button" class="btn btn-success" id="paleto" onClick="javascript:AbreModalPaletoB(<?php echo $reg_pro['pro_id'] ?>, <?php echo $id_l ?>);"> 		<img src="../iconos/procesos2.png" alt="">Paleto
						</button>
					</div>
					<div class="col-md-1">
						<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE7(<?php echo $reg_pro['pro_id'] ?>, 17);"> 
							<img src="../iconos/edit.png" alt="">Editar
						</button>
					</div>
					<div class="col-md-2" style="width: 110px">
						<button type="button" class="btn btn-success" id="permitir" onClick="javascript:AbreModalAgregarR(<?php echo $reg_pro['pro_id'] ?>, 17, <?php echo $valUltRen ?>);"> <img src="../iconos/add.png" alt="">Renglon
						</button>
					</div>
				<?php } ?>
				<div class="col-md-1" style="float: right;margin-right: 80px">
					<?php 
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
					if($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 4)
					{
						if($reg_fa['pfg7_id'] != '')
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
		<div class="modal" id="modalRenglon7" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

		<div class="modal" id="modalEditar7" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

		<div class="modal" id="modalPaleto7" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	</div>