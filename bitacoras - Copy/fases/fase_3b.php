<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
$reg_pro['pro_id'] = $idx_pro;

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 6");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_3b_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 6");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 6");
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

	function agregarCampo() {
		a++;
		var div = document.createElement('div');
		div.setAttribute('class', 'form-row');
		div.setAttribute('style', 'margin-top:-20px');

		div.innerHTML =
			'<div  class="form-group col-md-1" style="width: 30px"><label>' + a + 'o</label></div>' +
			'<div class="form-group col-md-1" style="width: 130px"><input class="form-control" name="txtLavTipAgua' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1"><input class="form-control" onKeyPress="return isNumberKey(event, this);" name="txtTemp' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1" style="width: 150px"><input class="form-control" name="txtHraIni' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1" style="width: 160px"><input class="form-control" name="txtHraFin' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1" style="width: 180px"><input class="form-control" name="txtHraIniMov' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1" style="width: 190px"><input class="form-control" name="txtHraFinMov' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1"><input class="form-control" onKeyPress="return isNumberKey(event, this);"name="txtPh' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1"><input class="form-control" onKeyPress="return isNumberKey(event, this);"name="txtCe' + a + '" type="text"/></div>';
		document.getElementById('campos').appendChild(div);
		document.getElementById('campos').appendChild(div);
	}

	$(document).ready(function() {
		$("#formFase3b").submit(function() {

			var formData = $(this).serialize();
			$.ajax({
				url: "fases/fase_3b_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase3bOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase3b').each(function() {
						this.reset();
					});
					setTimeout("location.reload()", 2000);

				}
			});
			return confirmEnviarFase3b();
			return false;

		});
	});


	//Bloquear boton al agregar material
	function confirmEnviarFase3b() {
		formFase3b.btn.disabled = true;
		formFase3b.btn.value = "Enviando...";

		setTimeout(function() {
			formFase3b.btn.disabled = true;
			formFase3b.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}

	function AbreModalE3b(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_3b.php',
			data: datos,
			success: function(result) {
				$("#modalEditar3b").html(result);
				$('#modalEditar3b').modal('show')
			}
		});
		return false;
	}

	//agregado 16-10-21
	//abre modal quimicos etapa 3b
	function quimicos_3b(proceso, etapa) {
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
	<form autocomplete="off" id="formFase3b" name="formFase3b">
		<!--<input type="hidden" value="<?php //echo $_GET['id_l'] 
										?>" name="txt_lavador">
		<input type="hidden" value="<?php //echo $_GET['id_p'] 
									?>" name="txt_paleto">-->
		<input type="hidden" value="<?php //echo $_GET['id_e'] 
									?>" name="txt_equipo">

		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="6" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg3_id']; ?>" />

		<div class="headerdivProcesos">
			<div class="col-md-2">ADICION A SOSA</div>
			<div class="col-md-4">Nota:Estar revisando los chequeos durante las 32 horas</div>
			<div class="col-md-5"></div>
		</div>

		<!--tiempos-->
		<div class="row" style="margin-bottom: 20px">
			<label class="col-md-1" style="width: 120px">Fecha inicio</label>
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

			<label class="col-md-1" style="width: 110px">Hora inicio</label>
			<div class="col-md-2 tiempos" style="width: 140px">
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

			<label class="col-md-1" style="width: 165px">Temp agua utilizada</label>
			<div class="col-md-2 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTempA" placeholder="TEMP" name="txtTempA" value="<?php if ($reg_fa['pfg3_temp_ag'] == '') {
																																														echo "";
																																														$str_prop = '';
																																													} else {
																																														echo $reg_fa['pfg3_temp_ag'];
																																														$str_prop = 'disabled';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3; ?> required>
			</div>
		</div>
		<div class="row" style="margin-bottom: 20px">
			<label class="col-md-2">Agrega Lts de sosa</label>
			<div class="col-md-1 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtSosa" placeholder="SOSA" name="txtSosa" value="<?php if ($reg_fa['pfg3_lts'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa['pfg3_lts'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-1">Lts</label>

			<label class="col-md-1" style="width: 60px">Ph</label>
			<div class="col-md-2 tiempos" style="width: 140px">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtPh" placeholder="PH" name="txtPh" value="<?php if ($reg_fa['pfg3_ph'] == '') {
																																												echo "";
																																												$str_prop = '';
																																											} else {
																																												echo $reg_fa['pfg3_ph'];
																																												$str_prop = 'disabled';
																																											} ?>" <?php echo $str_prop;
																																													echo " " . $strProp2;
																																													echo " " . $strProp3; ?> required>
			</div>

			<label class="col-md-1" style="width: 80px">Temp</label>
			<div class="col-md-2 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp" placeholder="TEMP" name="txtTemp" value="<?php if ($reg_fa['pfg3_temp'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa['pfg3_temp'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
			</div>

			<label class="col-md-1" style="width: 80px">Norm</label>
			<div class="col-md-2 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtNorm" placeholder="NORM" name="txtNorm" value="<?php if ($reg_fa['pfg3_norm'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa['pfg3_norm'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
			</div>
		</div>
		<!---->

		<div class="col-md-7">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr class="etiqueta_tbl">
					<td>Chequeo</td>
					<td>Fecha</td>
					<td>Hora</td>
					<td>Temp</td>
					<td>Norm</td>
					<td>Movimiento</td>
					<td>Reposo</td>
					<td>Sosa</td>
				</tr>
				<?php for ($i = 1; $i <= 44; $i += 4) {
					if ($i == 5) {
						$i -= 1;
					}

					if ($i == 20) {
						$i = 18;
					}

					if ($i == 22) {
						$i = 20;
					}

					if ($i == 40) {
						$i = 38;
					}

					if ($i == 42) {
						$i = 40;
					}

					if ($i == 44) {
						$i = 42;
					}

					$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_3b_d WHERE pfg3_id = '$reg_fa[pfg3_id]' and pfd3_ren = '$i' ");
					$reg_fad = mysqli_fetch_array($cad_fad);
				?>
					<tr>
						<td><?php echo $i . " Hora"; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
						<td><input type="date" class="form-control" id="<?php echo "txtFecha" . $i ?>" name="<?php echo "txtFecha" . $i ?>" value="<?php if ($reg_fad['pfd3_fecha'] == '') {
																																						echo date("Y-m-d");
																																						$str_prop = '';
																																					} else {
																																						echo $reg_fad['pfd3_fecha'];
																																						$str_prop = 'disabled';
																																					} ?>" <?php echo $str_prop;
																																							echo " " . $strProp2;
																																							echo " " . $strProp3;
																																							echo " " . $strProp4; ?> size="10"></td>
						<td><input type="time" class="form-control" id="<?php echo "txtHoraTb" . $i ?>" name="<?php echo "txtHoraTb" . $i ?>" value="<?php if ($reg_fad['pfd3_hr'] == '') {
																																							echo date("H:i");
																																							$str_prop = '';
																																						} else {
																																							echo $reg_fad['pfd3_hr'];
																																							$str_prop = 'disabled';
																																						} ?>" <?php echo $str_prop;
																																								echo " " . $strProp2;
																																								echo " " . $strProp3;
																																								echo " " . $strProp4; ?>></td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtTemp" . $i ?>" name="<?php echo "txtTemp" . $i ?>" value="<?php if ($reg_fad['pfd3_temp'] == '') {
																																																						echo "";
																																																						$str_prop = '';
																																																					} else {
																																																						echo $reg_fad['pfd3_temp'];
																																																						$str_prop = 'disabled';
																																																					} ?>" <?php echo $str_prop;
																																																							echo " " . $strProp2;
																																																							echo " " . $strProp3;
																																																							echo " " . $strProp4; ?> size="5" placeholder="Temp"></td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtNorm" . $i ?>" name="<?php echo "txtNorm" . $i ?>" value="<?php if ($reg_fad['pfd3_norm'] == '') {
																																																						echo "";
																																																						$str_prop = '';
																																																					} else {
																																																						echo $reg_fad['pfd3_norm'];
																																																						$str_prop = 'disabled';
																																																					} ?>" <?php echo $str_prop;
																																																							echo " " . $strProp2;
																																																							echo " " . $strProp3;
																																																							echo " " . $strProp4; ?> size="5" placeholder="Norm"></td>

						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtMovimiento" . $i ?>" name="<?php echo "txtMovimiento" . $i ?>" value="<?php if ($reg_fad['pfd3_movimiento'] == '') {
																																																									echo "";
																																																									$str_prop = '';
																																																								} else {
																																																									echo $reg_fad['pfd3_movimiento'];
																																																									$str_prop = 'disabled';
																																																								} ?>" <?php echo $str_prop;
																																																										echo " " . $strProp2;
																																																										echo " " . $strProp3;
																																																										echo " " . $strProp4; ?> size="7" placeholder="Mov"></td>

						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtReposo" . $i ?>" name="<?php echo "txtReposo" . $i ?>" value="<?php if ($reg_fad['pfd3_reposo'] == '') {
																																																							echo "";
																																																							$str_prop = '';
																																																						} else {
																																																							echo $reg_fad['pfd3_reposo'];
																																																							$str_prop = 'disabled';
																																																						} ?>" <?php echo $str_prop;
																																																								echo " " . $strProp2;
																																																								echo " " . $strProp3;
																																																								echo " " . $strProp4; ?> size="7" placeholder="Reposo"></td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtSosa<?php echo $i; ?>" name="txtSosa<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd3_sosa'] == '') {
																																																						echo "";
																																																						$str_prop = '';
																																																					} else {
																																																						echo $reg_fad['pfd3_sosa'];
																																																						$str_prop = 'disabled';
																																																					} ?>" <?php echo $str_prop;
																																																							echo " " . $strProp2;
																																																							echo " " . $strProp3;
																																																							echo " " . $strProp4; ?> placeholder="Sosa"></td>
					</tr>
					<?php if ($i == 24) { ?>
						<tr style="background: yellow;text-transform:uppercase;font-weight:bold">
							<td colspan="8" height="36">24 horas revisar el estado del material. Si ya esta "Liberar"</td>
						</tr>
				<?php }
				} ?>
			</table>
		</div>

		<div class="col-md-5">

			<div style="height: 30px; border-radius: 5px; border:1px solid #e6e6e6; margin-bottom: 3px; font-weight:bold; background-color:#CCCCCC;width: 380px;text-align: center;">
				<label>CP CHEQUEOS DE NORMALIDAD</label>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-2">
				<label for="inputPassword3" style="margin-bottom: 10px">Normalidad solucion</label>
				<label for="inputPassword3" style="margin-bottom: 10px">Nombre LCP</label>
				<label for="inputPassword3" style="margin-bottom: 10px">Normalidad solucion</label>
				<label for="inputPassword3" style="margin-bottom: 10px">Nombre LCP</label>
			</div>
			<div class="form-row">
				<div class="form-group col-md-1">
					<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtNormSb1" name="txtNormSb1" value="<?php if ($reg_fa['pfg3_norm1'] == '') {
																																											echo "";
																																											$str_prop = '';
																																										} else {
																																											echo $reg_fa['pfg3_norm1'];
																																											$str_prop = 'disabled';
																																										} ?>" <?php echo $str_prop;
																																												echo " " . $strProp1;
																																												echo " " . $strProp2; ?>>

					<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" id="txtUsu" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">

					<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtNormSb2" name="txtNormSb2" value="<?php if ($reg_fa['pfg3_norm2'] == '') {
																																											echo "";
																																											$str_prop = '';
																																										} else {
																																											echo $reg_fa['pfg3_norm2'];
																																											$str_prop = 'disabled';
																																										} ?>" <?php echo $str_prop;
																																												echo " " . $strProp1;
																																												echo " " . $strProp2; ?>>

					<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" id="txtUsu" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-1" style="width: 30px">
					<label for="inputPassword3" style="margin-bottom: 40px">Hrs</label>
					<label for="inputPassword3" style="margin-bottom: 10px">Hrs</label>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-1">
					<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" style="margin-bottom: 30px" id="txtHoraSb1" name="txtHoraSb1" value="<?php if ($reg_fa['pfg3_hr1'] == '') {
																																																		echo "";
																																																		$str_prop = '';
																																																	} else {
																																																		echo $reg_fa['pfg3_hr1'];
																																																		$str_prop = 'disabled';
																																																	} ?>" <?php echo $str_prop;
																																																			echo " " . $strProp1;
																																																			echo " " . $strProp2; ?>>

					<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" style="margin-bottom: 30px" id="txtHoraSb2" name="txtHoraSb2" value="<?php if ($reg_fa['pfg3_hr2'] == '') {
																																																		echo "";
																																																		$str_prop = '';
																																																	} else {
																																																		echo $reg_fa['pfg3_hr2'];
																																																		$str_prop = 'disabled';
																																																	} ?>" <?php echo $str_prop;
																																																			echo " " . $strProp1;
																																																			echo " " . $strProp2; ?>>
				</div>
			</div>
			<div class="col-md-4" style="margin-top: 20px;text-align: justify;">
				Nota: Seguir anotando los chequeos después de que cumpla su tiempo (32-36) o cuando se inicia a bajar normalidad.
			</div>
		</div>
		<div class="col-md-3 divEtapas3b">
			<div class="col-md-1 etapa3b">
				<p class="numEtapa">3b</p>
			</div>


			<div class="col-md-2 divEtapasInput">
				<label class="etiquetaEtapa">LIBERACION <?php echo fnc_rango_de(6) ?> - <?php echo fnc_rango_a(6) ?>Horas</label>
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" id="txtHrTotales" class="form-control" placeholder="Horas totales" name="txtHrTotales" value="<?php if ($reg_lib['prol_hr_totales'] == '') {
																																																		echo "";
																																																		$str_prop = '';
																																																	} else {
																																																		echo $reg_lib['prol_hr_totales'];
																																																		$str_prop = 'disabled';
																																																	} ?>" <?php echo $str_prop;
																																																			echo " " . $strProp1;
																																																			echo " " . $strProp2; ?>>
				<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" id="inputPassword" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
			</div>
		</div>
		<div class="row" id="campos"></div>


		<!--estilo general de estapas-->
		<div class="row">
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">Fecha termina sosa</label>
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
					<label for="inputPassword4">Hora termina sosa</label>
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
			<!--textareaobservaciones-->
			<div class="row">
				<div class="col-md-7">
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



		</div>
		<!---->




		<div class="form-row">
			<div class="form-group col-md-3">
				<label for="inputPassword4">Horas totales de todo el proceso</label>
				<input <?php if ($_SESSION['privilegio'] == 4) {
							echo "required";
						} ?> type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtHrasTot" name="txtHrasTot" placeholder="" value="<?php if ($reg_fa['pfg3_hr_totales'] == '') {
																																														echo "";
																																														$str_prop = '';
																																													} else {
																																														echo $reg_fa['pfg3_hr_totales'];
																																														$str_prop = 'disabled';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp6;
																																															echo " " . $strProp3 . " " . $strProp4; ?>>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-2">
				<label for="inputPassword4">(<?php echo fnc_hora_de(6) ?> a <?php echo fnc_hora_a(6) ?> Horas)</label>
			</div>
		</div>

		<!--barra botones-->
		<div class="row footerdivProcesos" style="margin-bottom: 10px">

			<div class=" col-md-4">
				<div class="alert alert-info hide" id="alerta-errorFase3bOpe" style="height: 40px;width: 270px;text-align: left;z-index: 10;font-size: 10px;">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>

			<div class="col-md-3">
				<?php if ($_SESSION['privilegio'] == 4) {	?>
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE3b(<?php echo $reg_pro['pro_id'] ?>, '6');">
						<img src="../iconos/edit.png" alt="">Editar
					</button>
				<?php } ?>
				<?php
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 4) {
					if ($reg_fa['pfg3_id'] != '') {
				?>
						<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
					<?php
					} else {
						echo "N/A Guardar";
					}
				} else { ?>
					<button type="button" class="btn btn-info" onClick="javascript:quimicos_3b(<?php echo $reg_pro['pro_id'] ?>, <?php echo $reg_et['pe_id'] ?> );"><img src="../iconos/matraz.png" alt="">Químicos</button>
					<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
				<?php } ?>
			</div>
		</div>

	</form>

	<div class="modal" id="modalEditar3b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	<div class="modal" id="m_modal_quimicos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

</div>