<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include('../seguridad/user_seguridad.php');
include('../conexion/conexion.php');
include('../funciones/funciones.php');

$cnx =  Conectarse();
?>
	<div id="main">
		<div class="col-md-2">
			<label for="recipient-name" class="col-form-label">Fecha:</label>
			<input name="txt_fecha" id="txt_fecha" type="text" size="10" value="<?php echo date("d-m-Y") ?>" readonly="true" class="form-control is-valid" />
		</div>
		<div class="col-md-2">
			<label for="recipient-name" class="col-form-label">Hora:</label>
			<input name="txt_hora" id="txt_hora" type="text" size="5" value="<?php echo date("H:i:s") ?>" readonly="true" class="form-control is-valid" />
		</div>
		<div class="col-md-2">
			<label for="recipient-name" class="col-form-label">Mes:</label>
			<input name="txt_mes" id="txt_mes" type="text" size="10" value="<?php echo fnc_formato_mes(date("m")) ?>" readonly="true" class="form-control is-valid" />
		</div>
		<div class="col-md-2">
			<label for="recipient-name" class="col-form-label">Turno:</label>
			<select name="slc_turno" id="slc_turno" class="form-control is-valid" required>
				<option value="">Selecciona...</option>
				<option value="D">Dia</option>
				<option value="N">Noche</option>
			</select>
		</div>
		<div class="col-md-2">
			<label for="recipient-name" class="col-form-label">Lote:</label>
			<input name="txt_lote" id="txt_lote" type="text" size="10" value="<?php echo fnc_lote(date("m")) ?>" readonly="true" class="form-control is-valid" required />
		</div>
		<div class="col-md-2">
			<label for="recipient-name" class="col-form-label">Proceso/Paleto:</label>
			<select name="cbxPaleto" class="form-control is-valid" id="cbxPaleto" placeholder="" required>
				<option value="">Selecciona...</option>
				<?php
				//$cad_est = mysqli_query($cnx,"select * from listado_estatus WHERE le_aplica = 'P' order by le_estatus asc");
				$cad_est = mysqli_query($cnx, "select * from procesos_paletos WHERE pp_id IN (1,2) and prop_estatus = 1");
				while ($reg_est =  mysqli_fetch_assoc($cad_est)) {

					if ($reg_est['pp_id'] == 1) {
						$str_es = '1A';
					} else {
						$str_es = '1B';
					}
				?>
					<option value="<?php echo mb_convert_encoding($reg_est['prop_id'], "UTF-8");  ?>" <?php if (mb_convert_encoding($reg_est['prop_id'], "UTF-8") == $registros['le_id']) { ?>selected="selected" <?php } ?>><?php echo mb_convert_encoding($reg_est['prop_id'], "UTF-8") . ' / ' . $str_es;  ?></option>
				<?php } ?>
			</select>
		</div>
	</div>