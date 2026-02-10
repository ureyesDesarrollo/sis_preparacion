<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
$reg_pro['pro_id'] = $idx_pro;
date_default_timezone_set('America/Mazatlan');

$cad_pro_con = mysqli_query($cnx, "SELECT hrs_totales_calculadas, hrs_totales_capturadas, pro_hrs_tot_muerto  FROM procesos WHERE pro_id = '$reg_pro[pro_id]'");
$reg_pro_con = mysqli_fetch_array($cad_pro_con);

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 20");
$reg_aux = mysqli_fetch_array($cad_aux);
$tot_aux = mysqli_num_rows($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_8_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 20");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion_b WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 20");
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

//Para bloquear laboratorio si no hay fecha de termino capturada por operador
if ($reg_aux['proa_fe_fin'] == '') {
	$requerido = '';
} else {
	$requerido = 'required';
}
?>
<script language="javascript">
	$(document).ready(function() {
		$("#formFase8").submit(function() {
			for (var i = 1; i <= 5; i++) {
				cocido = $("#txtPhLib" + i).val();
				ce = $("#txtCeLib" + i).val();
				cuero = $("#txtCue_sob" + i).val();
				ext = $("#txtpor_ext" + i).val();

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
					url: "fases/fase_8_insertar.php",
					type: 'POST',
					data: formData,
					success: function(result) {

						data = JSON.parse(result);
						alertas("#alerta-errorFase8Ope", 'Listo!', data["mensaje"], 1, true, 5000);
						$('#formFase8').each(function() {
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
				$("#modalRenglon8").html(result);
				$('#modalRenglon8').modal('show')
			}
		});
		return false;
	}

	function AbreModalE8(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_8.php',
			data: datos,
			success: function(result) {
				$("#modalEditar8").html(result);
				$('#modalEditar8').modal('show')
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

	function requerido_hrs_8() {
		var fechaTerminacion = document.getElementById('txtFeTerm').value;
		// Verifica si la fecha está seleccionada
		if (fechaTerminacion.value !== '') {
			// Si la fecha está seleccionada, agrega el atributo "required" al campo de horas totales
			document.getElementById('txtHrTerm_8').setAttribute('required', 'required');
			document.getElementById('txtHrasTotales').setAttribute('required', 'required');
			document.getElementById('txtHrsMuerto').setAttribute('required', 'required');
			document.getElementById('txtHrTotales1').setAttribute('required', 'required');
		} else {
			// Si la fecha no está seleccionada, quita el atributo "required" del campo de horas totales
			document.getElementById('txtHrTerm_8').removeAttribute('required');
			document.getElementById('txtHrasTotales').removeAttribute('required');
			document.getElementById('txtHrsMuerto').removeAttribute('required');
			document.getElementById('txtHrTotales1').removeAttribute('required');
		}
	}

	function quimicos_8(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'quimicos/modal_quimicos.php',
			data: datos,
			success: function(result) {
				$("#modal_quimicos8").html(result);
				$('#modal_quimicos8').modal('show')
			}
		});
		return false;
	}
</script>
<div class="divProcesos">
	<form autocomplete="off" id="formFase8" name="formFase8">
		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="20" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg8_id']; ?>" />
		<!--<input name="hdd_lav" type="hidden" id="hdd_lav" value="<?php echo $id_l; ?>"/>-->
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
		<div class="row">
			<div class="col-md-7">

				<table border="0" cellspacing="5" cellpadding="5">
					<tr class="etiqueta_tbl">
						<td width="15">&nbsp;</td>
						<td></td>
						<td></td>
						<!-- <td width="90"><span style="color: red;font-weight: bold;"> * </span> Movimiento</td> -->

						<td>Hr Ini Llenado</td>
						<td>Hr Fin Llenado</td>
						<td>PH</td>
						<td>CE</td>
						<td>Observaciones</td>
					</tr>
					<?php
					for ($i = 1; $i <= 10; $i++) {
						$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_8_d WHERE pfg8_id = '$reg_fa[pfg8_id]' and pfd8_ren = '$i' ");
						$reg_fad = mysqli_fetch_array($cad_fad);

						$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 20);

						if ($i == 1 || $i == 3) {
							$nivel = "er";
						}
						if ($i == 2) {
							$nivel = "do";
						}
						if ($i == 4 || $i == 5) {
							$nivel = "to";
						}
						if ($i <= 5 or $bolVal == 'Si') {
					?>
							<tr>
								<td>&nbsp;</td>
								<td><?php echo $i . $nivel; ?>
									<input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>">
									<input type="hidden" class="form-control" id="<?php echo "hddRen" . $i ?>" name="<?php echo "hddRen" . $i ?>" value="<?php echo $reg_fad['pfd8_id']; ?>" />
								</td>


								<td style="padding-left: 5px;">
									Lav
									<input type="hidden" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtMov" . $i ?>" name="<?php echo "txtMov" . $i ?>" size="7" placeholder="Mov" value="<?php if ($reg_fad['pfd8_mov'] == '') {
																																																															echo "";
																																																															$str_prop = '';
																																																														} else {
																																																															echo $reg_fad['pfd8_mov'];
																																																															$str_prop = 'disabled';
																																																														} ?>" <?php echo $str_prop;
																																																																echo " " . $strProp2;
																																																																echo " " . $strProp3;
																																																																echo " " . $strProp4; ?>>
								</td>
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

								<td><input style="width: 100px;" type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" name="txtPh<?php echo $i; ?>" id="txtPh<?php echo $i; ?>" class="form-control" placeholder="PH" value="<?php if ($reg_fad['pfd8_ph'] == '') {
																																																															echo "";
																																																															$str_prop = '';
																																																														} else {
																																																															echo $reg_fad['pfd8_ph'];
																																																															$str_prop = 'disabled';
																																																														} ?>" <?php echo $str_prop;
																																																																echo " " . $strProp2;
																																																																echo " " . $strProp3;
																																																																echo " " . $strProp4; ?> /> </td>
								<td><input style="width: 100px;" type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" name="txtCe<?php echo $i; ?>" id="txtCe<?php echo $i; ?>" class="form-control" placeholder="CE" value="<?php if ($reg_fad['pfd8_ce'] == '') {
																																																															echo "";
																																																															$str_prop = '';
																																																														} else {
																																																															echo $reg_fad['pfd8_ce'];
																																																															$str_prop = 'disabled';
																																																														} ?>" <?php echo $str_prop;
																																																																echo " " . $strProp2;
																																																																echo " " . $strProp3;
																																																																echo " " . $strProp4; ?> /></td>


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

				<!--tiempos-->
				<div class="col-md-3">
					<label>F. term lavados finales</label>
					<input onchange="requerido_hrs_8()" type="date" class="form-control" id="txtFeTerm" placeholder="" name="txtFeTerm" value="<?php if ($reg_aux['proa_fe_fin'] == '') {
																																					echo "";
																																					$str_prop = '';
																																				} else {
																																					echo $reg_aux['proa_fe_fin'];
																																					$str_prop = 'disabled';
																																				} ?>" <?php echo $str_prop;
																																						echo " " . $strProp6;
																																						echo " " . $strProp3 . " " . $strProp4; ?>>
				</div>
				<div class="col-md-3">
					<label>Hora termina</label>
					<input type="time" class="form-control" id="txtHrTerm_8" placeholder="" name="txtHrTerm" value="<?php if ($reg_aux['proa_hr_fin'] == '') {
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
					<input onKeyPress="return isNumberKeyFloat(event, this);" type="text" id="txtHrTotales1" class="form-control" placeholder="Horas totales" name="txtHrTotales1" value="<?php if ($reg_fa['pfg8_hr_totales'] == '') {
																																																echo "";
																																																$str_prop = '';
																																															} else {
																																																echo $reg_fa['pfg8_hr_totales'];
																																																$str_prop = 'disabled';
																																															} ?>" <?php echo $str_prop;
																																																	echo " " . $strProp6;
																																																	echo " " . $strProp3;
																																																	echo " " . $strProp4;
																																																	/* echo " " . $str_estilo; */ ?>>
				</div>
				<div class="col-md-2">
					<label>Realizó</label>
					<input type="text" id="txtRealizo" class="form-control" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" disabled="">
				</div>
				<label for="inputPassword4">(<?php echo fnc_hora_de(20) ?> a <?php echo fnc_hora_a(20) ?> horas)</label>

				<!--tiempos-->
				<div class="col-md-11" style="border: 1px solid#e6e6e6;border-radius: 5px;background: #e6e6e6;margin-left: 20px;margin-top: 10px;margin-bottom: 10px">
					<div class="col-md-4 tiempos">
						<label>Hrs totales proceso</label>
						<input <?php echo $requerido ?> onKeyPress="return isNumberKey(event, this);" type="text" id="txtHrasTotales" class="form-control" placeholder="" name="txtHrasTotales" value="<?php if ($reg_pro_con['hrs_totales_capturadas'] == '') {
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
					<div class="col-md-4">
						<label>Horas tiempo muerto</label>
						<input <?php echo $requerido ?> type="text" id="txtHrsMuerto" class="form-control" onKeyPress="return isNumberKeyFloat(event, this);" placeholder="Horas tiempo muerto" name="txtHrsMuerto" value="<?php
																																																							$str_estilo = '';
																																																							if ($reg_pro_con['pro_hrs_tot_muerto'] == '') {
																																																								echo "";
																																																								$str_prop = '';
																																																							} else {
																																																								echo $reg_pro_con['pro_hrs_tot_muerto'];
																																																							} ?>" <?php echo $str_prop;
																																																									echo " " . $strProp6;
																																																									echo " " . $strProp3;
																																																									echo " " . $strProp4;
																																																									?>>
					</div>
					<div class="col-md-2">
						<label>Revisó</label>
						<input type="text" id="txtRealizo2" class="form-control" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" disabled="">
					</div>
					<label for="inputPassword4">(67 A 92 HORAS)</label>
				</div>


				<!--tiempos-->
				<div class="col-md-12">
					<div class="col-md-2" style="margin-left: -5px;width: 210px">
						<label>Fecha sale a producción</label>
						<input type="date" class="form-control" id="txtFeLibProd" name="txtFeLibProd" placeholder="" value="<?php if ($reg_fa['pfg8_fe_lib_prod'] == '') {
																																echo date("Y-m-d");
																																$str_prop = 'readonly';
																																$str_estilo = "style='background-color:#FFFF99;'";
																															} else {
																																echo $reg_fa['pfg8_fe_lib_prod'];
																																$str_prop = 'readonly';
																															} ?>" <?php echo $str_prop;
																																	echo " " . $strProp6;
																																	echo " " . $strProp3 . " " . $strProp4;
																																	echo " " . $str_estilo; ?>>
					</div>
					<div class="col-md-4">
						<label>Hora que sale a producción</label>
						<input type="time" class="form-control" id="txtHrLibProd" name="txtHrLibProd" placeholder="" value="<?php if ($reg_fa['pfg8_hr_lib_prod'] == '') {
																																echo date("H:i");
																																$str_prop = 'readonly';
																																$str_estilo = "style='background-color:#FFFF99;'";
																															} else {
																																echo $reg_fa['pfg8_hr_lib_prod'];
																																$str_prop = 'readonly';
																															} ?>" <?php echo $str_prop;
																																	echo " " . $strProp6;
																																	echo " " . $strProp3 . " " . $strProp4;
																																	echo " " . $str_estilo; ?>>
					</div>
				</div>
				<!---->
			</div>
			<div class="col-md-5 divEtapas7_mod" style="padding: 0px;margin-left:-3rem">
				<div class="col-md-1" style="height: 270px;border-radius: 5px;border: 1px solid #e6e6e6;width: 70px;margin-left: 0px;">
					<p class="numEtapa_mod">8</p>
				</div>
				<div class="col-md-10" style="height: 25px;padding: 0px;">
					<label class="etiquetaEtapa_mod">COCIDO LIBERACIÓN ( PH <?php echo fnc_rango_de(20) ?> - <?php echo fnc_rango_a(20) ?>) (CE 5.0 MAX) </label>
					<?php

					for ($i = 1; $i <= 2; $i++) { ?>
						<input type="hidden" name="<?php echo "R" . $i ?>" value="<?php echo $i ?>">
						<input type="date" class="form-control col-md-1" style="width: 105px" id="txtFeLib" name="<?php echo "txtFeLib" . $i ?>" placeholder="" value="<?php if ($reg_lib['prol_fecha'] == '') {
																																											echo "";
																																											$str_prop = '';
																																										} else {
																																											echo $reg_lib['prol_fecha'];
																																											$str_prop = 'disabled';
																																										} ?>" <?php echo $str_prop;
																																												echo " " . $strProp1;
																																												echo " " . $strProp2; ?>>
						<input type="time" class="form-control col-md-1" style="width: 100px" id="txtHrLib" name="<?php echo "txtHrLib" . $i ?>" placeholder="" value="<?php if ($reg_lib['prol_hora'] == '') {
																																											echo "";
																																											$str_prop = '';
																																										} else {
																																											echo $reg_lib['prol_hora'];
																																											$str_prop = 'disabled';
																																										} ?>" <?php echo $str_prop;
																																												echo " " . $strProp1;
																																												echo " " . $strProp2; ?>>
						<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="tex" class="form-control col-md-1" style="width: 80px" id="<?php echo "txtPhLib" . $i ?>" name="<?php echo "txtPhLib" . $i ?>" placeholder="<?php echo  $i ?> Cocido ph" value="<?php if ($reg_lib['prol_cocido_ph1'] == '') {
																																																																						echo "";
																																																																						$str_prop = '';
																																																																					} else {
																																																																						echo $reg_lib['prol_cocido_ph1'];
																																																																						$str_prop = 'disabled';
																																																																					} ?>" <?php echo $str_prop;
																																																																							echo " " . $strProp1;
																																																																							echo " " . $strProp2; ?> />
						<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" class="form-control col-md-1" style="width: 55px" id="<?php echo "txtCeLib" . $i ?>" name="<?php echo "txtCeLib" . $i ?>" placeholder="Ce" value="<?php if ($reg_lib['prol_ce1'] == '') {
																																																																echo "";
																																																																$str_prop = '';
																																																															} else {
																																																																echo $reg_lib['prol_ce1'];
																																																																$str_prop = 'disabled';
																																																															} ?>" <?php echo $str_prop;
																																																																	echo " " . $strProp1;
																																																																	echo " " . $strProp2; ?>>
						<!-- 	<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" class="form-control col-md-1" style="width: 75px" id="<?php echo "txtCue_sob" . $i ?>" name="<?php echo "txtCue_sob" . $i ?>" placeholder="Cuero sob" value="<?php if ($reg_lib['prol_cu1'] == '') {
																																																																					echo "";
																																																																					$str_prop = '';
																																																																				} else {
																																																																					echo $reg_lib['prol_cu1'];
																																																																					$str_prop = 'disabled';
																																																																				} ?>" <?php echo $str_prop;
																																																																						echo " " . $strProp1;
																																																																						echo " " . $strProp2; ?>> -->
						<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" class="form-control col-md-1" style="width: 50px" id="<?php echo "txtpor_ext" . $i ?>" name="<?php echo "txtpor_ext" . $i ?>" placeholder="% ext" value="<?php if ($reg_lib['prol_ext1'] == '') {
																																																																	echo "";
																																																																	$str_prop = '';
																																																																} else {
																																																																	echo $reg_lib['prol_ext1'];
																																																																	$str_prop = 'disabled';
																																																																} ?>" <?php echo $str_prop;
																																																																		echo " " . $strProp1;
																																																																		echo " " . $strProp2; ?>>

					<?php } ?>
					<!--quitar de la consulta
			 <?php echo $str_prop;
				echo " " . $strProp1;
				echo " " . $strProp2; ?> />-->
					<select type="text" id="cbxColor" class="form-control " placeholder="Colores" name="cbxColor" <?php if ($reg_lib['prol_color'] == '') {
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

					<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" id="txtHrTotales" class="form-control" placeholder="Horas totales" name="txtHrTotales" value="<?php if ($reg_lib['prol_hr_totales'] == '') {
																																																		echo "";
																																																		$str_prop = '';
																																																	} else {
																																																		echo $reg_lib['prol_hr_totales'];
																																																		$str_prop = 'disabled';
																																																	} ?>" <?php echo $str_prop;
																																																			echo " " . $strProp1;
																																																			echo " " . $strProp2; ?>>
					<input type="text" id="txtUsuario" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" disabled="">

				</div>
			</div>

			<!--textareaobservaciones-->
			<div class="col-md-5" style="margin-left:-5rem">
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
		<!--barra botones-->
		<div class="row" <?php echo $oculta_opciones ?> style="margin-right: 0px">
			<div class="col-md-6">
				<div class="alert alert-info hide" id="alerta-errorFase8Ope" style="height: 40px;text-align: left;z-index: 10;font-size: 10px;margin-bottom: -10px">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>
			<div class="col-md-6" style="margin-top:1rem;margin-bottom:1rem;text-align:right">
				<?php if ($_SESSION['privilegio'] == 4) {	?>


					<!--Nota: si no ocupa es-->
					<button type="button" class="btn btn-success" id="paleto" onClick="javascript:abre_modal_equipos(<?php echo $reg_pro['pro_id'] ?>,<?php echo $id_e ?>);"> <img src="../iconos/procesos2.png" alt="">Equipo
					</button>

					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE8(<?php echo $reg_pro['pro_id'] ?>, 20);">
						<img src="../iconos/edit.png" alt="">Editar
					</button>

					<button type="button" class="btn btn-success" id="permitir" onClick="javascript:AbreModalAgregarR(<?php echo $reg_pro['pro_id'] ?>, 20, <?php echo $valUltRen ?>);"> <img src="../iconos/add.png" alt="">Renglon
					</button>


				<?php } ?>
				<?php
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if ($reg_fa['pfg8_id'] != '' && $_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28  or $_SESSION['privilegio'] == 4) {
					if ($reg_aux['proa_fe_fin'] != '' && $_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28 ) {
				?>
						<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
					<?php
					} else { //if ($reg_aux['proa_fe_fin'] == '') {
						if ($_SESSION['privilegio'] == 4) {

							echo '<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>';
						} else {

							echo "<span style='font-weight:bold'>N/A Guardar. El operador y/o supervisor no ha capturado fecha de termino de lavados finales</span>";
						}
					} /*else {
						echo "<span style='font-weight:bold'>N/A Guardar</span>";
					}*/
				} else if ($_SESSION['privilegio'] == 3) { //si es operador
					?>
					<button type="button" class="btn btn-info" onClick="javascript:quimicos_8(<?php echo $reg_pro['pro_id'] ?>, <?php echo $reg_et['pe_id'] ?> );"><img src="../iconos/matraz.png" alt="">Químicos</button>
					<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
				<?php } else {
					echo "N/A Guardar";
				} ?>
			</div>
		</div>
	</form>
	<div class="modal" id="modalRenglon8" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	<div class="modal" id="modalPaleto8" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	<div class="modal" id="modalEditar8" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
	<div class="modal" id="modal_quimicos8" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

</div>