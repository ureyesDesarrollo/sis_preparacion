<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2029*/

$reg_pro['pro_id'] = $idx_pro;

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 29");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 29");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 29");
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
<script language="javascript">
	$(document).ready(function() {
		$("#formFase7d").submit(function() {

			var formData = $(this).serialize();
			$.ajax({
				url: "fases/fase_7d_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase7dOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase7d').each(function() {
						this.reset();
					});
					setTimeout("location.reload()", 2000);
				}
			});
			return confirmEnviarFase7b();
			return false;

		});

	});


	//Bloquear boton al agregar material
	function confirmEnviarFase7b() {
		formFase7d.btn.disabled = true;
		formFase7d.btn.value = "Enviando...";

		setTimeout(function() {
			formFase7d.btn.disabled = true;
			formFase7d.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}

	function AbreModalE7b(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_7d.php',
			data: datos,
			success: function(result) {
				$("#modalEditar7b").html(result);
				$('#modalEditar7b').modal('show')
			}
		});
		return false;
	}

	//agregado 16-10-21
	function quimicos_7b(proceso, etapa) {
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
	<form autocomplete="off" id="formFase7d" name="formFase7d">
		<input type="hidden" value="<?php //echo $_GET['id_e'] 
									?>" name="txt_equipo">

		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="29" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg7_id']; ?>" />

		<div class="headerdivProcesos">
			<div class="col-md-2">SEGUNDO ÁCIDO</div>
			<div class="col-md-8">PPRO = pH 2.0 máx</div>
			<div class="col-md-5"></div>
		</div>

		<!--tiempos-->
		<div class="row">
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
		</div>

		<div class="row" style="margin-top: 1rem;">
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
			<label class="col-md-2">4 horas continuas</label>
		</div>
		<!---->

		<div class="row" style="margin-top: 2rem;margin-bottom: 2rem;">
			<div class="col-md-5">
				<table border="0" cellspacing="5" cellpadding="5">
					<tr class="etiqueta_tbl">
						<td width="50">Ajust</td>
						<td>NORMALIDAD</td>
						<td align="center" bgcolor="#FF0000">pH</td>
						<td>CE</td>
						<td>TEMP</td>
						<td width="100px">ACIDO</td>
					</tr>
					<?php
					for ($i = 1; $i <= 3; $i++) {
						if ($i == 1) {
							$val = '0:30';
						}
						if ($i == 2) {
							$val = '1:00';
						}
						if ($i == 3) {
							$val = '1:30';
						}
						$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d WHERE pfg7_id = '$reg_fa[pfg7_id]' and pfd7_ren = '$i' ");
						$reg_fad = mysqli_fetch_array($cad_fad);
					?>
						<tr>
							<td>
								&nbsp;<?php echo $val; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>">
							</td>
							<td>
								<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtNormF" . $i ?>" name="<?php echo "txtNormF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_norm'] == '') {
																																																										echo "";
																																																										$str_prop = '';
																																																									} else {
																																																										echo $reg_fad['pfd7_norm'];
																																																										$str_prop = 'disabled';
																																																									} ?>" <?php echo $str_prop;
																																																											echo " " . $strProp2;
																																																											echo " " . $strProp3;
																																																											echo " " . $strProp4; ?> placeholder="Norm">
							</td>
							<td align="center" bgcolor="#FF0000">
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
							</td>
							<td>
								<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtCeF" . $i ?>" name="<?php echo "txtCeF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_ce'] == '') {
																																																									echo "";
																																																									$str_prop = '';
																																																								} else {
																																																									echo $reg_fad['pfd7_ce'];
																																																									$str_prop = 'disabled';
																																																								} ?>" <?php echo $str_prop;
																																																										echo " " . $strProp2;
																																																										echo " " . $strProp3;
																																																										echo " " . $strProp4; ?> placeholder="Ce">
							</td>
							<td>
								<?php
								if ($i != 2 && $i != 5) { ?>
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
							<td>
								<?php
								if ($i == 2 || $i == 5) { ?>
									<input style="display: inline;width:50px" type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtAcidoF" . $i ?>" name="<?php echo "txtAcidoF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_acido'] == '') {
																																																																				echo "";
																																																																				$str_prop = '';
																																																																			} else {
																																																																				echo $reg_fad['pfd7_acido'];
																																																																				$str_prop = 'disabled';
																																																																			} ?>" <?php echo $str_prop;
																																																																					echo " " . $strProp2;
																																																																					echo " " . $strProp3;
																																																																					echo " " . $strProp4; ?> placeholder="Acido">
									<span style="display: inline;">LTS</span>
								<?php } ?>
							</td>

						</tr>
					<?php } ?>
				</table>
			</div>
			<div class="col-md-5">
				<table border="0" cellspacing="5" cellpadding="5">
					<tr class="etiqueta_tbl">
						<td width="50">Ajust</td>
						<td>NORMALIDAD</td>
						<td align="center" bgcolor="#FF0000">pH</td>
						<td>CE</td>
						<td>TEMP</td>
						<td width="100px">ACIDO</td>
					</tr>
					<?php
					for ($i = 4; $i <= 6; $i++) {
						if ($i == 4) {
							$val = '2:30';
						}
						if ($i == 5) {
							$val = '3:00';
						}
						if ($i == 6) {
							$val = '3:30';
						}
						$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d WHERE pfg7_id = '$reg_fa[pfg7_id]' and pfd7_ren = '$i' ");
						$reg_fad = mysqli_fetch_array($cad_fad);
					?>
						<tr>
							<td>
								&nbsp;<?php echo $val; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>">
							</td>
							<td>
								<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtNormF" . $i ?>" name="<?php echo "txtNormF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_norm'] == '') {
																																																										echo "";
																																																										$str_prop = '';
																																																									} else {
																																																										echo $reg_fad['pfd7_norm'];
																																																										$str_prop = 'disabled';
																																																									} ?>" <?php echo $str_prop;
																																																											echo " " . $strProp2;
																																																											echo " " . $strProp3;
																																																											echo " " . $strProp4; ?> placeholder="Norm">
							</td>
							<td align="center" bgcolor="#FF0000">
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
							</td>
							<td>
								<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtCeF" . $i ?>" name="<?php echo "txtCeF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_ce'] == '') {
																																																									echo "";
																																																									$str_prop = '';
																																																								} else {
																																																									echo $reg_fad['pfd7_ce'];
																																																									$str_prop = 'disabled';
																																																								} ?>" <?php echo $str_prop;
																																																										echo " " . $strProp2;
																																																										echo " " . $strProp3;
																																																										echo " " . $strProp4; ?> placeholder="Ce">
							</td>
							<td>
								<?php
								if ($i != 2 && $i != 5) { ?>
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
							<td>
								<?php
								if ($i == 2 || $i == 5) { ?>
									<input style="display: inline;width:50px" type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtAcidoF" . $i ?>" name="<?php echo "txtAcidoF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd7_acido'] == '') {
																																																																				echo "";
																																																																				$str_prop = '';
																																																																			} else {
																																																																				echo $reg_fad['pfd7_acido'];
																																																																				$str_prop = 'disabled';
																																																																			} ?>" <?php echo $str_prop;
																																																																					echo " " . $strProp2;
																																																																					echo " " . $strProp3;
																																																																					echo " " . $strProp4; ?> placeholder="Acido">
									<span style="display: inline;">LTS</span>
								<?php } ?>
							</td>

						</tr>
					<?php } ?>
				</table>
			</div>
		</div>


		<!--estilo general de estapas-->
		<div class="row" style="margin-top: 2rem;">
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
																															echo " " . $strProp6;
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
																															echo " " . $strProp6;
																															echo " " . $strProp3 . " " . $strProp4; ?>>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">Horas totales</label>
					<input maxlength="6" type="text" id="txtHrTotales1" class="form-control" placeholder="Horas totales" name="txtHrTotales1" value="<?php if ($reg_fa['pfg7_hr_totales'] == '') {
																																							echo "";
																																							$str_prop = '';
																																						} else {
																																							echo $reg_fa['pfg7_hr_totales'];
																																							$str_prop = 'disabled';
																																						} ?>" <?php echo $str_prop;
																																								echo " " . $strProp6;
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
					<label for="inputPassword4">Total (<?php echo fnc_hora_de(29) ?>-<?php echo fnc_hora_a(29) ?>) hrs</label>
				</div>
			</div>
		</div>
		<!---->

		<div class="row" style="font-weight:bold;margin-bottom: 2rem;">
			(En esta parte son 15 minutos de movimiento y 1:45 de reposo durante 4-6 horas)
		</div>

		<div class="row">
			<div class="col-md-8">
				<table border="0" cellspacing="5" cellpadding="5">
					<tr class="">
						<td width="15">&nbsp;</td>
						<td></td>
						<td>Inicia movimiento</td>
						<td>&nbsp;</td>
						<td>Inicia reposo</td>
						<td>&nbsp;</td>
						<td>NORM</td>
						<td>PH</td>
						<td>CE</td>
						<td>TEMP</td>
					</tr>
					<?php
					for ($i = 1; $i <= 4; $i++) {
						$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d2 WHERE pfg7_id = '$reg_fa[pfg7_id]' and pfd7_ren = '$i' ");
						$reg_fad = mysqli_fetch_array($cad_fad);
					?>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen2" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen2" . $i ?>"></td>
							<td>
								<input type="time" id="txtIniMovD<?php echo $i; ?>" name="txtIniMovD<?php echo $i; ?>" class="form-control" value="<?php if ($reg_fad['pfd7_ini_mov'] == '') {
																																						echo "";
																																						$str_prop = '';
																																					} else {
																																						echo $reg_fad['pfd7_ini_mov'];
																																						$str_prop = 'disabled';
																																					} ?>" <?php echo $str_prop;
																																							echo " " . $strProp2;
																																							echo " " . $strProp3;
																																							echo " " . $strProp4; ?> />
							</td>
							<td>HRS</td>
							<td>
								<input type="time" id="txtIniRepD<?php echo $i; ?>" name="txtIniRepD<?php echo $i; ?>" class="form-control" value="<?php if ($reg_fad['pfd7_ini_reposo'] == '') {
																																						echo "";
																																						$str_prop = '';
																																					} else {
																																						echo $reg_fad['pfd7_ini_reposo'];
																																						$str_prop = 'disabled';
																																					} ?>" <?php echo $str_prop;
																																							echo " " . $strProp2;
																																							echo " " . $strProp3;
																																							echo " " . $strProp4; ?> />
							</td>
							<td>HRS</td>
							<td>
								<?php if ($i == 1 || $i == 2) { ?>
									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" name="txtNormD<?php echo $i; ?>" id="txtNormD<?php echo $i; ?>" class="form-control" placeholder="NORM" value="<?php if ($reg_fad['pfd7_norm'] == '') {
																																																											echo "";
																																																											$str_prop = '';
																																																										} else {
																																																											echo $reg_fad['pfd7_norm'];
																																																											$str_prop = 'disabled';
																																																										} ?>" <?php echo $str_prop;
																																																												echo " " . $strProp2;
																																																												echo " " . $strProp3;
																																																												echo " " . $strProp4; ?>>
								<?php } ?>

							</td>

							<td>
								<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" name="txtPhD<?php echo $i; ?>" id="txtPhD<?php echo $i; ?>" class="form-control" placeholder="PH" value="<?php if ($reg_fad['pfd7_ph'] == '') {
																																																									echo "";
																																																									$str_prop = '';
																																																								} else {
																																																									echo $reg_fad['pfd7_ph'];
																																																									$str_prop = 'disabled';
																																																								} ?>" <?php echo $str_prop;
																																																										echo " " . $strProp2;
																																																										echo " " . $strProp3;
																																																										echo " " . $strProp4; ?> />
							</td>
							<td>
								<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" name="txtCeD<?php echo $i; ?>" id="txtCeD<?php echo $i; ?>" class="form-control" placeholder="CE" value="<?php if ($reg_fad['pfd7_ce'] == '') {
																																																									echo "";
																																																									$str_prop = '';
																																																								} else {
																																																									echo $reg_fad['pfd7_ce'];
																																																									$str_prop = 'disabled';
																																																								} ?>" <?php echo $str_prop;
																																																										echo " " . $strProp2;
																																																										echo " " . $strProp3;
																																																										echo " " . $strProp4; ?> />
							</td>
							<td>
								<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" name="txtTempD<?php echo $i; ?>" id="txtTempD<?php echo $i; ?>" class="form-control" placeholder="TEMP" value="<?php if ($reg_fad['pfd7_temp'] == '') {
																																																										echo "";
																																																										$str_prop = '';
																																																									} else {
																																																										echo $reg_fad['pfd7_temp'];
																																																										$str_prop = 'disabled';
																																																									} ?>" <?php echo $str_prop;
																																																											echo " " . $strProp2;
																																																											echo " " . $strProp3;
																																																											echo " " . $strProp4; ?> />
							</td>


						</tr>
					<?php } ?>
				</table>
			</div>
			<div class="col-md-4">
				<div class="col-md-12 divEtapas" style="height: 8.7rem;">
					<div class="col-md-1 etapa" style="height: 8.7rem;font-size: 50px;padding-top:1rem">
						<p class="">7d</p>
					</div>
					<div class="col-md-2 divEtapasInput">
						<!-- <label class="etiquetaEtapa">Cocido ph liberación (<?php echo fnc_rango_de(29) ?>-<?php echo fnc_rango_a(29) ?>)</label> -->
						<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" id="txtPhLib" class="form-control" placeholder="Cocido ph (1.7-2.1)" name="txtPhLib" value="<?php if ($reg_lib['prol_ph'] == '') {
																																																		echo "";
																																																		$str_prop = '';
																																																	} else {
																																																		echo $reg_lib['prol_ph'];
																																																		$str_prop = 'disabled';
																																																	} ?>" <?php echo $str_prop;
																																																			echo " " . $strProp1;
																																																			echo " " . $strProp2; ?>>
						<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" id="txtHrTotales" class="form-control" placeholder="Horas totales" name="txtHrTotales" value="<?php if ($reg_lib['prol_hr_totales'] == '') {
																																																			echo "";
																																																			$str_prop = '';
																																																		} else {
																																																			echo $reg_lib['prol_hr_totales'];
																																																			$str_prop = 'disabled';
																																																		} ?>" <?php echo $str_prop;
																																																				echo " " . $strProp1;
																																																				echo " " . $strProp2; ?>>
						<input type="text" id="txtUsuario" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
					</div>
				</div>
			</div>
		</div>


		<!--estilo general de estapas-->
		<div class="row">
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">Fecha termina lavados</label>
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
					<label for="inputPassword4">Hora termina</label>
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
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">Realizó</label>
					<input type="text" id="txtRealizo2" class="form-control" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="inputPassword4">Observaciones</label>
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
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">(8 - 12 Horas)</label>
				</div>
			</div>

		</div>



		<!--barra botones-->
		<div class="row footerdivProcesos" style="margin-bottom: 10px">
			<div class="col-md-5"></div>

			<div class="form-group col-md-4">
				<div class="alert alert-info hide" id="alerta-errorFase7dOpe" style="height: 40px; width: 270px; text-align: left; z-index: 10; font-size: 10px;">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>
			<div class="col-md-3" style="text-align: right;margin-left:  -40px">
				<?php if ($_SESSION['privilegio'] == 4) {	?>
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE7b(<?php echo $reg_pro['pro_id'] ?>, 29);">
						<img src="../iconos/edit.png" alt="">Editar
					</button>
				<?php } ?>
				<?php
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 4) {
					if ($reg_fa['pfg7_id'] != '') {
				?>
						<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
					<?php
					} else {
						echo "N/A Guardar";
					}
				} else { ?>
					<button type="button" class="btn btn-info" onClick="javascript:quimicos_7b(<?php echo $reg_pro['pro_id'] ?>, <?php echo $reg_et['pe_id'] ?> );"><img src="../iconos/matraz.png" alt="">Químicos</button>

					<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
				<?php } ?>
			</div>
		</div>

	</form>

	<div class="modal" id="modalEditar7b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
	<div class="modal" id="m_modal_quimicos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>


</div>