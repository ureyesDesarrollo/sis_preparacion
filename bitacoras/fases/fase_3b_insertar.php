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

if ($txt_lavador == '') {
	$lavador = '0';
} else {
	$lavador = "$txt_lavador";
}

if ($txt_paleto == '') {
	$paleto = '0';
} else {
	$paleto = "$txt_paleto";
}


if ($txtFeIni != '') { //echo $txtTempA;
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 1");

	mysqli_query($cnx, "INSERT INTO procesos_fase_3b_g(pro_id,pe_id, pfg3_temp_ag, pfg3_lts, pfg3_ph, pfg3_norm, pfg3_temp, usu_id) VALUES('$hdd_pro_id', '$hdd_pe_id','$txtTempA', '$txtSosa', '$txtPh', '$txtNorm', '$txtTemp', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 2");

	$respuesta = array('mensaje' => "Fase 3b agregada");
} else {
	for ($i = 1; $i <= 44; $i++) {
		$txtRen = ${"txtRen" . $i};
		$txtFecha = ${"txtFecha" . $i};
		$txtHora = ${"txtHoraTb" . $i};
		$txtTemp = ${"txtTemp" . $i};
		$txtNorm = ${"txtNorm" . $i};
		$txtSosa = ${"txtSosa" . $i};
		$txtMovimiento = ${"txtMovimiento" . $i};
		$txtReposo = ${"txtReposo" . $i};
		//echo $txtFecha."-".$txtHora."-".$txtTemp."-".$txtNorm."-".$txtMovimiento."-".$txtReposo."\n";	
		if ($txtFecha != '' and $txtHora != '' and $txtTemp != '' and $txtNorm != '' and $txtMovimiento != '' and $txtReposo != '' and $txtSosa != '') {
			mysqli_query($cnx, "INSERT INTO procesos_fase_3b_d(pfg3_id, pfd3_ren, pfd3_fecha, pfd3_hr, pfd3_temp, pfd3_norm, pfd3_sosa, pfd3_movimiento, pfd3_reposo, usu_id, pfd3_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$txtFecha', '$txtHora', '$txtTemp', '$txtNorm', '$txtSosa', '$txtMovimiento', '$txtReposo', '" . $_SESSION['idUsu'] . "', SYSDATE() ) ") or die(mysqli_error($cnx) . " Error al insertar " . $i);

			$strMsj = "Se agrego el renglon " . $i;

			//fnc_alertas($hdd_pe_id, 'N', $hdd_pro_id, $txtNorm, $_SESSION['idUsu'], $lavador, $paleto, 'R', 'Temp', $txtTemp);
		}
	}

	for ($i = 1; $i <= 2; $i++) {
		$txtNormSb = ${"txtNormSb" . $i};
		$txtHoraSb = ${"txtHoraSb" . $i};

		if ($txtNormSb != '' and $txtHoraSb != '' and $i == 1) {
			mysqli_query($cnx, "UPDATE procesos_fase_3b_g SET pfg3_norm1 = '$txtNormSb', pfg3_hr1 = '$txtHoraSb', pfg3_usu1 = '" . $_SESSION['idUsu'] . "' WHERE pfg3_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 3b -" . $i . " de chequeo");
			$strMsj = "Se agrego el renglon " . $i;
		} else if ($txtNormSb != '' and $txtHoraSb != '' and $i == 2) {
			mysqli_query($cnx, "UPDATE procesos_fase_3b_g SET pfg3_norm2 = '$txtNormSb', pfg3_hr2 = '$txtHoraSb', pfg3_usu2 = '" . $_SESSION['idUsu'] . "' WHERE pfg3_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 3b -" . $i . " de chequeo");
			$strMsj = "Se agrego el renglon " . $i;
		}
	}

	if ($strMsj != '') {
		$respuesta = array('mensaje' => $strMsj);
	} else {



		if ($txtFeTerm != ''  and $txtHrTerm != '' and $txtHrasTot != '') {
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");

			mysqli_query($cnx, "UPDATE procesos_fase_3b_g SET pfg3_hr_totales = '$txtHrasTot' WHERE pfg3_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 3b");

			$strMsj = "Fase 3 actualizada";
		} else {
			if ($txtHrTotales != '') {
				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales) VALUES('" . $_SESSION['idUsu'] . "', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales' ) ") or die(mysqli_error($cnx) . " Error al insertar L");

				//fnc_alertas($hdd_pe_id, 'Hr', $txtHrTotales, $txtPhF, $_SESSION['idUsu'], $lavador, $paleto, 'R', '0', '0');
				//fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhS, $_SESSION['idUsu'], $lavador, $paleto, 'L');
				//fnc_alertas_v2($hdd_pe_id, 'Hr', $hdd_pro_id, $txtHrTotales, $_SESSION['idUsu'], $lavador, $paleto, 'L');

				$strMsj = "Fase 3 parametros capturados";
			} else {
				$strMsj = "Esta vacio";
			}
		}

		$respuesta = array('mensaje' => $strMsj);
	}
}


echo json_encode($respuesta);
