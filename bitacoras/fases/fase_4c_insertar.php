<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();



extract($_POST);

if ($txtFeIni != '') {
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 1");

	mysqli_query($cnx, "INSERT INTO procesos_fase_4b_g(pro_id, pe_id, pfg4_temp_ag, pfg4_ph,pfg4_ce,usu_id) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtTemp','$txt_ph_d','$txt_ce_d' ,'" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 2");

	$respuesta = array('mensaje' => "Fase 4c agregada");
} else {
	for ($i = 1; $i <= 10; $i++) {
		$txtRen = ${"txtRen" . $i};
		$hddRen = ${"hddRen" . $i};
		$cbxTipAg = ${"cbxTipAg" . $i};
		$txtHraIni = ${"txtHraIni" . $i};
		$txtHraFin = ${"txtHraFin" . $i};
		$txtPh = ${"txtPh" . $i};
		$txtCe = ${"txtCe" . $i};
		$txtObs = ${"txtObs" . $i};


		if ($hddRen == '' and $cbxTipAg != '') {
			mysqli_query($cnx, "INSERT INTO procesos_fase_4b_d(pfg4_id, pfd4_ren, tpa_id, usu_id, pfd4_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$cbxTipAg', '" . $_SESSION['idUsu'] . "', SYSDATE() ) ") or die(mysqli_error($cnx) . " Error al insertar 1");
			$strMsj = "Se agrego el renglon " . $i;
		} else {

			if ($hddRen != '' and $txtHraIni  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_4b_d SET pfd4_hr_ini = '$txtHraIni' WHERE pfd4_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtHraFin  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_4b_d SET pfd4_hr_fin = '$txtHraFin' WHERE pfd4_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtPh  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_4b_d SET pfd4_ph = '$txtPh' WHERE pfd4_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtCe  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_4b_d SET pfd4_ce = '$txtCe' WHERE pfd4_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}

			if ($hddRen != '' and $txtObs  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_4b_d SET pfd4_observaciones = '$txtObs' WHERE pfd4_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
		}
	}

	if ($strMsj != '') {
		$respuesta = array('mensaje' => $strMsj);
	} else {
		if ($txtFeTerm != '' && $txtHrTerm != '' && $txtTempFinal != '') {
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '" . $_SESSION['idUsu'] . "', proa_temp_final = '$txtTempFinal' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");

			$strMsj = "Fase 4c actualizada";
		} else {
			if ($txtHrTotales != '') {
				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_ce) VALUES('" . $_SESSION['idUsu'] . "', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales', '$txtCeLib') ") or die(mysqli_error($cnx) . " Error al insertar L");

				$strMsj = "Fase 4c parametros capturados";
			} else {
				$strMsj = "Esta vacio";
			}
		}

		$respuesta = array('mensaje' => $strMsj);
	}
}


echo json_encode($respuesta);
