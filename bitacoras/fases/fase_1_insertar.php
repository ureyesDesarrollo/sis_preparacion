<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();



extract($_POST);

if ($txt_temp_final == '') {
	$txt_temp_final = 'NULL';
} else {
	$txt_temp_final = "'$txt_temp_final'";
}

if ($txtFeIni != '') {


	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini,proa_temp_final, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', $txt_temp_final, '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 1");

	mysqli_query($cnx, "INSERT INTO procesos_fase_1_g(pro_id, pfg1_ph_agua,pfg1_ce_agua,pfg1_temp_ag, pe_id) VALUES('$hdd_pro_id', '$txtAgIni','$txtCeIni','$txtTemp', '$hdd_pe_id') ") or die(mysqli_error($cnx) . " Error al insertar 2");

	$respuesta = array('mensaje' => "Fase 1 agregada");
} else {
	for ($i = 1; $i <= 10; $i++) {
		$txtRen = ${"txtRen" . $i};
		$hddRen = ${"hddRen" . $i};
		$cbxTipAg = ${"cbxTipAg" . $i};
		$txtTemp = ${"txtTemp" . $i};
		$txtHraIni = ${"txtHraIni" . $i};
		$txtHraFin = ${"txtHraFin" . $i};
		$txtHraIniMov = ${"txtHraIniMov" . $i};
		$txtHraFinMov = ${"txtHraFinMov" . $i};
		$txtPh = ${"txtPh" . $i};
		$txtCe = ${"txtCe" . $i};
		$txtagua_a = ${"cbxAgua" . $i};
		$txtObs = ${"txtObs" . $i};

		if ($hddRen == '' and $cbxTipAg != '') {
			/*if($cbxTipAg != '')
			{*/
			mysqli_query($cnx, "INSERT INTO procesos_fase_1_d(pfg1_id, pfd1_ren, tpa_id, usu_id, pfd1_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$cbxTipAg', '" . $_SESSION['idUsu'] . "', SYSDATE() ) ") or die(mysqli_error($cnx) . " Error al insertar 1");
			$strMsj = "Se agrego el renglon " . $i;
			//}
		}

		//if($hddRen != '' and ($txtTemp != '' or $txtHraIni != '' or $txtHraFin != '' or $txtHraFin != '' or $txtHraIniMov != '' or $txtHraFinMov != '' or $txtPh != '' or $txtCe != ''))
		else {

			if ($hddRen != '' and $txtTemp != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_temp = '$txtTemp' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtHraIni  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_hr_ini = '$txtHraIni' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtHraFin  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_hr_fin = '$txtHraFin' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $hddRen != '' and $txtHraIniMov  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_hr_ini_mov = '$txtHraIniMov' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $hddRen != '' and $txtHraFinMov  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_hr_fin_mov = '$txtHraFinMov' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtPh  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_ph = '$txtPh' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtCe  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_ce = '$txtCe' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtagua_a  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET taa_id  = '$txtagua_a' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtObs  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_1_d SET pfd1_observaciones = '$txtObs' WHERE pfd1_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
		}
	}


	if ($strMsj == '') {
		if ($txtFeTerm != '') {
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones',proa_temp_final = $txt_temp_final, usu_sup = '" . $_SESSION['idUsu'] . "' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");

			//mysqli_query($cnx, "UPDATE procesos_fase_1_g SET pfg1_extractivilidad = '$txt_extractivilidad' WHERE pro_id = '$hdd_pro_id' ") or die(mysqli_error($cnx) . " Error al actualizar extractibilidad");

			$strMsj = "Fase 1 actualizada";
		} else {
			if ($txtHrTotales != '') {
				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_ce,extractibilidad) VALUES('" . $_SESSION['idUsu'] . "', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales', '$txtCeLib','$txt_extractivilidad') ") or die(mysqli_error($cnx) . " Error al insertar L");

				$strMsj = "Fase 1 parametros capturados";
			} else {
				$strMsj = "Esta vacio";
			}
		}
	}

	$respuesta = array('mensaje' => $strMsj);
}


echo json_encode($respuesta);
