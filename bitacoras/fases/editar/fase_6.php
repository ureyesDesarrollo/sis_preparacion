<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*20 - Abril - 2019*/
require '../../../conexion/conexion.php';
require '../../funciones_procesos.php';
include('../../../seguridad/user_seguridad.php');
$cnx =  Conectarse();

$reg_pro['pro_id'] = $_POST['pro_id'];

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 14");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_6_g WHERE pro_id = '$reg_pro[pro_id]'");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 14");
$reg_lib = mysqli_fetch_array($cad_lib);
?>
<script>
	$(document).ready(function() {
		$("#formFase6E").submit(function() {

			var formData = $(this).serialize();
			$.ajax({
				url: "fases/editar/fase_6_actualizar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase6EOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase6').each(function() {
						this.reset();
					});
					setTimeout("location.reload()", 2000);
				}
			});
			//return confirmEnviar5();
			return false;

		});

	});

	//Bloquear boton guardar
	function confirmEnviar5() {

		formFase6E.btn.disabled = true;
		formFase6E.btn.value = "Enviando...";

		setTimeout(function() {
			formFase6E.btn.disabled = true;
			formFase6E.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}
</script>
<div class="modal-dialog modal-lg" role="document" style="width: 1100px">
	<div class="modal-content">
		<div class="divProcesos">
			<form autocomplete="off" id="formFase6E" name="formFase6E">
				<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
				<input name="hdd_pe_id" type="hidden" value="14" id="hdd_pe_id" />
				<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
				<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg6_id']; ?>" />

				<div class="headerdivProcesos">
					<div class="col-md-2">SEGUNDO ÁCIDO</div>
					<div class="col-md-8">Este proceso se utiliza el agua de los lavadores de 1er ácido o solo de ácido fuerte</div>
				</div>

				<!--tiempos-->
				<div class="row" style="margin-bottom: 10px">
					<label class="col-md-1" style="width: 150px;">Fecha que inicio</label>
					<div class="col-md-2 tiempos">
						<input type="date" class="form-control" id="txtFeIni" placeholder="" name="txtFeIni" value="<?php if ($reg_aux['proa_fe_ini'] == '') {
																														echo date("Y-m-d");
																														$str_prop = 'disabled';
																													} else {
																														echo $reg_aux['proa_fe_ini'];
																														$str_prop = '';
																													} ?>" <?php echo $str_prop;
																															echo " " . $strProp2;
																															echo " " . $strProp3; ?> required>
					</div>

					<label class="col-md-1" style="width: 110px">Hora inicio</label>
					<div class="col-md-2 tiempos">
						<input type="time" class="form-control" id="txtHrIni" placeholder="" name="txtHrIni" value="<?php if ($reg_aux['proa_hr_ini'] == '') {
																														echo date("H:i");
																														$str_prop = 'disabled';
																													} else {
																														echo $reg_aux['proa_hr_ini'];
																														$str_prop = '';
																													} ?>" <?php echo $str_prop;
																															echo " " . $strProp2;
																															echo " " . $strProp3; ?> required>
					</div>

					<label class="col-md-1" style="width: 165px">Temp agua utilizada</label>
					<div class="col-md-1 tiempos">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtTemp" name="txtTemp" placeholder="TEMP" value="<?php if ($reg_fa['pfg6_temp_ag'] == '') {
																																														echo "";
																																														$str_prop = 'disabled';
																																													} else {
																																														echo $reg_fa['pfg6_temp_ag'];
																																														$str_prop = '';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3; ?> required>
					</div>
					<label class="col-md-1" style="width: 120px">Ácido diluido</label>
					<div class="col-md-2">
						<select type="text" id="cbxDiluido" class="form-control" placeholder="Ácido fuerte" name="cbxDiluido" <?php if ($reg_fa['pfg6_acido_diluido'] == '') {
																																	echo "";
																																	$str_prop = 'disabled';
																																} else {
																																	$str_prop = '';
																																} ?> <?php echo $str_prop;
																																		echo " " . $strProp2;
																																		echo " " . $strProp3; ?> required>
							<?php if ($reg_fa['pfg6_acido_diluido'] != '') {
								echo "<option value='$reg_fa[pfg6_acido_diluido]'>$reg_fa[pfg6_acido_diluido]</option>";
							} ?>
							<option value="NA">N/A</option>
							<option value="SI">SI</option>
							<option value="NO">NO</option>
						</select>
					</div>

				</div>
				<!---->


				<!---->
				<div class="row" style="margin-bottom: 30px">
					<label class="col-md-1" style="width: 50px">TEMP</label>
					<div class="col-md-1">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtTemp2" name="txtTemp2" placeholder="TEMP" value="<?php if ($reg_fa['pfg6_temp'] == '') {
																																														echo "";
																																														$str_prop = 'disabled';
																																													} else {
																																														echo $reg_fa['pfg6_temp'];
																																														$str_prop = '';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3; ?> required>
					</div>

					<label class="col-md-1" style="width: 50px">ACIDO</label>
					<div class="col-md-1">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtAcido" name="txtAcido" placeholder="Acido" value="<?php if ($reg_fa['pfg6_acido'] == '') {
																																															echo "";
																																															$str_prop = 'disabled';
																																														} else {
																																															echo $reg_fa['pfg6_acido'];
																																															$str_prop = '';
																																														} ?>" <?php echo $str_prop;
																																																echo " " . $strProp2;
																																																echo " " . $strProp3; ?> required>
					</div>
					<label class="col-md-1">LTS</label>
					<label class="col-md-1" style="width: 50px">PH</label>
					<div class="col-md-1">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtPh" name="txtPh" placeholder="PH" value="<?php if ($reg_fa['pfg6_ph'] == '') {
																																												echo "";
																																												$str_prop = 'disabled';
																																											} else {
																																												echo $reg_fa['pfg6_ph'];
																																												$str_prop = '';
																																											} ?>" <?php echo $str_prop;
																																													echo " " . $strProp2;
																																													echo " " . $strProp3; ?> required>
					</div>
					<!--<label>LTS</label>-->

					<label class="col-md-1" style="width: 50px">TEMP</label>
					<div class="col-md-1">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtTemp3" name="txtTemp3" placeholder="TEMP" value="<?php if ($reg_fa['pfg6_temp2'] == '') {
																																														echo "";
																																														$str_prop = 'disabled';
																																													} else {
																																														echo $reg_fa['pfg6_temp2'];
																																														$str_prop = '';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3; ?> required>
					</div>
					<label class="col-md-1">NORMALIDAD</label>
					<div class="col-md-1">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtNorm" name="txtNorm" placeholder="Norm" value="<?php if ($reg_fa['pfg6_norm'] == '') {
																																														echo "";
																																														$str_prop = 'disabled';
																																													} else {
																																														echo $reg_fa['pfg6_norm'];
																																														$str_prop = '';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3; ?> required>
					</div>


				</div>
				<!---->
				<table border="0" cellspacing="5" cellpadding="5">
					<tr class="etiqueta_tbl">
						<td width="15">&nbsp;</td>
						<td width="50">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" bgcolor="#FFCF00">PPRO</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="etiqueta_tbl">
						<td width="15">&nbsp;</td>
						<td width="50">Ajust</td>
						<td>AC</td>
						<td>&nbsp;</td>
						<td>TEMP</td>
						<td align="center" bgcolor="#FFCF00">pH</td>
						<td>CE</td>
						<td>NORMALIDAD</td>
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

						$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_6_d WHERE pfg6_id = '$reg_fa[pfg6_id]' and pfd6_ren = '$i' ");
						$reg_fad = mysqli_fetch_array($cad_fad);
					?>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;<?php echo $val; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
							<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php echo "txtAcidoF" . $i ?>" name="<?php echo "txtAcidoF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd6_acido'] == '') {
																																																									echo "";
																																																									$str_prop = 'disabled';
																																																								} else {
																																																									echo $reg_fad['pfd6_acido'];
																																																									$str_prop = '';
																																																								} ?>" <?php echo $str_prop;
																																																									echo " " . $strProp2;
																																																									echo " " . $strProp3;
																																																									echo " " . $strProp4; ?> placeholder="Acido"></td>
							<td>LTS</td>
							<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php echo "txtTempF" . $i ?>" name="<?php echo "txtTempF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd6_temp'] == '') {
																																																								echo "";
																																																								$str_prop = 'disabled';
																																																							} else {
																																																								echo $reg_fad['pfd6_temp'];
																																																								$str_prop = '';
																																																							} ?>" <?php echo $str_prop;
																																																								echo " " . $strProp2;
																																																								echo " " . $strProp3;
																																																								echo " " . $strProp4; ?> placeholder="Temp"></td>
							<td align="center" bgcolor="#FFCF00"><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php echo "txtPhF" . $i ?>" name="<?php echo "txtPhF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd6_ph'] == '') {
																																																																echo "";
																																																																$str_prop = 'disabled';
																																																															} else {
																																																																echo $reg_fad['pfd6_ph'];
																																																																$str_prop = '';
																																																															} ?>" <?php echo $str_prop;
																																																																echo " " . $strProp2;
																																																																echo " " . $strProp3;
																																																																echo " " . $strProp4; ?> placeholder="pH"></td>
							<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php echo "txtCeF" . $i ?>" name="<?php echo "txtCeF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd6_ce'] == '') {
																																																							echo "";
																																																							$str_prop = 'disabled';
																																																						} else {
																																																							echo $reg_fad['pfd6_ce'];
																																																							$str_prop = '';
																																																						} ?>" <?php echo $str_prop;
																																																							echo " " . $strProp2;
																																																							echo " " . $strProp3;
																																																							echo " " . $strProp4; ?> placeholder="Ce"></td>
							<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php echo "txtNormF" . $i ?>" name="<?php echo "txtNormF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd6_norm'] == '') {
																																																								echo "";
																																																								$str_prop = 'disabled';
																																																							} else {
																																																								echo $reg_fad['pfd6_norm'];
																																																								$str_prop = '';
																																																							} ?>" <?php echo $str_prop;
																																																								echo " " . $strProp2;
																																																								echo " " . $strProp3;
																																																								echo " " . $strProp4; ?> placeholder="Norm"></td>
						</tr>
					<?php } ?>
				</table>
				<!--estilo general de estapas-->
				<div class="row">
					<div class="form-row">
						<div class="form-group col-md-3">
							<label for="inputPassword4">Fecha termina 2da acidificación</label>
							<input type="date" class="form-control" id="txtFeTermS" placeholder="" name="txtFeTermS" value="<?php if ($reg_fa['pfg6_fe_fin'] == '') {
																																echo date("Y-m-d");
																																$str_prop = 'disabled';
																															} else {
																																echo $reg_fa['pfg6_fe_fin'];
																																$str_prop = '';
																															} ?>" <?php echo $str_prop;
																																	echo " " . $strProp6;
																																	echo " " . $strProp3 . " " . $strProp4; ?>>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-2">
							<label for="inputPassword4">Hora termina</label>
							<input type="time" class="form-control" id="txtHrTermS" placeholder="" name="txtHrTermS" value="<?php if ($reg_fa['pfg6_hr_fin'] == '') {
																																echo date("H:i");
																																$str_prop = 'disabled';
																															} else {
																																echo $reg_fa['pfg6_hr_fin'];
																																$str_prop = '';
																															} ?>" <?php echo $str_prop;
																																	echo " " . $strProp6;
																																	echo " " . $strProp3 . " " . $strProp4; ?>>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-2">
							<label for="inputPassword4">Hora totales</label>
							<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtHrsReales" name="txtHrsReales" placeholder="Hrs reales" value="<?php if ($reg_fa['pfg6_hr_totales'] == '') {
																																																			echo "";
																																																			$str_prop = 'disabled';
																																																		} else {
																																																			echo $reg_fa['pfg6_hr_totales'];
																																																			$str_prop = '';
																																																		} ?>" <?php echo $str_prop;
																																																				echo " " . $strProp6;
																																																				echo " " . $strProp3 . " " . $strProp4; ?>>
						</div>
					</div>
					<!-- <div class="form-row">
						<div class="form-group col-md-3">
							<label for="inputPassword4">Agua a</label>
							<select id="cbxAgua" class="form-control" style="width: 250px" name="cbxAgua" <?php /* if ($reg_fa['taa_id'] == '') {
																												echo "";
																												$str_prop = 'disabled';
																											} else {
																												$str_prop = '';
																											}
																											echo $str_prop;
																											echo " " . $strProp6;
																											echo " " . $strProp3 . " " . $strProp4; ?>>
								<option value="">Seleccionar</option>
								<?php
								$cadena =  mysqli_query($cnx, "SELECT * from tipos_agua_a ORDER BY taa_descripcion");
								$registros =  mysqli_fetch_array($cadena);

								do {
								?><option value="<?php echo $registros['taa_id'] ?>" <?php if ($registros['taa_id'] == $reg_fa['taa_id']) { ?>selected="selected" <?php } ?>><?php echo $registros['taa_descripcion'] ?></option><?php
																																																								} while ($registros =  mysqli_fetch_array($cadena));
																							*/																																		?>
							</select>
						</div>
					</div>-->
					<div class="form-row">
						<div class="form-group col-md-2">
							<label for="inputPassword4">Realizó</label>
							<input type="text" class="form-control" id="inputPassword4" placeholder="" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-3">
							<label for="inputPassword4">(4 horas continuas)</label>
						</div>
					</div>
					<!---->

				</div>


				<div class="row">
					<div class="col-md-12" style="margin-bottom: 20px;background: #e6e6e6;width: 60%">
						<label style="font-weight:bold; margin-left:20px;">En esta parte son 15 minutos de movimiento y 1:45 horas de reposo durante 18 horas</label>
					</div>
				</div>

				<table border="0" cellspacing="5" cellpadding="5">
					<tr class="etiqueta_tbl">
						<td width="15">&nbsp;</td>
						<td></td>
						<td>Inicia movimiento</td>
						<td>&nbsp;</td>
						<td>Inicia reposo</td>
						<td>&nbsp;</td>
						<td>PH</td>
						<td>CE</td>
						<td>NORM</td>
						<td>TEMP</td>
					</tr>
					<?php
					for ($i = 1; $i <= 6; $i++) {
						$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_6_d2 WHERE pfg6_id = '$reg_fa[pfg6_id]' and pfd6_ren = '$i' ");
						$reg_fad = mysqli_fetch_array($cad_fad);
					?>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen2" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen2" . $i ?>"></td>
							<td><input type="time" id="txtIniMovD<?php echo $i; ?>" name="txtIniMovD<?php echo $i; ?>" class="form-control" value="<?php if ($reg_fad['pfd6_ini_mov'] == '') {
																																						echo "";
																																						$str_prop = 'disabled';
																																					} else {
																																						echo $reg_fad['pfd6_ini_mov'];
																																						$str_prop = '';
																																					} ?>" <?php echo $str_prop;
																																							echo " " . $strProp2;
																																							echo " " . $strProp3;
																																							echo " " . $strProp4; ?> /> </td>
							<td>HRS</td>
							<td><input type="time" id="txtIniRepD<?php echo $i; ?>" name="txtIniRepD<?php echo $i; ?>" class="form-control" value="<?php if ($reg_fad['pfd6_ini_reposo'] == '') {
																																						echo "";
																																						$str_prop = 'disabled';
																																					} else {
																																						echo $reg_fad['pfd6_ini_reposo'];
																																						$str_prop = '';
																																					} ?>" <?php echo $str_prop;
																																							echo " " . $strProp2;
																																							echo " " . $strProp3;
																																							echo " " . $strProp4; ?> /></td>
							<td>HRS</td>
							<?php if ($i <= 3) { ?>
								<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" name="txtPhD<?php echo $i; ?>" id="txtPhD<?php echo $i; ?>" class="form-control" placeholder="PH" value="<?php if ($reg_fad['pfd6_ph'] == '') {
																																																								echo "";
																																																								$str_prop = 'disabled';
																																																							} else {
																																																								echo $reg_fad['pfd6_ph'];
																																																								$str_prop = '';
																																																							} ?>" <?php echo $str_prop;
																																																									echo " " . $strProp2;
																																																									echo " " . $strProp3;
																																																									echo " " . $strProp4; ?> /> </td>
								<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" name="txtCeD<?php echo $i; ?>" id="txtCeD<?php echo $i; ?>" class="form-control" placeholder="CE" value="<?php if ($reg_fad['pfd6_ce'] == '') {
																																																								echo "";
																																																								$str_prop = 'disabled';
																																																							} else {
																																																								echo $reg_fad['pfd6_ce'];
																																																								$str_prop = '';
																																																							} ?>" <?php echo $str_prop;
																																																									echo " " . $strProp2;
																																																									echo " " . $strProp3;
																																																									echo " " . $strProp4; ?> /></td>
								<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" name="txtNormD<?php echo $i; ?>" id="txtNormD<?php echo $i; ?>" class="form-control" placeholder="NORM" value="<?php if ($reg_fad['pfd6_norm'] == '') {
																																																										echo "";
																																																										$str_prop = 'disabled';
																																																									} else {
																																																										echo $reg_fad['pfd6_norm'];
																																																										$str_prop = '';
																																																									} ?>" <?php echo $str_prop;
																																																											echo " " . $strProp2;
																																																											echo " " . $strProp3;
																																																											echo " " . $strProp4; ?> /></td>
								<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" name="txtTempD<?php echo $i; ?>" id="txtTempD<?php echo $i; ?>" class="form-control" placeholder="TEMP" value="<?php if ($reg_fad['pfd6_temp'] == '') {
																																																										echo "";
																																																										$str_prop = 'disabled';
																																																									} else {
																																																										echo $reg_fad['pfd6_temp'];
																																																										$str_prop = '';
																																																									} ?>" <?php echo $str_prop;
																																																											echo " " . $strProp2;
																																																											echo " " . $strProp3;
																																																											echo " " . $strProp4; ?> /></td>
							<?php } else { ?>
								<td colspan="4">&nbsp;</td>
							<?php } ?>
						</tr>
					<?php } ?>
				</table>
				<!--tiempos-->
				<div class="row" style="margin-bottom: 50px">
					<!--<div class="col-md-12">
						<div class="col-md-5  tiempos" style="margin-left: -10px">
							<label>COCIDO A LAS 8 A 12 HORAS DE LAS 2DA ACIDIFICACIÓN</label>
						</div>
						<div class="col-md-2" style="margin-right:0px">
							<label>HORA INICIA COCIDO</label>
						</div>
						<div class="col-md-2 tiempos" style="width: 150px">
							<input type="time" class="form-control" id="txtHrIniCo" name="txtHrIniCo" placeholder="Hrs inicia" value="<?php if ($reg_fa['pfg6_hr_cocido'] == '') {
																																			echo "";
																																			$str_prop = 'disabled';
																																		} else {
																																			echo $reg_fa['pfg6_hr_cocido'];
																																			$str_prop = '';
																																		} ?>" <?php echo $str_prop;
																																				echo " " . $strProp6;
																																				echo " " . $strProp3 . " " . $strProp4; ?>>
						</div>
					</div>-->
					<!--<div class="col-md-12">
						<div class="col-md-5 tiempos" style="margin-left: -10px">
							<label>COCIDO PH (1.7-2.1) BUSCAR RANGO INFERIOR</label>
						</div>
						<div class="col-md-4" style="margin-right:0px">
							<label>AGUA PH(1.3-1.7) BUSCAR RANGO INFERIOR</label>
						</div>
					</div>-->
					<div class="col-md-12">
						<div class="col-md-1" style="width: 60px;margin-left: 40px">
							<label>PH</label>
						</div>
						<div class="col-md-1 tiempos">
							<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtPh2F" name="txtPh2F" placeholder="Ph" value="<?php if ($reg_fa['pfg6_ph2'] == '') {
																																														echo "";
																																														$str_prop = 'disabled';
																																													} else {
																																														echo $reg_fa['pfg6_ph2'];
																																														$str_prop = '';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp6;
																																															echo " " . $strProp3 . " " . $strProp4; ?>>
						</div>
						<div class="col-md-1" style="width: 60px">
							<label>CE</label>
						</div>
						<div class="col-md-1 tiempos">
							<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtCe2F" name="txtCe2F" placeholder="Ce" value="<?php if ($reg_fa['pfg6_ce2'] == '') {
																																														echo "";
																																														$str_prop = 'disabled';
																																													} else {
																																														echo $reg_fa['pfg6_ce2'];
																																														$str_prop = '';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp6;
																																															echo " " . $strProp3 . " " . $strProp4; ?>>
						</div>
						<div class="col-md-1" style="width: 60px;margin-left: 220px">
							<label>PH</label>
						</div>
						<div class="col-md-1 tiempos">
							<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtPh3F" name="txtPh3F" placeholder="Ph" value="<?php if ($reg_fa['pfg6_ph3'] == '') {
																																														echo "";
																																														$str_prop = 'disabled';
																																													} else {
																																														echo $reg_fa['pfg6_ph3'];
																																														$str_prop = '';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp6;
																																															echo " " . $strProp3 . " " . $strProp4; ?>>
						</div>
						<div class="col-md-1" style="width: 60px">
							<label>CE</label>
						</div>
						<div class="col-md-1 tiempos">
							<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtCe3F" name="txtCe3F" placeholder="Ce" value="<?php if ($reg_fa['pfg6_ce3'] == '') {
																																														echo "";
																																														$str_prop = 'disabled';
																																													} else {
																																														echo $reg_fa['pfg6_ce3'];
																																														$str_prop = '';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp6;
																																															echo " " . $strProp3 . " " . $strProp4; ?>>
						</div>
					</div>
				</div>
				<!---->

				<!--tiempos-->
				<div class="col-md-12">
					<div class="col-md-3 tiempos" style="margin-left: -10px">
						<label>Fecha termina movim. y reposo</label>
						<input type="date" class="form-control" id="txtFeTerm" placeholder="" name="txtFeTerm" value="<?php if ($reg_aux['proa_fe_fin'] == '') {
																															echo "";
																															$str_prop = 'disabled';
																														} else {
																															echo $reg_aux['proa_fe_fin'];
																															$str_prop = '';
																														} ?>" <?php echo $str_prop;
																																echo " " . $strProp6;
																																echo " " . $strProp3 . " " . $strProp4; ?>>
					</div>
					<div class="col-md-2 tiempos" style="margin-left: 20px">
						<label>Hora termina</label>
						<input type="time" class="form-control" id="txtHrTerm" placeholder="" name="txtHrTerm" value="<?php if ($reg_aux['proa_hr_fin'] == '') {
																															echo "";
																															$str_prop = 'disabled';
																														} else {
																															echo $reg_aux['proa_hr_fin'];
																															$str_prop = '';
																														} ?>" <?php echo $str_prop;
																																echo " " . $strProp6;
																																echo " " . $strProp3 . " " . $strProp4; ?>>
					</div>
					<label for="inputPassword4">(<?php echo fnc_hora_a(14) ?> horas)</label>
				</div>
				<!---->



				<!--textareaobservaciones-->
				<div class="row">
					<div class="col-md-7 textareaObservaciones6">
						<label for="inputPassword4">Observaciones</label>
						<!--<label class="col-md-1"  style="width: 50px">1er</label>-->
						<textarea type="textarea" maxlength="300" class="form-control" id="" placeholder="Observaciones..." name="txaObservaciones" value="<?php if ($reg_aux['proa_observaciones'] == '') {
																																								$str_prop = 'disabled';
																																							} else {
																																								$str_prop = '';
																																							} ?>" <?php echo $str_prop;
																																									echo " " . $strProp6;
																																									echo " " . $strProp3;
																																									echo " " . $strProp5 . " " . $strProp4; ?>><?php echo $reg_aux['proa_observaciones']; ?></textarea>
					</div>

					<div class="col-md-3 divEtapas6">
						<div class="col-md-1 etapa6">
							<p class="numEtapa">6</p>
						</div>
						<div class="col-md-2 divEtapasInput6">
							<label class="etiquetaEtapa">COCIDO PH LIBERACIÓN (<?php echo fnc_rango_de(14) ?> - <?php echo fnc_rango_a(14) ?>)</label>
							<input type="text" id="txtPhLib" class="form-control" placeholder="Cocido ph (1.7-2.1)" name="txtPhLib" value="<?php if ($reg_lib['prol_ph'] == '') {
																																				echo "";
																																				$str_prop = 'disabled';
																																			} else {
																																				echo $reg_lib['prol_ph'];
																																				$str_prop = '';
																																			} ?>" <?php echo $str_prop;
																																					echo " " . $strProp1;
																																					echo " " . $strProp2; ?>>
							<input type="text" id="txtHrTotales" class="form-control" placeholder="Horas totales" name="txtHrTotales" value="<?php if ($reg_lib['prol_hr_totales'] == '') {
																																					echo "";
																																					$str_prop = 'disabled';
																																				} else {
																																					echo $reg_lib['prol_hr_totales'];
																																					$str_prop = '';
																																				} ?>" <?php echo $str_prop;
																																						echo " " . $strProp1;
																																						echo " " . $strProp2; ?>>
							<input type="text" id="txtUsuario" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
							<!--<input  type="text" id="inputPassword" class="form-control" placeholder="Firma LCP">-->
						</div>
					</div>
				</div>

				<!--barra botones-->
				<div class="row footerdivProcesos" style="margin-bottom: 10px">
					<div class="col-md-5">
						<label style="font-weight:bold; margin-left:20px;">El agua de este proceso se manda a depositos de agua acida</label>
					</div>

					<div class="form-group col-md-4">
						<div class="alert alert-info hide" id="alerta-errorFase6EOpe" style="height: 40px;width: 270px;text-align: left;z-index: 10;font-size: 10px">
							<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
							<strong>Titulo</strong> &nbsp;&nbsp;
							<span> Mensaje </span>
						</div>
					</div>
					<?php /*if($_SESSION['privilegio'] == 4){	?>
		<div class="col-md-1">
			<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE(<?php echo $reg_pro['pro_id'] ?>, 1);"> 
				<img src="../../iconos/edit.png" alt="">Editar
			</button>
		</div>
	<?php }*/ ?>
					<div class="col-md-1" style="float: right;margin-right: 80px">
						<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
					</div>
					<div class="col-md-1">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();"><img src="../iconos/close.png" alt="">Cerrar</button>
					</div>

			</form>

		</div>
	</div>
</div>