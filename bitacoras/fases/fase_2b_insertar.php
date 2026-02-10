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


if ($txtFeIni != ''  and $txtHrIni != '' and $txt_temp != '' and $txt_ph != '' and $txtEnzima != '' and $txt_ajustesosa != '') {
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 1");

	mysqli_query($cnx, "INSERT INTO procesos_fase_2b_g(pro_id, pe_id, pfg2_temp_ag, pfg2_enzima,pfg2_ph1, usu_id,pfg2_ajustesosa) 
			VALUES('$hdd_pro_id', '$hdd_pe_id','$txt_temp', '$txtEnzima','$txt_ph', '" . $_SESSION['idUsu'] . "' ,$txt_ajustesosa) ") or die(mysqli_error($cnx) . " Error al insertar 2");

	$respuesta = array('mensaje' => "Fase 2 agregada");
} else {
	for ($i = 1; $i <= 42; $i++) {
		//if($i > 2){$i+=1;}

		$txtRen = ${"txtRen" . $i};
		$txtHoraD = ${"txtHoraD" . $i};
		$txtPhD = ${"txtPhD" . $i};
		$txtSosaD = ${"txtSosaD" . $i};
		$txt_tempd = ${"txt_tempd" . $i};

		if ($txtSosaD == '' || !isset($txtSosaD)) {
			$sosa_ren = 0;
		} else {
			$sosa_ren = $txtSosaD;
		}

		if ($txt_tempd == '' || !isset($txt_tempd)) {
			$temp_ren = 0;
		} else {
			$temp_ren = $txt_tempd;
		}



		if (isset($temp_ren) && isset($sosa_ren)) {

			$sosa = $sosa_ren;
			$temp = $temp_ren;

			/* 	echo "SOSA ->" . $sosa."<br><br>";
			echo "TEMP ->" . $temp."<br><br>"; */

			if ($txtHoraD != '' and $txtPhD != '') {

				mysqli_query($cnx, "INSERT INTO procesos_fase_2b_d(pfg2_id, pfd2_ren, pfd2_hr, pfd2_ph, pfd2_sosa, pfd2_temp, pfd2_acido,usu_id, pfd2_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$txtHoraD', '$txtPhD', '$sosa', '$temp', '0','" . $_SESSION['idUsu'] . "', SYSDATE() ) ") or die(mysqli_error($cnx) . " Error al insertar " . $i);
				$strMsj = "Se agrego el renglon " . $i;
				//fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhD, $_SESSION['idUsu'], 0, 0, 'R', '0', '0');
			}
		}
	}

	if ($strMsj != '') {
		$respuesta = array('mensaje' => $strMsj);
	} else {

		if ($txtFeTerm != '' && $txtHrTerm != '' && $txtHrasTot != '') {
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");

			mysqli_query($cnx, "UPDATE procesos_fase_2b_g SET pfg2_hr_totales = '$txtHrasTot' WHERE pfg2_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 2b");

			$strMsj = "Fase 2 actualizada";
		} else {
			if ($txtHrTotales != '') {
				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales,extractibilidad) VALUES('" . $_SESSION['idUsu'] . "', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales','$txt_extractibilidad' ) ") or die(mysqli_error($cnx) . " Error al insertar L");

				$strMsj = "Fase 2 parametros capturados";
			} else {
				$strMsj = "Esta vacio";
			}
		}

		$respuesta = array('mensaje' => $strMsj);
	}
}

if (isset($hddSaltar)) {
	if ($hddSaltar == 'Si') {
		mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_ce) VALUES('" . $_SESSION['idUsu'] . "', '$hdd_pro_id', '$hdd_pe_id', '0', '0') ") or die(mysqli_error($cnx) . " Error al insertar L");

		$strMsj = "Fase 2b liberada";
	} else {
		$strMsj = "Esta vacio";
	}
}
$respuesta = array('mensaje' => $strMsj);

echo json_encode($respuesta);
