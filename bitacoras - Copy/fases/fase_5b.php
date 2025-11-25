<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

$reg_pro['pro_id'] = $idx_pro;

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 11");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_5b_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 11");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 11");
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
		$("#formFase5b").submit(function() {

			var formData = $(this).serialize();
			$.ajax({
				url: "fases/fase_5b_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase5bOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase5b').each(function() {
						this.reset();
					});
					setTimeout("location.reload()", 2000);
				}
			});
			return false;

		});

	});

	function AbreModalE5b(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_5b.php',
			data: datos,
			success: function(result) {
				$("#modalEditar5b").html(result);
				$('#modalEditar5b').modal('show')
			}
		});
		return false;
	}

	//agregado 16-10-21
	function quimicos_5b(proceso, etapa) {
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
	<form autocomplete="off" id="formFase5b" name="formFase5b">
		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="11" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg5_id']; ?>" />

		<div class="headerdivProcesos">
			<div class="col-md-2">PRIMER ÁCIDO</div>
			<div class="col-md-4"></div>
			<div class="col-md-5"></div>
		</div>

		<!--tiempos-->
		<div class="row" style="margin-bottom: 20px">
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

			<label class="col-md-1" style="width: 165px">Temp agua inicial</label>
			<div class="col-md-2 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp" name="txtTemp" placeholder="TEMP" value="<?php if ($reg_fa['pfg5_temp_ag'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa['pfg5_temp_ag'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
			</div>
		</div>
		<div class="row" style="margin-bottom: 20px">
			<!--<label class="col-md-1" >Ajuste</label>-->
			<label class="col-md-1">Temp</label>
			<div class="col-md-1 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp2" name="txtTemp2" placeholder="TEMP" value="<?php if ($reg_fa['pfg5_temp'] == '') {
																																														echo "";
																																														$str_prop = '';
																																													} else {
																																														echo $reg_fa['pfg5_temp'];
																																														$str_prop = 'disabled';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3; ?> required>
			</div>
			<label class="col-md-1">Ácido</label>
			<div class="col-md-1 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtAcido" name="txtAcido" placeholder="Acido" value="<?php if ($reg_fa['pfg5_acido'] == '') {
																																														echo "";
																																														$str_prop = '';
																																													} else {
																																														echo $reg_fa['pfg5_acido'];
																																														$str_prop = 'disabled';
																																													} ?>" <?php echo $str_prop;
																																															echo " " . $strProp2;
																																															echo " " . $strProp3; ?> required>
			</div>

			<label class="col-md-1">Lts</label>
			<label class="col-md-1">Termina</label>
			<div class="col-md-1 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTermina" name="txtTermina" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" placeholder="Termina" value="<?php if ($reg_fa['pfg5_termina'] == '') {
																																																																echo "";
																																																																$str_prop = '';
																																																															} else {
																																																																echo $reg_fa['pfg5_termina'];
																																																																$str_prop = 'disabled';
																																																															} ?>" <?php echo $str_prop;
																																																																	echo " " . $strProp2;
																																																																	echo " " . $strProp3; ?> required>
			</div>
			<div class="col-md-4">
				<label for="inputPassword3" style="margin-bottom: 0px">MANTENER UN PH 3.0 A 5.0 DURANTE TODO EL PRECESO</label>
			</div>
		</div>
		<!---->

		<div class="row">
			<div class="form-row">
				<div class="col-md-3">
					<!---->
					<table border="0" cellspacing="0" cellpadding="0">
						<tr class="etiqueta_tbl">
							<td width="50">Ajust</td>
							<td>Ph</td>
							<td>Acido</td>
						</tr>
						<?php
						for ($i = 1; $i <= 4; $i++) {

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
							$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_5b_d WHERE pfg5_id = '$reg_fa[pfg5_id]' and pfd5_ren = '$i' ");
							$reg_fad = mysqli_fetch_array($cad_fad);
						?>
							<tr>
								<td>&nbsp;<?php echo $val; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
								<td>
									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtPhF" . $i ?>" name="<?php echo "txtPhF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd5_ph'] == '') {
																																																										echo "";
																																																										$str_prop = '';
																																																									} else {
																																																										echo $reg_fad['pfd5_ph'];
																																																										$str_prop = 'disabled';
																																																									} ?>" <?php echo $str_prop;
																																																											echo " " . $strProp2;
																																																											echo " " . $strProp3;
																																																											echo " " . $strProp4; ?> placeholder="pH">
								</td>

								<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtAcidoF" . $i ?>" name="<?php echo "txtAcidoF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd5_acido'] == '') {
																																																											echo "";
																																																											$str_prop = '';
																																																										} else {
																																																											echo $reg_fad['pfd5_acido'];
																																																											$str_prop = 'disabled';
																																																										} ?>" <?php echo $str_prop;
																																																												echo " " . $strProp2;
																																																												echo " " . $strProp3;
																																																												echo " " . $strProp4; ?> placeholder="Acido"></td>
								<td>LTS</td>

							</tr>
						<?php } ?>
					</table>
				</div>
				<div class="col-md-3">
					<!---->
					<table border="0" cellspacing="0" cellpadding="0">
						<tr class="etiqueta_tbl">
							<td width="50">Ajust</td>
							<td>Ph</td>
							<td>Acido</td>
						</tr>
						<?php
						for ($i = 5; $i <= 8; $i++) {
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

							$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_5b_d WHERE pfg5_id = '$reg_fa[pfg5_id]' and pfd5_ren = '$i' ");
							$reg_fad = mysqli_fetch_array($cad_fad);
						?>
							<tr>
								<td>&nbsp;<?php echo $val; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
								<td>
									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtPhF" . $i ?>" name="<?php echo "txtPhF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd5_ph'] == '') {
																																																										echo "";
																																																										$str_prop = '';
																																																									} else {
																																																										echo $reg_fad['pfd5_ph'];
																																																										$str_prop = 'disabled';
																																																									} ?>" <?php echo $str_prop;
																																																											echo " " . $strProp2;
																																																											echo " " . $strProp3;
																																																											echo " " . $strProp4; ?> placeholder="pH">
								</td>

								<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="<?php echo "txtAcidoF" . $i ?>" name="<?php echo "txtAcidoF" . $i ?>" size="5" value="<?php if ($reg_fad['pfd5_acido'] == '') {
																																																											echo "";
																																																											$str_prop = '';
																																																										} else {
																																																											echo $reg_fad['pfd5_acido'];
																																																											$str_prop = 'disabled';
																																																										} ?>" <?php echo $str_prop;
																																																												echo " " . $strProp2;
																																																												echo " " . $strProp3;
																																																												echo " " . $strProp4; ?> placeholder="Acido"></td>
								<td>LTS</td>

							</tr>
						<?php } ?>
					</table>
				</div>
			</div>
			<div class="col-md-4" style="height: 140px;border-radius: 5px;border: 1px solid #e6e6e6;float: right;margin-right: 40px;">
				<div class="col-md-1" style="height: 140px;border-radius: 5px;border: 1px solid #e6e6e6;width: 80px;margin-left: -15px;">
					<p class="numEtapa">5b</p>
				</div>
				<div class="col-md-9" style="height: 25px;padding: 0px;background: #e6e6e6;">
					<!-- <label class="etiquetaEtapa">Nota: La adición de ácido se agrega por los dos lados del lavador y mantener el PH <?php echo fnc_rango_de(11) ?> - <?php echo fnc_rango_a(11) ?> durante todo el proceso de 1er ácido</label> -->
					<select type="text" id="cbxAdel" class="form-control" placeholder="Adelgasamiento" name="cbxAdel" <?php if ($reg_lib['prol_adelgasamiento'] == '') {
																															echo "";
																															$str_prop = '';
																														} else {
																															$str_prop = 'disabled';
																														} ?> <?php echo $str_prop;
																																echo " " . $strProp1;
																																echo " " . $strProp2; ?>>
						<?php if ($reg_lib['prol_adelgasamiento'] != '') {
							echo "<option value='$reg_lib[prol_adelgasamiento]'>$reg_lib[prol_adelgasamiento]</option>";
						} ?>
						<option value="">Adelgazamiento</option>
						<option value="SI">SI</option>
						<option value="NO">NO</option>
					</select>
					<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" id="txtPhLib" class="form-control" placeholder="Ph promedio" name="txtPhLib" value="<?php if ($reg_lib['prol_ph'] == '') {
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
					<input maxlength="6" onKeyPress="return isNumberKey(event, this);" type="text" id="txtUsuario" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
					<!---<input  type="text" id="inputPassword" class="form-control" placeholder="Firma LCP">-->
				</div>
			</div>
		</div>


		<div class="row" id="campos"></div>


		<!--estilo general de estapas-->
		<div class="row">
			<div class="form-row">
				<div class="form-group col-md-3">
					<label for="inputPassword4">Fecha termina 1er acidificación</label>
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
				<div class="col-md-4 ">
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
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="inputPassword4" style="color: #fff">.............................</label>
					<label for="inputPassword4">(<?php echo fnc_hora_de(13) ?> a <?php echo fnc_hora_a(13) ?> HORAS MOV. CONT.)</label>
				</div>
			</div>
		</div>
		<!---->

		<!--barra botones-->
		<div class="row footerdivProcesos" style="margin-bottom: 10px">
			<div class="col-md-5">

			</div>
			<div class="form-group col-md-4">
				<div class="alert alert-info hide" id="alerta-errorFase5bOpe" style="height: 40px;width: 270px;text-align: left;z-index: 10;font-size: 10px;">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>
			<div class="col-md-3" style="text-align: right;margin-left:  -40px">

				<?php if ($_SESSION['privilegio'] == 4) {	?>
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE5b(<?php echo $reg_pro['pro_id'] ?>, 11);">
						<img src="../iconos/edit.png" alt="">Editar
					</button>
				<?php } ?>
				<?php
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 4) {
					if ($reg_fa['pfg5_id'] != '') {
				?>
						<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
					<?php
					} else {
						echo "N/A Guardar";
					}
				} else { ?>
					<button type="button" class="btn btn-info" onClick="javascript:quimicos_5b(<?php echo $reg_pro['pro_id'] ?>, <?php echo $reg_et['pe_id'] ?> );"><img src="../iconos/matraz.png" alt="">Químicos</button>
					<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
				<?php } ?>
			</div>

		</div>

	</form>

	<div class="modal" id="modalEditar5b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
	<div class="modal" id="m_modal_quimicos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

</div>