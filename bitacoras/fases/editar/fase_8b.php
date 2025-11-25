<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*20 - Abril - 2019*/
require '../../../conexion/conexion.php';
require '../../funciones_procesos.php';
include('../../../seguridad/user_seguridad.php');
$cnx =  Conectarse();

$reg_pro['pro_id'] = $_POST['pro_id'];

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 21");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_8_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 21");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 21");
$reg_lib = mysqli_fetch_array($cad_lib);

?>
<script language="javascript">
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
				$("#modalRenglon8b").html(result);
				$('#modalRenglon8b').modal('show')
			}
		});
		return false;
	}

	function AbreModalPaletoB(proceso, lavador) {
		var datos = {
			"pro_id": proceso,
			"lavador": lavador
		}
		//alert($("hdd_pro_id").val());
		$.ajax({
			type: 'post',
			url: 'modal_paleto2.php',
			data: datos,
			//data: {nombre:n},
			success: function(result) {
				$("#modalPaleto8b").html(result);
				$('#modalPaleto8b').modal('show')
			}
		});
		return false;
	}

	function AbreModalE8b(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_8b.php',
			data: datos,
			success: function(result) {
				$("#modalEditar8b").html(result);
				$('#modalEditar8b').modal('show')
			}
		});
		return false;
	}

	$(document).ready(function() {
		$("#formFase8bE").submit(function() {

			var formData = $(this).serialize();
			$.ajax({
				url: "fases/editar/fase_8_actualizar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase8bEOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase8bE').each(function() {
						this.reset();
					});
					setTimeout("location.reload()", 2000);
				}
			});
			return confirmEnviar5();
			return false;

		});

	});
	//Bloquear boton guardar
	function confirmEnviar5() {

		formFase8bE.btn.disabled = true;
		formFase8bE.btn.value = "Enviando...";

		setTimeout(function() {
			formFase8bE.btn.disabled = true;
			formFase8bE.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}
</script>
<div class="modal-dialog modal-lg" role="document" style="width: 1210px">
	<div class="modal-content">
		<div class="divProcesos1">
			<form autocomplete="off" id="formFase8bE" name="formFase8bE">
				<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
				<input name="hdd_pe_id" type="hidden" value="21" id="hdd_pe_id" />
				<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
				<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg8_id']; ?>" />
				<input name="hdd_lav" type="hidden" id="hdd_lav" value="<?php echo $id_l; ?>" />

				<div class="headerdivProcesos">
					<div class="col-md-2">LAVADOS FINALES</div>
					<div class="col-md-4">Este proceso se utilizará agua limpia</div>
				</div>

				<!--tiempos-->
				<div class="row" style="margin-bottom: 10px">
					<label class="col-md-1" style="width: 200px;">Fecha que inicio</label>
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
				</div>
				<!---->

				<table border="0" cellspacing="5" cellpadding="5">
					<tr class="etiqueta_tbl">
						<td width="15">&nbsp;</td>
						<td></td>
						<td>Tipo agua</td>
						<td>Movimiento</td>
						<td>&nbsp;</td>
						<td>Hr Ini Llenado</td>
						<td>Hr Fin Llenado</td>
						<td>Hr Ini Drenado</td>
						<td>Hr Fin Drenado</td>
						<td>PH</td>
						<td>CE</td>
						<td>TEMP</td>
						<td>Agua a</td>
						<td>Observaciones</td>
						<td rowspan="5">Para hacer los lavados finales deben de tardar de (6 a 10 hrs)</td>
					</tr>
					<?php
					for ($i = 1; $i <= 10; $i++) {
						$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_8_d WHERE pfg8_id = '$reg_fa[pfg8_id]' and pfd8_ren = '$i' ");
						$reg_fad = mysqli_fetch_array($cad_fad);

						$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 21);

						if ($i <= 4 or $bolVal == 'Si') {
					?>
							<tr>
								<td>&nbsp;</td>
								<td><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
								<td>
									<select id="cbxTipAgd<?php echo $i  ?>" class="form-control" style="width: 140px" name="cbxTipAgd<?php echo $i  ?>" <?php if ($reg_fad['tpa_id'] == '') {
																																							echo "";
																																							$str_prop = 'disabled';
																																						} else {
																																							$str_prop = '';
																																						}
																																						echo $str_prop;
																																						echo " " . $strProp2;
																																						echo " " . $strProp3;
																																						echo " " . $strProp4; ?>>
										<option value="">Seleccionar</option>
										<?php
										$cadena =  mysqli_query($cnx, "SELECT * from tipos_agua ORDER BY tpa_descripcion");
										$registros =  mysqli_fetch_array($cadena);

										do {
										?><option value="<?php echo $registros['tpa_id'] ?>" <?php if ($registros['tpa_id'] == $reg_fad['tpa_id']) { ?>selected="selected" <?php } ?>><?php echo $registros['tpa_descripcion'] ?></option><?php
																																																										} while ($registros =  mysqli_fetch_array($cadena));
																																																											?>
									</select>
								</td>
								<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php echo "txtMov" . $i ?>" name="<?php echo "txtMov" . $i ?>" size="7" placeholder="Mov" value="<?php if ($reg_fad['pfd8_mov'] == '') {
																																																													echo "";
																																																													$str_prop = 'disabled';
																																																												} else {
																																																													echo $reg_fad['pfd8_mov'];
																																																													$str_prop = '';
																																																												} ?>" <?php echo $str_prop;
																																																														echo " " . $strProp2;
																																																														echo " " . $strProp3;
																																																														echo " " . $strProp4; ?>></td>
								<td>MIN</td>
								<td><input type="time" id="txtIniLlen<?php echo $i; ?>" name="txtIniLlen<?php echo $i; ?>" class="form-control" value="<?php if ($reg_fad['pfd8_ini_llenado'] == '') {
																																							echo date("H:i");
																																							$str_prop = 'disabled';
																																						} else {
																																							echo $reg_fad['pfd8_ini_llenado'];
																																							$str_prop = '';
																																						} ?>" <?php echo $str_prop;
																																								echo " " . $strProp2;
																																								echo " " . $strProp3;
																																								echo " " . $strProp4; ?> /> </td>

								<td><input type="time" id="txtFinLlen<?php echo $i; ?>" name="txtFinLlen<?php echo $i; ?>" class="form-control" value="<?php if ($reg_fad['pfd8_fin_llenado'] == '') {
																																							echo date("H:i");
																																							$str_prop = 'disabled';
																																						} else {
																																							echo $reg_fad['pfd8_fin_llenado'];
																																							$str_prop = '';
																																						} ?>" <?php echo $str_prop;
																																								echo " " . $strProp2;
																																								echo " " . $strProp3;
																																								echo " " . $strProp4; ?> /></td>
								<td><input type="time" id="txtIniDren<?php echo $i; ?>" name="txtIniDren<?php echo $i; ?>" class="form-control" value="<?php if ($reg_fad['pfd8_ini_dren'] == '') {
																																							echo date("H:i");
																																							$str_prop = 'disabled';
																																						} else {
																																							echo $reg_fad['pfd8_ini_dren'];
																																							$str_prop = '';
																																						} ?>" <?php echo $str_prop;
																																								echo " " . $strProp2;
																																								echo " " . $strProp3;
																																								echo " " . $strProp4; ?> /> </td>

								<td><input type="time" id="txtFinDren<?php echo $i; ?>" name="txtFinDren<?php echo $i; ?>" class="form-control" value="<?php if ($reg_fad['pfd8_fin_dren'] == '') {
																																							echo date("H:i");
																																							$str_prop = 'disabled';
																																						} else {
																																							echo $reg_fad['pfd8_fin_dren'];
																																							$str_prop = '';
																																						} ?>" <?php echo $str_prop;
																																								echo " " . $strProp2;
																																								echo " " . $strProp3;
																																								echo " " . $strProp4; ?> /></td>
								<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" name="txtPh<?php echo $i; ?>" id="txtPh<?php echo $i; ?>" class="form-control" placeholder="PH" value="<?php if ($reg_fad['pfd8_ph'] == '') {
																																																								echo "";
																																																								$str_prop = 'disabled';
																																																							} else {
																																																								echo $reg_fad['pfd8_ph'];
																																																								$str_prop = '';
																																																							} ?>" <?php echo $str_prop;
																																																									echo " " . $strProp2;
																																																									echo " " . $strProp3;
																																																									echo " " . $strProp4; ?> /> </td>
								<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" name="txtCe<?php echo $i; ?>" id="txtCe<?php echo $i; ?>" class="form-control" placeholder="CE" value="<?php if ($reg_fad['pfd8_ce'] == '') {
																																																								echo "";
																																																								$str_prop = 'disabled';
																																																							} else {
																																																								echo $reg_fad['pfd8_ce'];
																																																								$str_prop = '';
																																																							} ?>" <?php echo $str_prop;
																																																									echo " " . $strProp2;
																																																									echo " " . $strProp3;
																																																									echo " " . $strProp4; ?> /></td>
								<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" name="txtTemp<?php echo $i; ?>" id="txtTemp<?php echo $i; ?>" class="form-control" placeholder="TEMP" value="<?php if ($reg_fad['pfd8_temp'] == '') {
																																																									echo "";
																																																									$str_prop = 'disabled';
																																																								} else {
																																																									echo $reg_fad['pfd8_temp'];
																																																									$str_prop = '';
																																																								} ?>" <?php echo $str_prop;
																																																										echo " " . $strProp2;
																																																										echo " " . $strProp3;
																																																										echo " " . $strProp4; ?> /></td>
								<td align="center">
									<select id="cbxAgua<?php echo $i  ?>" class="form-control" style="width: 150px" name="cbxAgua<?php echo $i  ?>" <?php if ($reg_fad['taa_id'] == '') {
																																						echo "";
																																						$str_prop = 'disabled';
																																					} else {
																																						$str_prop = '';
																																					}
																																					echo $str_prop;
																																					echo " " . $strProp2;
																																					echo " " . $strProp3;
																																					echo " " . $strProp4; ?>>


										<option value="">Seleccionar</option>
										<?php
										$cadena =  mysqli_query($cnx, "SELECT * from tipos_agua_a");
										$registros =  mysqli_fetch_array($cadena);

										do {
										?><option value="<?php echo $registros['taa_id'] ?>" <?php if ($registros['taa_id'] == $reg_fad['taa_id']) { ?>selected="selected" <?php } ?>><?php echo $registros['taa_descripcion'] ?></option><?php
																																																										} while ($registros =  mysqli_fetch_array($cadena));

																																																										//mysqli_free_result($registros);

																																																											?>
									</select>
								</td>
								<td><input type="text" class="form-control" maxlength="100" id="<?php echo "txtObs" . $i ?>" name="<?php echo "txtObs" . $i ?>" value="<?php if ($reg_fad['pfd8_observaciones'] == '') {
																																											echo "";
																																											$str_prop = 'disabled';
																																										} else {
																																											echo $reg_fad['pfd8_observaciones'];
																																											$str_prop = '';
																																										} ?>" <?php echo $str_prop;
																																												echo " " . $strProp2;
																																												echo " " . $strProp3;
																																												echo " " . $strProp4; ?> size="5" placeholder="Observaciones"></td>
							</tr>
					<?php
							$valUltRen = $i;
						} //termina if

					} // termina for
					?>
				</table>
				<div id="campos7"></div>
				<!--tiempos-->
				<div class="col-md-12">
					<div class="col-md-2 tiempos" style="margin-left: -10px;width: 220px">
						<label>Fecha term lavados finales</label>
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
					<div class="col-md-1 tiempos" style="margin-left: 20px;width: 130px">
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
					<div class="col-md-1 tiempos" style="margin-left: 20px;width: 130px">
						<label>Hora totales</label>
						<input maxlength="4" type="text" id="txtHrTotales1" class="form-control" placeholder="Horas totales" name="txtHrTotales1" value="<?php if ($reg_fa['pfg8_hr_totales'] == 'disabled') {
																																								echo "";
																																								$str_prop = '';
																																							} else {
																																								echo $reg_fa['pfg8_hr_totales'];
																																								$str_prop = '';
																																							} ?>" <?php echo $str_prop;
																																									echo " " . $strProp1;
																																									echo " " . $strProp3; ?>>
					</div>
					<div class="col-md-2 tiempos" style="margin-left: 20px">
						<label>Realizó</label>
						<input type="text" id="txtRealizo" class="form-control" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
					</div>
					<label for="inputPassword4">(<?php echo fnc_hora_de(21) ?> a <?php echo fnc_hora_a(21) ?> horas)</label>
				</div>
				<!---->

				<div class="col-md-12">
					<label style="font-weight:bold; margin-left:20px;margin-top: 20px">El agua de este proceso se manda a (Pila 1) agua recuperada limpia</label>
				</div>
				<!--tiempos-->
				<div class="col-md-8" style="border: 1px solid#e6e6e6;border-radius: 5px;background: #e6e6e6;margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
					<div class="col-md-4 tiempos" style="margin-left: -25px;">
						<label>Hrs totales del proceso</label>
						<input type="text" id="txtHrasTotales" class="form-control" placeholder="Horas totales" name="txtHrasTotales" value="<?php if ($reg_fa['pfg8_hr_totales2'] == '') {
																																					echo fnc_horas($strFech, date("Y-m-d"), $strHr, date("H:i"));;
																																					$str_prop = 'disabled';
																																				} else {
																																					echo $reg_fa['pfg8_hr_totales2'];
																																					$str_prop = '';
																																				} ?>" <?php echo $str_prop;
																																						echo " " . $strProp1;
																																						echo " " . $strProp3; ?>>
					</div>
					<div class="col-md-2 tiempos" style="margin-left: 20px">
						<label>Revisó</label>
						<input type="text" id="txtRealizo2" class="form-control" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
					</div>
					<label for="inputPassword4">(67 a 94 horas)</label>
				</div>


				<!--tiempos-->
				<div class="col-md-12">
					<div class="col-md-2 tiempos" style="margin-left: -10px;width: 220px">
						<label>Fecha sale a producción</label>
						<input type="date" class="form-control" id="txtFeLibProd" name="txtFeLibProd" placeholder="" value="<?php if ($reg_fa['pfg8_fe_lib_prod'] == '') {
																																echo date("Y-m-d");
																																$str_prop = 'disabled';
																															} else {
																																echo $reg_fa['pfg8_fe_lib_prod'];
																																$str_prop = '';
																															} ?>" <?php echo $str_prop;
																																	echo " " . $strProp1;
																																	echo " " . $strProp3; ?>>
					</div>
					<div class="col-md-2 tiempos" style="margin-left: 20px;">
						<label>Hora</label>
						<input type="time" class="form-control" id="txtHrLibProd" name="txtHrLibProd" placeholder="" value="<?php if ($reg_fa['pfg8_hr_lib_prod'] == '') {
																																echo date("H:i");
																																$str_prop = 'disabled';
																															} else {
																																echo $reg_fa['pfg8_hr_lib_prod'];
																																$str_prop = '';
																															} ?>" <?php echo $str_prop;
																																	echo " " . $strProp1;
																																	echo " " . $strProp3; ?> />
					</div>
				</div>
				<!---->



				<!--textareaobservaciones-->
				<div class="row">
					<div class="col-md-7 textareaObservaciones" style="margin-top: 20px">
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

					<div class="col-md-3 divEtapas8" style="height: 250px">
						<div class="col-md-1 etapa" style="height: 250px">
							<p class="numEtapa">8b</p>
						</div>
						<div class="col-md-2 divEtapasInput8">
							<label class="etiquetaEtapa">COCIDO LIBERACIÓN ( PH <?php echo fnc_rango_de(21) ?> - <?php echo fnc_rango_a(21) ?>)</label>
							<input type="date" class="form-control" id="txtFeLib" name="txtFeLib" placeholder="" style="width: 125px" value="<?php if ($reg_lib['prol_fecha'] == '') {
																																					echo "";
																																					$str_prop = 'disabled';
																																				} else {
																																					echo $reg_lib['prol_fecha'];
																																					$str_prop = '';
																																				} ?>" <?php echo $str_prop;
																																						echo " " . $strProp1;
																																						echo " " . $strProp2; ?>>
							<input type="time" class="form-control" id="txtHrLib" name="txtHrLib" placeholder="" style="width: 85px;float: right;margin-top: -30px" value="<?php if ($reg_lib['prol_hora'] == '') {
																																												echo "";
																																												$str_prop = 'disabled';
																																											} else {
																																												echo $reg_lib['prol_hora'];
																																												$str_prop = '';
																																											} ?>" <?php echo $str_prop;
																																													echo " " . $strProp1;
																																													echo " " . $strProp2; ?>>
							<input type="tex" class="form-control" id="txtPhLib1" name="txtPhLib1" placeholder="(L1) Cocido ph" style="width: 125px" value="<?php if ($reg_lib['prol_cocido_ph1'] == '') {
																																								echo "";
																																								$str_prop = 'disabled';
																																							} else {
																																								echo $reg_lib['prol_cocido_ph1'];
																																								$str_prop = '';
																																							} ?>" <?php echo $str_prop;
																																									echo " " . $strProp1;
																																									echo " " . $strProp2; ?> />
							<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtCeLib1" name="txtCeLib1" placeholder="Ce" style="width: 85px;float: right;margin-top: -30px" value="<?php if ($reg_lib['prol_ce1'] == '') {
																																																												echo "";
																																																												$str_prop = 'disabled';
																																																											} else {
																																																												echo $reg_lib['prol_ce1'];
																																																												$str_prop = '';
																																																											} ?>" <?php echo $str_prop;
																																																													echo " " . $strProp1;
																																																													echo " " . $strProp2; ?>>
							<input type="tex" class="form-control" id="txtPhLib2" name="txtPhLib2" placeholder="(L2) Cocido ph" style="width: 125px" value="<?php if ($reg_lib['prol_cocido_ph2'] == '') {
																																								echo "";
																																								$str_prop = 'disabled';
																																							} else {
																																								echo $reg_lib['prol_cocido_ph2'];
																																								$str_prop = '';
																																							} ?>" <?php echo $str_prop;
																																									echo " " . $strProp1;
																																									echo " " . $strProp2; ?> />
							<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtCeLib2" name="txtCeLib2" placeholder="Ce" style="width: 85px;float: right;margin-top: -30px" value="<?php if ($reg_lib['prol_ce2'] == '') {
																																																												echo "";
																																																												$str_prop = 'disabled';
																																																											} else {
																																																												echo $reg_lib['prol_ce2'];
																																																												$str_prop = '';
																																																											} ?>" <?php echo $str_prop;
																																																													echo " " . $strProp1;
																																																													echo " " . $strProp2; ?>>
							<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" id="txtCocidoLib" name="txtCocidoLib" class="form-control" placeholder="Cocido liberación % ext" value="<?php if ($reg_lib['prol_cocido_lib'] == '') {
																																																						echo "";
																																																						$str_prop = 'disabled';
																																																					} else {
																																																						echo $reg_lib['prol_cocido_lib'];
																																																						$str_prop = '';
																																																					} ?>" <?php echo $str_prop;
																																																							echo " " . $strProp1;
																																																							echo " " . $strProp2; ?> />
							<select type="text" id="cbxColor" class="form-control" placeholder="Colores" name="cbxColor" value="<?php if ($reg_lib['prol_color'] == '') {
																																	echo "";
																																	$str_prop = 'disabled';
																																} else {
																																	echo $reg_lib['prol_color'];
																																	$str_prop = '';
																																} ?>" <?php echo $str_prop;
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
							<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" id="txtSolides" name="txtSolides" class="form-control" placeholder="% de solides" value="<?php if ($reg_lib['prol_solides'] == '') {
																																																		echo "";
																																																		$str_prop = 'disabled';
																																																	} else {
																																																		echo $reg_lib['prol_solides'];
																																																		$str_prop = '';
																																																	} ?>" <?php echo $str_prop;
																																																			echo " " . $strProp1;
																																																			echo " " . $strProp2; ?>>
							<input type="text" id="txtUsuario" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
						</div>
					</div>
				</div>

				<!--barra botones-->
				<div class="row footerdivProcesos" style="margin-bottom: 10px;">
					<div class="form-group col-md-7">
						<div class="alert alert-info hide" id="alerta-errorFase8bEOpe" style="height: 40px;width: 270px;text-align: left;z-index: 10;font-size: 10px;margin-bottom: -10px">
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
			<div class="modal" id="modalRenglon8b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

			<div class="modal" id="modalPaleto8b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

			<div class="modal" id="modalEditar8b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

		</div>
	</div>
</div>