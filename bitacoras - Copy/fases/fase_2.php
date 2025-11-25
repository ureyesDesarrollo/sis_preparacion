<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
$reg_pro['pro_id'] = $idx_pro;

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 2");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 2");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 2");
$reg_lib = mysqli_fetch_array($cad_lib);

if ($_SESSION['privilegio'] == 3) {
	$strProp1 = 'disabled';
	$strProp6 = '';
} else {
	$strProp1 = '';
} //Operador
if ($_SESSION['privilegio'] == 4) {
	$strProp2 = 'readonly';
} else {
	$strProp2 = '';
} //Supervidor
if ($_SESSION['privilegio'] == 6) {
	$strProp3 = 'readonly';
} else {
	$strProp3 = '';
} //Laboratorio

//Para capturar primero los datos generales
if ($reg_aux['proa_id'] == '') {
	$strProp4 = 'readonly';
} else {
	$strProp4 = '';
}

//Para bloquear las observaciones si capturo los datos el supervisor.
if ($reg_aux['proa_fe_fin'] != '') {
	$strProp5 = 'readonly';
} else {
	$strProp5 = '';
}
?>
<script>
	a = 6;

	function agregarCampoFase2() {
		a++;
		var div = document.createElement('div');
		div.setAttribute('class', 'form-row');
		//div.setAttribute('style', 'margin-top:-20px');

		div.innerHTML =
			'<div  class="form-group col-md-1" style="width:100px"><label>' + a + 'ta Hora</label></div>' +
			'<div class="form-group col-md-1" ><input class="form-control" name="txtLavTipAgua' + a + '" type="text"/></div>' +
			'<label style="padding:0px" class="col-md-1">HR</label>' +
			'<label class="col-md-1" style="width:50px" >pH</label>' +
			'<div class="form-group col-md-1" ><input class="form-control" name="txtLavTipAgua' + a + '" type="text"/></div>' +
			'<label class="col-md-1">SOSA</label>' +
			'<div class="form-group col-md-1" ><input class="form-control" name="txtLavTipAgua' + a + '" type="text"/></div>' +
			'<label class="col-md-1">TEMP</label>' +
			'<div class="form-group col-md-1"><input class="form-control" name="txtPh' + a + '" type="text"/></div>';

		document.getElementById('camposFase2').appendChild(div);
		document.getElementById('camposFase2').appendChild(div);
	}

	$(document).ready(function() {
		$("#formFase2").submit(function() {
			var formData = $(this).serialize();
			$.ajax({
				url: "fases/fase_2_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase2Ope", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase2').each(function() {
						this.reset();
					});
					//setTimeout(location.reload(), 23000);
					setTimeout("location.reload()", 2000);
				}
			});
			return confirmEnviarFase2();
			return false;
		});
	});

	//Bloquear boton al agregar material
	function confirmEnviarFase2() {

		formFase2.btn.disabled = true;
		formFase2.btn.value = "Enviando...";

		setTimeout(function() {
			formFase2.btn.disabled = true;
			formFase2.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}

	function AbreModalEsp2(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_2_esp.php',
			data: datos,
			success: function(result) {
				$("#modalEditar2").html(result);
				$('#modalEditar2').modal('show')
			}
		});
		return false;
	}

	function AbreModalE2(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_2.php',
			data: datos,
			success: function(result) {
				$("#modalEditar2").html(result);
				$('#modalEditar2').modal('show')
			}
		});
		return false;
	}

	function AbreModalAgregarR(proceso, etapa, uren) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa,
			"uren": uren
		}
		//alert($("hdd_pro_id").val());
		$.ajax({
			type: 'post',
			url: 'modal_renglon.php',
			data: datos,
			//data: {nombre:n},
			success: function(result) {
				$("#modalRenglon2").html(result);
				$('#modalRenglon2').modal('show')
			}
		});
		return false;
	}

	function isNumberKeyFloat(evt, input) {
		// Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
		var key = window.Event ? evt.which : evt.keyCode;
		var chark = String.fromCharCode(key);
		var tempValue = input.value + chark;
		if (key >= 48 && key <= 57) {
			if (filter(tempValue) === false) {
				return false;
			} else {
				return true;
			}
		} else {
			if (key == 8 || key == 13 || key == 0) {
				return true;
			} else if (key == 46) {
				if (filter(tempValue) === false) {
					return false;
				} else {
					return true;
				}
			} else {
				return false;
			}
		}
	}

	function quimicos_2(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'quimicos/modal_quimicos.php',
			data: datos,
			success: function(result) {
				$("#m_modal_quimicos").html(result);
				$('#m_modal_quimicos').modal('show')
			}
		});
		return false;
	}
</script>
<div class="divProcesos">
	<form autocomplete="off" id="formFase2" name="formFase2">
		<input type="hidden" value="<?php //echo $_GET['id_e'] 
									?>" name="txt_equipo">

		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="2" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg2_id']; ?>" />

		<div class="headerdivProcesos">
			<div class="col-md-2">BLANQUEO</div>
			<!-- <div class="col-md-10">Este proceso se puede hacer con aguar recuperada limpia (pila 1) y limpia</div> -->
		</div>
		<?php //if($_SESSION['privilegio'] == 3 ){
		?>
		<div class="row" style="margin-bottom: 10px">
			<label class="col-md-1" style="width: 160px;">Fecha que inicia</label>
			<div class="col-md-2 tiempos">
				<input type="date" class="form-control" id="txtFeIni" placeholder="" name="txtFeIni" value="<?php if ($reg_aux['proa_fe_ini'] == '') {
																												echo date("Y-m-d");
																												$str_prop = '';
																											} else {
																												echo $reg_aux['proa_fe_ini'];
																												$str_prop = 'disabled';
																											} ?>" <?php echo $str_prop;
																													echo " " . $strProp2;
																													echo " " . $strProp3; ?> required>
			</div>

			<label class="col-md-1" style="width: 130px;">Hora inicio</label>
			<div class="col-md-2 tiempos">
				<input type="time" class="form-control" id="txtHrIni" placeholder="" name="txtHrIni" value="<?php if ($reg_aux['proa_hr_ini'] == '') {
																												echo date("H:i");
																												$str_prop = '';
																											} else {
																												echo $reg_aux['proa_hr_ini'];
																												$str_prop = 'disabled';
																											} ?>" <?php echo $str_prop;
																													echo " " . $strProp2;
																													echo " " . $strProp3; ?> required>
			</div>

			<!-- <label class="col-md-1" style="width: 200px;">Temp agua utilizada</label>
			<div class="col-md-2 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTempB" placeholder="TEMP" name="txtTempB" value="<?php if ($reg_fa['pfg2_temp_ag'] == '') {
																																														echo "";
																																														$str_prop = '';
																																													} else {
																																														echo $reg_fa['pfg2_temp_ag'];
																																														$str_prop = 'disabled';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3; ?> required>
			</div> -->
		</div>


		<div class="row" style="margin-bottom: 30px">
			<label class="col-md-1" style="width: 160px">pH antes de ajuste</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtPhAnt" placeholder="PH" name="txtPhAnt" value="<?php if ($reg_fa['pfg2_ph_ant'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa['pfg2_ph_ant'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
			</div>

			<label class="col-md-1" style="width: 40px">CE</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtCe" placeholder="CE" name="txtCe" value="<?php if ($reg_fa['pfg2_ce'] == '') {
																																												echo "";
																																												$str_prop = '';
																																											} else {
																																												echo $reg_fa['pfg2_ce'];
																																												$str_prop = 'disabled';
																																											} ?>" <?php echo $str_prop;
																																													echo " " . $strProp2;
																																													echo " " . $strProp3; ?> required>
			</div>

			<label class="col-md-1" style="width: 160px">Ajuste con SOSA</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtAjSosa" placeholder="SOSA" name="txtAjSosa" value="<?php if ($reg_fa['pfg2_sosa'] == '') {
																																														echo "";
																																														$str_prop = '';
																																													} else {
																																														echo $reg_fa['pfg2_sosa'];
																																														$str_prop = 'disabled';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3; ?> required>
			</div>
			<!--<label>LTS</label>-->

			<label class="col-md-1" style="width: 210px">pH ajustado (11.9 A 12.3)</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtPhAj" placeholder="PH" name="txtPhAj" value="<?php if ($reg_fa['pfg2_ph_aju'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa['pfg2_ph_aju'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
			</div>

			<label class="col-md-1" style="width: 90px">Peroxido</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtPeroxido" placeholder="PEROXIDO" name="txtPeroxido" value="<?php if ($reg_fa['pfg2_peroxido'] == '') {
																																																echo "";
																																																$str_prop = '';
																																															} else {
																																																echo $reg_fa['pfg2_peroxido'];
																																																$str_prop = 'disabled';
																																															} ?>" <?php echo $str_prop;
																																																	echo " " . $strProp2;
																																																	echo " " . $strProp3; ?> required>
			</div>

		</div>

		<div>
			<table>
				<tr>
					<td>
						<table border="0" cellspacing="5" cellpadding="5">

							<tr class="etiqueta_tbl">
								<td width="15">&nbsp;</td>
								<td>No.</td>
								<td>Hr</td>
								<td>Ph</td>
								<td align="center" bgcolor="#FF6493">REDOX</td>
								<td  width="8%"></td>
								<td width="10%" rowspan="6">
									MIN 340<br>
									PPM
								</td>
								<td width="95">SOSA LTS</td>
								<!-- <th>Peroxido</th> -->
								<td>TEMP</td>

								<!--<td>Acido</td>-->

							</tr>
							<?php
							for ($i = 1; $i <= 10; $i++) {
								$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_d WHERE pfg2_id = '$reg_fa[pfg2_id]' and pfd2_ren = '$i' ");
								$reg_fad = mysqli_fetch_array($cad_fad);

								$fltVal = fnc_parametro_max(2, 'P');

								$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 2);

								if ($i <= 4 or $bolVal == 'Si') {
							?>
									<tr>
										<td>&nbsp;</td>
										<td><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
										<td><input type="time" class="form-control" id="txtHr<?php echo $i; ?>" placeholder="" name="txtHr<?php echo $i; ?>" value="<?php if ($reg_fad['pfd2_hr'] == '') {
																																										echo date("H:i");
																																										$str_prop = '';
																																									} else {
																																										echo $reg_fad['pfd2_hr'];
																																										$str_prop = 'disabled';
																																									} ?>" <?php echo $str_prop;
																																											echo " " . $strProp2;
																																											echo " " . $strProp3;
																																											echo " " . $strProp4; ?>></td>
										<td>
											<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtPh<?php echo $i; ?>" placeholder="Ph" name="txtPh<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_ph'] == '') {
																																																													echo "";
																																																													$str_prop = '';
																																																												} else {
																																																													echo $reg_fad['pfd2_ph'];
																																																													$str_prop = 'disabled';
																																																												} ?>" <?php echo $str_prop;
																																																														echo " " . $strProp2;
																																																														echo " " . $strProp3;
																																																														echo " " . $strProp4; ?> <?php if ($fltVal >= $reg_fad['pfd2_ph'] and $reg_fad['pfd2_ph'] != '') { ?>style="background-color:#66FF66;" <?php } ?>>
										</td>
										<td align="center" bgcolor="#FF6493" width="100">
											<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtRedox<?php echo $i; ?>" placeholder="Redox" name="txtRedox<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_redox'] == '') {
																																																																echo "";
																																																																$str_prop = '';
																																																															} else {
																																																																echo $reg_fad['pfd2_redox'];
																																																																$str_prop = 'disabled';
																																																															} ?>" <?php echo $str_prop;
																																																																	echo " " . $strProp2;
																																																																	echo " " . $strProp3;
																																																																	echo " " . $strProp4; ?>>
										</td>
										<td>PPM</td>
										<td>
											<?php if ($i == 1 || $i == 3) { ?>
												<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtSosa<?php echo $i; ?>" placeholder="Sosa" name="txtSosa<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_sosa'] == '') {
																																																																echo "";
																																																																$str_prop = '';
																																																															} else {
																																																																echo $reg_fad['pfd2_sosa'];
																																																																$str_prop = 'disabled';
																																																															} ?>" <?php echo $str_prop;
																																																																	echo " " . $strProp2;
																																																																	echo " " . $strProp3;
																																																																	echo " " . $strProp4; ?>>
											<?php }	?>
										</td>
										<td>
											<?php if ($i == 2 || $i == 4) { ?>
												<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp<?php echo $i; ?>" placeholder="Temp" name="txtTemp<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_temp'] == '') {
																																																																echo "";
																																																																$str_prop = '';
																																																															} else {
																																																																echo $reg_fad['pfd2_temp'];
																																																																$str_prop = 'disabled';
																																																															} ?>" <?php echo $str_prop;
																																																																	echo " " . $strProp2;
																																																																	echo " " . $strProp3;
																																																																	echo " " . $strProp4; ?>>
											<?php }	?>
										</td>
									</tr>
							<?php $valUltRen = $i;
								}  //termina if

							}  // termina for
							?>
						</table>
					</td>
					<td width="30">&nbsp;</td>
					<td>
						<?php //if($_SESSION['privilegio'] == 6 ){
						?>
						<div class="col-md-3 divEtapas" style="height: 140px">
							<div class="col-md-1 etapa" style="height: 140px">
								<p class="numEtapa">2</p>
							</div>
							<div class="col-md-2 divEtapasInput">
								<label class="etiquetaEtapa">LIBERACION pH <?php echo fnc_rango_de(2) ?> - <?php echo fnc_rango_a(2) ?></label>
								<select type="text" id="cbxColor" class="form-control" placeholder="Colores" name="cbxColor" <?php if ($reg_lib['prol_color'] == '') {
																																	echo "";
																																	$str_prop = '';
																																} else {
																																	echo "<option value='$reg_lib[prol_color]'>$reg_lib[prol_color]</option>";
																																	$str_prop = 'disabled';
																																} ?> <?php echo $str_prop;
																																		echo " " . $strProp1;
																																		echo " " . $strProp2; ?>>
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
								<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" id="txtPhLib" class="form-control" placeholder="Ph liberacion" name="txtPhLib" value="<?php if ($reg_lib['prol_ph'] == '') {
																																																echo "";
																																																$str_prop = '';
																																															} else {
																																																echo $reg_lib['prol_ph'];
																																																$str_prop = 'disabled';
																																															} ?>" <?php echo $str_prop;
																																																	echo " " . $strProp1;
																																																	echo " " . $strProp2; ?>>
								<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" id="txtHrTotales" class="form-control" placeholder="Horas totales" name="txtHrTotales" value="<?php if ($reg_lib['prol_hr_totales'] == '') {
																																																		echo "";
																																																		$str_prop = '';
																																																	} else {
																																																		echo $reg_lib['prol_hr_totales'];
																																																		$str_prop = 'disabled';
																																																	} ?>" <?php echo $str_prop;
																																																			echo " " . $strProp1;
																																																			echo " " . $strProp2; ?>>

								<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" id="inputPassword" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
								<!--<input  type="text" id="inputPassword" class="form-control" placeholder="Firma LCP">-->
							</div>
						</div>
						<?php //}
						?>
					</td>
				</tr>
			</table>
		</div>
		<?php //}
		?>

		<div class="row" id="camposFase2">

		</div>
		<div class="row" style="margin-top: 20px">

			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">Fecha termina</label>
					<input type="date" class="form-control" id="txtFeTerm" placeholder="" name="txtFeTerm" value="<?php if ($reg_aux['proa_fe_fin'] == '') {
																														echo "";
																														$str_prop = '';
																													} else {
																														echo $reg_aux['proa_fe_fin'];
																														$str_prop = 'disabled';
																													} ?>" <?php echo $str_prop;
																															echo " " . $strProp6;
																															echo " " . $strProp3 . " " . $strProp4; ?>>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">Hora termina</label>
					<input type="time" class="form-control" id="txtHrTerm" placeholder="" name="txtHrTerm" value="<?php if ($reg_aux['proa_hr_fin'] == '') {
																														echo "";
																														$str_prop = '';
																													} else {
																														echo $reg_aux['proa_hr_fin'];
																														$str_prop = 'disabled';
																													} ?>" <?php echo $str_prop;
																															echo " " . $strProp6;
																															echo " " . $strProp3 . " " . $strProp4; ?>>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-5">
					<label for="inputPassword4">Observaciones</label>
					<!--<label class="col-md-1"  style="width: 50px">1er</label>-->
					<textarea type="textarea" class="form-control" id="" placeholder="Observaciones..." name="txaObservaciones" value="<?php if ($reg_aux['proa_observaciones'] == '') {
																																			$str_prop = '';
																																		} else {
																																			$str_prop = 'disabled';
																																		} ?>" <?php echo $str_prop;
																																				echo " " . $strProp6;
																																				echo " " . $strProp3;
																																				echo " " . $strProp5 . " " . $strProp4; ?>><?php echo $reg_aux['proa_observaciones']; ?></textarea>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">(<?php echo fnc_hora_de(2) ?> a <?php echo fnc_hora_a(2) ?> Horas totales)</label>
				</div>
			</div>
			<?php //}
			?>

		</div>

		<!--barra botones-->
		<div class="row footerdivProcesos" style="margin-bottom: 10px">
			<div class="form-group col-md-6">
				<div class="alert alert-info hide" id="alerta-errorFase2Ope" style="height: 40px;width: 300px;text-align: left;z-index: 10;font-size: 10px;">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
				<?php
				//Valida si el operador capturo quimicos en la etapa
				$cad_quim = mysqli_query($cnx, "SELECT * FROM quimicos_etapas WHERE pro_id = '$reg_pro[pro_id]' and pe_id = '2'");
				$tot_q = mysqli_num_rows($cad_quim);

				if($reg_fa['pfg2_peroxido'] != '0' and $tot_q == 0) {echo "<div style='background-color:#ccc0ff'>El operador debe capturar los  químicos </div>"; $str_bandera_q = "No";} else{$str_bandera_q = "Si"; }
				?>
			</div>
			<div class="col-md-6" <?php echo $oculta_opciones ?> style="text-align: right;margin-left:  -40px">
				<!--<div class="col-md-2" style="margin-bottom: 10px"><input type="button" class="btn btn-success" id="add_cancion()" onClick="agregarCampoFase2()" value="+ Agregar campo" /></div>-->
				<?php if ($_SESSION['privilegio'] == 4) {	?>
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE2(<?php echo $reg_pro['pro_id'] ?>, 2);">
						<img src="../iconos/edit.png" alt="">Editar
					</button>

					<button type="button" class="btn btn-success" id="permitir" onClick="javascript:AbreModalAgregarR(<?php echo $reg_pro['pro_id'] ?>, 2, <?php echo $valUltRen ?>);"> <img src="../iconos/add.png" alt="">Renglon
					</button>
				<?php } ?>
				<!--<button type="submit" class="btn btn-primary" id="btn" style="margin-bottom: 10px"><img src="../iconos/guardar.png" alt="">Guardar</button>-->
				<?php
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 4) {
					if ($reg_fa['pfg2_id'] != '' and $str_bandera_q == 'Si') {
				?>
						<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
					<?php
					} else {
						echo "N/A Guardar";
					}
				} else { ?>
					<button type="button" class="btn btn-info" onClick="javascript:quimicos_2(<?php echo $reg_pro['pro_id'] ?>, <?php echo $reg_et['pe_id'] ?> );"><img src="../iconos/matraz.png" alt="">Químicos</button>
					<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>

					<!-- <button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalEsp2(<?php echo $reg_pro['pro_id'] ?>, 2);">
						<img src="../iconos/edit.png" alt="">Completar
					</button> -->
				<?php } ?>
			</div>
		</div>
	</form>

	<div class="modal" id="modalEditar2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	<div class="modal" id="modalRenglon2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
	<div class="modal" id="m_modal_quimicos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>


</div>