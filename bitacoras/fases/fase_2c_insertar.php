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

if ($txtFeIni != '' and $txtHrIni != '' and $txtTemp != '' and $txtPhAnt != '' and $txtCe != '' and $txtAjSosa != '' and $txtPhAj != '' and  $txtPeroxido != '' and $txtTemp != ' ' and $txtPhAnt != ' ' and $txtCe != ' ' and $txtAjSosa != ' ' and $txtPhAj != ' ' and  $txtPeroxido != ' ') {
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 1");

	mysqli_query($cnx, "INSERT INTO procesos_fase_2_g(pro_id, pe_id, pfg2_temp_ag, pfg2_ph_ant, pfg2_ce, pfg2_sosa, pfg2_ph_aju, pfg2_peroxido) VALUES('$hdd_pro_id', '4', '$txtTemp', '$txtPhAnt', '$txtCe', '$txtAjSosa', '$txtPhAj', '$txtPeroxido' ) ") or die(mysqli_error($cnx) . " Error al insertar 2");

	$respuesta = array('mensaje' => "Fase 2c agregada");
} else {
	for ($i = 1; $i <= 15; $i++) {
		$txtRen = ${"txtRen" . $i};
		$txtHr = ${"txtHr" . $i};
		$txtPh = ${"txtPh" . $i};
		$txtSosa = ${"txtSosa" . $i};
		$txtTemp = ${"txtTemp" . $i};
		$txtRedox = ${"txtRedox" . $i};
		//$txtAcido = ${"txtAcido".$i};

		if ($txtRedox == '') {
			$txtRedox = 0;
		}

		if ($txtHr != '' and $txtPh != '' and $txtSosa != '' and $txtTemp != '') // and $txtRedox != ''
		{
			mysqli_query($cnx, "INSERT INTO procesos_fase_2_d(pfg2_id, pfd2_ren, pfd2_hr, pfd2_ph, pfd2_sosa, pfd2_acido,pfd2_peroxido, pfd2_temp, pfd2_redox, usu_id, pfd2_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$txtHr', '$txtPh', '$txtSosa', '0', '0', '$txtTemp', '$txtRedox', '" . $_SESSION['idUsu'] . "', SYSDATE() ) ") or die(mysqli_error($cnx) . " Error al insertar " . $i);
			$strMsj = "Se agrego el renglon " . $i;

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
			//fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPh, $_SESSION['idUsu'], $lavador, $paleto, 'R', '0', '0');
			//fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPh, $_SESSION['idUsu'], $lavador, $paleto, 'R');
			//if ($txtRen <= '4') {
				//echo "renglon  dentro de la funcion= ".$txtRen;
				//fnc_alertas($etapa, $parametro, $proceso, $valor, $usuario, $lavador, $paleto, $tipo);
				//fnc_alertas($hdd_pe_id, 'ppm', $hdd_pro_id, $txtRedox, $_SESSION['idUsu'], $lavador, $paleto, 'R', '0', '0');
				//fnc_alertas_v2($hdd_pe_id, 'ppm', $hdd_pro_id, $txtRedox, $_SESSION['idUsu'], $lavador, $paleto, 'R');
			//}
		}
	}

	if ($strMsj != '') {
		$respuesta = array('mensaje' => $strMsj);
	} else {
		if ($txtFeTerm != '') {
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");

			$strMsj = "Fase 2c actualizada";
		} else {
			if ($txtHrTotales != '') {
				mysqli_query($cnx, "INSERT INTO procesos_liberacion (usu_id, pro_id, pe_id, prol_hr_totales, prol_ph, prol_color,extractibilidad ) VALUES('" . $_SESSION['idUsu'] . "', '$hdd_pro_id', '$hdd_pe_id', '$txtHrTotales', '$txtPhLib', '$cbxColor',0) ") or die(mysqli_error($cnx) . " Error al insertar L");

				$strMsj = "Fase 2c parametros capturados";
			} else {
				$strMsj = "Esta vacio";
			}
		}

		$respuesta = array('mensaje' => $strMsj);
	}
}


echo json_encode($respuesta);
