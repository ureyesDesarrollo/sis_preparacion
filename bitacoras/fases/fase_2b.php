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

// Inicializar variables con valores por defecto
$reg_aux = $reg_aux ?? [];
$reg_fa = $reg_fa ?? [];
$reg_lib = $reg_lib ?? [];
$reg_pro = $reg_pro ?? [];
$reg_et = $reg_et ?? [];
$strProp1 = $strProp1 ?? '';
$strProp2 = $strProp2 ?? '';
$strProp3 = $strProp3 ?? '';
$strProp4 = $strProp4 ?? '';
$strProp5 = $strProp5 ?? '';
$strProp6 = $strProp6 ?? '';

// Verificar si las variables de sesión y auxiliares existen
$privilegio = $_SESSION['privilegio'] ?? null;
$proa_id = $reg_aux['proa_id'] ?? null;
$proa_fe_fin = $reg_aux['proa_fe_fin'] ?? null;

// Asignar propiedades según privilegios
switch ($privilegio) {
	case 3: // Operador
		$strProp1 = 'disabled';
		$strProp6 = '';
		break;
	case 4: // Supervisor
		$strProp2 = 'readonly';
		break;
	case 6:
	case 28: // Laboratorio
		$strProp3 = 'readonly';
		break;
}

// Control para datos generales
$strProp4 = ($proa_id === '') ? 'readonly' : '';

// Control para observaciones
$strProp5 = ($proa_fe_fin !== '') ? 'readonly' : '';
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

		<input name="hdd_pro_id" type="hidden" value="<?= $reg_pro['pro_id'] ?? ''; ?>" id="hdd_pro_id" />
		<input name="hdd_pe_id" type="hidden" value="3" id="hdd_pe_id" />
		<input name="hdd_proa" type="hidden" id="hdd_proa" value="<?= $reg_aux['proa_id'] ?? ''; ?>" />
		<input name="hdd_pfg" type="hidden" id="hdd_pfg" value="<?= $reg_fa['pfg2_id'] ?? ''; ?>" />

		<div class="headerdivProcesos">
			<div class="col-md-2">ENZIMA</div>
			<div class="col-md-8">Las primeras 6 horas en movimiento continuo y según como se vea el material se le dan reposos</div>
		</div>

		<!--tiempos-->
		<?php
		// Inicializar variables para evitar errores
		$reg_aux = $reg_aux ?? [];
		$reg_fa = $reg_fa ?? [];
		$str_prop = '';
		?>

		<div class="row" style="margin-bottom: 20px">
			<!-- Fecha inicio -->
			<div class="col-md-2">
				<label>Fecha inicio</label>
				<input type="date" class="form-control" id="txtFeIni" name="txtFeIni"
					value="<?= !empty($reg_aux['proa_fe_ini']) ? htmlspecialchars($reg_aux['proa_fe_ini']) : date("Y-m-d") ?>"
					<?= (!empty($reg_aux['proa_fe_ini']) ? 'disabled' : '') . " " . ($strProp2 ?? '') . " " . ($strProp3 ?? '') ?>
					required>
			</div>

			<!-- Hora inicio -->
			<div class="col-md-2">
				<label>Hora inicio</label>
				<input type="time" class="form-control" id="txtHrIni" name="txtHrIni"
					value="<?= !empty($reg_aux['proa_hr_ini']) ? htmlspecialchars($reg_aux['proa_hr_ini']) : date("H:i") ?>"
					<?= (!empty($reg_aux['proa_hr_ini']) ? 'disabled' : '') . " " . ($strProp2 ?? '') . " " . ($strProp3 ?? '') ?>
					required>
			</div>

			<!-- Enzima -->
			<div class="col-md-2">
				<label>Enzima (Kg)</label>
				<input type="text" maxlength="6" class="form-control" id="txtEnzima"
					placeholder="Kg" name="txtEnzima"
					onKeyPress="return isNumberKeyFloat(event, this);"
					value="<?= !empty($reg_fa['pfg2_enzima']) ? htmlspecialchars($reg_fa['pfg2_enzima']) : '' ?>"
					<?= (!empty($reg_fa['pfg2_enzima']) ? 'disabled' : '') . " " . ($strProp2 ?? '') . " " . ($strProp3 ?? '') ?>
					required>
			</div>

			<!-- PH -->
			<div class="col-md-2">
				<label>PH</label>
				<input type="text" maxlength="6" class="form-control" id="txt_ph"
					placeholder="PH" name="txt_ph"
					onKeyPress="return isNumberKeyFloat(event, this);"
					value="<?= !empty($reg_fa['pfg2_ph1']) ? htmlspecialchars($reg_fa['pfg2_ph1']) : '' ?>"
					<?= (!empty($reg_fa['pfg2_ph1']) ? 'disabled' : '') . " " . ($strProp2 ?? '') . " " . ($strProp3 ?? '') ?>
					required>
			</div>

			<!-- Ajuste con Sosa -->
			<div class="col-md-2">
				<label>Ajuste con Sosa</label>
				<input type="text" maxlength="6" class="form-control" id="txt_ajustesosa"
					placeholder="Sosa" name="txt_ajustesosa"
					onKeyPress="return isNumberKeyFloat(event, this);"
					value="<?= !empty($reg_fa['pfg2_ajustesosa']) ? htmlspecialchars($reg_fa['pfg2_ajustesosa']) : '' ?>"
					<?= (!empty($reg_fa['pfg2_ajustesosa']) ? 'disabled' : '') . " " . ($strProp2 ?? '') . " " . ($strProp3 ?? '') ?>
					required>
			</div>

			<!-- Temp agua utilizada -->
			<div class="col-md-2">
				<label>Temp agua utilizada</label>
				<input type="text" maxlength="6" class="form-control" id="txt_temp"
					placeholder="Temp" name="txt_temp"
					onKeyPress="return isNumberKeyFloat(event, this);"
					value="<?= !empty($reg_fa['pfg2_temp_ag']) ? htmlspecialchars($reg_fa['pfg2_temp_ag']) : '' ?>"
					<?= (!empty($reg_fa['pfg2_temp_ag']) ? 'disabled' : '') . " " . ($strProp2 ?? '') . " " . ($strProp3 ?? '') ?>
					required>
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
					<?php
					// Preparamos los registros de la fase 2b
					$registros_fase = [];
					if (!empty($reg_fa['pfg2_id'])) {
						$query = "SELECT * FROM procesos_fase_2b_d WHERE pfg2_id = '" . mysqli_real_escape_string($cnx, $reg_fa['pfg2_id']) . "'";
						$result = mysqli_query($cnx, $query);
						while ($row = mysqli_fetch_assoc($result)) {
							$registros_fase[$row['pfd2_ren']] = $row;
						}
					}

					// Definimos la secuencia especial de números
					$secuencia = [1, 2, 4, 5, 7, 11, 15, 19, 23, 27, 32, 36, 40];

					foreach ($secuencia as $i):
						$reg_fad = $registros_fase[$i] ?? [];
						$disabled = !empty($reg_fad) ? 'disabled' : '';
						$props = implode(' ', array_filter([$disabled, $strProp2, $strProp3, $strProp4]));
						$showField = !in_array($i, [2, 6, 11, 19, 27, 36, 40]);
					?>
						<tr>
							<td style="padding-right:1.5rem">
								<?= $i ?>
								<input type="hidden" class="form-control" id="txtRen<?= $i ?>" value="<?= $i ?>" name="txtRen<?= $i ?>">
							</td>
							<td style="padding-right:1.5rem">
								<input type="time" class="form-control" id="txtHoraD<?= $i ?>" name="txtHoraD<?= $i ?>"
									value="<?= !empty($reg_fad['pfd2_hr']) ? htmlspecialchars($reg_fad['pfd2_hr']) : date("H:i") ?>"
									<?= $props ?>>
							</td>
							<td style="padding-right:1.5rem">
								<input onKeyPress="return isNumberKey(event, this);" maxlength="6" style="width: 70px"
									type="text" class="form-control" id="txtPhD<?= $i ?>" name="txtPhD<?= $i ?>"
									value="<?= !empty($reg_fad['pfd2_ph']) ? htmlspecialchars($reg_fad['pfd2_ph']) : '' ?>"
									<?= $props ?> placeholder="pH">
							</td>
							<td style="padding-right:1.5rem">
								<?= $showField ?
									'<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" 
                            class="form-control" id="txtSosaD' . $i . '" name="txtSosaD' . $i . '" 
                            value="' . (!empty($reg_fad['pfd2_sosa']) ? htmlspecialchars($reg_fad['pfd2_sosa']) : '') . '" 
                            ' . $props . ' size="5" placeholder="Sosa">' : '-' ?>
							</td>
							<td style="padding-right:1.5rem">
								<?= $showField ?
									'<input type="text" maxlength="6" onKeyPress="return isNumberKeyFloat(event, this);" 
                            class="form-control" id="txt_tempd' . $i . '" name="txt_tempd' . $i . '" size="5" 
                            value="' . (!empty($reg_fad['pfd2_temp']) ? htmlspecialchars($reg_fad['pfd2_temp']) : '') . '" 
                            ' . $props . ' placeholder="Temp">' : '-' ?>
							</td>
						</tr>
					<?php endforeach; ?>
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
					<div class="col-md-3" style="height: 110px;border-radius: 5px;border: 1px solid #e6e6e6;">
						<p class="numEtapa">2b</p>
					</div>
					<div class="col-md-9" style="padding:0px">
						<label class="etiquetaEtapa" style="background:#e6e6e6;width:257px">
							Liberación de la extractibilidad entre 80-85%
						</label>
						<input style="width:15rem;display: inline;" type="text" maxlength="6"
							onKeyPress="return isNumberKeyFloat(event, this);" id="txt_extractibilidad"
							class="form-control" placeholder="Extractibilidad" name="txt_extractibilidad"
							value="<?= !empty($reg_lib['prol_hr_totales']) ? htmlspecialchars($reg_lib['prol_hr_totales']) : '' ?>"
							<?= !empty($reg_lib['prol_hr_totales']) ? 'disabled' : '' ?>
							<?= $strProp1 . ' ' . $strProp2 ?>>
						<br>
						<input style="width:15rem;display: inline;" type="text" maxlength="6"
							onKeyPress="return isNumberKeyFloat(event, this);" id="txtHrTotales"
							class="form-control" placeholder="Horas totales" name="txtHrTotales"
							value="<?= !empty($reg_lib['prol_hr_totales']) ? htmlspecialchars($reg_lib['prol_hr_totales']) : '' ?>"
							<?= !empty($reg_lib['prol_hr_totales']) ? 'disabled' : '' ?>
							<?= $strProp1 . ' ' . $strProp2 ?>>
						<input style="width:15rem" type="text" onKeyPress="return isNumberKeyFloat(event, this);"
							id="inputPassword" class="form-control" placeholder="Nombre LCP"
							value="<?= htmlspecialchars(fnc_nom_usu($_SESSION['idUsu'] ?? '')) ?>" readonly>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-12">
						<label for="inputPassword4">(<?= htmlspecialchars(fnc_hora_de(3)) ?> a <?= htmlspecialchars(fnc_hora_a(3)) ?> Horas)</label>
					</div>
					<div class="form-group col-md-12">
						<label>Fecha termina enzima</label>
						<input type="date" class="form-control" id="txtFeTerm" name="txtFeTerm"
							value="<?= !empty($reg_aux['proa_fe_fin']) ? htmlspecialchars($reg_aux['proa_fe_fin']) : '' ?>"
							<?= !empty($reg_aux['proa_fe_fin']) ? 'disabled' : '' ?>
							<?= $strProp6 . ' ' . $strProp3 . ' ' . $strProp4 ?>>
					</div>
					<div class="form-group col-md-12">
						<label>Hora termina enzima</label>
						<input type="time" class="form-control" id="txtHrTerm" name="txtHrTerm"
							value="<?= !empty($reg_aux['proa_hr_fin']) ? htmlspecialchars($reg_aux['proa_hr_fin']) : '' ?>"
							<?= !empty($reg_aux['proa_hr_fin']) ? 'disabled' : '' ?>
							<?= $strProp6 . ' ' . $strProp3 . ' ' . $strProp4 ?>>
					</div>
					<div class="form-group col-md-12">
						<label>Hrs totales del proceso</label>
						<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control"
							id="txtHrasTot" name="txtHrasTot"
							value="<?= !empty($reg_fa['pfg2_hr_totales']) ? htmlspecialchars($reg_fa['pfg2_hr_totales']) : '' ?>"
							<?= !empty($reg_fa['pfg2_hr_totales']) ? 'disabled' : '' ?>
							<?= $strProp6 . ' ' . $strProp3 . ' ' . $strProp4 ?>>
					</div>
					<div class="col-md-12">
						<label>Observaciones</label>
						<textarea class="form-control" placeholder="Observaciones..." name="txaObservaciones"
							<?= !empty($reg_aux['proa_observaciones']) ? 'disabled' : '' ?>
							<?= $strProp6 . ' ' . $strProp3 . ' ' . $strProp5 . ' ' . $strProp4 ?>>
                    <?= !empty($reg_aux['proa_observaciones']) ? htmlspecialchars($reg_aux['proa_observaciones']) : '' ?>
                </textarea>
					</div>

					<div class="col-md-12" style="text-align: right;margin-top:1rem">
						<?php if (($_SESSION['privilegio'] ?? 0) == 4): ?>
							<input type="hidden" id="hddSaltar" name="hddSaltar" value="Si" readonly>
							<button type="submit" class="btn btn-primary" id="btn">
								<img src="../iconos/quitar.png" alt="">Saltar
							</button>
							<button type="button" class="btn btn-success" id="editar"
								onClick="AbreModalE2b(<?= htmlspecialchars($reg_pro['pro_id'] ?? 0) ?>, 30);">
								<img src="../iconos/edit.png" alt="">Editar
							</button>
						<?php endif; ?>

						<?php
						// Validación de químicos
						$str_bandera_q2 = "Si";
						if (!empty($reg_fa['pfg2_enzima']) && $reg_fa['pfg2_enzima'] != '0') {
							$query = "SELECT * FROM quimicos_etapas WHERE pro_id = '" . mysqli_real_escape_string($cnx, $reg_pro['pro_id'] ?? '') . "' AND pe_id = '3'";
							$result = mysqli_query($cnx, $query);
							if (mysqli_num_rows($result) == 0) {
								echo "<div style='background-color:#ccc0ff'>El operador debe capturar los químicos</div>";
								$str_bandera_q2 = "No";
							}
						}

						// Botones según privilegio
						$privilegio = $_SESSION['privilegio'] ?? 0;
						if (in_array($privilegio, [6, 28, 4])) {
							if (!empty($reg_fa['pfg2_id']) && $str_bandera_q2 == 'Si') {
								echo '<button type="submit" class="btn btn-primary" id="btn">
                            <img src="../iconos/guardar.png" alt="">Guardar
                        </button>';
							} else {
								echo "N/A Guardar";
							}
						} else {
							echo '<button type="button" class="btn btn-info" 
                          onClick="quimicos_2b(' . htmlspecialchars($reg_pro['pro_id'] ?? 0) . ', ' . htmlspecialchars($reg_et['pe_id'] ?? 0) . ');">
                          <img src="../iconos/matraz.png" alt="">Químicos</button>
                          <button type="submit" class="btn btn-primary" id="btn">
                          <img src="../iconos/guardar.png" alt="">Guardar</button>';
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="campos"></div>


	</form>

	<div class="modal" id="modalEditar2b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>
	<div class="modal" id="m_modal_quimicos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false"></div>

</div>