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

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 4");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 4");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 4");
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

	function agregarCampoFase2() {
		a++;
		var div = document.createElement('div');
		div.setAttribute('class', 'form-row');
		//div.setAttribute('style', 'margin-top:-20px');

		div.innerHTML =
			'<div  class="form-group col-md-1" style="width:100px"><label>' + a + 'ta Hora</label></div>' +
			'<div class="form-group col-md-1" ><input class="form-control" name="txtLavTipAgua' + a + '" type="text"/></div>' +
			'<label style="padding:0px" class="col-md-1">HR</label>' +
			'<label class="col-md-1" style="width:50px" >pH</label>' +
			'<div class="form-group col-md-1" ><input class="form-control" name="txtLavTipAgua' + a + '" type="text"/></div>' +
			'<label class="col-md-1">SOSA</label>' +
			'<div class="form-group col-md-1" ><input class="form-control" name="txtLavTipAgua' + a + '" type="text"/></div>' +
			'<label class="col-md-1">TEMP</label>' +
			'<div class="form-group col-md-1"><input class="form-control" name="txtPh' + a + '" type="text"/></div>';

		document.getElementById('camposFase2').appendChild(div);
		document.getElementById('camposFase2').appendChild(div);
	}

	$(document).ready(function() {
		$("#formFase2c").submit(function() {
			var formData = $(this).serialize();
			$.ajax({
				url: "fases/fase_2c_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase2cOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase2c').each(function() {
						this.reset();
					});
					//setTimeout(location.reload(), 23000);
					setTimeout("location.reload()", 2000);
				}
			});
			return confirmEnviarFase2c();
			return false;
		});
	});

	//Bloquear boton al agregar material
	function confirmEnviarFase2c() {

		formFase2c.btn.disabled = true;
		formFase2c.btn.value = "Enviando...";

		setTimeout(function() {
			formFase2c.btn.disabled = true;
			formFase2c.btn.value = "Guardar";
		}, 2000);

		var statSend = false;
		return false;
	}

	function AbreModalE2c(proceso, etapa) {
		var datos = {
			"pro_id": proceso,
			"pe_id": etapa
		}
		$.ajax({
			type: 'post',
			url: 'fases/editar/fase_2c.php',
			data: datos,
			success: function(result) {
				$("#modalEditar2c").html(result);
				$('#modalEditar2c').modal('show')
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
				$("#modalRenglon2c").html(result);
				$('#modalRenglon2c').modal('show')
			}
		});
		return false;
	}

	//agregado 16-10-21
	function quimicos_2c(proceso, etapa) {
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
	<form autocomplete="off" id="formFase2c" name="formFase2c">
		<!--<input type="hidden" value="<?php //echo $_GET['id_l'] 
										?>" name="txt_lavador">
		<input type="hidden" value="<?php //echo $_GET['id_p'] 
									?>" name="txt_paleto">-->
		<input type="hidden" value="<?php //echo $_GET['id_e'] 
									?>" name="txt_equipo">

		<input name="hdd_pro_id" type="hidden" value="<?php echo $reg_pro['pro_id'] ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="4" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo $reg_aux['proa_id']; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo $reg_fa['pfg2_id']; ?>" />

		<div class="headerdivProcesos">
			<div class="col-md-2">BLANQUEO</div>
			<div class="col-md-10"></div>
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

			<label class="col-md-1" style="width: 130px;">Hora inicio</label>
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

			<label class="col-md-1" style="width: 200px;">Temp agua utilizada</label>
			<div class="col-md-2 tiempos">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp" placeholder="" name="txtTemp" value="<?php if ($reg_fa['pfg2_temp_ag'] == '') {
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


		<div class="row" style="margin-bottom: 30px">
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
			<!--<label>LTS</label>-->

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

			<label class="col-md-1" style="width: 90px">Peroxido</label>
			<div class="col-md-1">
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtPeroxido" placeholder="" name="txtPeroxido" value="<?php if ($reg_fa['pfg2_peroxido'] == '') {
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
					<td>No.</td>
					<td>Hora</td>
					<td>Ph</td>
					<td>SOSA</td>
					<!--<td>Acido</td>-->
					<!-- <td>Peroxido</td> -->
					<td>TEMP</td>
					<td align="center" bgcolor="#FF6493">REDOX</td>
					<td>&nbsp;</td>

				</tr>
				<?php
				for ($i = 1; $i <= 25; $i++) {
					/* if ($i > 2) {
						$i += 1;
					} */

					if ($i == '1') {
						$etiqueta = "MIN 2000 PPM";
					} else if ($i == '4') {
						$etiqueta = "MIN 700 PPM";
					} else if ($i == '6') {
						$etiqueta = "MIN 300 PPM";
					} else {
						$etiqueta = "";
					}
					$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_2_d WHERE pfg2_id = '$reg_fa[pfg2_id]' and pfd2_ren = '$i' ");
					$reg_fad = mysqli_fetch_array($cad_fad);

					$bolVal = fnc_valida_renglon($i, $reg_pro['pro_id'], 4);

					if ($i <= 10 or $bolVal == 'Si') {
				?>
						<tr>
							<td><?php echo $i; ?><input type="hidden" class="form-control" id="<?php echo "txtRen" . $i ?>" value="<?php echo $i ?>" name="<?php echo "txtRen" . $i ?>"></td>
							<td><input type="time" class="form-control" id="txtHr<?php echo $i; ?>" placeholder="" name="txtHr<?php echo $i; ?>" value="<?php if ($reg_fad['pfd2_hr'] == '') {
																																							echo date("H:i");
																																							$str_prop = '';
																																						} else {
																																							echo $reg_fad['pfd2_hr'];
																																							$str_prop = 'disabled';
																																						} ?>" <?php echo $str_prop;
																																								echo " " . $strProp2;
																																								echo " " . $strProp3;
																																								echo " " . $strProp4; ?>></td>
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
							<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtSosa<?php echo $i; ?>" placeholder="Sosa" name="txtSosa<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_sosa'] == '') {
																																																												echo "";
																																																												$str_prop = '';
																																																											} else {
																																																												echo $reg_fad['pfd2_sosa'];
																																																												$str_prop = 'disabled';
																																																											} ?>" <?php echo $str_prop;
																																																													echo " " . $strProp2;
																																																													echo " " . $strProp3;
																																																													echo " " . $strProp4; ?>></td>


							<td><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtTemp<?php echo $i; ?>" placeholder="Temp" name="txtTemp<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_temp'] == '') {
																																																												echo "";
																																																												$str_prop = '';
																																																											} else {
																																																												echo $reg_fad['pfd2_temp'];
																																																												$str_prop = 'disabled';
																																																											} ?>" <?php echo $str_prop;
																																																													echo " " . $strProp2;
																																																													echo " " . $strProp3;
																																																													echo " " . $strProp4; ?>></td>
							<td align="center" bgcolor="#FF6493" width="100">
								<?php if ($i == '1' || $i == '4' || $i == '6') { ?><input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" id="txtRedox<?php echo $i; ?>" placeholder="Redox" name="txtRedox<?php echo $i; ?>" size="5" value="<?php if ($reg_fad['pfd2_redox'] == '') {
																																																																									echo "";
																																																																									$str_prop = '';
																																																																								} else {
																																																																									echo $reg_fad['pfd2_redox'];
																																																																									$str_prop = 'disabled';
																																																																								} ?>" <?php echo $str_prop;
																																																																										echo " " . $strProp2;
																																																																										echo " " . $strProp3;
																																																																										echo " " . $strProp4; ?>><?php } ?>
							</td>
							<td><?php echo $etiqueta ?></td>
						</tr>
				<?php $valUltRen = $i + 1;
					} //termina if

				} // termina for
				?>
			</table>

		</div>

		<?php //if($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28  ){
		?>
		<div class="row">
			<div class="col-md-4" style="border-radius: 5px;border: 1px solid #e6e6e6;height: 140px;padding:0px">
				<div class="col-md-3" style="height: 140px;border-radius: 5px;border: 1px solid #e6e6e6;">
					<p class="numEtapa">2c</p>
				</div>
				<div class="col-md-9 ">
					<label class="etiquetaEtapa">LIBERACION pH <?php echo fnc_rango_de(4) ?> - <?php echo fnc_rango_a(4) ?></label>
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
					<!--<input  type="text" id="inputPassword" class="form-control" placeholder="Firma LCP">-->
				</div>
			</div>
			<div class="col-md-4">
				<label for="inputPassword4">(<?php echo fnc_hora_de(4) ?> a <?php echo fnc_hora_a(4) ?> Horas)</label>
			</div>
			<div class="col-md-4" style="margin-top: 1rem;">
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
			<div class="col-md-4">
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
			<div class="col-md-4">
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
		<div class="row" id="camposFase2">

		</div>
		<!--barra botones-->
		<div class="row footerdivProcesos" style="margin-bottom: 10px">
			<div class="col-md-5">
				<label style="font-weight:bold; margin-left:20px;">&nbsp;</label>
			</div>

			<div class="form-group col-md-3">
				<div class="alert alert-info hide" id="alerta-errorFase2cOpe" style="height: 40px;width: 300px;text-align: left;z-index: 10;font-size: 10px;">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>
			<div class="col-md-4" style="text-align: right;margin-left:  -40px">
				<?php if ($_SESSION['privilegio'] == 4) {	?>
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE2c(<?php echo $reg_pro['pro_id'] ?>, 4);">
						<img src="../iconos/edit.png" alt="">Editar
					</button>

					<button type="button" class="btn btn-success" id="permitir" onClick="javascript:AbreModalAgregarR(<?php echo $reg_pro['pro_id'] ?>, 4, <?php echo $valUltRen ?>);"> <img src="../iconos/add.png" alt="">Renglon
					</button>
				<?php } ?>
				<!--<button type="submit" class="btn btn-primary" id="btn" style="margin-bottom: 10px"><img src="../iconos/guardar.png" alt="">Guardar</button>-->
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
					<button type="button" class="btn btn-info" onClick="javascript:quimicos_2c(<?php echo $reg_pro['pro_id'] ?>, <?php echo $reg_et['pe_id'] ?> );"><img src="../iconos/matraz.png" alt="">Químicos</button>

					<button type="submit" class="btn btn-primary" id="btn"><img src="../iconos/guardar.png" alt="">Guardar</button>
				<?php } ?>
			</div>
		</div>


	</form>

	<div class="modal" id="modalEditar2c" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

	<div class="modal" id="modalRenglon2c" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
	<div class="modal" id="m_modal_quimicos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

</div>