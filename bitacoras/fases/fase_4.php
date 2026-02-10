<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

/*$id_l = $_GET['id_l'];

$cad_pro = mysqli_query($cnx, "SELECT p.pro_id  
	FROM procesos as p
	WHERE p.pl_id = '$id_l' AND p.pro_estatus = 1");
$reg_pro = mysqli_fetch_array($cad_pro);*/

$reg_pro['pro_id'] = $idx_pro;

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 7");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_4_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 7");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 7");
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
	d = 4;
	x = 0;

	function agregarCampo4() {
		d++;
		x += 30;
		var div = document.createElement('div');
		div.setAttribute('class', 'row');
		div.setAttribute('style', 'padding-bottom:-110px');

		div.innerHTML =
			'<div  class="form-group col-md-2" style="width: 50px;padding-right: 80px"><label>' + d + ':' + x + '</label></div>' +
			'<div class="form-group col-md-1" style="width: 130px"><input class="form-control" name="txtAcido' + d + '" type="text"/></div>' +
			'<div  class="form-group col-md-2" style="width: 30px;padding-right: 80px" name="txtLts"><label>LTS</label></div>' +
			'<div class="form-group col-md-2" style="margin-right: 40px"><input class="form-control" name="txtPh' + d + '" type="text"/></div>' +
			'<div class="form-group col-md-2"><input class="form-control" name="txtTemp' + d + '" type="text"/></div>';
		document.getElementById('camposFase4').appendChild(div);
		document.getElementById('camposFase4').appendChild(div);
	}

	$(document).ready(function() {
		$("#formFase4").submit(function() {

			var formData = $(this).serialize();
			$.ajax({
				url: "fases/fase_4_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase4Ope", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase4').each(function() {
						this.reset();
					});
					setTimeout("location.reload()", 2000);
				}
			});
			return false;

		});

	});

	function AbreModalE4(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_4.php',
			data: datos,
			success: function(result) {
				$("#modalEditar4").html(result);
				$('#modalEditar4').modal('show')
			}
		});
		return false;
	}

	function quimicos_4(proceso, etapa) {
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
	<form autocomplete="off" id="formFase4" name="formFase4">
		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="7" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg4_id']; ?>" />

		<div class="headerdivProcesos">
			<div class="col-md-2">PRIMER ÁCIDO</div>
			<div class="col-md-4">Este proceso se puede hacer con agua de depositos de agua acida (Recupera de 2do ácido)</div>
		</div>

		<!--tiempos-->
		<div class="row" style="margin-bottom: 10px">
			<label class="col-md-1" style="width: 150px;">Fecha que inicio</label>
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
			<label class="col-md-1" style="width: 165px">Temp agua utilizada</label>
			<div class="col-md-2 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp" name="txtTemp" placeholder="TEMP" value="<?php if ($reg_fa['pfg4_temp_ag'] == '') {
																																										echo "";
																																										$str_prop = '';
																																									} else {
																																										echo $reg_fa['pfg4_temp_ag'];
																																										$str_prop = 'disabled';
																																									} ?>" <?php echo $str_prop;
																														echo " " . $strProp2;
																														echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-1" style="width: 90px">Enzima</label>
			<div class="col-md-1 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtEnzima" name="txtEnzima" placeholder="ENZIMA" value="<?php if ($reg_fa['pfg4_enzima'] == '') {
																																											echo "";
																																											$str_prop = '';
																																										} else {
																																											echo $reg_fa['pfg4_enzima'];
																																											$str_prop = 'disabled';
																																										} ?>" <?php echo $str_prop;
																															echo " " . $strProp2;
																															echo " " . $strProp3; ?> required>
			</div>
		</div>
		<!---->

		<!---->
		<div class="row" style="margin-bottom: 30px">
			<label class="col-md-1" style="width: 50px">TEMP</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp2" name="txtTemp2" placeholder="TEMP" value="<?php if ($reg_fa['pfg4_temp'] == '') {
																																										echo "";
																																										$str_prop = '';
																																									} else {
																																										echo $reg_fa['pfg4_temp'];
																																										$str_prop = 'disabled';
																																									} ?>" <?php echo $str_prop;
																														echo " " . $strProp2;
																														echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-1" style="width: 50px">ACIDO</label>
			<div class="col-md-1" style="margin-right: -25px">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtAcido" name="txtAcido" placeholder="ACIDO" value="<?php if ($reg_fa['pfg4_acido'] == '') {
																																										echo "";
																																										$str_prop = '';
																																									} else {
																																										echo $reg_fa['pfg4_acido'];
																																										$str_prop = 'disabled';
																																									} ?>" <?php echo $str_prop;
																															echo " " . $strProp2;
																															echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-1">LTS</label>
			<label class="col-md-2" style="width: 170px">SOL ACIDA FUERTE</label>
			<div class="col-md-2">
				<!--<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtAcidoF" name="txtAcidoF" placeholder="ACIDO" value="<?php  //if($reg_fa['pfg4_acido_fuerte'] == ''){echo ""; $str_prop = '';}else{echo $reg_fa['pfg4_acido_fuerte']; $str_prop = 'disabled';} 
																																											?>" <?php //echo $str_prop; echo " ".$strProp2; echo " ".$strProp3; 
																															?> required>-->

				<select type="text" id="cbxAcidoF" class="form-control" placeholder="Acido" name="cbxAcidoF" <?php if ($reg_fa['pfg4_acido_fuerte'] == '') {
																													echo "";
																													$str_prop = '';
																												} else {
																													$str_prop = 'disabled';
																												} ?> <?php echo $str_prop;
																														echo " " . $strProp2;
																														echo " " . $strProp3; ?> required>
					<?php if ($reg_fa['pfg4_acido_fuerte'] != '') {
						echo "<option value='$reg_fa[pfg4_acido_fuerte]'>$reg_fa[pfg4_acido_fuerte]</option>";
					} ?>
					<option value="">Acida fuerte</option>
					<option value="SI">SI</option>
					<option value="NO">NO</option>
				</select>
			</div>
			<label class="col-md-1" style="width: 70px">TERMINA</label>
			<div class="col-md-2">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTermina" name="txtTermina" placeholder="TERMINA" value="<?php if ($reg_fa['pfg4_termina'] == '') {
																																												echo "";
																																												$str_prop = '';
																																											} else {
																																												echo $reg_fa['pfg4_termina'];
																																												$str_prop = 'disabled';
																																											} ?>" <?php echo $str_prop;
																																echo " " . $strProp2;
																																echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-1" style="width: 50px">TEMP</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp3" name="txtTemp3" placeholder="TEMP" value="<?php if ($reg_fa['pfg4_temp2'] == '') {
																																										echo "";
																																										$str_prop = '';
																																									} else {
																																										echo $reg_fa['pfg4_temp2'];
																																										$str_prop = 'disabled';
																																									} ?>" <?php echo $str_prop;
																														echo " " . $strProp2;
																														echo " " . $strProp3; ?> required>
			</div>
		</div>
		<div class="col-md-6">
			<!---->
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr class="etiqueta_tbl">
					<td width="50">Ajust</td>
					<td>Acido</td>
					<td>&nbsp;</td>
					<td>Ph lado A</td>
					<td>Ph lado B</td>
					<td>TEMP</td>
					<td>PPM</td>
				</tr>
				<?php
				for ($i = 1; $i <= 8; $i++) {

					if ($i == 1) {
						$val = '0:30';
					}
					if ($i == 2) {
						$val = '1:00';
					}
					if ($i == 3) {
						$val = '1:30';
					}
					if ($i == 4) {
						$val = '2:00';
					}
					if ($i == 5) {
						$val = '2:30';
					}
					if ($i == 6) {
						$val = '3:00';
					}
					if ($i == 7) {
						$val = '3:30';
					}
					if ($i == 8) {
						$val = '4:00';
					}

					$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_4_d WHERE pfg4_id = '$reg_fa[pfg4_id]' and pfd4_ren = '$i' ");
					$reg_fad = mysqli_fetch_array($cad_fad);
				?>
					<tr>
						<td>&nbsp;<?php echo $val; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtAcidoF" . $i ?>" name="<?php echo "txtAcidoF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd4_acido'] == '') {
																																																						echo "";
																																																						$str_prop = '';
																																																					} else {
																																																						echo $reg_fad['pfd4_acido'];
																																																						$str_prop = 'disabled';
																																																					} ?>" <?php echo $str_prop;
																																									echo " " . $strProp2;
																																									echo " " . $strProp3;
																																									echo " " . $strProp4; ?> placeholder="Acido"></td>
						<td>LTS</td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtPhF" . $i ?>" name="<?php echo "txtPhF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd4_ph'] == '') {
																																																				echo "";
																																																				$str_prop = '';
																																																			} else {
																																																				echo $reg_fad['pfd4_ph'];
																																																				$str_prop = 'disabled';
																																																			} ?>" <?php echo $str_prop;
																																							echo " " . $strProp2;
																																							echo " " . $strProp3;
																																							echo " " . $strProp4; ?> placeholder="pH"></td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtPhBF" . $i ?>" name="<?php echo "txtPhBF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd4_ph_b'] == '') {
																																																					echo "";
																																																					$str_prop = '';
																																																				} else {
																																																					echo $reg_fad['pfd4_ph_b'];
																																																					$str_prop = 'disabled';
																																																				} ?>" <?php echo $str_prop;
																																								echo " " . $strProp2;
																																								echo " " . $strProp3;
																																								echo " " . $strProp4; ?> placeholder="pH B"></td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtTempF" . $i ?>" name="<?php echo "txtTempF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd4_temp'] == '') {
																																																					echo "";
																																																					$str_prop = '';
																																																				} else {
																																																					echo $reg_fad['pfd4_temp'];
																																																					$str_prop = 'disabled';
																																																				} ?>" <?php echo $str_prop;
																																								echo " " . $strProp2;
																																								echo " " . $strProp3;
																																								echo " " . $strProp4; ?> placeholder="Temp"></td>
						<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtPpm" . $i ?>" name="<?php echo "txtPpm" . $i ?>" value="<?php if ($reg_fad['pfd4_ppm'] == '') {
																																																		echo "";
																																																		$str_prop = '';
																																																	} else {
																																																		echo $reg_fad['pfd4_ppm'];
																																																		$str_prop = 'disabled';
																																																	} ?>" <?php echo $str_prop;
																																					echo " " . $strProp2;
																																					echo " " . $strProp3;
																																					echo " " . $strProp4; ?> size="5" placeholder="PPM"></td>
					</tr>
				<?php } ?>
			</table>
		</div>


		<div class="col-md-6">
			<div class="col-md-3 divEtapas4">
				<div class="col-md-1 etapa4">
					<p class="numEtapa" style="margin-top: 80px">4</p>
				</div>
				<div class="col-md-2 divEtapasInput4">
					<label class="etiquetaEtapa">Nota: La adición de ácido se agrega por los dos lados del lavador y mantener ph 3.0 a 5 durante todo el proceso del primer ácido</label>
					<!--<input  type="text" id="txtAdelgasamiento" name="txtAdelgasamiento" class="form-control" placeholder="Adelgasamiento" value="<?php /*if($reg_lib['prol_adelgasamiento'] == ''){ echo ""; $str_prop = '';}else{echo $reg_lib['prol_adelgasamiento']; $str_prop = 'disabled';} ?>" <?php echo $str_prop; echo " ".$strProp1; echo " ".$strProp2;*/ ?> >-->

					<select type="text" id="cbxAdel" class="form-control" placeholder="Adelgasamiento" name="cbxAdel" <?php if ($reg_lib['prol_adelgasamiento'] == '') {
																															echo "";
																															$str_prop = '';
																														} else {
																															echo "<option value='$reg_lib[prol_adelgasamiento]'>$reg_lib[prol_adelgasamiento]</option>";
																															$str_prop = 'disabled';
																														} ?> <?php echo $str_prop;
																																echo " " . $strProp1;
																																echo " " . $strProp2; ?>>
						<option value="">Adelgazamiento</option>
						<option value="SI">SI</option>
						<option value="NO">NO</option>
					</select>
					<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" id="txtPhLib" class="form-control" placeholder="Ph promedio" name="txtPhLib" value="<?php if ($reg_lib['prol_ph'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_lib['prol_ph'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																	echo " " . $strProp1;
																																	echo " " . $strProp2; ?>>
					<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" id="txtHrTotales" class="form-control" placeholder="Horas totales" name="txtHrTotales" value="<?php if ($reg_lib['prol_hr_totales'] == '') {
																																															echo "";
																																															$str_prop = '';
																																														} else {
																																															echo $reg_lib['prol_hr_totales'];
																																															$str_prop = 'disabled';
																																														} ?>" <?php echo $str_prop;
																																				echo " " . $strProp1;
																																				echo " " . $strProp2; ?>>
					<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" id="txtUsuario" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
					<!--<input  type="text" id="inputPassword" class="form-control" placeholder="Firma LCP">-->
				</div>
			</div>
		</div>


		<!--estilo general de estapas-->
		<div class="col-md-12" style="margin-top: 10px">
			<div class="col-md-2">
				<label for="inputPassword4">Cocido cuero ph(6.0)</label>
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtCocido" placeholder="Cocido" name="txtCocido" value="<?php if ($reg_fa['pfg4_cocido_ph'] == '') {
																																											echo "";
																																											$str_prop = '';
																																										} else {
																																											echo $reg_fa['pfg4_cocido_ph'];
																																											$str_prop = 'disabled';
																																										} ?>" <?php echo $str_prop;
																															echo " " . $strProp6;
																															echo " " . $strProp3 . " " . $strProp4; ?>>
			</div>
			<div class="col-md-2">
				<label for="inputPassword4">Ce</label>
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtCeG" placeholder="Ce" name="txtCeG" value="<?php if ($reg_fa['pfg4_ce'] == '') {
																																									echo "";
																																									$str_prop = '';
																																								} else {
																																									echo $reg_fa['pfg4_ce'];
																																									$str_prop = 'disabled';
																																								} ?>" <?php echo $str_prop;
																													echo " " . $strProp6;
																													echo " " . $strProp3 . " " . $strProp4; ?>>
			</div>
			<div class="col-md-2">
				<label for="inputPassword4">Fecha term 1er acidif.</label>
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
			<div class="col-md-2">
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
			<div class="col-md-2">
				<label for="inputPassword4">Realizó</label>
				<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="inputPassword4" placeholder="">
			</div>
		</div>-->
			<div class="col-md-4">
				<label for="inputPassword4">MANTENER PH <?php echo fnc_rango_de(7) ?> - <?php echo fnc_rango_a(7) ?> (<?php echo fnc_hora_de(7) ?> a <?php echo fnc_hora_a(7) ?> hrs. mov.cont)</label>
			</div>
		</div>
		<!---->

		<!--textareaobservaciones-->
		<div class="row">
			<div class="col-md-7 textareaObservaciones" style="margin-top: 0px">
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
		<div class="row footerdivProcesos" style="margin-bottom: 10px">
			<div class="form-group col-md-8">
				<div class="alert alert-info hide" id="alerta-errorFase4Ope" style="height: 40px;width: 270px;text-align: left;z-index: 10;font-size: 10px;">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>
			<div class="form-group col-md-4" style="text-align: right;margin-left:  -40px">
				<?php if ($_SESSION['privilegio'] == 4) {	?>
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE4(<?php echo $reg_pro['pro_id'] ?>, 7);">
						<img src="../iconos/edit.png" alt="">Editar
					</button>
				<?php } ?>
				<?php
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28  or $_SESSION['privilegio'] == 4) {
					if ($reg_fa['pfg4_id'] != '') {
				?>
						<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
					<?php
					} else {
						echo "N/A Guardar";
					}
				} else { ?>
					<button type="button" class="btn btn-info" onClick="javascript:quimicos_4(<?php echo $reg_pro['pro_id'] ?>, <?php echo $reg_et['pe_id'] ?> );"><img src="../iconos/matraz.png" alt="">Químicos</button>

					<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
				<?php } ?>
			</div>
		</div>

	</form>

	<div class="modal" id="modalEditar4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
	<div class="modal" id="m_modal_quimicos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>


</div>