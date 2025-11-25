<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*20 - Abril - 2019*/
require '../../../conexion/conexion.php';
require '../../funciones_procesos.php';
include('../../../seguridad/user_seguridad.php');
$cnx =  Conectarse();

$reg_pro['pro_id'] = $_POST['pro_id'];

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 25");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_6b_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 25");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 25");
$reg_lib = mysqli_fetch_array($cad_lib);
?>
<script>
	$(document).ready(function() {
		$("#formFase6dE").submit(function() {

			var formData = $(this).serialize();
			$.ajax({
				url: "fases/editar/fase_6d_actualizar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase6dEOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase6dE').each(function() {
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

		formFase6dE.btn.disabled = true;
		formFase6dE.btn.value = "Enviando...";

		setTimeout(function() {
			formFase6dE.btn.disabled = true;
			formFase6dE.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
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
				$("#modalRenglon6d").html(result);
				$('#modalRenglon6d').modal('show')
			}
		});
		return false;
	}

	function AbreModalPaleto(proceso, lavador) {
		var datos = {
			"pro_id": proceso,
			"lavador": lavador
		}
		//alert($("hdd_pro_id").val());
		$.ajax({
			type: 'post',
			url: 'modal_paleto.php',
			data: datos,
			//data: {nombre:n},
			success: function(result) {
				$("#modalPaleto6d").html(result);
				$('#modalPaleto6d').modal('show')
			}
		});
		return false;
	}

	function AbreModalE6d(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_6d.php',
			data: datos,
			success: function(result) {
				$("#modalEditar6d").html(result);
				$('#modalEditar6d').modal('show')
			}
		});
		return false;
	}

	//Bloquear boton modal de lavadores a paleto
	function confirmEnviar3() {

		formModalP.btn.disabled = true;
		formModalP.btn.value = "Enviando...";

		setTimeout(function() {
			formModalP.btn.disabled = true;
			formModalP.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}
</script>
<div class="modal-dialog modal-lg" role="document" style="width: 1190px">
	<div class="modal-content">
		<div class="divProcesos1">
			<form autocomplete="off" id="formFase6dE" name="formFase6dE">
				<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
				<input name="hdd_pe_id" type="hidden" value="25" id="hdd_pe_id" />
				<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
				<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg6_id']; ?>" />

				<div class="headerdivProcesos">
					<div class="col-md-2">LAVADOS 1ER ÁCIDO</div>
					<div class="col-md-4">Este proceso se puede hacer con agua limpia</div>
				</div>

				<!--tiempos-->
				<div class="row" style="margin-bottom: 10px">
					<label class="col-md-1" style="width: 200px;">Fecha incio</label>
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
						<td>Lav</td>
						<td>Tipo Agua</td>
						<td>TEMP</td>
						<td>HR inicia llenado</td>
						<td>HR termina llenado</td>
						<td>HR inicia movimiento</td>
						<td>HR termina movimiento</td>
						<td>CE</td>
						<td>pH</td>
						<td>Agua a</td>
						<td>Observaciones</td>
					</tr>
					<?php
					$strVal = 'NO';

					for ($i = 1; $i <= 10; $i++) {
						$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_6b_d WHERE pfg6_id = '$reg_fa[pfg6_id]' and pfd6_ren = '$i' ");
						$reg_fad = mysqli_fetch_array($cad_fad);

						if ($i == 2 and $reg_fad['pfd6_ce'] == '') {
							$strVal = '';
						}/*else{$strVal = 'NO';}*/

						$fltVal = fnc_parametro_max(25, 'C');

						$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 25);

						if ($i <= 4 or $bolVal == 'Si') {
					?>
							<tr>
								<td>&nbsp;</td>
								<td align="center"><?php echo $i; ?></td>
								<td><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>">
									<!--<input type="text" class="form-control" id="<?php /*echo "txtLavTipAgua".$i ?>" name="<?php echo "txtLavTipAgua".$i ?>"  value="<?php if($reg_fad['pfd4_tipo_ag'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad['pfd4_tipo_ag']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo $strProp4;*/ ?> size="5">-->

									<select id="cbxTipAg<?php echo $i  ?>" class="form-control" style="width: 150px" name="cbxTipAg<?php echo $i  ?>" <?php if ($reg_fad['tpa_id'] == '') {
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

																																																										//mysqli_free_result($registros);

																																																											?>
									</select>
								</td>
								<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php echo "txtTemp" . $i ?>" name="<?php echo "txtTemp" . $i ?>" value="<?php if ($reg_fad['pfd6_temp'] == '') {
																																																							echo "";
																																																							$str_prop = 'disabled';
																																																						} else {
																																																							echo $reg_fad['pfd6_temp'];
																																																							$str_prop = '';
																																																						} ?>" <?php echo $str_prop;
																																																								echo " " . $strProp2;
																																																								echo " " . $strProp3;
																																																								echo " " . $strProp4; ?> size="5" placeholder="Temp"></td>
								<td><input type="time" class="form-control" id="<?php echo "txtHraIni" . $i ?>" name="<?php echo "txtHraIni" . $i ?>" value="<?php if ($reg_fad['pfd6_hr_ini'] == '') {
																																									echo date("H:i");
																																									$str_prop = 'disabled';
																																								} else {
																																									echo $reg_fad['pfd6_hr_ini'];
																																									$str_prop = '';
																																								} ?>" <?php echo $str_prop;
																																										echo " " . $strProp2;
																																										echo " " . $strProp3;
																																										echo " " . $strProp4; ?>></td>
								<td><input type="time" class="form-control" id="<?php echo "txtHraFin" . $i ?>" name="<?php echo "txtHraFin" . $i ?>" value="<?php if ($reg_fad['pfd6_hr_fin'] == '') {
																																									echo date("H:i");
																																									$str_prop = 'disabled';
																																								} else {
																																									echo $reg_fad['pfd6_hr_fin'];
																																									$str_prop = '';
																																								} ?>" <?php echo $str_prop;
																																										echo " " . $strProp2;
																																										echo " " . $strProp3;
																																										echo " " . $strProp4; ?>></td>
								<td><input type="time" class="form-control" id="<?php echo "txtHraIniMov" . $i ?>" name="<?php echo "txtHraIniMov" . $i ?>" value="<?php if ($reg_fad['pfd6_hr_ini_mov'] == '') {
																																										echo date("H:i");
																																										$str_prop = 'disabled';
																																									} else {
																																										echo $reg_fad['pfd6_hr_ini_mov'];
																																										$str_prop = '';
																																									} ?>" <?php echo " " . $str_prop;
																																											echo " " . $strProp2;
																																											echo " " . $strProp3;
																																											echo " " . $strProp4; ?>></td>
								<td><input type="time" class="form-control" id="<?php echo "txtHraFinMov" . $i ?>" name="<?php echo "txtHraFinMov" . $i ?>" value="<?php if ($reg_fad['pfd6_hr_fin_mov'] == '') {
																																										echo date("H:i");
																																										$str_prop = 'disabled';
																																									} else {
																																										echo $reg_fad['pfd6_hr_fin_mov'];
																																										$str_prop = '';
																																									} ?>" <?php echo " " . $str_prop;
																																											echo " " . $strProp2;
																																											echo " " . $strProp3;
																																											echo " " . $strProp4; ?>></td>
								<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php echo "txtCe" . $i ?>" name="<?php echo "txtCe" . $i ?>" value="<?php if ($reg_fad['pfd6_ce'] == '') {
																																																						echo "";
																																																						$str_prop = 'disabled';
																																																					} else {
																																																						echo $reg_fad['pfd6_ce'];
																																																						$str_prop = '';
																																																					} ?>" <?php echo $str_prop;
																																																							echo " " . $strProp2;
																																																							echo " " . $strProp3;
																																																							echo " " . $strProp4; ?> size="5" <?php if ($fltVal >= $reg_fad['pfd6_ce'] and $reg_fad['pfd6_ce'] != '') { ?>style="background-color:#66FF66;" <?php } ?> placeholder="Ce"></td>
								<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php echo "txtPh" . $i ?>" name="<?php echo "txtPh" . $i ?>" value="<?php if ($reg_fad['pfd6_ph'] == '') {
																																																						echo "";
																																																						$str_prop = 'disabled';
																																																					} else {
																																																						echo $reg_fad['pfd6_ph'];
																																																						$str_prop = '';
																																																					} ?>" <?php echo $str_prop;
																																																							echo " " . $strProp2;
																																																							echo " " . $strProp3;
																																																							echo " " . $strProp4; ?> size="5" placeholder="pH"></td>
								<td>
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
								<td><input type="text" class="form-control" maxlength="100" id="<?php echo "txtObs" . $i ?>" name="<?php echo "txtObs" . $i ?>" value="<?php if ($reg_fad['pfd6_observaciones'] == '') {
																																											echo "";
																																											$str_prop = 'disabled';
																																										} else {
																																											echo $reg_fad['pfd6_observaciones'];
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
				<div class="row" id="campos"></div>
				<div class="row">
					<div class="form-row">
						<div class="form-group col-md-2">
							<label for="inputPassword4">Fecha termina lavados</label>
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
					</div>
					<div class="form-row">
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
					</div>
					<div class="form-row">
						<div class="col-md-2">
							<label for="inputPassword4">Horas reales</label>
							<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtHrsReales" name="txtHrsReales" placeholder="Hrs reales" value="<?php if ($reg_fa['pfg6_horas_reales'] == '') {
																																																			echo "";
																																																			$str_prop = 'disabled';
																																																		} else {
																																																			echo $reg_fa['pfg6_horas_reales'];
																																																			$str_prop = '';
																																																		} ?>" <?php echo $str_prop;
																																																				echo " " . $strProp6;
																																																				echo " " . $strProp3 . " " . $strProp4; ?>>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-2">
							<label for="inputPassword4">(<?php echo fnc_hora_de(16) ?> a <?php echo fnc_hora_a(16) ?> Horas)</label>
						</div>
					</div>
					<div class="col-md-3 divEtapas">
						<div class="col-md-1 etapa">
							<p class="numEtapa">6d</p>
						</div>
						<div class="col-md-2 divEtapasInput">
							<label class="etiquetaEtapa">LIBERACION CE A 1.2-2.0 MAX</label>
							<input type="text" id="txtCeLib" class="form-control" placeholder="Ce liberacion" name="txtCeLib" value="<?php if ($reg_lib['prol_ce'] == '') {
																																			echo "";
																																			$str_prop = 'disabled';
																																		} else {
																																			echo $reg_lib['prol_ce'];
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

				<!--textareaobservaciones-->
				<div class="row">
					<div class="col-md-7 textareaObservaciones">
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
				</div>

				<div class="row" style="margin-bottom: 10px">
					<label style="font-weight:bold; margin-left:20px;">Para pasar de lavados de 1er ácido a 2do ácido el tiempo debe ser de (1 A 3).</label>
					<label style="font-weight:bold; margin-left:20px;">En agua de este proceso se manda a agua recuperada semilimpia (PILA 2)</label>
				</div>

				<!--barra botones-->
				<div class="row footerdivProcesos" style="margin-bottom: 10px;">
					<div class="form-group col-md-7">
						<div class="alert alert-info hide" id="alerta-errorFase6dEOpe" style="height: 40px;width: 270px;text-align: left;z-index: 10;font-size: 10px; margin-bottom: -10px">
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
				<!--</div>-->

			</form>
			<div class="modal" id="modalRenglon6d" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

			<div class="modal" id="modalPaleto6d" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

			<div class="modal" id="modalEditar6d" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

		</div>
	</div>
</div>