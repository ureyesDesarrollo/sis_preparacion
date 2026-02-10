<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/
//require 'funciones_procesos.php';

/*$id_l = $_GET['id_l'];

$cad_pro = mysqli_query($cnx, "SELECT p.pro_id  
	FROM procesos as p
	WHERE p.pl_id = '$id_l' AND p.pro_estatus = 1");
	$reg_pro = mysqli_fetch_array($cad_pro);*/

$reg_pro['pro_id'] = $idx_pro;

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 28");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 28");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 28");
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
	$(document).ready(function() {
		$("#formFase2e").submit(function() {
			var formData = $(this).serialize();
			$.ajax({
				url: "fases/fase_2e_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase2eOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase2e').each(function() {
						this.reset();
					});
					setTimeout("location.reload()", 2000);
				}
			});
			return confirmEnviarFase2e();
			return false;
		});
	});

	//Bloquear boton al agregar material
	function confirmEnviarFase2e() {

		formFase2e.btn.disabled = true;
		formFase2e.btn.value = "Enviando...";

		setTimeout(function() {
			formFase2e.btn.disabled = true;
			formFase2e.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}

	function AbreModalE2e(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_2e.php',
			data: datos,
			success: function(result) {
				$("#modalEditar2e").html(result);
				$('#modalEditar2e').modal('show')
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
				$("#modalRenglon2e").html(result);
				$('#modalRenglon2e').modal('show')
			}
		});
		return false;
	}

	//agregado 16-10-21
	function quimicos_2e(proceso, etapa) {
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
	<form autocomplete="off" id="formFase2e" name="formFase2e">

		<input type="hidden" value="<?php echo $_GET['id_e'] ?>" name="txt_equipo">

		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="28" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg2_id']; ?>" />

		<div class="headerdivProcesos">
			<div class="col-md-2">BLANQUEO</div>
			<div class="col-md-10">Este proceso se puede hacer con aguar recuperada limpia (pila 1) y agua limpia</div>
		</div>
		<?php //if($_SESSION['privilegio'] == 3 ){
		?>
		<div class="row" style="margin-bottom: 10px">
			<label class="col-md-1" style="width: 160px;">Fecha que inicia</label>
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

			<label class="col-md-1">Hora inicio</label>
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



			<label class="col-md-1" style="width: 160px">pH antes de ajuste</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtPhAnt" placeholder="" name="txtPhAnt" value="<?php if ($reg_fa['pfg2_ph_ant'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa['pfg2_ph_ant'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
			</div>

			<label class="col-md-1" style="width: 40px">CE</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtCe" placeholder="" name="txtCe" value="<?php if ($reg_fa['pfg2_ce'] == '') {
																																											echo "";
																																											$str_prop = '';
																																										} else {
																																											echo $reg_fa['pfg2_ce'];
																																											$str_prop = 'disabled';
																																										} ?>" <?php echo $str_prop;
																																												echo " " . $strProp2;
																																												echo " " . $strProp3; ?> required>
			</div>
		</div>
		<div class="row" style="margin-bottom: 10px">
			<label class="col-md-1" style="width: 160px">Ajuste con SOSA</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtAjSosa" placeholder="" name="txtAjSosa" value="<?php if ($reg_fa['pfg2_sosa'] == '') {
																																													echo "";
																																													$str_prop = '';
																																												} else {
																																													echo $reg_fa['pfg2_sosa'];
																																													$str_prop = 'disabled';
																																												} ?>" <?php echo $str_prop;
																																														echo " " . $strProp2;
																																														echo " " . $strProp3; ?> required>
			</div>

			<label class="col-md-1" style="width: 210px">pH ajustado (11.9 A 12.3)</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtPhAj" placeholder="" name="txtPhAj" value="<?php if ($reg_fa['pfg2_ph_aju'] == '') {
																																												echo "";
																																												$str_prop = '';
																																											} else {
																																												echo $reg_fa['pfg2_ph_aju'];
																																												$str_prop = 'disabled';
																																											} ?>" <?php echo $str_prop;
																																													echo " " . $strProp2;
																																													echo " " . $strProp3; ?> required>
			</div>

			<label class="col-md-1">Peroxido</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtPeroxido" placeholder="LTS" name="txtPeroxido" value="<?php if ($reg_fa['pfg2_peroxido'] == '') {
																																															echo "";
																																															$str_prop = '';
																																														} else {
																																															echo $reg_fa['pfg2_peroxido'];
																																															$str_prop = 'disabled';
																																														} ?>" <?php echo $str_prop;
																																																echo " " . $strProp2;
																																																echo " " . $strProp3; ?> required>
			</div>
		</div>

		<div class="col-md-7">
			<table border="0" cellspacing="5" cellpadding="5">
				<tr class="etiqueta_tbl">
					<td></td>
					<td>Hora</td>
					<td>Ph</td>
					<td align="center" bgcolor="#FF6493">REDOX</td>
					<td rowspan="11">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>MIN 340 PPM</td>
							</tr>
						</table>
					</td>
					<td></td>
				</tr>
				<?php
				for ($i = 1; $i <= 25; $i++) {
					if ($i > 4) {
						$i += 1;
					}

					$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_d WHERE pfg2_id = '$reg_fa[pfg2_id]' and pfd2_ren = '$i' ");
					$reg_fad = mysqli_fetch_array($cad_fad);

					$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 28);

					if ($i <= 16 or $bolVal == 'Si') {
				?>
						<tr>
							<td><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
							<td>
								<input type="time" class="form-control" id="txtHr<?php echo $i; ?>" placeholder="" name="txtHr<?php echo $i; ?>" value="<?php
																																						if ($reg_fad['pfd2_hr'] == '') {
																																							echo date("H:i");
																																							$str_prop = '';
																																						} else {
																																							echo $reg_fad['pfd2_hr'];
																																							$str_prop = 'disabled';
																																						} ?>" <?php
																																								echo $str_prop;
																																								echo " " . $strProp2;
																																								echo " " . $strProp3;
																																								echo " " . $strProp4;
																																								?>>
							</td>
							<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtPh<?php echo $i; ?>" placeholder="pH" name="txtPh<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_ph'] == '') {
																																																										echo "";
																																																										$str_prop = '';
																																																									} else {
																																																										echo $reg_fad['pfd2_ph'];
																																																										$str_prop = 'disabled';
																																																									} ?>" <?php echo $str_prop;
																																																											echo " " . $strProp2;
																																																											echo " " . $strProp3;
																																																											echo " " . $strProp4; ?>></td>


							<td align="center" bgcolor="#FF6493">
								<?php
								if ($i != 6 && $i != 10 && $i != 14) { ?>
									<input style="display: inline;width:100px" type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtRedox<?php echo $i; ?>" placeholder="Redox" name="txtRedox<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_redox'] == '') {
																																																																							echo "";
																																																																							$str_prop = '';
																																																																						} else {
																																																																							echo $reg_fad['pfd2_redox'];
																																																																							$str_prop = 'disabled';
																																																																						} ?>" <?php echo $str_prop;
																																																																								echo " " . $strProp2;
																																																																								echo " " . $strProp3;
																																																																								echo " " . $strProp4; ?>>

								<?php echo "<span style='display: inline;'>PPM</span>";
								} ?>

							</td>

							<td>
								<?php if ($i == 1 || $i == 3 || $i == 8) { ?>
									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtSosa<?php echo $i; ?>" placeholder="LTS SOSA" name="txtSosa<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_sosa'] == '') {
																																																														echo "";
																																																														$str_prop = '';
																																																													} else {
																																																														echo $reg_fad['pfd2_sosa'];
																																																														$str_prop = 'disabled';
																																																													} ?>" <?php echo $str_prop;
																																																															echo " " . $strProp2;
																																																															echo " " . $strProp3;
																																																															echo " " . $strProp4; ?>>
								<?php } ?>
								<?php if ($i == 2 || $i == 4 || $i == 10) { ?>
									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp<?php echo $i; ?>" placeholder="TEMP" name="txtTemp<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_temp'] == '') {
																																																													echo "";
																																																													$str_prop = '';
																																																												} else {
																																																													echo $reg_fad['pfd2_temp'];
																																																													$str_prop = 'disabled';
																																																												} ?>" <?php echo $str_prop;
																																																														echo " " . $strProp2;
																																																														echo " " . $strProp3;
																																																														echo " " . $strProp4; ?>>
								<?php } ?>
							</td>

						</tr>
				<?php $valUltRen = $i + 1;
					} //termina if

				} // termina for
				?>
			</table>
		</div>
		<!-- LIBERACION -->
		<div class="col-md-4">
			<div class="row" style="border-radius: 5px;border:1px solid #e6e6e6;">
				<div class="col-md-3" style="border-radius: 5px;border:1px solid #e6e6e6;height:140px">
					<p class="numEtapa">2e</p>
				</div>
				<div class="col-md-9">
					<label class="etiquetaEtapa">LIBERACION pH <?php echo fnc_rango_de(28) ?> - <?php echo fnc_rango_a(28) ?> <label for="inputPassword4">(<?php echo fnc_hora_de(4) ?> a <?php echo fnc_hora_a(4) ?> Horas)</label></label>
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
					<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" id="txtPhLib" class="form-control" placeholder="pH liberacion" name="txtPhLib" value="<?php if ($reg_lib['prol_ph'] == '') {
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
					<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" id="inputPassword" class="form-control" placeholder="Nombre LCP" value="<?php echo fnc_nom_usu($_SESSION['idUsu']); ?>" readonly="">
				</div>
			</div>

			<div class="row" style="margin-top: 1rem;">
				<div class="form-group">
					<label for="inputPassword4">Fecha termina</label>
					<input style="width: 120px;;display:inline" type="date" class="form-control" id="txtFeTerm" placeholder="" name="txtFeTerm" value="<?php if ($reg_aux['proa_fe_fin'] == '') {
																																							echo "";
																																							$str_prop = '';
																																						} else {
																																							echo $reg_aux['proa_fe_fin'];
																																							$str_prop = 'disabled';
																																						} ?>" <?php echo $str_prop;
																																								echo " " . $strProp6;
																																								echo " " . $strProp3 . " " . $strProp4; ?>>
				</div>
				<div class="form-group">
					<label for="inputPassword4">Hora termina</label>
					<input style="width: 120px;;display:inline" type="time" class="form-control" id="txtHrTerm" placeholder="" name="txtHrTerm" value="<?php if ($reg_aux['proa_hr_fin'] == '') {
																																							echo "";
																																							$str_prop = '';
																																						} else {
																																							echo $reg_aux['proa_hr_fin'];
																																							$str_prop = 'disabled';
																																						} ?>" <?php echo $str_prop;
																																								echo " " . $strProp6;
																																								echo " " . $strProp3 . " " . $strProp4; ?>>
				</div>
				<div class="form-group">
					<label for="">Observaciones</label>
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
		</div>
		<div class="row" id="camposFase2">

		</div>

		<!--barra botones-->
		<div class="row footerdivProcesos" style="margin-bottom: 10px">
			<div class="col-md-5">
				<label style="font-weight:bold; margin-left:20px;">&nbsp;</label>
			</div>

			<div class="form-group col-md-3">
				<div class="alert alert-info hide" id="alerta-errorFase2eOpe" style="height: 40px;width: 300px;text-align: left;z-index: 10;font-size: 10px;">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>
			<div class="col-md-4" style="text-align: right;margin-left:  -40px">
				<?php if ($_SESSION['privilegio'] == 4) {	?>
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE2e(<?php echo $reg_pro['pro_id'] ?>, 28);">
						<img src="../iconos/edit.png" alt="">Editar
					</button>

					<button type="button" class="btn btn-success" id="permitir" onClick="javascript:AbreModalAgregarR(<?php echo $reg_pro['pro_id'] ?>, 28, <?php echo $valUltRen ?>);"> <img src="../iconos/add.png" alt="">Renglon
					</button>
				<?php } ?>
				<?php
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28  or $_SESSION['privilegio'] == 4) {
					if ($reg_fa['pfg2_id'] != '') {
				?>
						<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
					<?php
					} else {
						echo "N/A Guardar";
					}
				} else { ?>
					<button type="button" class="btn btn-info" onClick="javascript:quimicos_2e(<?php echo $reg_pro['pro_id'] ?>, <?php echo $reg_et['pe_id'] ?> );"><img src="../iconos/matraz.png" alt="">Químicos</button>

					<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
				<?php } ?>
			</div>
		</div>


	</form>

	<div class="modal" id="modalEditar2e" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	<div class="modal" id="modalRenglon2e" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
	<div class="modal" id="m_modal_quimicos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

</div>

<!-- <style>
	.form-control {
		border: none;
		border-bottom: 1px solid#000;
		border-radius: 0px;
		outline: none;
		box-shadow: none;
	}
</style> -->