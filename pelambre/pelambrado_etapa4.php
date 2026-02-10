<form id="form_lavado">
	<div class="row renglones" id="titulos">
		<div class="col">
			<label for="formFile" class="form-label">Fecha inicio</label>
		</div>
		<div class="col">
			<label for="formFile" class="form-label">Hora inicio</label>
		</div>
		<div class="col">
			<label for="formFile" class="form-label">Hora termino</label>
		</div>
		<div class="col">
			<label for="formFile" class="form-label">PH DEL AGUA</label>
		</div>
		<div class="col">
			<label for="formFile" class="form-label">CE DEL AGUA</label>
		</div>
		<input type="hidden" name="hdd_id_pelambre" id="hdd_id_pelambre" value="<?php echo $reg_pelambre['ip_id'] ?>">
	</div>
	<?php


	for ($i = 1; $i <= 4; $i++) {

		#consulta información capturada en fases remojo y pelambre
		$pelambre2 = mysqli_query($cnx, "SELECT * FROM 
		inventario_pelambre_etapas_2 WHERE ipe_ren = '$i' and ipe_etapa = '4' and ip_id = " . $reg_pelambre['ip_id'] . "");
		$reg_pelambre2 = mysqli_fetch_assoc($pelambre2);

	?>
		<div class="row renglones">
			<div class="col">

				<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "hdd_id" . $i ?>" name="<?php echo "hdd_id" . $i ?>" value="<?php echo $reg_pelambre2['ipe_id'] ?>" readonly>
				<input type="hidden" class="form-control" onkeypress="return isNumberKey(event, this);" id="<?php echo "txt_renglon" . $i ?>" name="<?php echo "txt_renglon" . $i ?>" value="<?php echo $i ?>" readonly>

				<?php if ($reg_pelambre2['ipe_fe_inicio'] != '') {
					$res_fe_ini_lav = $reg_pelambre2['ipe_fe_inicio'];
					$readonly = 'readonly';
				} else {
					$res_fe_ini_lav = '';
					$readonly = '';
				}
				?>
				<input type="date" class="form-control" name="<?php echo "txt_fe_ini_lav" . $i ?>" id="<?php echo "txt_fe_ini_lav" . $i ?>" value="<?php echo $res_fe_ini_lav ?>" <?php echo $readonly ?>>

			</div>
			<div class="col">
				<?php if ($reg_pelambre2['ipe_hr_inicio'] != '') {
					$res_hora_ini_lav = $reg_pelambre2['ipe_hr_inicio'];
					$readonly = 'readonly';
				} else {
					$res_hora_ini_lav = '';
					$readonly = '';
				}
				?>
				<input type="time" class="form-control" name="<?php echo "txt_hora_ini" . $i ?>" id="<?php echo "txt_hora_ini" . $i ?>" value="<?php echo $res_hora_ini_lav ?>" <?php echo $readonly ?>>
			</div>
			<div class="col">
				<?php if ($reg_pelambre2['ipe_hr_fin'] != '') {
					$res_hora_fin = $reg_pelambre2['ipe_hr_fin'];
					$readonly = 'readonly';
				} else {
					$res_hora_fin = '';
					$readonly = '';
				}
				?>
				<input type="time" class="form-control" name="<?php echo "txt_hora_fin" . $i ?>" id="<?php echo "txt_hora_fin" . $i ?>" value="<?php echo $res_hora_fin ?>" <?php echo $readonly ?>>
			</div>
			<div class="col">
				<?php if ($reg_pelambre2['ipe_ph'] != '') {
					$res_ph = $reg_pelambre2['ipe_ph'];
					$readonly = 'readonly';
				} else {
					$res_ph = '';
					$readonly = '';
				}
				?>
				<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" name="<?php echo "txt_ph_ag" . $i ?>" id="<?php echo "txt_ph_ag" . $i ?>" value="<?php echo $res_ph ?>" <?php echo $readonly ?>>
			</div>
			<div class="col">
				<?php if ($reg_pelambre2['ipe_ce'] != '') {
					$res_ce = $reg_pelambre2['ipe_ce'];
					$readonly = 'readonly';
				} else {
					$res_ce = '';
					$readonly = '';
				}
				?>
				<input type="text" onKeyPress="return isNumberKeyFloat(event, this);" class="form-control" name="<?php echo "txt_ce_ag" . $i ?>" id="<?php echo "txt_ce_ag" . $i ?>" value="<?php echo $res_ce ?>" <?php echo $readonly ?>>
			</div>
		</div>
	<?php } ?>
	<div class="row renglones">
		<div class="col-md-5">
		</div>
		<!--mensajes-->
		<div class="col">
			<div id="alerta-accion_lavados" class="alert d-none" style="margin-top:2rem;">
				<strong class="alert-heading">¡Error!</strong>
				<span class="alert-body"></span>
			</div>
		</div>
		<?php if ($_SESSION['privilegio'] == 3) { ?>
			<div class="col" id="boton">
				<button id="btnGuardar_lavados" style="margin-top:2rem;" type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xl" style="color: #000;"></i> Guardar</button>
			</div>
		<?php } ?>
	</div>
</form>