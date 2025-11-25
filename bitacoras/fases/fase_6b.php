<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

$reg_pro['pro_id'] = $idx_pro;

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 15");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_6b_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 15");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 15");
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
			'<div class="form-group col-md-1"><input class="form-control"  maxlength="6" onKeyPress="return isNumberKey(event, this);" name="txtTemp' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1" style="width: 150px"><input class="form-control" name="txtHraIni' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1" style="width: 160px"><input class="form-control" name="txtHraFin' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1" style="width: 180px"><input class="form-control" name="txtHraIniMov' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1" style="width: 190px"><input class="form-control" name="txtHraFinMov' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1"><input class="form-control"  maxlength="6" onKeyPress="return isNumberKey(event, this);" name="txtPh' + a + '" type="text"/></div>' +
			'<div class="form-group col-md-1"><input class="form-control"  maxlength="6" onKeyPress="return isNumberKey(event, this);" name="txtCe' + a + '" type="text"/></div>';
		document.getElementById('campos').appendChild(div);
		document.getElementById('campos').appendChild(div);
	}

	$(document).ready(function() {
		$("#formFase6b").submit(function() {

			var formData = $(this).serialize();
			$.ajax({
				url: "fases/fase_6b_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase6bOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase6b').each(function() {
						this.reset();
					});
					setTimeout("location.reload()", 2000);
				}
			});
			return false;

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
				$("#modalRenglon6b").html(result);
				$('#modalRenglon6b').modal('show')
			}
		});
		return false;
	}

	function AbreModalE6b(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_6b.php',
			data: datos,
			success: function(result) {
				$("#modalEditar6b").html(result);
				$('#modalEditar6b').modal('show')
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
<div class="divProcesos">
	<form autocomplete="off" id="formFase6b" name="formFase6b">
		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="15" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg6_id']; ?>" />

		<div class="headerdivProcesos">
			<div class="col-md-2">LAVADOS 1ER ÁCIDO</div>
			<div class="col-md-4">Este proceso se puede hacer con agua limpia</div>
			<div class="col-md-5"><!--Lavados finales a partir del 2do. lavado de paleto a paleto--></div>
		</div>

		<!--tiempos-->
		<div class="row" style="margin-bottom: 10px">
			<label class="col-md-1" style="width: 200px;">Fecha incio</label>
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
			<label class="col-md-1" style="width: 165px">Temp agua inicial</label>
			<div class="col-md-1 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp" name="txtTemp" placeholder="TEMP" value="<?php if ($reg_fa['pfg6_temp_ag'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa['pfg6_temp_ag'];
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
				<td>Lav </td>
				<td><span style="color: red;font-weight: bold;"> * </span>Tipo Agua</td>
				<td><span style="color: red;font-weight: bold;"> * </span>HR ini lavado</td>
				<td>HR ter lavado</td>
				<td>CE</td>
				<td>pH</td>
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

				$fltVal = fnc_parametro_max(15, 'C');

				$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 15);

				if ($i <= 5 or $bolVal == 'Si') {
			?>
					<tr>
						<td>&nbsp;</td>
						<td align="center"><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>">
							<input type="hidden" class="form-control" id="<?php echo "hddRen" . $i ?>" name="<?php echo "hddRen" . $i ?>" value="<?php echo $reg_fad['pfd6_id']; ?>" />
						</td>
						<td>
							<select id="cbxTipAg<?php echo $i  ?>" class="form-control" style="width: 140px" name="cbxTipAg<?php echo $i  ?>" <?php if ($reg_fad['tpa_id'] == '') {
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
							</select>
						</td>

						<td><input type="time" class="form-control" id="<?php echo "txtHraIni" . $i ?>" name="<?php echo "txtHraIni" . $i ?>" value="<?php if ($reg_fad['pfd6_hr_ini'] == '') {
																																							echo "";
																																							$str_prop = '';
																																						} else {
																																							echo $reg_fad['pfd6_hr_ini'];
																																							$str_prop = 'disabled';
																																						} ?>" <?php echo $str_prop;
																																								echo " " . $strProp2;
																																								echo " " . $strProp3;
																																								echo " " . $strProp4; ?>></td>
						<td><input type="time" class="form-control" id="<?php echo "txtHraFin" . $i ?>" name="<?php echo "txtHraFin" . $i ?>" value="<?php if ($reg_fad['pfd6_hr_fin'] == '') {
																																							echo "";
																																							$str_prop = '';
																																						} else {
																																							echo $reg_fad['pfd6_hr_fin'];
																																							$str_prop = 'disabled';
																																						} ?>" <?php echo $str_prop;
																																								echo " " . $strProp2;
																																								echo " " . $strProp3;
																																								echo " " . $strProp4; ?>></td>

						<td <?php if ($fltVal >= $reg_fad['pfd6_ce'] and $reg_fad['pfd6_ce'] != '') { ?>style="background-color:#66FF66;" <?php } ?> align="center"><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtCe" . $i ?>" name="<?php echo "txtCe" . $i ?>" value="<?php if ($reg_fad['pfd6_ce'] == '') {
																																																																																							echo "";
																																																																																							$str_prop = '';
																																																																																						} else {
																																																																																							echo $reg_fad['pfd6_ce'];
																																																																																							$str_prop = 'disabled';
																																																																																						} ?>" <?php echo $str_prop;
																																																																																								echo " " . $strProp2;
																																																																																								echo " " . $strProp3;
																																																																																								echo " " . $strProp4; ?> size="5" style="width:100px;" placeholder="Ce"></td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtPh" . $i ?>" name="<?php echo "txtPh" . $i ?>" value="<?php if ($reg_fad['pfd6_ph'] == '') {
																																																					echo "";
																																																					$str_prop = '';
																																																				} else {
																																																					echo $reg_fad['pfd6_ph'];
																																																					$str_prop = 'disabled';
																																																				} ?>" <?php echo $str_prop;
																																																						echo " " . $strProp2;
																																																						echo " " . $strProp3;
																																																						echo " " . $strProp4; ?> size="5" placeholder="pH"></td>

						<td><input type="text" class="form-control" id="<?php echo "txtObs" . $i ?>" name="<?php echo "txtObs" . $i ?>" value="<?php if ($reg_fad['pfd6_observaciones'] == '') {
																																					echo "";
																																					$str_prop = '';
																																				} else {
																																					echo $reg_fad['pfd6_observaciones'];
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

		<div class="row" id="campos"></div>
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
					<label for="inputPassword4">Temp final</label>
					<input onKeyPress="return isNumberKey(event, this);" type="text" class="form-control" id="txtTempFinal" placeholder="" name="txtTempFinal" value="<?php if ($reg_aux['proa_temp_final'] == '') {
																																											echo "";
																																											$str_prop = '';
																																										} else {
																																											echo $reg_aux['proa_temp_final'];
																																											$str_prop = 'disabled';
																																										} ?>" <?php echo $str_prop;
																																echo " " . $strProp6;
																																echo " " . $strProp3 . " " . $strProp4; ?>>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4">(<?php echo fnc_hora_de(15) ?> a <?php echo fnc_hora_a(15) ?> Horas)</label>
				</div>
			</div>
			<div class="col-md-3 divEtapas">
				<div class="col-md-1 etapa">
					<p class="numEtapa">6b</p>
				</div>
				<div class="col-md-2 divEtapasInput">
					<label class="etiquetaEtapa">LIBERACION CE A <?php echo fnc_rango_a(15) ?> MAX</label>
					<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" id="txtCeLib" class="form-control" placeholder="Ce liberacion" name="txtCeLib" value="<?php if ($reg_lib['prol_ce'] == '') {
																																																echo "";
																																																$str_prop = '';
																																															} else {
																																																echo $reg_lib['prol_ce'];
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
					<!--<input  type="text" id="inputPassword" class="form-control" placeholder="Firma LCP">-->
				</div>
			</div>
		</div>

		<!--textareaobservaciones-->
		<div class="row">
			<div class="col-md-7 textareaObservaciones">
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

		<div class="row" style="margin-bottom: 10px">
			<label style="font-weight:bold; margin-left:20px;">El agua de este roceso se manda a agua recuperada semilimpia(pila 1</label>
		</div>

		<div class="row" style="margin-bottom: 10px">
			<i><label style="margin-left:20px;color: red"> * Los campos "Tipo Agua" y "HR ini llenado" deben ser capturados juntos</label></i>
		</div>

		<!--barra botones-->
		<div class="row footerdivProcesos" style="margin-bottom: 10px;">
			<!--		<div class="col-md-6">El agua de este roceso se manda a agua recuperada semilimpia(pila 1)</div>-->

			<div class="form-group col-md-7">
				<div class="alert alert-info hide" id="alerta-errorFase6bOpe" style="height: 40px;width: 400px;text-align: left;z-index: 10;font-size: 10px;margin-bottom: -10px">
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
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE6b(<?php echo $reg_pro['pro_id'] ?>, 15);">
						<img src="../iconos/edit.png" alt="">Editar
					</button>
				</div>
				<div class="col-md-2" style="width: 110px">
					<button type="button" class="btn btn-success" id="permitir" onClick="javascript:AbreModalAgregarR(<?php echo $reg_pro['pro_id'] ?>, 15, <?php echo $valUltRen ?>);"> <img src="../iconos/add.png" alt="">Renglon
					</button>
				</div>
			<?php } ?>
			<div class="col-md-1" style="float: right;margin-right: 80px">
				<?php
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28  or $_SESSION['privilegio'] == 4) {
					if ($reg_fa['pfg6_id'] != '') {
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
	<div class="modal" id="modalRenglon6b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	<div class="modal" id="modalPaleto6b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	<div class="modal" id="modalEditar6b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

</div>