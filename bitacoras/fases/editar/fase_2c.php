<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*20 - Abril - 2019*/
require '../../../conexion/conexion.php';
require '../../funciones_procesos.php';
include('../../../seguridad/user_seguridad.php');
$cnx =  Conectarse();

$reg_pro['pro_id'] = $_POST['pro_id'];

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 4");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 4");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 4");
$reg_lib = mysqli_fetch_array($cad_lib);
?>
<script>
	$(document).ready(function() {
		$("#formFase2cE").submit(function() {
			var formData = $(this).serialize();
			$.ajax({
				url: "fases/editar/fase_2c_actualizar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase2cEOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase2E').each(function() {
						this.reset();
					});
					//setTimeout(location.reload(), 23000);
					//setTimeout("location.reload()", 2000);
				}
			});
			return confirmEnviar2c();
			return false;

		});
	});


	//Bloquear boton guardar
	function confirmEnviar2c() {

		formFase2cE.btn.disabled = true;
		formFase2cE.btn.value = "Enviando...";

		setTimeout(function() {
			formFase2cE.btn.disabled = true;
			formFase2cE.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}
</script>
<div class="modal-dialog modal-lg" role="document" style="width: 1100px">
	<div class="modal-content">
		<div class="divProcesos1">
			<form autocomplete="off" id="formFase2cE" name="formFase2cE" >
				<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
				<input name="hdd_pe_id" type="hidden" value="4" id="hdd_pe_id" />
				<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
				<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg2_id']; ?>" />

				<div class="headerdivProcesos">
					<div class="col-md-2">BLANQUEO</div>
					<div class="col-md-10"></div>
				</div>
				<?php //if($_SESSION['privilegio'] == 3 ){
				?>
				<div class="row" style="margin-bottom: 10px">
					<label class="col-md-1" style="width: 160px;">Fecha que inicia</label>
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

					<label class="col-md-1" style="width: 130px;">Hora inicio</label>
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

					<label class="col-md-1" style="width: 200px;">Temp agua utilizada</label>
					<div class="col-md-2 tiempos">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtTemp" placeholder="" name="txtTemp" value="<?php if ($reg_fa['pfg2_temp_ag'] == '') {
																																													echo "";
																																													$str_prop = 'disabled';
																																												} else {
																																													echo $reg_fa['pfg2_temp_ag'];
																																													$str_prop = '';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
					</div>
				</div>


				<div class="row" style="margin-bottom: 30px">
					<label class="col-md-1" style="width: 140px">pH antes de ajuste</label>
					<div class="col-md-1">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtPhAnt" placeholder="" name="txtPhAnt" value="<?php if ($reg_fa['pfg2_ph_ant'] == '') {
																																													echo "";
																																													$str_prop = 'disabled';
																																												} else {
																																													echo $reg_fa['pfg2_ph_ant'];
																																													$str_prop = '';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
					</div>

					<label class="col-md-1" style="width: 40px">CE</label>
					<div class="col-md-1">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtCe" placeholder="" name="txtCe" value="<?php if ($reg_fa['pfg2_ce'] == '') {
																																												echo "";
																																												$str_prop = 'disabled';
																																											} else {
																																												echo $reg_fa['pfg2_ce'];
																																												$str_prop = '';
																																											} ?>" <?php echo $str_prop;
																																													echo " " . $strProp2;
																																													echo " " . $strProp3; ?> required>
					</div>

					<label class="col-md-1" style="width: 160px">Ajuste con SOSA</label>
					<div class="col-md-1">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtAjSosa" placeholder="" name="txtAjSosa" value="<?php if ($reg_fa['pfg2_sosa'] == '') {
																																														echo "";
																																														$str_prop = 'disabled';
																																													} else {
																																														echo $reg_fa['pfg2_sosa'];
																																														$str_prop = '';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3; ?> required>
					</div>
					<label class="col-md-1" style="width: 210px">pH ajustado (11.9 A 12.3)</label>
					<div class="col-md-1">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtPhAj" placeholder="" name="txtPhAj" value="<?php if ($reg_fa['pfg2_ph_aju'] == '') {
																																													echo "";
																																													$str_prop = 'disabled';
																																												} else {
																																													echo $reg_fa['pfg2_ph_aju'];
																																													$str_prop = '';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
					</div>

					<label class="col-md-1" style="width: 90px">Peroxido</label>
					<div class="col-md-1">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtPeroxido" placeholder="Peroxido" name="txtPeroxido" value="<?php if ($reg_fa['pfg2_peroxido'] == '') {
																																																	echo "";
																																																	$str_prop = 'disabled';
																																																} else {
																																																	echo $reg_fa['pfg2_peroxido'];
																																																	$str_prop = '';
																																																} ?>" <?php echo $str_prop;
																																																		echo " " . $strProp2;
																																																		echo " " . $strProp3; ?> required>
					</div>
				</div>
				<div class="row">
					<div class="col-md-7">
						<table border="0" cellspacing="5" cellpadding="5">
							<!-- <tr class="etiqueta_tbl">
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="center" bgcolor="#FF6493">PPRO</td>
								<td>&nbsp;</td>
								<td></td>
							</tr>-->
							<tr class="etiqueta_tbl">
								<td>No.</td>
								<td>Hora</td>
								<td>Ph</td>
								<td>SOSA</td>
								<!--<td>Acido</td>-->
								<!--<td>Peroxido</td>-->
								<td>TEMP</td>
								<td align="center" bgcolor="#FF6493">REDOX</td>
								<td>&nbsp;</td>
								<!--<td rowspan="3">
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td>PPM 1,500</td>
										</tr>
										<tr>
											<td>MIN 700 PPM</td>
										</tr>
									</table>
								</td>-->
							</tr>
							<?php
							for ($i = 1; $i <= 15; $i++) {
								//if ($i > 2) {
								//	$i += 1;
								//}
								if ($i == '1') {
									$etiqueta = "MIN 2000 PPM";
								} else if ($i == '4') {
									$etiqueta = "MIN 700 PPM";
								} else if ($i == '6') {
									$etiqueta = "MIN 300 PPM";
								} else {
									$etiqueta = "";
								}

								$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_d WHERE pfg2_id = '$reg_fa[pfg2_id]' and pfd2_ren = '$i' ");
								$reg_fad = mysqli_fetch_array($cad_fad);

								$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 4);

								if ($i <= 10 or $bolVal == 'Si') {
							?>
									<tr>
										<td><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
										<td><input type="time" class="form-control" id="txtHr<?php echo $i; ?>" placeholder="" name="txtHr<?php echo $i; ?>" value="<?php if ($reg_fad['pfd2_hr'] == '') {
																																										echo date("H:i");
																																										$str_prop = 'disabled';
																																									} else {
																																										echo $reg_fad['pfd2_hr'];
																																										$str_prop = '';
																																									} ?>" <?php echo $str_prop;
																																											echo " " . $strProp2;
																																											echo " " . $strProp3;
																																											echo " " . $strProp4; ?>></td>
										<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtPh<?php echo $i; ?>" placeholder="pH" name="txtPh<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_ph'] == '') {
																																																												echo "";
																																																												$str_prop = 'disabled';
																																																											} else {
																																																												echo $reg_fad['pfd2_ph'];
																																																												$str_prop = '';
																																																											} ?>" <?php echo $str_prop;
																																																													echo " " . $strProp2;
																																																													echo " " . $strProp3;
																																																													echo " " . $strProp4; ?>></td>
										<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtSosa<?php echo $i; ?>" placeholder="Sosa" name="txtSosa<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_sosa'] == '') {
																																																														echo "";
																																																														$str_prop = 'disabled';
																																																													} else {
																																																														echo $reg_fad['pfd2_sosa'];
																																																														$str_prop = '';
																																																													} ?>" <?php echo $str_prop;
																																																															echo " " . $strProp2;
																																																															echo " " . $strProp3;
																																																															echo " " . $strProp4; ?>></td>
										<!--<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtAcido<?php echo $i; ?>" placeholder="Acido" name="txtAcido<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_acido'] == '') {
																																																															echo "";
																																																															$str_prop = 'disabled';
																																																														} else {
																																																															echo $reg_fad['pfd2_acido'];
																																																															$str_prop = '';
																																																														} ?>" <?php echo $str_prop;
																																																																echo " " . $strProp2;
																																																																echo " " . $strProp3;
																																																																echo " " . $strProp4; ?>></td>-->
										<!-- <td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtPeroxido<?php /* echo $i; ?>" placeholder="Temp" name="txtPeroxido<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_peroxido'] == '') {
																																																																echo "";
																																																																$str_prop = 'disabled';
																																																															} else {
																																																																echo $reg_fad['pfd2_peroxido'];
																																																																$str_prop = '';
																																																															} ?>" <?php echo $str_prop;
																																																																	echo " " . $strProp2;
																																																																	echo " " . $strProp3;
																																																																	echo " " . $strProp4; */ ?>></td>-->
										<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtTemp<?php echo $i; ?>" placeholder="Temp" name="txtTemp<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_temp'] == '') {
																																																														echo "";
																																																														$str_prop = 'disabled';
																																																													} else {
																																																														echo $reg_fad['pfd2_temp'];
																																																														$str_prop = '';
																																																													} ?>" <?php echo $str_prop;
																																																															echo " " . $strProp2;
																																																															echo " " . $strProp3;
																																																															echo " " . $strProp4; ?>></td>
										<td align="center" bgcolor="#FF6493" width="100"><?php if ($i == '1' || $i == '4' || $i == '6') { ?><input type="text" maxlength="8" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtRedox<?php echo $i; ?>" placeholder="Redox" name="txtRedox<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_redox'] == '') {
																																																																															echo "";
																																																																															$str_prop = 'disabled';
																																																																														} else {
																																																																															echo $reg_fad['pfd2_redox'];
																																																																															$str_prop = '';
																																																																														} ?>" <?php echo $str_prop;
																																																																																echo " " . $strProp2;
																																																																																echo " " . $strProp3;
																																																																																echo " " . $strProp4; ?>><?php } ?></td>
										<td><?php echo $etiqueta ?></td>
									</tr>
							<?php $valUltRen = $i + 1;
								} //termina if

							} // termina for
							?>
						</table>

					</div>

					<?php //if($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28  ){
					?>
					<div class="col-md-3 divEtapas">
						<div class="col-md-1 etapa">
							<p class="numEtapa">2c</p>
						</div>
						<div class="col-md-2 divEtapasInput">
							<label class="etiquetaEtapa">LIBERACION pH <?php echo fnc_rango_de(2) ?> - <?php echo fnc_rango_a(2) ?></label>
							<select type="text" id="cbxColor" class="form-control" placeholder="Colores" name="cbxColor" <?php if ($reg_lib['prol_color'] == '') {
																																echo "";
																																$str_prop = 'disabled';
																															} else {
																																echo "<option value='$reg_lib[prol_color]'>$reg_lib[prol_color]</option>";
																																$str_prop = '';
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
							<input type="text" id="txtPhLib" class="form-control" placeholder="pH liberacion" name="txtPhLib" value="<?php if ($reg_lib['prol_ph'] == '') {
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
							<input type="text" id="inputPassword" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
							<!--<input  type="text" id="inputPassword" class="form-control" placeholder="Firma LCP">-->
						</div>
					</div>
				</div>
				<div class="row" style="margin-top: 20px">
					<div class="form-group col-md-2">
						<label for="inputPassword4">Fecha termina</label>
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

					<div class="form-group col-md-2">
						<label for="inputPassword4">Hora termina</label>
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


					<div class="form-group col-md-2">
						<label for="inputPassword4">(<?php echo fnc_hora_de(4) ?> a <?php echo fnc_hora_a(4) ?> Horas)</label>
					</div>
				</div>
			<!-- </form> aqui ceci-->
		<!--</div>-->
		<div class="row">
			<div class="col-md-7 ">
				<label for="inputPassword4">Observaciones</label>
				<textarea type="textarea" maxlength="300" class="form-control" id="" placeholder="Observaciones..." name="txaObservaciones" value="<?php if ($reg_aux['proa_observaciones'] == '') {
																																						$str_prop = 'disabled';
																																					} else {
																																						$str_prop = '';
																																					} ?>" <?php echo $str_prop;
																																							echo " " . $strProp6;
																																							echo " " . $strProp3;
																																							echo " " . $strProp5 . " " . $strProp4; ?>><?php echo $reg_aux['proa_observaciones']; ?></textarea>
			</div>
		</div>

		<!--barra botones-->
		<div class="row footerdivProcesos" style="margin-bottom: 10px">
			<div class="col-md-4">
				<label style="font-weight:bold; margin-left:20px;">&nbsp;</label>
			</div>

			<div class="form-group col-md-4">
				<div class="alert alert-info hide" id="alerta-errorFase2cEOpe" style="height: 40px;width: 300px;text-align: left;z-index: 10;font-size: 10px;">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>
			<div class="col-md-1" style="float: right;margin-right: 80px">
				<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
			</div>
			<div class="col-md-1" style="float: right;margin-right: 30px">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();"><img src="../iconos/close.png" alt="">Cerrar</button>
			</div>
			
		</div>
		</form>
		</div>
	</div>
</div>