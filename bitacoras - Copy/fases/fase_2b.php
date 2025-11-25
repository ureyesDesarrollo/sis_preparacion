<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
$reg_pro['pro_id'] = $idx_pro;

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 3");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 3");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 3");
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
	$(document).ready(function() {
		$("#formFase2b").submit(function() {
			var formData = $(this).serialize();
			$.ajax({
				url: "fases/fase_2b_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase2bOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase2b').each(function() {
						this.reset();
					});
					setTimeout("location.reload()", 2500);
				}
			});
			return confirmEnviarFase2b();
			return false;
		});
	});

	//Bloquear boton al agregar material
	function confirmEnviarFase2b() {

		formFase2b.btn.disabled = true;
		formFase2b.btn.value = "Enviando...";

		setTimeout(function() {
			formFase2b.btn.disabled = true;
			formFase2b.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}

	function AbreModalE2b(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_2b.php',
			data: datos,
			success: function(result) {
				$("#modalEditar2b").html(result);
				$('#modalEditar2b').modal('show')
			}
		});
		return false;
	}
	//agregado 16-10-21
	function quimicos_2b(proceso, etapa) {
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
	<form autocomplete="off" id="formFase2b" name="formFase2b">
		<input type="hidden" value="<?php //echo $_GET['id_e'] 
									?>" name="txt_equipo">

		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="3" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg2_id']; ?>" />

		<div class="headerdivProcesos">
			<div class="col-md-2">ENZIMA</div>
			<div class="col-md-8">Las primeras 6 horas en movimiento continuo y según como se vea el material se le dan reposos</div>
		</div>

		<!--tiempos-->
		<div class="row" style="margin-bottom: 20px">
			<label class="col-md-2" style="width: 120px">Fecha inicio</label>
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
			<div class="col-md-2">
				<label style="display: inline;">Enzima</label>
				<input style="display: inline;width:70px" type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtEnzima" placeholder="Kg" name="txtEnzima" value="<?php if ($reg_fa['pfg2_enzima'] == '') {
																																																						echo "";
																																																						$str_prop = '';
																																																					} else {
																																																						echo $reg_fa['pfg2_enzima'];
																																																						$str_prop = 'disabled';
																																																					} ?>" <?php echo $str_prop;
																																																							echo " " . $strProp2;
																																																							echo " " . $strProp3; ?> required>
				<label style="display: inline;">Kg</label>
			</div>

			<div class="col-md-2">
				<label style="display: inline;">PH</label>
				<input style="display: inline;width:70px" type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txt_ph" placeholder="PH" name="txt_ph" value="<?php if ($reg_fa['pfg2_ph1'] == '') {
																																																					echo "";
																																																					$str_prop = '';
																																																				} else {
																																																					echo $reg_fa['pfg2_ph1'];
																																																					$str_prop = 'disabled';
																																																				} ?>" <?php echo $str_prop;
																																																						echo " " . $strProp2;
																																																						echo " " . $strProp3; ?> required>
			</div>

			<label class="col-md-1" style="width: 165px">Temp agua utilizada</label>
			<div class="col-md-1 tiempos">
				<input type="text" maxlength="6" placeholder="Temp" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txt_temp" placeholder="" name="txt_temp" value="<?php if ($reg_fa['pfg2_temp_ag'] == '') {
																																																	echo "";
																																																	$str_prop = '';
																																																} else {
																																																	echo $reg_fa['pfg2_temp_ag'];
																																																	$str_prop = 'disabled';
																																																} ?>" <?php echo $str_prop;
																																																		echo " " . $strProp2;
																																																		echo " " . $strProp3; ?> required>
			</div>

		</div>
		<!---->

		<div class="row">
			<div class="col-md-5">
				<table border="0" cellspacing="5" cellpadding="5">
					<tr class="etiqueta_tbl">
						<td style="padding-right:1.5rem">No</td>
						<td style="padding-right:1.5rem">Hora</td>
						<td style="padding-right:1.5rem">Ph</td>
						<td style="padding-right:1.5rem">Lts sosa</td>
						<td style="padding-right:1.5rem">Temp</td>
					</tr>
					<?php for ($i = 1; $i <= 41; $i++) {


						if ($i > 2) {
							$i += 1;
						}

						if ($i == 8) {
							$i = 7;
						}

						if ($i == 9) {
							$i = 11;
						}

						if ($i == 13) {
							$i = 15;
						}

						if ($i == 17) {
							$i = 19;
						}

						if ($i == 21) {
							$i = 23;
						}

						if ($i == 25) {
							$i = 27;
						}

						if ($i == 29) {
							$i = 32;
						}

						if ($i == 34) {
							$i = 36;
						}



						$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_2b_d WHERE pfg2_id = '$reg_fa[pfg2_id]' and pfd2_ren = '$i' ");
						$reg_fad = mysqli_fetch_array($cad_fad);
					?>
						<tr>
							<td style="padding-right:1.5rem"><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
							<td style="padding-right:1.5rem"><input type="time" class="form-control" id="<?php echo "txtHoraD" . $i ?>" name="<?php echo "txtHoraD" . $i ?>" value="<?php if ($reg_fad['pfd2_hr'] == '') {
																																														echo date("H:i");
																																														$str_prop = '';
																																													} else {
																																														echo $reg_fad['pfd2_hr'];
																																														$str_prop = 'disabled';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3;
																																															echo " " . $strProp4; ?>></td>
							<td style="padding-right:1.5rem"><input onKeyPress="return isNumberKey(event, this);" maxlength="6" style="width: 70px" type="text" class="form-control" id="<?php echo "txtPhD" . $i ?>" name="<?php echo "txtPhD" . $i ?>" value="<?php if ($reg_fad['pfd2_ph'] == '') {
																																																																	echo "";
																																																																	$str_prop = '';
																																																																} else {
																																																																	echo $reg_fad['pfd2_ph'];
																																																																	$str_prop = 'disabled';
																																																																} ?>" <?php echo $str_prop;
																																																																		echo " " . $strProp2;
																																																																		echo " " . $strProp3;
																																																																		echo " " . $strProp4; ?> placeholder="pH"></td>
							<td style="padding-right:1.5rem">
								<?php if ($i != 2 && $i != 6 && $i != 11 && $i != 19 && $i != 27 && $i != 36 && $i != 40) { ?>
									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtSosaD" . $i ?>" name="<?php echo "txtSosaD" . $i ?>" value="<?php if ($reg_fad['pfd2_sosa'] == '') {
																																																								echo "";
																																																								$str_prop = '';
																																																							} else {
																																																								echo $reg_fad['pfd2_sosa'];
																																																								$str_prop = 'disabled';
																																																							} ?>" <?php echo $str_prop;
																																																									echo " " . $strProp2;
																																																									echo " " . $strProp3;
																																																									echo " " . $strProp4; ?> size="5" placeholder="Sosa">
								<?php } else {
									echo "-";
								} ?>
							</td>
							<td style="padding-right:1.5rem">
								<?php if ($i != 2 && $i != 6 && $i != 11 && $i != 19 && $i != 27 && $i != 36 && $i != 40) { ?>
									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txt_tempd<?php echo $i; ?>" name="txt_tempd<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_temp'] == '') {
																																																									echo "";
																																																									$str_prop = '';
																																																								} else {
																																																									echo $reg_fad['pfd2_temp'];
																																																									$str_prop = 'disabled';
																																																								} ?>" <?php echo $str_prop;
																																																										echo " " . $strProp2;
																																																										echo " " . $strProp3;
																																																										echo " " . $strProp4; ?> placeholder="Temp">
								<?php } else {
									echo "-";
								} ?>
							</td>
						</tr>
					<?php } ?>
				</table>
			</div>
			<div class="col-md-3" style="display: flex;height:450px">
				<div class="alert alert-info hide" id="alerta-errorFase2bOpe" style="height: 40px;width:300px;text-align: left;z-index: 10;font-size: 10px;margin-top:auto">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>

			<div class="col-md-4" style="border-radius: 5px;border: 1px solid #e6e6e6;height: 110px;width:350px;padding:0px">

				<div class="row">
					<div class="col-md-3" style="    height: 110px;border-radius: 5px;border: 1px solid #e6e6e6;">
						<p class="numEtapa">2b</p>
					</div>
					<div class="col-md-9" style="padding:0px">
						<label class="etiquetaEtapa" style="background:#e6e6e6;width:257px">Liberación de la extractibilidad entre 80-85%<?php /* echo fnc_rango_de(3) ?> - <?php echo fnc_rango_a(3) */ ?></label>
						<input style="width:15rem;display: inline;" type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" id="txt_extractibilidad" class="form-control" placeholder="Extractibiliad" name="txt_extractibilidad" value="<?php if ($reg_lib['prol_hr_totales'] == '') {
																																																																	echo "";
																																																																	$str_prop = '';
																																																																} else {
																																																																	echo $reg_lib['prol_hr_totales'];
																																																																	$str_prop = 'disabled';
																																																																} ?>" <?php echo $str_prop;
																																																																		echo " " . $strProp1;
																																																																		echo " " . $strProp2; ?>>
						<br>
						<input style="width:15rem;display: inline;" type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" id="txtHrTotales" class="form-control" placeholder="Horas totales" name="txtHrTotales" value="<?php if ($reg_lib['prol_hr_totales'] == '') {
																																																													echo "";
																																																													$str_prop = '';
																																																												} else {
																																																													echo $reg_lib['prol_hr_totales'];
																																																													$str_prop = 'disabled';
																																																												} ?>" <?php echo $str_prop;
																																																														echo " " . $strProp1;
																																																														echo " " . $strProp2; ?>>
						<input style="width:15rem" type="text" onKeyPress="return isNumberKeyFloat(event, this);" id="inputPassword" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
						<!--<input  type="text" id="inputPassword" class="form-control" placeholder="Firma LCP">-->
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-12">
						<label for="inputPassword4">(<?php echo fnc_hora_de(3) ?> a <?php echo fnc_hora_a(3) ?> Horas)</label>
					</div>
					<div class="form-group col-md-12">
						<label for="inputPassword4">Fecha termina enzima</label>
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
					<div class="form-group col-md-12">
						<label for="inputPassword4">Hora termina enzima</label>
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
					<div class="form-group col-md-12">
						<label for="inputPassword4">Hrs totales del proceso</label>
						<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtHrasTot" name="txtHrasTot" placeholder="" value="<?php if ($reg_fa['pfg2_hr_totales'] == '') {
																																												echo "";
																																												$str_prop = '';
																																											} else {
																																												echo $reg_fa['pfg2_hr_totales'];
																																												$str_prop = 'disabled';
																																											} ?>" <?php echo $str_prop;
																																													echo " " . $strProp6;
																																													echo " " . $strProp3 . " " . $strProp4; ?>>
					</div>
					<div class="col-md-12">
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

					<div class="col-md-12" style="text-align: right;margin-top:1rem">
						<?php if ($_SESSION['privilegio'] == 4) {	?>
							<input type="hidden" id="hddSaltar" name="hddSaltar" class="form-control" placeholder="Nombre LCP" value="Si" readonly="">
							<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/quitar.png" alt="">Saltar</button>

							<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE2b(<?php echo $reg_pro['pro_id'] ?>, 30);">
								<img src="../iconos/edit.png" alt="">Editar
							</button>
						<?php } ?>
				<?php
				//Valida si el operador capturo quimicos en la etapa
				$cad_quim = mysqli_query($cnx, "SELECT * FROM quimicos_etapas WHERE pro_id = '$reg_pro[pro_id]' and pe_id = '3'");
				$tot_q = mysqli_num_rows($cad_quim);

				if($reg_fa['pfg2_enzima'] != '0' and $tot_q == 0) {echo "<div style='background-color:#ccc0ff'>El operador debe capturar los  químicos </div>"; $str_bandera_q2 = "No";} else{$str_bandera_q2 = "Si"; }
				?>
						<?php
						//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
						if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 4) {
							if ($reg_fa['pfg2_id'] != '' and $str_bandera_q2 == 'Si') {
						?>
								<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
							<?php
							} else {
								echo "N/A Guardar";
							}
						} else { ?>
							<button type="button" class="btn btn-info" onClick="javascript:quimicos_2b(<?php echo $reg_pro['pro_id'] ?>, <?php echo $reg_et['pe_id'] ?> );"><img src="../iconos/matraz.png" alt="">Químicos</button>

							<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
						<?php } ?>
					</div>
				</div>
			</div>

		</div>
		<div class="row" id="campos"></div>


	</form>

	<div class="modal" id="modalEditar2b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
	<div class="modal" id="m_modal_quimicos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

</div>