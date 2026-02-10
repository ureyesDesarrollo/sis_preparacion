<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

$reg_pro['pro_id'] = $idx_pro;

$cad_aux = mysqli_query($cnx, "SELECT * FROM procesos_auxiliar WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 18");
$reg_aux = mysqli_fetch_array($cad_aux);

$cad_fa = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_g WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 18");
$reg_fa = mysqli_fetch_array($cad_fa);

$cad_lib = mysqli_query($cnx, "SELECT * FROM procesos_liberacion_b WHERE pro_id = '$reg_pro[pro_id]' and pe_id = 18");
$reg_lib = mysqli_fetch_array($cad_lib);

define('PRIVILEGE_OPERATOR', 3);
define('PRIVILEGE_SUPERVISOR', 4);
define('PRIVILEGE_LABORATORY_1', 6);
define('PRIVILEGE_LABORATORY_2', 28);

// Inicializar todas las propiedades con valores por defecto
$strProp1 = '';
$strProp2 = '';
$strProp3 = '';
$strProp4 = ($reg_aux['proa_id'] == '') ? 'readonly' : '';
$strProp5 = ($reg_aux['proa_fe_fin'] != '') ? 'readonly' : '';
$strProp6 = '';

// Asignar propiedades según el privilegio del usuario
switch ($_SESSION['privilegio']) {
	case PRIVILEGE_OPERATOR:
		$strProp1 = 'disabled';
		$strProp6 = '';
		break;

	case PRIVILEGE_SUPERVISOR:
		$strProp2 = 'readonly';
		break;

	case PRIVILEGE_LABORATORY_1:
	case PRIVILEGE_LABORATORY_2:
		$strProp3 = 'readonly';
		break;
}
?>
<script language="javascript">
	$(document).ready(function() {
		$("#formFase7b").submit(function() {

			var formData = $(this).serialize();
			$.ajax({
				url: "fases/fase_7b_insertar.php",
				type: 'POST',
				data: formData,
				success: function(result) {

					data = JSON.parse(result);
					alertas("#alerta-errorFase7bOpe", 'Listo!', data["mensaje"], 1, true, 5000);
					$('#formFase7b').each(function() {
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
		formFase7b.btn.disabled = true;
		formFase7b.btn.value = "Enviando...";

		setTimeout(function() {
			formFase7b.btn.disabled = true;
			formFase7b.btn.value = "Guardar";
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
			url: 'fases/editar/fase_7b.php',
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
	<form autocomplete="off" id="formFase7b" name="formFase7b">

		<input name="hdd_pro_id" type="hidden" value="<?php echo htmlspecialchars($reg_pro['pro_id'] ?? ''); ?>" id="hdd_pro_id">
		<input name="hdd_pe_id" type="hidden" value="18" id="hdd_pe_id">
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?php echo htmlspecialchars($reg_aux['proa_id'] ?? ''); ?>">
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?php echo htmlspecialchars($reg_fa['pfg7_id'] ?? ''); ?>">

		<div class="headerdivProcesos">
			<div class="col-md-2">SEGUNDO ÁCIDO</div>
			<div class="col-md-8">PPRO = pH 2.0 máx</div>
			<div class="col-md-5"></div>
		</div>

		<!--tiempos-->
		<div class="row">
			<!-- Fecha inicio -->
			<label class="col-md-1" style="width: 120px">Fecha inicio</label>
			<div class="col-md-2 tiempos">
				<?php
				$fechaIniValue = $reg_aux['proa_fe_ini'] ?: date("Y-m-d");
				$fechaIniDisabled = $reg_aux['proa_fe_ini'] ? 'disabled' : '';
				?>
				<input type="date" class="form-control" id="txtFeIni" name="txtFeIni"
					value="<?= htmlspecialchars($fechaIniValue) ?>"
					<?= "$fechaIniDisabled $strProp2 $strProp3" ?> required>
			</div>

			<!-- Hora inicio -->
			<label class="col-md-1" style="width: 110px">Hora inicio</label>
			<div class="col-md-2 tiempos" style="width: 140px">
				<?php
				$horaIniValue = $reg_aux['proa_hr_ini'] ?: date("H:i");
				$horaIniDisabled = $reg_aux['proa_hr_ini'] ? 'disabled' : '';
				?>
				<input type="time" class="form-control" id="txtHrIni" name="txtHrIni"
					value="<?= htmlspecialchars($horaIniValue) ?>"
					<?= "$horaIniDisabled $strProp2 $strProp3" ?> required>
			</div>

			<!-- Temp agua utilizada -->
			<label class="col-md-1" style="width: 165px">Temp agua utilizada</label>
			<div class="col-md-1 tiempos">
				<?php
				$tempValue = $reg_fa['pfg7_temp_ag'] ?? '';
				$tempDisabled = $reg_fa['pfg7_temp_ag'] ? 'disabled' : '';
				?>
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
					class="form-control" id="txtTemp" name="txtTemp" placeholder="TEMP"
					value="<?= htmlspecialchars($tempValue) ?>"
					<?= "$tempDisabled $strProp2 $strProp3" ?> required>
			</div>

			<!-- Temp -->
			<label class="col-md-1">Temp</label>
			<div class="col-md-1 tiempos">
				<?php
				$temp2Value = $reg_fa['pfg7_temp'] ?? '';
				$temp2Disabled = $reg_fa['pfg7_temp'] ? 'disabled' : '';
				?>
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
					class="form-control" id="txtTemp2" name="txtTemp2" placeholder="TEMP"
					value="<?= htmlspecialchars($temp2Value) ?>"
					<?= "$temp2Disabled $strProp2 $strProp3" ?> required>
			</div>

			<!-- Ácido diluido -->
			<label class="col-md-1" style="width: 110px;">Ácido diluido</label>
			<div class="col-md-1">
				<?php
				$diluidoValue = $reg_fa['pfg7_acido_diluido'] ?? '';
				$diluidoDisabled = $reg_fa['pfg7_acido_diluido'] ? 'disabled' : '';
				?>
				<select id="cbxDiluido" class="form-control" name="cbxDiluido"
					<?= "$diluidoDisabled $strProp2 $strProp3" ?> required>
					<?php if ($diluidoValue): ?>
						<option value="<?= htmlspecialchars($diluidoValue) ?>"><?= htmlspecialchars($diluidoValue) ?></option>
					<?php endif; ?>
					<option value="NA">NA</option>
					<option value="SI">SI</option>
					<option value="NO">NO</option>
				</select>
			</div>
		</div>

		<div class="row" style="margin-top: 1rem;">
			<!-- Ácido -->
			<label class="col-md-1">Ácido</label>
			<div class="col-md-1 tiempos">
				<?php
				$acidoValue = $reg_fa['pfg7_acido'] ?? '';
				$acidoDisabled = $reg_fa['pfg7_acido'] ? 'disabled' : '';
				?>
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
					class="form-control" id="txtAcido" name="txtAcido" placeholder="Acido"
					value="<?= htmlspecialchars($acidoValue) ?>"
					<?= "$acidoDisabled $strProp2 $strProp3" ?> required>
			</div>

			<!-- Normalidad -->
			<label class="col-md-1" style="width: 120px">Normalidad</label>
			<div class="col-md-1 tiempos">
				<?php
				$normValue = $reg_fa['pfg7_norm'] ?? '';
				$normDisabled = $reg_fa['pfg7_norm'] ? 'disabled' : '';
				?>
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
					class="form-control" id="txtNorm" name="txtNorm" placeholder="Norm"
					value="<?= htmlspecialchars($normValue) ?>"
					<?= "$normDisabled $strProp2 $strProp3" ?> required>
			</div>

			<!-- Lts (solo etiqueta) -->
			<label class="col-md-1">Lts</label>

			<!-- PH -->
			<label class="col-md-1">PH</label>
			<div class="col-md-1 tiempos">
				<?php
				$phValue = $reg_fa['pfg7_ph'] ?? '';
				$phDisabled = $reg_fa['pfg7_ph'] ? 'disabled' : '';
				?>
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
					class="form-control" id="txtPh" name="txtPh" placeholder="PH"
					value="<?= htmlspecialchars($phValue) ?>"
					<?= "$phDisabled $strProp2 $strProp3" ?> required>
			</div>

			<!-- CE -->
			<label class="col-md-1">CE</label>
			<div class="col-md-1 tiempos">
				<?php
				$ceValue = $reg_fa['pfg7_ce'] ?? '';
				$ceDisabled = $reg_fa['pfg7_ce'] ? 'disabled' : '';
				?>
				<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
					class="form-control" id="txtCe" name="txtCe" placeholder="Ce"
					value="<?= htmlspecialchars($ceValue) ?>"
					<?= "$ceDisabled $strProp2 $strProp3" ?> required>
			</div>

			<label class="col-md-2">4 horas continuas</label>
		</div>
		<!---->

		<div class="row" style="margin-top: 2rem; margin-bottom: 2rem;">
			<?php
			// Función para generar las filas de la tabla
			function generateTableRows($start, $end, $cnx, $reg_fa, $strProp2, $strProp3, $strProp4)
			{
				$timeValues = [
					1 => '0:30',
					2 => '1:00',
					3 => '1:30',
					4 => '2:30',
					5 => '3:00',
					6 => '3:30'
				];

				for ($i = $start; $i <= $end; $i++) {
					$cad_fad = mysqli_query($cnx, "SELECT * FROM procesos_fase_7b_d WHERE pfg7_id = '" . mysqli_real_escape_string($cnx, $reg_fa['pfg7_id']) . "' and pfd7_ren = '$i'");
					$reg_fad = mysqli_fetch_array($cad_fad);

					$commonProps = "$strProp2 $strProp3 $strProp4";

					// Valores comunes
					$normValue = htmlspecialchars($reg_fad['pfd7_norm'] ?? '');
					$phValue = htmlspecialchars($reg_fad['pfd7_ph'] ?? '');
					$ceValue = htmlspecialchars($reg_fad['pfd7_ce'] ?? '');
					$tempValue = htmlspecialchars($reg_fad['pfd7_temp'] ?? '');
					$acidoValue = htmlspecialchars($reg_fad['pfd7_acido'] ?? '');

					$disabled = !empty($reg_fad) ? 'disabled' : '';
			?>
					<tr>
						<td>&nbsp;<?= $timeValues[$i] ?>
							<input type="hidden" class="form-control" id="txtRen<?= $i ?>" value="<?= $i ?>" name="txtRen<?= $i ?>">
						</td>
						<td>
							<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
								class="form-control" id="txtNormF<?= $i ?>" name="txtNormF<?= $i ?>" size="5"
								value="<?= $normValue ?>" <?= "$disabled $commonProps" ?> placeholder="Norm">
						</td>
						<td align="center" bgcolor="#FF0000">
							<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
								class="form-control" id="txtPhF<?= $i ?>" name="txtPhF<?= $i ?>" size="5"
								value="<?= $phValue ?>" <?= "$disabled $commonProps" ?> placeholder="pH">
						</td>
						<td>
							<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
								class="form-control" id="txtCeF<?= $i ?>" name="txtCeF<?= $i ?>" size="5"
								value="<?= $ceValue ?>" <?= "$disabled $commonProps" ?> placeholder="Ce">
						</td>
						<td>
							<?php if ($i != 2 && $i != 5): ?>
								<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
									class="form-control" id="txtTempF<?= $i ?>" name="txtTempF<?= $i ?>" size="5"
									value="<?= $tempValue ?>" <?= "$disabled $commonProps" ?> placeholder="Temp">
							<?php endif; ?>
						</td>
						<td>
							<?php if ($i == 2 || $i == 5): ?>
								<input style="display: inline; width:50px" type="text" maxlength="6"
									onKeyPress="return isNumberKeyFloat(event, this);" class="form-control"
									id="txtAcidoF<?= $i ?>" name="txtAcidoF<?= $i ?>" size="5"
									value="<?= $acidoValue ?>" <?= "$disabled $commonProps" ?> placeholder="Acido">
								<span style="display: inline;">LTS</span>
							<?php endif; ?>
						</td>
					</tr>
			<?php
				}
			}
			?>

			<!-- Primera tabla (filas 1-3) -->
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
					<?php generateTableRows(1, 3, $cnx, $reg_fa, $strProp2, $strProp3, $strProp4); ?>
				</table>
			</div>

			<!-- Segunda tabla (filas 4-6) -->
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
					<?php generateTableRows(4, 6, $cnx, $reg_fa, $strProp2, $strProp3, $strProp4); ?>
				</table>
			</div>
		</div>


		<!--estilo general de estapas-->
		<div class="row" style="margin-top: 2rem;">
			<div class="form-row">
				<!-- Fecha termina 2da acidificación -->
				<div class="form-group col-md-3">
					<?php
					$fechaTermValue = $reg_fa['pfg7_fe_fin'] ?: date("Y-m-d");
					$fechaTermDisabled = $reg_fa['pfg7_fe_fin'] ? 'disabled' : '';
					$fechaTermProps = "$fechaTermDisabled $strProp6 $strProp3 $strProp4";
					?>
					<label>Fecha termina 2da acidificación</label>
					<input type="date" class="form-control" id="txtFeTermA" name="txtFeTermA"
						value="<?= htmlspecialchars($fechaTermValue) ?>"
						<?= $fechaTermProps ?>>
				</div>

				<!-- Hora termina -->
				<div class="form-group col-md-2">
					<?php
					$horaTermValue = $reg_fa['pfg7_hr_fin'] ?: date("H:i");
					$horaTermDisabled = $reg_fa['pfg7_hr_fin'] ? 'disabled' : '';
					$horaTermProps = "$horaTermDisabled $strProp6 $strProp3 $strProp4";
					?>
					<label>Hora termina</label>
					<input type="time" class="form-control" id="txtHrTermA" name="txtHrTermA"
						value="<?= htmlspecialchars($horaTermValue) ?>"
						<?= $horaTermProps ?>>
				</div>

				<!-- Horas totales -->
				<div class="form-group col-md-2">
					<?php
					$horasTotalesValue = $reg_fa['pfg7_hr_totales'] ?? '';
					$horasTotalesDisabled = $reg_fa['pfg7_hr_totales'] ? 'disabled' : '';
					$horasTotalesProps = "$horasTotalesDisabled $strProp6 $strProp3 $strProp4";
					?>
					<label>Horas totales</label>
					<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
						class="form-control" id="txtHrTotales1" name="txtHrTotales1"
						placeholder="Horas totales"
						value="<?= htmlspecialchars($horasTotalesValue) ?>"
						<?= $horasTotalesProps ?>>
				</div>

				<!-- Realizo proceso -->
				<div class="form-group col-md-2">
					<label>Realizo proceso</label>
					<input type="text" class="form-control" id="txtRealizo"
						value="<?= htmlspecialchars(fnc_nom_usu($_SESSION['idUsu'])) ?>"
						readonly>
				</div>
			</div>
		</div>
		<!---->

		<div class="row" style="font-weight:bold;margin-bottom: 2rem;">
			(En esta parte son 15 minutos de movimiento y 1:45 de reposo)
		</div>

		<div class="row">
			<div class="col-md-7">
				<table border="0" cellspacing="5" cellpadding="5">
					<tr>
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
					<?php for ($i = 1; $i <= 4; $i++): ?>
						<?php
						$query = "SELECT * FROM procesos_fase_7b_d2 WHERE pfg7_id = '" . mysqli_real_escape_string($cnx, $reg_fa['pfg7_id']) . "' and pfd7_ren = '$i'";
						$cad_fad = mysqli_query($cnx, $query);
						$reg_fad = mysqli_fetch_array($cad_fad);

						// Valores comunes
						$disabled = !empty($reg_fad) ? 'disabled' : '';
						$commonProps = "$disabled $strProp2 $strProp3 $strProp4";

						// Valores de campos
						$iniMovValue = htmlspecialchars($reg_fad['pfd7_ini_mov'] ?? '');
						$iniRepValue = htmlspecialchars($reg_fad['pfd7_ini_reposo'] ?? '');
						$normValue = htmlspecialchars($reg_fad['pfd7_norm'] ?? '');
						$phValue = htmlspecialchars($reg_fad['pfd7_ph'] ?? '');
						$ceValue = htmlspecialchars($reg_fad['pfd7_ce'] ?? '');
						$tempValue = htmlspecialchars($reg_fad['pfd7_temp'] ?? '');
						?>
						<tr>
							<td>&nbsp;</td>
							<td>
								<?= $i ?>
								<input type="hidden" class="form-control" id="txtRen2<?= $i ?>" value="<?= $i ?>" name="txtRen2<?= $i ?>">
							</td>
							<td>
								<input type="time" id="txtIniMovD<?= $i ?>" name="txtIniMovD<?= $i ?>"
									class="form-control" value="<?= $iniMovValue ?>"
									<?= $commonProps ?>>
							</td>
							<td>HRS</td>
							<td>
								<input type="time" id="txtIniRepD<?= $i ?>" name="txtIniRepD<?= $i ?>"
									class="form-control" value="<?= $iniRepValue ?>"
									<?= $commonProps ?>>
							</td>
							<td>HRS</td>
							<td>
								<?php if ($i == 1 || $i == 2): ?>
									<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
										name="txtNormD<?= $i ?>" id="txtNormD<?= $i ?>"
										class="form-control" placeholder="NORM"
										value="<?= $normValue ?>" <?= $commonProps ?>>
								<?php endif; ?>
							</td>
							<td>
								<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
									name="txtPhD<?= $i ?>" id="txtPhD<?= $i ?>"
									class="form-control" placeholder="PH"
									value="<?= $phValue ?>" <?= $commonProps ?>>
							</td>
							<td>
								<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
									name="txtCeD<?= $i ?>" id="txtCeD<?= $i ?>"
									class="form-control" placeholder="CE"
									value="<?= $ceValue ?>" <?= $commonProps ?>>
							</td>
							<td>
								<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);"
									name="txtTempD<?= $i ?>" id="txtTempD<?= $i ?>"
									class="form-control" placeholder="TEMP"
									value="<?= $tempValue ?>" <?= $commonProps ?>>
							</td>
						</tr>
					<?php endfor; ?>
				</table>
			</div>
			<div class="col-md-5 divEtapas7_mod" style="padding: 0px;margin-left:-1rem">
				<div class="col-md-1" style="height: 348px; border-radius: 5px;border: 1px solid #e6e6e6;width: 70px;margin-left: 0px;">
					<p class="numEtapa_mod">7b</p>
				</div>
				<div class="col-md-9">
					<label class="etiquetaEtapa_mod">COCIDO LIBERACIÓN (PH <?php echo fnc_rango_de(20) ?> - <?php echo fnc_rango_a(20) ?>) (CE 5.0 MAX)</label>

					<?php
					// Prepara propiedades comunes
					$disabled = ($reg_lib['prol_fecha'] != '') ? 'disabled' : '';
					$commonProps = $disabled . ' ' . $strProp1 . ' ' . $strProp2;

					for ($i = 1; $i <= 2; $i++):
						$phValue = $reg_lib['prol_cocido_ph' . $i] ?? '';
						$ceValue = $reg_lib['prol_ce' . $i] ?? '';
						$extValue = $reg_lib['prol_por_extrac'] ?? '';
					?>
						<div class="form-inline" style="margin-bottom: 10px;"> <!-- Cada fila -->
							<input type="hidden" name="R<?= $i ?>" value="<?= $i ?>">

							<label class="sr-only">Fecha <?= $i ?></label>
							<input type="date" class="form-control" style="width: 150px; margin-right: 5px;"
								id="txtFeLib<?= $i ?>" name="txtFeLib<?= $i ?>"
								value="<?= $reg_lib['prol_fecha'] ?? '' ?>" <?= $commonProps ?>>

							<label class="sr-only">Hora <?= $i ?></label>
							<input type="time" class="form-control" style="width: 100px; margin-right: 5px;"
								id="txtHrLib<?= $i ?>" name="txtHrLib<?= $i ?>"
								value="<?= $reg_lib['prol_hora'] ?? '' ?>" <?= $commonProps ?>>

							<label class="sr-only"><?= $i ?>Cocido ph</label>
							<input type="text" class="form-control" style="width: 80px; margin-right: 5px;"
								id="txtPhLib<?= $i ?>" name="txtPhLib<?= $i ?>"
								placeholder="<?= $i ?> Cocido ph" maxlength="6"
								onKeyPress="return isNumberKey(event, this);"
								value="<?= $phValue ?>" <?= $commonProps ?>>

							<label class="sr-only">CE <?= $i ?></label>
							<input type="text" class="form-control" style="width: 70px; margin-right: 5px;"
								id="txtCeLib<?= $i ?>" name="txtCeLib<?= $i ?>"
								placeholder="CE" maxlength="6"
								onKeyPress="return isNumberKey(event, this);"
								value="<?= $ceValue ?>" <?= $commonProps ?>>

							<label class="sr-only">% Ext <?= $i ?></label>
							<input type="text" class="form-control" style="width: 70px;"
								id="txtpor_ext<?= $i ?>" name="txtpor_ext<?= $i ?>"
								placeholder="% Ext" maxlength="6"
								onKeyPress="return isNumberKey(event, this);"
								value="<?= $extValue ?>" <?= $commonProps ?>>
						</div>
					<?php endfor; ?>

					<!-- Selector de Color -->
					<select class="form-control" id="cbxColor" name="cbxColor" <?= $commonProps ?>>
						<option value="">Color</option>
						<?php
						$colors = [
							'1-A',
							'1-B',
							'1-C',
							'1-D',
							'2-A',
							'2-B',
							'2-C',
							'2-D',
							'3-A',
							'3-B',
							'3-C',
							'3-D',
							'4-A',
							'4-B',
							'4-C',
							'4-D'
						];
						$selectedColor = $reg_lib['prol_color'] ?? '';

						foreach ($colors as $color):
							$selected = ($color == $selectedColor) ? 'selected' : '';
						?>
							<option value="<?= $color ?>" <?= $selected ?>><?= $color ?></option>
						<?php endforeach; ?>
					</select>

					<!-- Selector de Color de Caldo -->
					<select class="form-control" id="cbxColor_caldo" name="cbxColor_caldo" <?= $commonProps ?>>
						<option value="">Color de caldo</option>
						<?php
						$selectedColorCaldo = $reg_lib['prol_color_caldo'] ?? '';

						foreach ($colors as $color):
							$selected = ($color == $selectedColorCaldo) ? 'selected' : '';
						?>
							<option value="<?= $color ?>" <?= $selected ?>><?= $color ?></option>
						<?php endforeach; ?>
					</select>

					<!-- % de sólidos -->
					<input type="text" class="form-control" id="txtSolides" name="txtSolides"
						placeholder="% de solidos" maxlength="6"
						onKeyPress="return isNumberKey(event, this);"
						value="<?= $reg_lib['prol_solides'] ?? '' ?>" <?= $commonProps ?>>

					<!-- Observaciones -->
					<textarea class="form-control" id="txta_obs" name="txta_obs"
						placeholder="Observaciones" <?= $commonProps ?>><?= $reg_lib['prol_observaciones'] ?? '' ?></textarea>

					<!-- Horas totales -->
					<input type="text" class="form-control" id="txtHrTotales" name="txtHrTotales"
						placeholder="Horas totales" maxlength="6"
						onKeyPress="return isNumberKey(event, this);"
						value="<?= $reg_lib['prol_hr_totales'] ?? '' ?>" <?= $commonProps ?>>

					<!-- Usuario (solo lectura) -->
					<input type="text" class="form-control" id="txtUsuario"
						placeholder="Nombre LCP" value="<?= fnc_nom_usu($_SESSION['idUsu']) ?>" disabled>
				</div>
			</div>

			<!--estilo general de estapas-->
			<div class="row">
				<div class="form-row">
					<?php
					// Common variables for date/time fields
					$feTermValue = $reg_aux['proa_fe_fin'] ?? '';
					$feTermDisabled = $reg_aux['proa_fe_fin'] ? 'disabled' : '';
					$feTermProps = "$feTermDisabled $strProp6 $strProp3 $strProp4";

					$hrTermValue = $reg_aux['proa_hr_fin'] ?? '';
					$hrTermDisabled = $reg_aux['proa_hr_fin'] ? 'disabled' : '';
					$hrTermProps = "$hrTermDisabled $strProp6 $strProp3 $strProp4";

					// Observaciones field
					$obsDisabled = $reg_aux['proa_observaciones'] ? 'disabled' : '';
					$obsProps = "$obsDisabled $strProp6 $strProp3 $strProp5 $strProp4";
					$obsValue = htmlspecialchars($reg_aux['proa_observaciones'] ?? '');

					// User name
					$userName = htmlspecialchars(fnc_nom_usu($_SESSION['idUsu']));
					?>

					<!-- Fecha termina lavados -->
					<div class="form-group col-md-2">
						<label>Fecha termina lavados</label>
						<input type="date" class="form-control" id="txtFeTerm" name="txtFeTerm"
							value="<?= $feTermValue ?>" <?= $feTermProps ?>>
					</div>

					<!-- Hora termina -->
					<div class="form-group col-md-2">
						<label>Hora termina</label>
						<input type="time" class="form-control" id="txtHrTerm" name="txtHrTerm"
							value="<?= $hrTermValue ?>" <?= $hrTermProps ?>>
					</div>

					<!-- Realizó -->
					<div class="form-group col-md-2">
						<label>Realizó</label>
						<input type="text" id="txtRealizo2" class="form-control"
							value="<?= $userName ?>" readonly>
					</div>
				</div>

				<div class="form-row">
					<!-- Observaciones -->
					<div class="form-group col-md-4">
						<label>Observaciones</label>
						<textarea class="form-control" placeholder="Observaciones..."
							name="txaObservaciones" <?= $obsProps ?>><?= $obsValue ?></textarea>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-2">
						<label>2 A 4 horas (8 - 12 Horas) totales</label>
					</div>
				</div>
			</div>
		</div>


		<!--barra botones-->
		<div class="row footerdivProcesos" style="margin-bottom: 10px">
			<div class="col-md-5"></div>

			<div class="form-group col-md-4">
				<div class="alert alert-info hide" id="alerta-errorFase7bOpe" style="height: 40px; width: 270px; text-align: left; z-index: 10; font-size: 10px;">
					<button type="button" class="close" id="cerrar_alerta" aria-label="Close">&times;</button>
					<strong>Titulo</strong> &nbsp;&nbsp;
					<span> Mensaje </span>
				</div>
			</div>
			<div class="col-md-3" style="text-align: right;margin-left:  -40px">
				<?php if ($_SESSION['privilegio'] == 4) {	?>
					<button type="button" class="btn btn-success" id="editar" onClick="javascript:AbreModalE7b(<?php echo $reg_pro['pro_id'] ?>, 18);">
						<img src="../iconos/edit.png" alt="">Editar
					</button>
				<?php } ?>
				<?php
				//Validar que si es el laboratorio y no existe guardado la etapa no pueda insertar
				if ($_SESSION['privilegio'] == 6 or $_SESSION['privilegio'] == 28  or $_SESSION['privilegio'] == 4) {
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
</div>

<div class="modal" id="modalEditar7b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
<div class="modal" id="m_modal_quimicos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>


</div>