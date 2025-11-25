<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
$reg_pro['pro_id'] = $idx_pro;

$cad_pro_con = mysqli_query($cnx, "SELECT hrs_totales_calculadas,hrs_totales_capturadas FROM procesos WHERE pro_id = '$reg_pro[pro_id]'");
$reg_pro_con = mysqli_fetch_array($cad_pro_con);

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 26");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_8_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 26");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 26");
$reg_lib = mysqli_fetch_array($cad_lib);

if ($_SESSION['privilegio'] == 3) {
	$strProp1 = 'disabled';
	$strProp6 = '';
} else {
	$strProp1 = '';
} //Operador
if ($_SESSION['privilegio'] == 4) {
	$strProp2 = 'disabled';
} else {
	$strProp2 = '';
} //Supervidor
if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28 ) {
	$strProp3 = 'disabled';
} else {
	$strProp3 = '';
} //Laboratorio

//Para capturar primero los datos generales
if ($reg_aux['proa_id'] == '') {
	$strProp4 = 'disabled';
} else {
	$strProp4 = '';
}

//Para bloquear las observaciones si capturo los datos el supervisor.
if ($reg_aux['proa_fe_fin'] != '') {
	$strProp5 = 'disabled';
} else {
	$strProp5 = '';
}
if ($reg_aux['proa_fe_fin'] == '') {
	$requerido = '';
} else {
	$requerido = 'required';
}
?>
<script language="javascript">
	$(document).ready(function() {
		$("#formFase8c").submit(function() {
			for (var i = 1; i <= 5; i++) {
				cocido = $("#txtPhLibc" + i).val();
				ce = $("#txtCeLibc" + i).val();
				cuero = $("#txtCue_sobc" + i).val();
				ext = $("#txtpor_extc" + i).val();


				if (cocido != '' && (ce == '' || cuero == '' || ext == '')) {
					alert("Hay campo vacios en renglon " + i + " de cocidos");
					intban = 1;
					//break
					return false;
				} else {
					intban = 0;
				}
			}
			if (intban == 0) {
				var formData = $(this).serialize();
				$.ajax({
					url: "fases/fase_8c_insertar.php",
					type: 'POST',
					data: formData,
					success: function(result) {

						data = JSON.parse(result);
						alertas("#alerta-errorFase8cOpe", 'Listo!', data["mensaje"], 1, true, 5000);
						$('#formFase8c').each(function() {
							this.reset();
						});
						setTimeout("location.reload()", 2000);
					}
				});
				return false;
			}
		});

	});

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
				$("#modalRenglon8c").html(result);
				$('#modalRenglon8c').modal('show')
			}
		});
		return false;
	}

	function AbreModalE8c(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_8c.php',
			data: datos,
			success: function(result) {
				$("#modalEditar8c").html(result);
				$('#modalEditar8c').modal('show')
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

	function requerido_hrs_8c() {
		var fechaTerminacion = document.getElementById('txtFeTerm').value;
		// Verifica si la fecha está seleccionada
		if (fechaTerminacion.value !== '') {
			// Si la fecha está seleccionada, agrega el atributo "required" al campo de horas totales
			document.getElementById('txtHrTerm_8c').setAttribute('required', 'required');
			document.getElementById('txtHrasTotales').setAttribute('required', 'required');
		} else {
			// Si la fecha no está seleccionada, quita el atributo "required" del campo de horas totales
			document.getElementById('txtHrTerm_8c').removeAttribute('required');
			document.getElementById('txtHrasTotales').removeAttribute('required');
		}
	}
</script>
<div class="divProcesos">
	<form autocomplete="off" id="formFase8c" name="formFase8c">
		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="26" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg8_id']; ?>" />
		<input type="hidden" name="hdd_equipo" value="<?php echo $_GET['id_e'] ?>">

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
																												$str_prop = '';
																											} else {
																												echo $reg_aux['proa_fe_ini'];
																												$str_prop = 'disabled';
																											} ?>" <?php echo $str_prop;
																													echo " " . $strProp2;
																													echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-1" style="width: 110px">Hora inicio</label>
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
		</div>
		<!---->

		<table border="0" cellspacing="5" cellpadding="5">
			<tr class="etiqueta_tbl">
				<td width="15">&nbsp;</td>
				<td></td>
				<td><span style="color: red;font-weight: bold;"> * </span> Tipo agua</td>
				<td width="90"><span style="color: red;font-weight: bold;"> * </span> Movimiento</td>
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

				$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 26);

				if ($i <= 4 or $bolVal == 'Si') {
			?>
					<tr>
						<td>&nbsp;</td>
						<td><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>">
							<input type="hidden" class="form-control" id="<?php echo "hddRen" . $i ?>" name="<?php echo "hddRen" . $i ?>" value="<?php echo $reg_fad['pfd8_id']; ?>" />
						</td>
						<td><select id="cbxTipAg<?php echo $i  ?>" class="form-control" style="width: 140px" name="cbxTipAg<?php echo $i  ?>" <?php if ($reg_fad['tpa_id'] == '') {
																																					echo "";
																																					$str_prop = '';
																																				} else {
																																					$str_prop = 'disabled';
																																				}
																																				echo $str_prop;
																																				echo " " . $strProp2;
																																				echo " " . $strProp3;
																																				echo " " . $strProp4; ?>>
								<option value="">Seleccionar</option>
								<?php
								$cadena =  mysqli_query($cnx, "SELECT * from tipos_agua ORDER BY tpa_descripcion ");
								$registros =  mysqli_fetch_array($cadena);

								do {
								?><option value="<?php echo $registros['tpa_id'] ?>" <?php if ($registros['tpa_id'] == $reg_fad['tpa_id']) { ?>selected="selected" <?php } ?>><?php echo $registros['tpa_descripcion'] ?></option><?php
																																																								} while ($registros =  mysqli_fetch_array($cadena));


																																																									?>
							</select></td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtMov" . $i ?>" name="<?php echo "txtMov" . $i ?>" size="7" placeholder="Mov" value="<?php if ($reg_fad['pfd8_mov'] == '') {
																																																												echo "";
																																																												$str_prop = '';
																																																											} else {
																																																												echo $reg_fad['pfd8_mov'];
																																																												$str_prop = 'disabled';
																																																											} ?>" <?php echo $str_prop;
																																																													echo " " . $strProp2;
																																																													echo " " . $strProp3;
																																																													echo " " . $strProp4; ?>></td>
						<td>MIN</td>
						<td><input type="time" id="txtIniLlen<?php echo $i; ?>" name="txtIniLlen<?php echo $i; ?>" class="form-control" value="<?php if ($reg_fad['pfd8_ini_llenado'] == '') {
																																					echo "";
																																					$str_prop = '';
																																				} else {
																																					echo $reg_fad['pfd8_ini_llenado'];
																																					$str_prop = 'disabled';
																																				} ?>" <?php echo $str_prop;
																																						echo " " . $strProp2;
																																						echo " " . $strProp3;
																																						echo " " . $strProp4; ?> /> </td>

						<td><input type="time" id="txtFinLlen<?php echo $i; ?>" name="txtFinLlen<?php echo $i; ?>" class="form-control" value="<?php if ($reg_fad['pfd8_fin_llenado'] == '') {
																																					echo "";
																																					$str_prop = '';
																																				} else {
																																					echo $reg_fad['pfd8_fin_llenado'];
																																					$str_prop = 'disabled';
																																				} ?>" <?php echo $str_prop;
																																						echo " " . $strProp2;
																																						echo " " . $strProp3;
																																						echo " " . $strProp4; ?> /></td>
						<td><input type="time" id="txtIniDren<?php echo $i; ?>" name="txtIniDren<?php echo $i; ?>" class="form-control" value="<?php if ($reg_fad['pfd8_ini_dren'] == '') {
																																					echo "";
																																					$str_prop = '';
																																				} else {
																																					echo $reg_fad['pfd8_ini_dren'];
																																					$str_prop = 'disabled';
																																				} ?>" <?php echo $str_prop;
																																						echo " " . $strProp2;
																																						echo " " . $strProp3;
																																						echo " " . $strProp4; ?> /> </td>
						<td><input type="time" id="txtFinDren<?php echo $i; ?>" name="txtFinDren<?php echo $i; ?>" class="form-control" value="<?php if ($reg_fad['pfd8_fin_dren'] == '') {
																																					echo "";
																																					$str_prop = '';
																																				} else {
																																					echo $reg_fad['pfd8_fin_dren'];
																																					$str_prop = 'disabled';
																																				} ?>" <?php echo $str_prop;
																																						echo " " . $strProp2;
																																						echo " " . $strProp3;
																																						echo " " . $strProp4; ?> /></td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" name="txtPh<?php echo $i; ?>" id="txtPh<?php echo $i; ?>" class="form-control" placeholder="PH" value="<?php if ($reg_fad['pfd8_ph'] == '') {
																																																							echo "";
																																																							$str_prop = '';
																																																						} else {
																																																							echo $reg_fad['pfd8_ph'];
																																																							$str_prop = 'disabled';
																																																						} ?>" <?php echo $str_prop;
																																																								echo " " . $strProp2;
																																																								echo " " . $strProp3;
																																																								echo " " . $strProp4; ?> /> </td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" name="txtCe<?php echo $i; ?>" id="txtCe<?php echo $i; ?>" class="form-control" placeholder="CE" value="<?php if ($reg_fad['pfd8_ce'] == '') {
																																																							echo "";
																																																							$str_prop = '';
																																																						} else {
																																																							echo $reg_fad['pfd8_ce'];
																																																							$str_prop = 'disabled';
																																																						} ?>" <?php echo $str_prop;
																																																								echo " " . $strProp2;
																																																								echo " " . $strProp3;
																																																								echo " " . $strProp4; ?> /></td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" name="txtTemp<?php echo $i; ?>" id="txtTemp<?php echo $i; ?>" class="form-control" placeholder="TEMP" value="<?php if ($reg_fad['pfd8_temp'] == '') {
																																																									echo "";
																																																									$str_prop = '';
																																																								} else {
																																																									echo $reg_fad['pfd8_temp'];
																																																									$str_prop = 'disabled';
																																																								} ?>" <?php echo $str_prop;
																																																										echo " " . $strProp2;
																																																										echo " " . $strProp3;
																																																										echo " " . $strProp4; ?> /></td>
						<td align="center">
							<select id="cbxAgua<?php echo $i  ?>" class="form-control" style="width: 140px" name="cbxAgua<?php echo $i  ?>" <?php if ($reg_fad['taa_id'] == '') {
																																				echo "";
																																				$str_prop = '';
																																			} else {
																																				$str_prop = 'disabled';
																																			}
																																			echo $str_prop;
																																			echo " " . $strProp2;
																																			echo " " . $strProp3 . " " . $strProp4; ?>>
								<option value="">Seleccionar</option>
								<?php
								$cadena =  mysqli_query($cnx, "SELECT * from tipos_agua_a ORDER BY taa_descripcion");
								$registros =  mysqli_fetch_array($cadena);

								do {
								?><option value="<?php echo $registros['taa_id'] ?>" <?php if ($registros['taa_id'] == $reg_fad['taa_id']) { ?>selected="selected" <?php } ?>><?php echo $registros['taa_descripcion'] ?></option><?php
																																																								} while ($registros =  mysqli_fetch_array($cadena));
																																																									?>
							</select>
						</td>
						<td><input type="text" class="form-control" id="<?php echo "txtObs" . $i ?>" name="<?php echo "txtObs" . $i ?>" value="<?php if ($reg_fad['pfd8_observaciones'] == '') {
																																					echo "";
																																					$str_prop = '';
																																				} else {
																																					echo $reg_fad['pfd8_observaciones'];
																																					$str_prop = 'disabled';
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
		<div id="campos7">.</div>
		<!--tiempos-->
		<div class="col-md-8">
			<div class="col-md-3">
				<label>F. term lavados finales</label>
				<input onchange="requerido_hrs_8c()" type="date" class="form-control" id="txtFeTerm" placeholder="" name="txtFeTerm" value="<?php if ($reg_aux['proa_fe_fin'] == '') {
																																				echo "";
																																				$str_prop = '';
																																			} else {
																																				echo $reg_aux['proa_fe_fin'];
																																				$str_prop = 'disabled';
																																			} ?>" <?php echo $str_prop;
																																						echo " " . $strProp6;
																																						echo " " . $strProp3 . " " . $strProp4; ?>>
			</div>
			<div class="col-md-2">
				<label>Hora termina</label>
				<input type="time" class="form-control" id="txtHrTerm_8c" placeholder="" name="txtHrTerm" value="<?php if ($reg_aux['proa_hr_fin'] == '') {
																														echo "";
																														$str_prop = '';
																													} else {
																														echo $reg_aux['proa_hr_fin'];
																														$str_prop = 'disabled';
																													} ?>" <?php echo $str_prop;
																														echo " " . $strProp6;
																														echo " " . $strProp3 . " " . $strProp4; ?>>
			</div>
			<div class="col-md-2">
				<label>Hora totales</label>
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" id="txtHrTotales1" class="form-control" placeholder="Horas totales" name="txtHrTotales1" value="<?php if ($reg_fa['pfg8_hr_totales'] == '') {
																																																		echo "";
																																																		$str_prop = '';
																																																		$str_estilo = "style='background-color:#FFFF99;'";
																																																	} else {
																																																		echo $reg_fa['pfg8_hr_totales'];
																																																		$str_prop = 'disabled';
																																																	} ?>" <?php echo $str_prop;
																																																			echo " " . $strProp1;
																																																			echo " " . $strProp3;
																																																			echo " " . $str_estilo; ?>>
			</div>
			<div class="col-md-2">
				<label>Realizó</label>
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" id="txtRealizo" class="form-control" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" disabled="">
			</div>
			<label for="inputPassword4">(<?php echo fnc_hora_de(21) ?> a <?php echo fnc_hora_a(21) ?> horas)</label>
			<!---->

			<div class="col-md-12">
				<label style="font-weight:bold; margin-left:20px;margin-top: 20px">El agua de este proceso se manda a (Pila 1) agua recuperada limpia</label>
			</div>

			<!--tiempos-->
			<div class="col-md-11" style="border: 1px solid#e6e6e6;border-radius: 5px;background: #e6e6e6;margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
				<div class="col-md-3 tiempos" style="margin-left: -25px;">
					<label>Hrs totales proceso</label>
					<input <?php echo $requerido ?> onKeyPress="return isNumberKeyFloat(event, this); type=" text" maxlength="6" " id=" txtHrasTotales" class="form-control" placeholder="" name="txtHrasTotales" value="<?php if ($reg_pro_con['hrs_totales_capturadas'] == '') {
																																																								$str_prop = '';
																																																								$str_estilo = "style='background-color:;'";
																																																							} else {
																																																								echo $reg_pro_con['hrs_totales_capturadas'];
																																																								$str_prop = 'disabled';
																																																							} ?>" <?php echo $str_prop;
																																																									echo " " . $strProp6;
																																																									echo " " . $strProp3 . " " . $strProp4;
																																																									?>>
				</div>
				<!-- <div class="col-md-3">
					<label>Hrs totales(sis)</label>
					<input style="background-color: #FFFF99;" type="text" id="txt_horas_tot_calculadas" class="form-control" placeholder="Horas totales" name="txt_horas_tot_calculadas" value="<?php if ($reg_pro_con['hrs_totales_calculadas'] == '') {
																																																	echo fnc_horas($strFech, date("Y-m-d"), $strHr, date("H:i"));
																																																} else {
																																																	echo $reg_pro_con['hrs_totales_calculadas'];
																																																} ?>" readonly>
				</div> -->
				<div class="col-md-2 tiempos" style="margin-left: 20px">
					<label>Revisó</label>
					<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" id="txtRealizo2" class="form-control" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" disabled="">
				</div>
				<label for="inputPassword4">(67 a 94 horas)</label>
			</div>


			<!--tiempos-->
			<div class="col-md-12">
				<div class="col-md-2 tiempos" style="margin-left: -10px;width: 220px">
					<label>Fecha sale a producción</label>
					<input type="date" class="form-control" id="txtFeLibProd" name="txtFeLibProd" placeholder="" value="<?php if ($reg_fa['pfg8_fe_lib_prod'] == '') {
																															echo date("Y-m-d");
																															$str_prop = '';
																															$str_estilo = "style='background-color:#FFFF99;'";
																														} else {
																															echo $reg_fa['pfg8_fe_lib_prod'];
																															$str_prop = 'disabled';
																														} ?>" <?php echo $str_prop;
																																echo " " . $strProp1;
																																echo " " . $strProp3;
																																echo " " . $str_estilo; ?>><?php //echo "x".$reg_fa['pfg8_fe_lib_prod']; 
																																							?>
				</div>
				<div class="col-md-2 tiempos" style="margin-left: 20px;">
					<label>Hora</label>
					<input type="time" class="form-control" id="txtHrLibProd" name="txtHrLibProd" placeholder="" value="<?php if ($reg_fa['pfg8_hr_lib_prod'] == '') {
																															echo date("H:i");
																															$str_prop = '';
																															$str_estilo = "style='background-color:#FFFF99;'";
																														} else {
																															echo $reg_fa['pfg8_hr_lib_prod'];
																															$str_prop = 'disabled';
																														} ?>" <?php echo $str_prop;
																																echo " " . $strProp1;
																																echo " " . $strProp3;
																																echo " " . $str_estilo; ?> />
				</div>
			</div>
			<!---->

			<!--textareaobservaciones-->
			<div class="col-md-7 textareaObservaciones" style="margin-top: 20px">
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
			<div class="row" style="margin-bottom: 10px">
				<i><label style="margin-left:20px;color: red"> * Los campos "Tipo Agua" y "Movimiento" deben ser capturados juntos</label></i>
			</div>
		</div>

		<div class="col-md-4 divEtapas7_mod">
			<div class="col-md-1 etapa" style="height: 362px">
				<p class="numEtapa_mod">8c</p>
			</div>
			<div class="col-md-12 divEtapasInput7_mod">
				<label class="etiquetaEtapa_mod">COCIDO LIBERACIÓN ( PH <?php echo fnc_rango_de(21) ?> - <?php echo fnc_rango_a(21) ?>)</label>
				<input type="date" class="form-control col-md-1" style="width: 140px" id="txtFeLib" name="txtFeLib" placeholder="" value="<?php if ($reg_lib['prol_fecha'] == '') {
																																				echo "";
																																				$str_prop = '';
																																			} else {
																																				echo $reg_lib['prol_fecha'];
																																				$str_prop = 'disabled';
																																			} ?>" <?php echo $str_prop;
																																					echo " " . $strProp1;
																																					echo " " . $strProp2; ?>>
				<input type="time" class="form-control col-md-1" style="width: 110px" id="txtHrLib" name="txtHrLib" placeholder="" value="<?php if ($reg_lib['prol_hora'] == '') {
																																				echo "";
																																				$str_prop = '';
																																			} else {
																																				echo $reg_lib['prol_hora'];
																																				$str_prop = 'disabled';
																																			} ?>" <?php echo $str_prop;
																																					echo " " . $strProp1;
																																					echo " " . $strProp2; ?>>

				<?php

				for ($i = 1; $i <= 5; $i++) { ?>
					<input type="hidden" name="<?php echo "R" . $i ?>" value="<?php echo $i ?>">
					<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="tex" class="form-control col-md-1" style="width: 100px" id="<?php echo "txtPhLibc" . $i ?>" name="<?php echo "txtPhLibc" . $i ?>" placeholder="(<?php echo "L" . $i ?>) Cocido ph" value="<?php if ($reg_lib['prol_cocido_ph1'] == '') {
																																																																							echo "";
																																																																							$str_prop = '';
																																																																						} else {
																																																																							echo $reg_lib['prol_cocido_ph1'];
																																																																							$str_prop = 'disabled';
																																																																						} ?>" <?php echo $str_prop;
																																																																								echo " " . $strProp1;
																																																																								echo " " . $strProp2; ?> />
					<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" class="form-control col-md-1" style="width: 55px" id="<?php echo "txtCeLibc" . $i ?>" name="<?php echo "txtCeLibc" . $i ?>" placeholder="Ce" value="<?php if ($reg_lib['prol_ce1'] == '') {
																																																															echo "";
																																																															$str_prop = '';
																																																														} else {
																																																															echo $reg_lib['prol_ce1'];
																																																															$str_prop = 'disabled';
																																																														} ?>" <?php echo $str_prop;
																																																																echo " " . $strProp1;
																																																																echo " " . $strProp2; ?>>
					<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" class="form-control col-md-1" style="width: 75px" id="<?php echo "txtCue_sobc" . $i ?>" name="<?php echo "txtCue_sobc" . $i ?>" placeholder="Cuero sob" value="<?php if ($reg_lib['prol_cu1'] == '') {
																																																																		echo "";
																																																																		$str_prop = '';
																																																																	} else {
																																																																		echo $reg_lib['prol_cu1'];
																																																																		$str_prop = 'disabled';
																																																																	} ?>" <?php echo $str_prop;
																																																																			echo " " . $strProp1;
																																																																			echo " " . $strProp2; ?>>
					<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" class="form-control col-md-1" style="width: 50px" id="<?php echo "txtpor_extc" . $i ?>" name="<?php echo "txtpor_extc" . $i ?>" placeholder="% ext" value="<?php if ($reg_lib['prol_ext1'] == '') {
																																																																	echo "";
																																																																	$str_prop = '';
																																																																} else {
																																																																	echo $reg_lib['prol_ext1'];
																																																																	$str_prop = 'disabled';
																																																																} ?>" <?php echo $str_prop;
																																																																		echo " " . $strProp1;
																																																																		echo " " . $strProp2; ?>>

				<?php } ?>


				<!--<input type="text"  maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" id="txtCocidoLib" name="txtCocidoLib" class="form-control" placeholder="Cocido liberación % ext" value="<?php if ($reg_lib['prol_cocido_lib'] == '') {
																																																						echo "";
																																																						$str_prop = '';
																																																					} else {
																																																						echo $reg_lib['prol_cocido_lib'];
																																																						$str_prop = 'disabled';
																																																					} ?>" <?php echo $str_prop;
																																																							echo " " . $strProp1;
																																																							echo " " . $strProp2; ?> />-->

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

				<!--Color de caldo agregar CC-->
				<select type="text" id="cbxColor_caldo" class="form-control" placeholder="Colores" name="cbxColor_caldo" <?php if ($reg_lib['prol_color_caldo'] == '') {
																																echo "";
																																$str_prop = '';
																															} else {
																																echo "<option value='$reg_lib[prol_color_caldo]'>$reg_lib[prol_color_caldo]</option>";
																																$str_prop = 'disabled';
																															} ?> <?php echo $str_prop;
																																	echo " " . $strProp1;
																																	echo " " . $strProp2; ?>>
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
				<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" id="txtSolides" name="txtSolides" class="form-control" placeholder="% de solidos" value="<?php if ($reg_lib['prol_solides'] == '') {
																																															echo "";
																																															$str_prop = '';
																																														} else {
																																															echo $reg_lib['prol_solides'];
																																															$str_prop = 'disabled';
																																														} ?>" <?php echo $str_prop;
																																																echo " " . $strProp1;
																																																echo " " . $strProp2; ?> />
				<textarea type="text" id="txta_obs" name="txta_obs" class="form-control" placeholder="Observaciones" value="<?php if ($reg_lib['prol_observaciones'] == '') {
																																echo "";
																																$str_prop = '';
																															} else {
																																echo $reg_lib['prol_observaciones'];
																																$str_prop = 'disabled';
																															} ?>" <?php echo $str_prop;
																																	echo " " . $strProp1;
																																	echo " " . $strProp2; ?> /></textarea>
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" id="txtUsuario" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" disabled="">
			</div>
		</div>

		<!--barra botones-->
		<div class="row footerdivProcesos" style="margin-bottom: 10px;">
			<div class="form-group col-md-4">
				<div class="alert alert-info hide" id="alerta-errorFase8cOpe" style="height: 40px;text-align: left;z-index: 10;font-size: 10px; margin-bottom: -10px">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>

			<?php if ($_SESSION['privilegio'] == 4) {	?>

				<div class="col-md-1">
					<!--Nota: si no ocupa es-->
					<button type="button" class="btn btn-success" id="paleto" onClick="javascript:abre_modal_equipos(<?php echo $reg_pro['pro_id'] ?>,<?php echo $id_e ?>);"> <img src="../iconos/procesos2.png" alt="">Equipo
					</button>
				</div>
				<div class="col-md-1">
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE8c(<?php echo $reg_pro['pro_id'] ?>, 26);">
						<img src="../iconos/edit.png" alt="">Editar
					</button>
				</div>
				<div class="col-md-2" style="width: 110px">
					<button type="button" class="btn btn-success" id="permitir" onClick="javascript:AbreModalAgregarR(<?php echo $reg_pro['pro_id'] ?>, 26, <?php echo $valUltRen ?>);"> <img src="../iconos/add.png" alt="">Renglon
					</button>
				</div>
			<?php } ?>
			<div class="col-md-4" style="float: right;margin-right: 80px">
				<?php
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28 ) {
					if ($reg_fa['pfg8_id'] != '' && $reg_aux['proa_fe_fin'] != '') {
				?>
						<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
					<?php
					} else if ($reg_aux['proa_fe_fin'] == '') {
						echo "<span style='font-weight:bold'>N/A Guardar. El operador y/o supervisor no ha capturado fecha de termino de lavados finales</span>";
					} else {
						echo "<span style='font-weight:bold'>N/A Guardar</span>";
					}
				} else { ?>
					<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
				<?php } ?>
			</div>
		</div>

	</form>
	<div class="modal" id="modalRenglon8c" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	<div class="modal" id="modalPaleto8c" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	<div class="modal" id="modalEditar8c" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

</div>