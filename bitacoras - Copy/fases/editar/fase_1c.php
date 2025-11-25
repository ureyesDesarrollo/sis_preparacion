<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*20 - Abril - 2019*/
require '../../../conexion/conexion.php';
require '../../funciones_procesos.php';
include('../../../seguridad/user_seguridad.php');
$cnx =  Conectarse();

$reg_pro['pro_id'] = $_POST['pro_id'];

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 31");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa1 = mysqli_query($cnx, "SELECT * FROM procesos_fase_1_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 31");
$reg_fa1 = mysqli_fetch_array($cad_fa1);
//echo "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 31";
$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 31");
$reg_lib = mysqli_fetch_array($cad_lib);

?>
<script>
	$(document).ready(function() {
		$("#formFase1cE").submit(function() {

			var formData = $(this).serialize();
			$.ajax({
				url: "fases/editar/fase_1c_actualizar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase1cEOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase1cE').each(function() {
						this.reset();
					});
					//setTimeout(location.reload(), 23000);
					setTimeout("location.reload()", 2000);
				}
			});
			return confirmEnviar5();
			return false;

		});
	});

	function AbreModalAgregarR(proceso, etapa, uren) {
		//alert(proceso);
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
				$("#modalRenglon1").html(result);
				$('#modalRenglon1').modal('show')
			}
		});

		return false;
	}

	//Bloquear boton guardar
	function confirmEnviar5() {

		formFase1cE.btn.disabled = true;
		formFase1cE.btn.value = "Enviando...";

		setTimeout(function() {
			formFase1cE.btn.disabled = true;
			formFase1cE.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}
</script>

<div class="modal-dialog modal-lg" role="document" style="width: 1210px">
	<div class="modal-content">
		<div class="divProcesos1">
			<form autocomplete="off" id="formFase1cE" name="formFase1cE">
				<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
				<input name="hdd_pe_id" type="hidden" value="31" id="hdd_pe_id" />
				<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
				<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa1['pfg1_id']; ?>" />
				<div class="headerdivProcesos">
					<div class="col-md-2">LAVADOS DE CARGA </div>
					<!-- <div class="col-md-4">Este proceso se puede hacer con aguar recuperada limpia (pila 1)</div>
			<div class="col-md-5">Lavados finales de paleto a paleto y en el ultimo lavado utilizar agua limpia si se necesita bajar CE</div> -->
				</div>

				<!--tiempos-->
				<?php //if($_SESSION['privilegio'] == 3 ){
				?>
				<div class="row" style="margin-bottom: 10px">
					<label class="col-md-1" style="width: 200px;">Fecha que inicia lavados</label>
					<div class="col-md-2 tiempos">
						<input type="date" class="form-control" id="txtFeIni" name="txtFeIni" placeholder="__________" value="<?php if ($reg_aux['proa_fe_ini'] == '') {
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
						<input type="time" class="form-control" id="txtHrIni" name="txtHrIni" placeholder="__________" value="<?php if ($reg_aux['proa_hr_ini'] == '') {
																																	echo date("H:i");
																																	$str_prop = 'disabled';
																																} else {
																																	echo $reg_aux['proa_hr_ini'];
																																	$str_prop = '';
																																} ?>" <?php echo $str_prop;
																																		echo " " . $strProp2;
																																		echo " " . $strProp3; ?> required>
					</div>

					<!--<label class="col-md-1" style="width: 165px">Temp agua utilizada</label>
					<div class="col-md-2 tiempos">
						<input  type="text"  maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtTemp" name="txtTemp" placeholder="TEMP" value="<?php if ($reg_fa1['pfg1_temp_ag'] == '') {
																																														echo "";
																																														$str_prop = 'disabled';
																																													} else {
																																														echo $reg_fa1['pfg1_temp_ag'];
																																														$str_prop = '';
																																													} ?>" 
															<?php
															echo $str_prop;
															echo " " . $strProp2;
															echo " " . $strProp3; ?> required>
					</div>-->

					<div class="col-md-2" style="height: 30px; border-radius: 5px; border:1px solid #e6e6e6; margin-bottom: 3px; font-weight:bold; background-color:#CCCCCC;width: 150px">
						<label>BAJAR CE A 3.0</label>
					</div>
				</div>
				<div class="row" style="margin-bottom: 10px">
					<label class="col-md-2" style="width: 120px;">Ph agua inicio</label>
					<div class="col-md-2 ">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtAgIni" name="txtAgIni" placeholder="Ph agua inicio" value="<?php if ($reg_fa1['pfg1_ph_agua'] == '') {
																																																		echo "";
																																																		$str_prop = 'disabled';
																																																	} else {
																																																		echo $reg_fa1['pfg1_ph_agua'];
																																																		$str_prop = '';
																																																	} ?>" <?php echo $str_prop;
																																																			echo " " . $strProp2;
																																																			echo " " . $strProp3; ?> required>
					</div>
					<label class="col-md-2" style="width: 120px">Ce agua inicio</label>
					<div class="col-md-2 ">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtCeIni" name="txtCeIni" placeholder="Ce agua de inicio" value="<?php if ($reg_fa1['pfg1_ce_agua'] == '') {
																																																			echo "";
																																																			$str_prop = 'disabled';
																																																		} else {
																																																			echo $reg_fa1['pfg1_ce_agua'];
																																																			$str_prop = '';
																																																		} ?>" <?php echo $str_prop;
																																																				echo " " . $strProp2;
																																																				echo " " . $strProp3; ?> required>
					</div>
					<label class="col-md-1" style="width: 140px">Temp agua inicio</label>
					<div class="col-md-1 tiempos">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txtTemp" name="txtTemp" placeholder="TEMP" value="<?php if ($reg_fa1['pfg1_temp_ag'] == '') {
																																															echo "";
																																															$str_prop = 'disabled';
																																														} else {
																																															echo $reg_fa1['pfg1_temp_ag'];
																																															$str_prop = '';
																																														} ?>" <?php echo $str_prop;
																																																echo " " . $strProp2;
																																																echo " " . $strProp3; ?> required>
					</div>
					<label class="col-md-1" style="width: 140px">Litros peróxido</label>
					<div class="col-md-2 tiempos">
						<input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="txt_peroxido" name="txt_peroxido" placeholder="Extractivilidad" value="<?php if ($reg_fa1['pfg1_peroxido'] == '') {
																																																				echo "";
																																																				$str_prop = 'disabled';
																																																			} else {
																																																				echo $reg_fa1['pfg1_peroxido'];
																																																				$str_prop = '';
																																																			} ?>" <?php echo $str_prop;
																																																					echo " " . $strProp2;
																																																					echo " " . $strProp3; ?> required>
					</div>
				</div>
				<!---->

				<!--<div>-->
				<table border="0" cellspacing="5" cellpadding="5">
					<tr class="etiqueta_tbl">
						<td width="15">&nbsp;</td>
						<td>Lav</td>
						<td>Tipo Agua</td>
						<!-- <td>TEMP</td> -->
						<td>HR ini lavado</td>
						<td>HR term lavado</td>
						<!-- 	<td>HR ini movimiento</td>
				<td>HR term movimiento</td> -->
						<td>pH</td>
						<td>CE</td>
						<!-- <td>Agua a</td> -->
						<td>Observaciones</td>
					</tr>
					<?php
					$strVal = 'NO';

					for ($i = 1; $i <= 10; $i++) {
						$cad_fad1 = mysqli_query($cnx, "SELECT * FROM procesos_fase_1_d WHERE pfg1_id = '$reg_fa1[pfg1_id]' and pfd1_ren = '$i' ");
						$reg_fad1 = mysqli_fetch_array($cad_fad1);

						if ($i == 2 and $reg_fad1['pfd1_ce'] == '') {
							$strVal = '';
						}/*else{$strVal = 'NO';}*/

						$fltVal = fnc_parametro_max(31, 'C');

						$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 31);

						if ($i <= 6 or $bolVal == 'Si') {
					?>
							<tr>
								<td>&nbsp;</td>
								<td align="center"><?php echo $i; ?></td>
								<td><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>">
									<!--<input type="text"  maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php /*echo "txtLavTipAgua".$i ?>" name="<?php echo "txtLavTipAgua".$i ?>"  value="<?php if($reg_fad1['pfd1_tipo_ag'] == ''){echo ""; $str_prop = '';}else{echo $reg_fad1['pfd1_tipo_ag']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; echo $strProp4;*/ ?> size="5">-->

									<select id="cbxTipAg<?php echo $i  ?>" class="form-control" style="width: 150px" name="cbxTipAg<?php echo $i  ?>" <?php if ($reg_fad1['tpa_id'] == '') {
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
										$cadena =  mysqli_query($cnx, "SELECT * from tipos_agua");
										$registros =  mysqli_fetch_array($cadena);

										do {
										?><option value="<?php echo $registros['tpa_id'] ?>" <?php if ($registros['tpa_id'] == $reg_fad1['tpa_id']) { ?>selected="selected" <?php } ?>><?php echo $registros['tpa_descripcion'] ?></option><?php
																																																										} while ($registros =  mysqli_fetch_array($cadena));

																																																										//mysqli_free_result($registros);

																																																											?>
									</select>
								</td>
								<!--<td><input type="text"  maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php echo "txtTemp" . $i ?>" name="<?php echo "txtTemp" . $i ?>" value="<?php if ($reg_fad1['pfd1_temp'] == '') {
																																																								echo "";
																																																								$str_prop = 'disabled';
																																																							} else {
																																																								echo $reg_fad1['pfd1_temp'];
																																																								$str_prop = '';
																																																							} ?>" <?php echo $str_prop;
																																																									echo " " . $strProp2;
																																																									echo " " . $strProp3;
																																																									echo " " . $strProp4; ?> size="5" placeholder="Temp"></td>-->
								<td><input type="time" class="form-control" id="<?php echo "txtHraIni" . $i ?>" name="<?php echo "txtHraIni" . $i ?>" value="<?php if ($reg_fad1['pfd1_hr_ini'] == '') {
																																									echo date("H:i");
																																									$str_prop = 'disabled';
																																								} else {
																																									echo $reg_fad1['pfd1_hr_ini'];
																																									$str_prop = '';
																																								} ?>" <?php echo $str_prop;
																																										echo " " . $strProp2;
																																										echo " " . $strProp3;
																																										echo " " . $strProp4; ?>></td>
								<td><input type="time" class="form-control" id="<?php echo "txtHraFin" . $i ?>" name="<?php echo "txtHraFin" . $i ?>" value="<?php if ($reg_fad1['pfd1_hr_fin'] == '') {
																																									echo date("H:i");
																																									$str_prop = 'disabled';
																																								} else {
																																									echo $reg_fad1['pfd1_hr_fin'];
																																									$str_prop = '';
																																								} ?>" <?php echo $str_prop;
																																										echo " " . $strProp2;
																																										echo " " . $strProp3;
																																										echo " " . $strProp4; ?>></td>
								<!--<td><input type="time" class="form-control" id="<?php echo "txtHraIniMov" . $i ?>" name="<?php echo "txtHraIniMov" . $i ?>" value="<?php if ($reg_fad1['pfd1_hr_ini_mov'] == '') {
																																											echo date("H:i");
																																											$str_prop = 'disabled';
																																										} else {
																																											echo $reg_fad1['pfd1_hr_ini_mov'];
																																											$str_prop = '';
																																										} ?>" <?php echo " " . $str_prop;
																																												echo " " . $strProp2;
																																												echo " " . $strProp3;
																																												echo " " . $strProp4; ?>></td>
								<td><input type="time" class="form-control" id="<?php echo "txtHraFinMov" . $i ?>" name="<?php echo "txtHraFinMov" . $i ?>" value="<?php if ($reg_fad1['pfd1_hr_fin_mov'] == '') {
																																										echo date("H:i");
																																										$str_prop = 'disabled';
																																									} else {
																																										echo $reg_fad1['pfd1_hr_fin_mov'];
																																										$str_prop = '';
																																									} ?>" <?php echo " " . $str_prop;
																																											echo " " . $strProp2;
																																											echo " " . $strProp3;
																																											echo " " . $strProp4; ?>></td>-->
								<td><input type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php echo "txtPh" . $i ?>" name="<?php echo "txtPh" . $i ?>" value="<?php if ($reg_fad1['pfd1_ph'] == '') {
																																																						echo "";
																																																						$str_prop = 'disabled';
																																																					} else {
																																																						echo $reg_fad1['pfd1_ph'];
																																																						$str_prop = '';
																																																					} ?>" <?php echo $str_prop;
																																																							echo " " . $strProp2;
																																																							echo " " . $strProp3;
																																																							echo " " . $strProp4; ?> size="5" placeholder="pH"></td>
								<td align="center"><input <?php if ($fltVal >= $reg_fad1['pfd1_ce'] and $reg_fad1['pfd1_ce'] != '') { ?>style="background-color:#66FF66;" <?php } ?> type="text" maxlength="6" onKeyPress="return isNumberKey(event, this);" class="form-control" id="<?php echo "txtCe" . $i ?>" name="<?php echo "txtCe" . $i ?>" value="<?php if ($reg_fad1['pfd1_ce'] == '') {
																																																																																								echo "";
																																																																																								$str_prop = 'disabled';
																																																																																							} else {
																																																																																								echo $reg_fad1['pfd1_ce'];
																																																																																								$str_prop = '';
																																																																																							} ?>" <?php echo $str_prop;
																																																																																									echo " " . $strProp2;
																																																																																									echo " " . $strProp3;
																																																																																									echo " " . $strProp4; ?> size="5" style="width:50px;" placeholder="Ce"></td>
								<!--agregado cc 11-08-2021-->
								<!--<td align="center">
									<select id="cbxAgua<?php echo $i  ?>" class="form-control" style="width: 150px" name="cbxAgua<?php echo $i  ?>" <?php if ($reg_fad1['taa_id'] == '') {
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
										?><option value="<?php echo $registros['taa_id'] ?>" <?php if ($registros['taa_id'] == $reg_fad1['taa_id']) { ?>selected="selected" <?php } ?>><?php echo $registros['taa_descripcion'] ?></option><?php
																																																										} while ($registros =  mysqli_fetch_array($cadena));

																																																										//mysqli_free_result($registros);

																																																											?>
									</select>
								</td>-->
								<td><input type="text" class="form-control" id="<?php echo "txtObs" . $i ?>" name="<?php echo "txtObs" . $i ?>" value="<?php if ($reg_fad1['pfd1_observaciones'] == '') {
																																							echo "";
																																							$str_prop = 'disabled';
																																						} else {
																																							echo $reg_fad1['pfd1_observaciones'];
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

				<!--</div>-->

				<!--estilo general de estapas-->
				<?php //if($_SESSION['privilegio'] == 4 ){
				?>
				<div class="row">
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
						<label for="inputPassword4">Redox</label>
						<input type="text" class="form-control" id="txt_redox" placeholder="" name="txt_redox" value="<?php if ($reg_fa1['pfg1_redox'] == '') {
																															echo "";
																															$str_prop = 'disabled';
																														} else {
																															echo $reg_fa1['pfg1_redox'];
																															$str_prop = '';
																														} ?>" <?php echo $str_prop;
																																echo " " . $strProp6;
																																echo " " . $strProp3 . " " . $strProp4; ?>>
					</div>

					<div class="form-group col-md-2">
						<label for="horas">(<?php echo fnc_hora_de(31) ?> a <?php echo fnc_hora_a(31) ?> Horas)</label>
					</div>
					<?php //}
					?>
					<?php //if($_SESSION['privilegio'] == 6 ){
					?>
					<div class="row">
						<div class="col-md-3 divEtapas">
							<div class="col-md-1 etapa">
								<p class="numEtapa">1c</p>
							</div>
							<div class="col-md-2 divEtapasInput">
								<label class="etiquetaEtapa">LIBERACION CE A <?php echo fnc_rango_a(31) ?> MAX</label>
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

						<!--textareaobservaciones-->
						<?php //if($_SESSION['privilegio'] == 4 ){
						?>
						<div class="row">
							<div class="col-md-8 col-sm-11">
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
						<?php //}
						?>
						<!---->
					</div>
				</div>
				<?php //}
				?>

				<div class="row" style="margin-bottom: 10px">
					<label style="font-weight:bold; margin-left:20px;">El agua de este proceso se manda a PILA 3 y 4 AGUA que no se recupera</label>
				</div>

				<!--barra botones-->
				<div class="row footerdivProcesos" style="margin-bottom: 10px">

					<div class="form-group col-md-6">
						<div class="alert alert-info hide" id="alerta-errorFase1cEOpe" style="height: 40px;width: 270px;text-align: left;z-index: 10;font-size: 10px;margin-bottom: -10px">
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

			<div class="modal" id="modalRenglon1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
			<?php //include('modal_materiales.php'); 
			?>
		</div>
	</div>
</div>