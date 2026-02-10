<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
//require 'funciones_procesos.php';


$reg_pro['pro_id'] = $idx_pro;

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 22");
$reg_aux = mysqli_fetch_array($cad_aux);
//echo "SELECT * FROM procesos_fase_1_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 22";
$cad_fa1 = mysqli_query($cnx, "SELECT * FROM procesos_fase_1_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 22");
$reg_fa1 = mysqli_fetch_array($cad_fa1);
//echo "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 1";
$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 22");
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
<script>
	a = 6;

	function agregarCampo() {
		a++;
		var div = document.createElement('div');
		div.setAttribute('class', 'form-row');
		div.setAttribute('style', 'margin-top:-20px');

		div.innerHTML =
			'<div  class="form-group col-md-1" style="width: 30px"><label>' + a + 'o</label></div>' +
			'<div class="form-group col-md-1" style="width: 130px"><input class="form-control" name="txtLavTipAgua' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1"><input class="form-control" name="txtTemp' + a + '" onKeyPress="return isNumberKey(event, this);" type="text"/></div>' +
			'<div class="form-group col-md-1" style="width: 150px"><input class="form-control" name="txtHraIni' + a + '" onKeyPress="return isNumberKey(event, this);" type="text"/></div>' +
			'<div class="form-group col-md-1" style="width: 160px"><input class="form-control" name="txtHraFin' + a + '" onKeyPress="return isNumberKey(event, this);" type="text"/></div>' +
			'<div class="form-group col-md-1" style="width: 180px"><input class="form-control" name="txtHraIniMov' + a + '" onKeyPress="return isNumberKey(event, this);" type="text"/></div>' +
			'<div class="form-group col-md-1" style="width: 190px"><input class="form-control" name="txtHraFinMov' + a + '" onKeyPress="return isNumberKey(event, this);" type="text"/></div>' +
			'<div class="form-group col-md-1"><input class="form-control" name="txtPh' + a + '" onKeyPress="return isNumberKey(event, this);" type="text"/></div>' +
			'<div class="form-group col-md-1"><input class="form-control" name="txtCe' + a + '" onKeyPress="return isNumberKey(event, this);" type="text"/></div>';
		document.getElementById('campos').appendChild(div);
		document.getElementById('campos').appendChild(div);
	}

	function AbreModalAgregar(valor) {
		//alert(valor);
		var datos = {
			"pro_id": valor,
		}
		//alert($("hdd_pro_id").val());
		$.ajax({
			type: 'post',
			url: 'modal_materiales.php',
			data: datos,
			//data: {nombre:n},
			success: function(result) {
				$("#modalMaterial").html(result);
				$('#modalMaterial').modal('show')
			}
		});
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
				$("#modalRenglon1b").html(result);
				$('#modalRenglon1b').modal('show')
			}
		});
		return false;
	}

	function AbreModalE1b(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_1b.php',
			data: datos,
			success: function(result) {
				$("#modalEditar1b").html(result);
				$('#modalEditar1b').modal('show')
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

	;

	$(document).ready(function() {
		$("#formFase1b").submit(function() {
			var formData = $(this).serialize();
			$.ajax({
				url: "fases/fase_1b_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase1bOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase1b').each(function() {
						this.reset();
					});
					//setTimeout(location.reload(), 23000);
					setTimeout("location.reload()", 2000);
				}
			});
			return false;
		});
	});
</script>

<div class="divProcesos">
	<form autocomplete="off" id="formFase1b" name="formFase1b">
		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="22" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa1['pfg1_id']; ?>" />
		<div class="headerdivProcesos">
			<div class="col-md-2">LAVADOS INICIALES </div>
			<div class="col-md-9">Nota: Si se junta agua recuperada con baja conductividad (Ce) con un cuero muy sucio, agregar más ácido limpio por cada lavado (20 -30 lts)</div>
		</div>

		<!--tiempos-->
		<?php //if($_SESSION['privilegio'] == 3 ){
		?>
		<div class="row" style="margin-bottom: 10px">
			<label class="col-md-1" style="width: 200px;">Fecha que inicia lavados</label>
			<div class="col-md-2 tiempos">
				<input type="date" class="form-control" id="txtFeIni" name="txtFeIni" placeholder="__________" value="<?php if ($reg_aux['proa_fe_ini'] == '') {
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
				<input type="time" class="form-control" id="txtHrIni" name="txtHrIni" placeholder="__________" value="<?php if ($reg_aux['proa_hr_ini'] == '') {
																															echo date("H:i");
																															$str_prop = '';
																														} else {
																															echo $reg_aux['proa_hr_ini'];
																															$str_prop = 'disabled';
																														} ?>" <?php echo $str_prop;
																																echo " " . $strProp2;
																																echo " " . $strProp3; ?> required>
			</div>

			<!--<label class="col-md-1" style="width: 165px">Temp agua utilizada/label>
				<div class="col-md-2 tiempos">
					<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp" name="txtTemp" placeholder="TEMP" value="<?php /*if($reg_fa1['pfg1_temp_ag'] == ''){echo ""; $str_prop = '';}else{echo $reg_fa1['pfg1_temp_ag']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp2; echo " ".$strProp3;*/ ?> required>
				</div>-->

			<div class="col-md-2" style="height: 30px; border-radius: 5px; border:1px solid #e6e6e6; margin-bottom: 3px; font-weight:bold; background-color:#CCCCCC;width: 150px">
				<label>BAJAR CE A 3.0</label>
			</div>
		</div>
		<div class="row" style="margin-bottom: 10px">
			<label class="col-md-1" style="width: 200px;">Ph agua de inicio</label>
			<div class="col-md-2 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtAgIni" name="txtAgIni" placeholder="Ph agua inicio" value="<?php if ($reg_fa1['pfg1_ph_agua'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa1['pfg1_ph_agua'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-2" style="width: 150px">Ce agua de inicio</label>
			<div class="col-md-2 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtCeIni" name="txtCeIni" placeholder="Ce agua de inicio" value="<?php if ($reg_fa1['pfg1_ce_agua'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa1['pfg1_ce_agua'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
			</div>
		</div>
		<!---->

		<div>
			<table border="0" cellspacing="5" cellpadding="5">
				<tr class="etiqueta_tbl">
					<td width="15">&nbsp;</td>
					<td>Lav</td>
					<td>Tipo Agua</td>
					<td>TEMP</td>
					<td>HR ini llenado</td>
					<td>HR ter llenado</td>
					<td>HR ini movimiento</td>
					<td>HR ter movimiento</td>
					<td>pH</td>
					<td>CE</td>
					<td>Agua a</td>
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

					$fltVal = fnc_parametro_max(22, 'C');

					$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 22);

					if ($i <= 6 or $bolVal == 'Si') {
				?>
						<tr>
							<td>&nbsp;</td>
							<td align="center"><?php echo $i; ?></td>
							<td><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>">
								<input type="hidden" class="form-control" id="<?php echo "hddRen" . $i ?>" name="<?php echo "hddRen" . $i ?>" value="<?php echo $reg_fad1['pfd1_id']; ?>">
								<select id="cbxTipAg<?php echo $i  ?>" class="form-control" style="width: 150px" name="cbxTipAg<?php echo $i  ?>" <?php if ($reg_fad1['tpa_id'] == '') {
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
									?><option value="<?php echo $registros['tpa_id'] ?>" <?php if ($registros['tpa_id'] == $reg_fad1['tpa_id']) { ?>selected="selected" <?php } ?>><?php echo $registros['tpa_descripcion'] ?></option><?php
																																																									} while ($registros =  mysqli_fetch_array($cadena));

																																																									//mysqli_free_result($registros);

																																																										?>
								</select>
							</td>
							<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtTemp" . $i ?>" name="<?php echo "txtTemp" . $i ?>" value="<?php if ($reg_fad1['pfd1_temp'] == '') {
																																																			echo "";
																																																			$str_prop = '';
																																																		} else {
																																																			echo $reg_fad1['pfd1_temp'];
																																																			$str_prop = 'disabled';
																																																		} ?>" <?php echo $str_prop;
																																																				echo " " . $strProp2;
																																																				echo " " . $strProp3;
																																																				echo " " . $strProp4; ?> size="5" placeholder="Temp"></td>
							<td><input type="time" class="form-control" id="<?php echo "txtHraIni" . $i ?>" name="<?php echo "txtHraIni" . $i ?>" value="<?php if ($reg_fad1['pfd1_hr_ini'] == '') {
																																								echo "";
																																								$str_prop = '';
																																							} else {
																																								echo $reg_fad1['pfd1_hr_ini'];
																																								$str_prop = 'disabled';
																																							} ?>" <?php echo $str_prop;
																																									echo " " . $strProp2;
																																									echo " " . $strProp3;
																																									echo " " . $strProp4; ?>></td>
							<td><input type="time" class="form-control" id="<?php echo "txtHraFin" . $i ?>" name="<?php echo "txtHraFin" . $i ?>" value="<?php if ($reg_fad1['pfd1_hr_fin'] == '') {
																																								echo "";
																																								$str_prop = '';
																																							} else {
																																								echo $reg_fad1['pfd1_hr_fin'];
																																								$str_prop = 'disabled';
																																							} ?>" <?php echo $str_prop;
																																									echo " " . $strProp2;
																																									echo " " . $strProp3;
																																									echo " " . $strProp4; ?>></td>
							<td><input type="time" class="form-control" id="<?php echo "txtHraIniMov" . $i ?>" name="<?php echo "txtHraIniMov" . $i ?>" value="<?php if ($reg_fad1['pfd1_hr_ini_mov'] == '') {
																																									echo "";
																																									$str_prop = '';
																																								} else {
																																									echo $reg_fad1['pfd1_hr_ini_mov'];
																																									$str_prop = 'disabled';
																																								} ?>" <?php echo " " . $str_prop;
																																										echo " " . $strProp2;
																																										echo " " . $strProp3;
																																										echo " " . $strProp4; ?>></td>
							<td><input type="time" class="form-control" id="<?php echo "txtHraFinMov" . $i ?>" name="<?php echo "txtHraFinMov" . $i ?>" value="<?php if ($reg_fad1['pfd1_hr_fin_mov'] == '') {
																																									echo "";
																																									$str_prop = '';
																																								} else {
																																									echo $reg_fad1['pfd1_hr_fin_mov'];
																																									$str_prop = 'disabled';
																																								} ?>" <?php echo " " . $str_prop;
																																										echo " " . $strProp2;
																																										echo " " . $strProp3;
																																										echo " " . $strProp4; ?>></td>
							<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtPh" . $i ?>" name="<?php echo "txtPh" . $i ?>" value="<?php if ($reg_fad1['pfd1_ph'] == '') {
																																																		echo "";
																																																		$str_prop = '';
																																																	} else {
																																																		echo $reg_fad1['pfd1_ph'];
																																																		$str_prop = 'disabled';
																																																	} ?>" <?php echo $str_prop;
																																																			echo " " . $strProp2;
																																																			echo " " . $strProp3;
																																																			echo " " . $strProp4; ?> size="5" placeholder="pH"></td>
							<td align="center"><input <?php if ($fltVal >= $reg_fad1['pfd1_ce'] and $reg_fad1['pfd1_ce'] != '') { ?>style="background-color:#66FF66;" <?php } ?> type="text" class="form-control" maxlength="6" onKeyPress="return isNumberKey(event, this);" id="<?php echo "txtCe" . $i ?>" name="<?php echo "txtCe" . $i ?>" value="<?php if ($reg_fad1['pfd1_ce'] == '') {
																																																																																				echo "";
																																																																																				$str_prop = '';
																																																																																			} else {
																																																																																				echo $reg_fad1['pfd1_ce'];
																																																																																				$str_prop = 'disabled';
																																																																																			} ?>" <?php echo $str_prop;
																																																																																					echo " " . $strProp2;
																																																																																					echo " " . $strProp3;
																																																																																					echo " " . $strProp4; ?> size="5" style="width:100px;" placeholder="Ce"></td>
							<td align="center">

								<select id="cbxAgua<?php echo $i  ?>" class="form-control" style="width: 150px" name="cbxAgua<?php echo $i  ?>" <?php if ($reg_fad1['taa_id'] == '') {
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
									$cadena =  mysqli_query($cnx, "SELECT * from tipos_agua_a ORDER BY taa_descripcion");
									$registros =  mysqli_fetch_array($cadena);

									do {
									?><option value="<?php echo $registros['taa_id'] ?>" <?php if ($registros['taa_id'] == $reg_fad1['taa_id']) { ?>selected="selected" <?php } ?>><?php echo $registros['taa_descripcion'] ?></option><?php
																																																									} while ($registros =  mysqli_fetch_array($cadena));
																																																										?>
								</select>

							</td>
							<td><input type="text" class="form-control" maxlength="100" id="<?php echo "txtObs" . $i ?>" name="<?php echo "txtObs" . $i ?>" value="<?php if ($reg_fad1['pfd1_observaciones'] == '') {
																																						echo "";
																																						$str_prop = '';
																																					} else {
																																						echo $reg_fad1['pfd1_observaciones'];
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

		</div>

		<!--estilo general de estapas-->
		<?php //if($_SESSION['privilegio'] == 4 ){
		?>
		<div class="row">
			<div class="form-group col-md-2">
				<label for="inputPassword4">Fecha termina</label>
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
			<!--<div class="form-row">
			<div class="form-group col-md-2">
				<label for="inputPassword4">Realizó</label>
				<select name="slcRealizo">
					<option value="">Seleccionar</option>
					<?php
					/*$html = '';
					 
					$cadena_usu =  mysqli_query($cnx,"SELECT usu_id, usu_usuario from usuarios WHERE up_id = 3 AND usu_est = 'A' ");
					$registros =  mysqli_fetch_array($cadena_usu);
					do
					{
						$html.= "<option value='".$registros['usu_id']."'>".$registros['usu_usuario']."</option>";
					}while($registros =  mysqli_fetch_array($cadena_usu));
					echo $html;*/
					?>
				</select>
			</div>
		</div>-->
			<div class="form-group col-md-2">
				<label for="horas">(<?php echo fnc_hora_de(1) ?> a <?php echo fnc_hora_a(1) ?> Horas)</label>
			</div>
			<?php //}
			?>
			<?php //if($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28  ){
			?>
			<div class="row">
				<div class="col-md-3 divEtapas">
					<div class="col-md-1 etapa">
						<p class="numEtapa">1b</p>
					</div>
					<div class="col-md-2 divEtapasInput">
						<label class="etiquetaEtapa">LIBERACION CE A <?php echo fnc_rango_a(1) ?> MAX</label>
						<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" maxlength="6" id="txtCeLib" class="form-control" placeholder="Ce liberacion" name="txtCeLib" value="<?php if ($reg_lib['prol_ce'] == '') {
																																														echo "";
																																														$str_prop = '';
																																													} else {
																																														echo $reg_lib['prol_ce'];
																																														$str_prop = 'disabled';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp1;
																																															echo " " . $strProp2; ?>>
						<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" maxlength="6" id="txtHrTotales" class="form-control" placeholder="Horas totales" name="txtHrTotales" value="<?php if ($reg_lib['prol_hr_totales'] == '') {
																																																echo "";
																																																$str_prop = '';
																																															} else {
																																																echo $reg_lib['prol_hr_totales'];
																																																$str_prop = 'disabled';
																																															} ?>" <?php echo $str_prop;
																																																	echo " " . $strProp1;
																																																	echo " " . $strProp2; ?>>
						<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" id="txtUsuario" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
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
						<textarea type="textarea" class="form-control" id="" maxlength="300" placeholder="Observaciones..." name="txaObservaciones" value="<?php if ($reg_aux['proa_observaciones'] == '') {
																																				$str_prop = '';
																																			} else {
																																				$str_prop = 'disabled';
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
			<!--<div class="col-md-4">
					<label style="font-weight:bold; margin-left:20px;">El agua de este proceso se manda a PILA 3 y 4 AGUA que no se recupera</label>
				</div>	-->

			<div class="form-group col-md-6">
				<div class="alert alert-info hide" id="alerta-errorFase1bOpe" style="height: 40px;width: 270px;text-align: left;z-index: 10;font-size: 10px;margin-bottom: -10px">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>
			<!--<div class="col-md-2" style="margin-bottom: 10px"><input type="button" class="btn btn-success" id="add_cancion()" onClick="agregarCampo()" value="+ Agregar campo" /></div>-->
			<?php /*if($strVal == ''  and $_SESSION['privilegio'] == 4){?><div class="col-md-2" style="margin-bottom: 10px;width: 160px"><input type="button" class="btn btn-success" id="agregar" value="+ Agregar material" onClick="javascript:AbreModalAgregar(<?php echo $reg_pro['pro_id'] ?>)"/> </div><?php }?>-->
				<button type="submit" style="margin-bottom: 5px" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>	

			</div>
		</form>
		<?php if($strVal == ''  and $_SESSION['privilegio'] == 4){?>
			<!--<div class="col-md-2" style="margin-bottom: 10px;width: 160px"><input type="button" class="btn btn-success" id="agregar" value="+ Agregar material" onClick="javascript:AbreModalAgregar(<?php echo $reg_pro['pro_id'] ?>)"/> </div>-->
			<?php include('modal_materiales.php'); ?>
			<button style="float: right;margin-top: -40px;margin-right:10px" type="button"  class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
				+ Agregar material
			</button>

		<?php }if($_SESSION['privilegio'] == 4){
		?>
		<div class="col-md-2" style="float: right;margin-top: -40px;margin-right:80px">
			<input type="button" class="btn btn-success" id="permitir" value="+ Renglon" onClick="javascript:AbreModalAgregarR(<?php echo $reg_pro['pro_id'] ?>, 22, <?php echo $valUltRen ?>);"/> 
			
		</div>
		<?php }*/ ?>
			<div class="col-md-6" style="text-align:right">
				<?php if ($_SESSION['privilegio'] == 4) {	?>

					<!--<div class="col-md-1" style="width: 170px">-->
					<!--Nota: si no ocupa es-->
					<!--<button type="button" class="btn btn-success" id="paleto" onClick="javascript:AbreModalPaleto(<?php //echo $reg_pro['pro_id'] 
																														?>, <?php //echo $id_l 
																															?>);"> <img src="../iconos/procesos2.png" alt="">Paleto
					</button>-->
					<?php /* if ($strVal == '') { ?>
						<button type="button" class="btn btn-success" id="material" data-toggle="modal" data-target="#exampleModal">
							<img src="../iconos/add.png" alt=""> Agregar material</button><?php } */ ?>
					<!--</div>-->
					
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE1b(<?php echo $reg_pro['pro_id'] ?>, 22);"> <img src="../iconos/edit.png" alt="">Editar
					</button>
					<button type="button" class="btn btn-success" id="permitir" onClick="javascript:AbreModalAgregarR(<?php echo $reg_pro['pro_id'] ?>, 22, <?php echo $valUltRen ?>);"> <img src="../iconos/add.png" alt="">Renglon
					</button>

				<?php } ?>


				<?php
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28  or $_SESSION['privilegio'] == 4) {
					if ($reg_fa1['pfg1_id'] != '') {
				?>
						<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
					<?php
					} else {
						echo "N/A Guardar";
					}
				} else { ?>
					<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
				<?php } ?>

			</div>
		</div>
	</form>

	<div class="modal" id="modalRenglon1b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	<div class="modal" id="modalEditar1b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	<?php include('modal_materiales_enfases.php'); ?>
</div>