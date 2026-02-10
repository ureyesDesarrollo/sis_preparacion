<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
$reg_pro['pro_id'] = $idx_pro;

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 19");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 19");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 19");
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
if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28 ) {
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
<script language="javascript">
	$(document).ready(function() {
		$("#formFase7c").submit(function() {

			var formData = $(this).serialize();
			$.ajax({
				url: "fases/fase_7c_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase7cOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase7c').each(function() {
						this.reset();
					});
					setTimeout("location.reload()", 2000);
				}
			});
			return confirmEnviarFase7c();
			return false;

		});

	});


	//Bloquear boton al agregar material
	function confirmEnviarFase7c() {
		formFase7c.btn.disabled = true;
		formFase7c.btn.value = "Enviando...";

		setTimeout(function() {
			formFase7c.btn.disabled = true;
			formFase7c.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}

	function AbreModalE7c(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_7c.php',
			data: datos,
			success: function(result) {
				$("#modalEditar7c").html(result);
				$('#modalEditar7c').modal('show')
			}
		});
		return false;
	}

	//agregado 16-10-21
	function quimicos_7c(proceso, etapa) {
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
	<form autocomplete="off" id="formFase7c" name="formFase7c">
		<input type="hidden" value="<?php //echo $_GET['id_e'] 
									?>" name="txt_equipo">

		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="19" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg7_id']; ?>" />

		<div class="headerdivProcesos">
			<div class="col-md-2">SEGUNDO ÁCIDO</div>
			<div class="col-md-8"></div>
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

			<label class="col-md-1" style="width: 165px">Temp agua inicial</label>
			<div class="col-md-2 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp" name="txtTemp" placeholder="TEMP" value="<?php if ($reg_fa['pfg7_temp_ag'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa['pfg7_temp_ag'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-1" style="width: 110px;">Ácido diluido</label>

			<div class="col-md-2">
				<select type="text" id="cbxDiluido" class="form-control" placeholder="Acido diluido" name="cbxDiluido" <?php if ($reg_fa['pfg7_acido_diluido'] == '') {
																															echo "";
																															$str_prop = '';
																														} else {
																															$str_prop = 'disabled';
																														} ?> <?php echo $str_prop;
																																echo " " . $strProp2;
																																echo " " . $strProp3; ?> required>
					<?php if ($reg_fa['pfg7_acido_diluido'] != '') {
						echo "<option value='$reg_fa[pfg7_acido_diluido]'>$reg_fa[pfg7_acido_diluido]</option>";
					} ?>
					<option value="NA">Ácido diluido</option>
					<option value="SI">SI</option>
					<option value="NO">NO</option>
				</select>
			</div>

		</div>

		<div class="row" style="margin-bottom: 20px">
			<label class="col-md-1">Ajuste</label>
			<!--<div class="col-md-1 tiempos">
			<input type="text"  maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="inputPassword" placeholder="">
		</div>-->
			<label class="col-md-1">Temp</label>
			<div class="col-md-1 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp2" name="txtTemp2" placeholder="TEMP" value="<?php if ($reg_fa['pfg7_temp'] == '') {
																																														echo "";
																																														$str_prop = '';
																																													} else {
																																														echo $reg_fa['pfg7_temp'];
																																														$str_prop = 'disabled';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-1">Ácido</label>
			<div class="col-md-1 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtAcido" name="txtAcido" placeholder="Acido" value="<?php if ($reg_fa['pfg7_acido'] == '') {
																																														echo "";
																																														$str_prop = '';
																																													} else {
																																														echo $reg_fa['pfg7_acido'];
																																														$str_prop = 'disabled';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-1" style="width: 120px">Normalidad</label>
			<div class="col-md-1 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtNorm" name="txtNorm" placeholder="Norm" value="<?php if ($reg_fa['pfg7_norm'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa['pfg7_norm'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-1">Lts</label>
			<label class="col-md-1">PH</label>
			<div class="col-md-1 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtPh" name="txtPh" placeholder="PH" value="<?php if ($reg_fa['pfg7_ph'] == '') {
																																												echo "";
																																												$str_prop = '';
																																											} else {
																																												echo $reg_fa['pfg7_ph'];
																																												$str_prop = 'disabled';
																																											} ?>" <?php echo $str_prop;
																																													echo " " . $strProp2;
																																													echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-1">CE</label>
			<div class="col-md-1 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtCe" name="txtCe" placeholder="Ce" value="<?php if ($reg_fa['pfg7_ce'] == '') {
																																												echo "";
																																												$str_prop = '';
																																											} else {
																																												echo $reg_fa['pfg7_ce'];
																																												$str_prop = 'disabled';
																																											} ?>" <?php echo $str_prop;
																																													echo " " . $strProp2;
																																													echo " " . $strProp3; ?> required>
			</div>

		</div>
		<!---->
		<div class="row">
			<div class="col-md-6">
				<table border="0" style="width:95%" cellspacing="5" cellpadding="5">
					<tr class="etiqueta_tbl">
						<td width="15">&nbsp;</td>
						<td width="50">Ajust</td>
						<td>NORMALIDAD</td>
						<td align="center" bgcolor="#FF0000">Ph</td>
						<td>CE</td>
						<td></td>
					</tr>
					<?php
					for ($i = 1; $i <= 8; $i++) {

						if ($i == 1) {
							$val = '0:30';
						}
						if ($i == 2) {
							$val = '1:00';
						}
						if ($i == 3) {
							$val = '1:30';
						}
						if ($i == 4) {
							$val = '2:00';
						}
						if ($i == 5) {
							$val = '2:30';
						}
						if ($i == 6) {
							$val = '3:00';
						}
						if ($i == 7) {
							$val = '3:30';
						}
						if ($i == 8) {
							$val = '4:00';
						}

						$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d WHERE pfg7_id = '$reg_fa[pfg7_id]' and pfd7_ren = '$i' ");
						$reg_fad = mysqli_fetch_array($cad_fad);
					?>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;<?php echo $val; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
							<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtNormF" . $i ?>" name="<?php echo "txtNormF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_norm'] == '') {
																																																										echo "";
																																																										$str_prop = '';
																																																									} else {
																																																										echo $reg_fad['pfd7_norm'];
																																																										$str_prop = 'disabled';
																																																									} ?>" <?php echo $str_prop;
																																																											echo " " . $strProp2;
																																																											echo " " . $strProp3;
																																																											echo " " . $strProp4; ?> placeholder="Norm"></td>
							<td align="center" bgcolor="#FF0000">
								<?php if ($i == 2 || $i == 4 || $i == 6  || $i == 8) { ?>

									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtPhF" . $i ?>" name="<?php echo "txtPhF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_ph'] == '') {
																																																										echo "";
																																																										$str_prop = '';
																																																									} else {
																																																										echo $reg_fad['pfd7_ph'];
																																																										$str_prop = 'disabled';
																																																									} ?>" <?php echo $str_prop;
																																																										echo " " . $strProp2;
																																																										echo " " . $strProp3;
																																																										echo " " . $strProp4; ?> placeholder="pH">
								<?php } ?>
							</td>
							<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtCeF" . $i ?>" name="<?php echo "txtCeF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_ce'] == '') {
																																																									echo "";
																																																									$str_prop = '';
																																																								} else {
																																																									echo $reg_fad['pfd7_ce'];
																																																									$str_prop = 'disabled';
																																																								} ?>" <?php echo $str_prop;
																																																										echo " " . $strProp2;
																																																										echo " " . $strProp3;
																																																										echo " " . $strProp4; ?> placeholder="Ce"></td>

							<td>
								<?php if ($i == 2 || $i == 10) { ?>
									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtAcidoF" . $i ?>" name="<?php echo "txtAcidoF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_acido'] == '') {
																																																											echo "";
																																																											$str_prop = '';
																																																										} else {
																																																											echo $reg_fad['pfd7_acido'];
																																																											$str_prop = 'disabled';
																																																										} ?>" <?php echo $str_prop;
																																																												echo " " . $strProp2;
																																																												echo " " . $strProp3;
																																																												echo " " . $strProp4; ?> placeholder="LTS Acido">
								<?php } else { ?>
									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtTempF" . $i ?>" name="<?php echo "txtTempF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_temp'] == '') {
																																																											echo "";
																																																											$str_prop = '';
																																																										} else {
																																																											echo $reg_fad['pfd7_temp'];
																																																											$str_prop = 'disabled';
																																																										} ?>" <?php echo $str_prop;
																																																												echo " " . $strProp2;
																																																												echo " " . $strProp3;
																																																												echo " " . $strProp4; ?> placeholder="Temp">
								<?php } ?>

							</td>
						</tr>
					<?php } ?>
				</table>
			</div>
			<div class="col-md-6">
				<table border="0" style="width:95%" cellspacing="5" cellpadding="5">

					<tr class="etiqueta_tbl">
						<td width="15">&nbsp;</td>
						<td width="50">Ajust</td>
						<td>NORMALIDAD</td>
						<td align="center" bgcolor="#FF0000">Ph</td>
						<td>CE</td>
						<td></td>
					</tr>
					<?php
					for ($i = 9; $i <= 15; $i++) {
						if ($i == 9) {
							$val = '5:00';
						}
						if ($i == 10) {
							$val = '6:00';
						}
						if ($i == 11) {
							$val = '7:00';
						}
						if ($i == 12) {
							$val = '8:00';
						}
						if ($i == 13) {
							$val = '9:00';
						}
						if ($i == 14) {
							$val = '10:00';
						}
						if ($i == 15) {
							$val = '11:00';
						}
						if ($i == 16) {
							$val = '12:00';
						}

						$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d WHERE pfg7_id = '$reg_fa[pfg7_id]' and pfd7_ren = '$i' ");
						$reg_fad = mysqli_fetch_array($cad_fad);
					?>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;<?php echo $val; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
							<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtNormF" . $i ?>" name="<?php echo "txtNormF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_norm'] == '') {
																																																										echo "";
																																																										$str_prop = '';
																																																									} else {
																																																										echo $reg_fad['pfd7_norm'];
																																																										$str_prop = 'disabled';
																																																									} ?>" <?php echo $str_prop;
																																																											echo " " . $strProp2;
																																																											echo " " . $strProp3;
																																																											echo " " . $strProp4; ?> placeholder="Norm"></td>
							<td align="center" bgcolor="#FF0000"><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtPhF" . $i ?>" name="<?php echo "txtPhF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_ph'] == '') {
																																																																	echo "";
																																																																	$str_prop = '';
																																																																} else {
																																																																	echo $reg_fad['pfd7_ph'];
																																																																	$str_prop = 'disabled';
																																																																} ?>" <?php echo $str_prop;
																																																																		echo " " . $strProp2;
																																																																		echo " " . $strProp3;
																																																																		echo " " . $strProp4; ?> placeholder="pH"></td>
							<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtCeF" . $i ?>" name="<?php echo "txtCeF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_ce'] == '') {
																																																									echo "";
																																																									$str_prop = '';
																																																								} else {
																																																									echo $reg_fad['pfd7_ce'];
																																																									$str_prop = 'disabled';
																																																								} ?>" <?php echo $str_prop;
																																																										echo " " . $strProp2;
																																																										echo " " . $strProp3;
																																																										echo " " . $strProp4; ?> placeholder="Ce"></td>

							<td>
								<?php if ($i == 2 || $i == 10) { ?>
									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtAcidoF" . $i ?>" name="<?php echo "txtAcidoF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_acido'] == '') {
																																																											echo "";
																																																											$str_prop = '';
																																																										} else {
																																																											echo $reg_fad['pfd7_acido'];
																																																											$str_prop = 'disabled';
																																																										} ?>" <?php echo $str_prop;
																																																												echo " " . $strProp2;
																																																												echo " " . $strProp3;
																																																												echo " " . $strProp4; ?> placeholder="LTS Acido">
								<?php } else { ?>
									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtTempF" . $i ?>" name="<?php echo "txtTempF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_temp'] == '') {
																																																											echo "";
																																																											$str_prop = '';
																																																										} else {
																																																											echo $reg_fad['pfd7_temp'];
																																																											$str_prop = 'disabled';
																																																										} ?>" <?php echo $str_prop;
																																																												echo " " . $strProp2;
																																																												echo " " . $strProp3;
																																																												echo " " . $strProp4; ?> placeholder="Temp">
								<?php } ?>

							</td>
						</tr>
					<?php } ?>
				</table>
			</div>
		</div>
		<!--estilo general de estapas-->
		<div class="row">
			<div class="form-row">
				<div class="form-group col-md-3">
					<label for="inputPassword4">Fecha termina 2da acidificación</label>
					<input type="date" class="form-control" id="txtFeTermA" placeholder="" name="txtFeTermA" value="<?php if ($reg_fa['pfg7_fe_fin'] == '') {
																														echo date("Y-m-d");
																														$str_prop = '';
																													} else {
																														echo $reg_fa['pfg7_fe_fin'];
																														$str_prop = 'disabled';
																													} ?>" <?php echo $str_prop;
																															echo " " . $strProp4;
																															echo " " . $strProp3 . " " . $strProp4; ?>>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">Hora termina</label>
					<input type="time" class="form-control" id="txtHrTermA" placeholder="" name="txtHrTermA" value="<?php if ($reg_fa['pfg7_hr_fin'] == '') {
																														echo date("H:i");
																														$str_prop = '';
																													} else {
																														echo $reg_fa['pfg7_hr_fin'];
																														$str_prop = 'disabled';
																													} ?>" <?php echo $str_prop;
																															echo " " . $strProp4;
																															echo " " . $strProp3 . " " . $strProp4; ?>>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">Horas totales</label>
					<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" id="txtHrTotales1" class="form-control" placeholder="Horas totales" name="txtHrTotales1" value="<?php if ($reg_fa['pfg7_hr_totales'] == '') {
																																																		echo "";
																																																		$str_prop = '';
																																																	} else {
																																																		echo $reg_fa['pfg7_hr_totales'];
																																																		$str_prop = 'disabled';
																																																	} ?>" <?php echo $str_prop;
																																																			echo " " . $strProp4;
																																																			echo " " . $strProp3 . " " . $strProp4; ?>>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">Realizo proceso</label>
					<input type="text" id="txtRealizo" class="form-control" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">(12 Horas continuas)</label>
				</div>
			</div>
		</div>
		<!---->



		<div class="row">
			<div class="col-md-1">
				<label for="inputPassword4">PH</label>
				<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" id="txtPhR1" class="form-control" placeholder="PH" name="txtPhR1" value="<?php if ($reg_fa['pfg7_agua_ph'] == '') {
																																											echo "";
																																											$str_prop = '';
																																										} else {
																																											echo $reg_fa['pfg7_agua_ph'];
																																											$str_prop = 'disabled';
																																										} ?>" <?php echo $str_prop;
																																												echo " " . $strProp6;
																																												echo " " . $strProp3 . " " . $strProp4; ?>>





			</div>
			<div class="col-md-1">
				<label for="inputPassword4">CE</label>

				<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" class="form-control" id="txtCeR1" name="txtCeR1" placeholder="CE" value="<?php if ($reg_fa['pfg7_agua_ce'] == '') {
																																											echo "";
																																											$str_prop = '';
																																										} else {
																																											echo $reg_fa['pfg7_agua_ce'];
																																											$str_prop = 'disabled';
																																										} ?>" <?php echo $str_prop;
																																												echo " " . $strProp6;
																																												echo " " . $strProp3 . " " . $strProp4; ?>>
			</div>
			<div class="col-md-1">
				<label for="inputPassword4">TEMP</label>

				<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" class="form-control" id="txtTemR1" name="txtTemR1" placeholder="TEMP" value="<?php if ($reg_fa['pfg7_tem_final'] == '') {
																																												echo "";
																																												$str_prop = '';
																																											} else {
																																												echo $reg_fa['pfg7_tem_final'];
																																												$str_prop = 'disabled';
																																											} ?>" <?php echo $str_prop;
																																													echo " " . $strProp6;
																																													echo " " . $strProp3 . " " . $strProp4; ?>>
			</div>
			<div class=" col-md-3">
				<label for="inputPassword4">4-6 horas (<?php echo fnc_hora_de(19) ?> a <?php echo fnc_hora_a(19) ?> Horas continuas)</label>
			</div>

		</div>


		<div class="col-md-3 divEtapas" style="height: 110px;">
			<div class="col-md-1 etapa" style="height: 110px;">
				<p class="numEtapa">7c</p>
			</div>
			<div class="col-md-2 divEtapasInput" style="width: 200px">
				<label class="etiquetaEtapa"><!-- Cocido ph liberación (<?php echo fnc_rango_de(19) ?> - <?php echo fnc_rango_a(19) ?>) --></label>

				<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" id="txtHrTotales" class="form-control" placeholder="Horas totales" name="txtHrTotales" value="<?php if ($reg_lib['prol_hr_totales'] == '') {
																																																	echo "";
																																																	$str_prop = '';
																																																} else {
																																																	echo $reg_lib['prol_hr_totales'];
																																																	$str_prop = 'disabled';
																																																} ?>" <?php echo $str_prop;
																																																		echo " " . $strProp1;
																																																		echo " " . $strProp2; ?>>
				<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" id="txtExtr2doAcido" class="form-control" placeholder="Extractibilidad 2do acido" name="txtExtr2doAcido" value="<?php if ($reg_lib['prol_extrac2doacido'] == '') {
																																																	echo "";
																																																	$str_prop = '';
																																																} else {
																																																	echo $reg_lib['prol_extrac2doacido'];
																																																	$str_prop = 'disabled';
																																																} ?>" <?php echo $str_prop;
																																																		echo " " . $strProp1;
																																																		echo " " . $strProp2; ?>>
				
				<input type="text" id="txtUsuario" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
			</div>
		</div>
		<!--<div class="row" id="campos"></div>-->
		<!--textareaobservaciones-->
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
		<!---->


		<!--barra botones-->
		<div class="row" <?php echo $oculta_opciones ?> style="margin-right: 0px">
			<div class="form-group col-md-9">
				<div class="alert alert-info hide" id="alerta-errorFase7cOpe" style="height: 40px;width: 270px;text-align: left;z-index: 10;font-size: 10px;">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
				<?php
				//Valida si el operador capturo quimicos en la etapa
				$cad_quim = mysqli_query($cnx, "SELECT * FROM quimicos_etapas WHERE pro_id = '$reg_pro[pro_id]' and pe_id = '19'");
				$tot_q = mysqli_num_rows($cad_quim);

				if($reg_fa['pfg7_acido'] != '0' and $tot_q == 0) {echo "<div style='background-color:#ccc0ff'>El operador debe capturar los  químicos </div>"; $str_bandera_q7 = "No";} else{$str_bandera_q7 = "Si"; }
				?>
			</div>
			<div class="col-md-3" style="margin-top:1rem;margin-bottom:1rem;text-align:right">
				<?php if ($_SESSION['privilegio'] == 4) {	?>
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE7c(<?php echo $reg_pro['pro_id'] ?>, 19);">
						<img src="../iconos/edit.png" alt="">Editar
					</button>
				<?php } ?>
				<?php
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28  or $_SESSION['privilegio'] == 4) {
					if ($reg_fa['pfg7_id'] != '' and $str_bandera_q7 == 'Si') {
				?>
						<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
					<?php
					} else {
						echo "N/A Guardar";
					}
				} else { ?>
					<button type="button" class="btn btn-info" onClick="javascript:quimicos_7c(<?php echo $reg_pro['pro_id'] ?>, <?php echo $reg_et['pe_id'] ?>);"><img src="../iconos/matraz.png" alt="">Químicos</button>
					<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
				<?php } ?>
			</div>

		</div>

	</form>

	<div class="modal" id="modalEditar7c" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
	<div class="modal" id="m_modal_quimicos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

</div>