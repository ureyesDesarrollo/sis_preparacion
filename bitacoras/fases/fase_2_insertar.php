<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
/* require("../../alertas/class.phpmailer.php"); */
require "../../alertas/PHPMailer/Exception.php";
require "../../alertas/PHPMailer/PHPMailer.php";
require "../../alertas/PHPMailer/SMTP.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$cnx = Conectarse();



extract($_POST);

if ($txtFeIni != '' and $txtHrIni != '' and $txtPhAnt != '' and $txtCe != '' and $txtAjSosa != '' and $txtPhAj != '' and $txtPeroxido != '' and $txtPhAnt != ' ' and $txtCe != ' ' and $txtAjSosa != ' ' and $txtPhAj != ' ' and $txtPeroxido != '') {
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 1");

	mysqli_query($cnx, "INSERT INTO procesos_fase_2_g(pro_id, pe_id, pfg2_temp_ag, pfg2_ph_ant, pfg2_ce, pfg2_sosa, pfg2_ph_aju, pfg2_peroxido) VALUES('$hdd_pro_id', '2', '0', '$txtPhAnt', '$txtCe', '$txtAjSosa', '$txtPhAj', '$txtPeroxido' ) ") or die(mysqli_error($cnx) . " Error al insertar 2");

	$respuesta = array('mensaje' => "Fase 2 agregada");
} else {
	for ($i = 1; $i <= 10; $i++) {
		$txtRen = ${"txtRen" . $i};
		$txtHr = ${"txtHr" . $i};
		$txtPh = ${"txtPh" . $i};
		$txtSosa = ${"txtSosa" . $i};
		$txtTemp = ${"txtTemp" . $i};
		$txtRedox = ${"txtRedox" . $i};
		//$txtAcidoX = ${"txtAcidoX".$i};
		/* 	$txtPeroxido = ${"txtPeroxido" . $i}; */



		if ($txtHr != '' and $txtPh != '' and $txtRedox != '') {
			if ($txtTemp == '') {
				$txtTemp = 0;
			}
			if ($txtSosa == '') {
				$txtSosa = 0;
			}
			mysqli_query($cnx, "INSERT INTO procesos_fase_2_d(pfg2_id, pfd2_ren, pfd2_hr, pfd2_ph, pfd2_sosa, pfd2_peroxido, pfd2_temp, pfd2_redox, pfd2_acido, usu_id, pfd2_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$txtHr', '$txtPh', '$txtSosa', '0','$txtTemp', '$txtRedox', '0', '" . $_SESSION['idUsu'] . "', SYSDATE() ) ") or die(mysqli_error($cnx) . " Error al insertar " . $i);
			$strMsj = "Se agrego el renglon " . $i;

			fnc_alertas($hdd_pe_id, 'ph', $hdd_pro_id, $txtPh, $_SESSION['idUsu'], 0, 0, 'R', 'ppm', $txtRedox);
			
		}
	}
	if ($strMsj != '') {
		$respuesta = array('mensaje' => $strMsj);
	} else {
		if ($txtFeTerm != '') {
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");

			$strMsj = "Fase 2 actualizada";
		} else {
			if ($txtHrTotales != '') {

				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_peroxido, prol_ph, prol_color) VALUES('" . $_SESSION['idUsu'] . "', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales', NULL,'$txtPhLib', '$cbxColor') ") or die(mysqli_error($cnx) . " Error al insertar L");

				$strMsj = "Fase 2 parametros capturados";
			} else {
				$strMsj = "Esta vacio";
			}
		}

		$respuesta = array('mensaje' => $strMsj);
	}
}


echo json_encode($respuesta);
