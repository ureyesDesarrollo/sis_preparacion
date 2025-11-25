<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
require "../../alertas/PHPMailer/Exception.php";
require "../../alertas/PHPMailer/PHPMailer.php";
require "../../alertas/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$cnx = Conectarse();
extract($_POST);
$sql_usu = mysqli_query($cnx, "SELECT * FROM usuarios WHERE usu_id = '" . $_SESSION['idUsu'] . "'") or die(mysqli_error($cnx) . "Error: en consultar usuarios");
$reg_usu = mysqli_fetch_assoc($sql_usu);

if ($txtFeIni != '' and $txtHrIni != '' and $txtTemp != '') {
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 1");

	mysqli_query($cnx, "INSERT INTO procesos_fase_7b_g(pro_id, pe_id, pfg7_temp_ag,pfg7_acido_diluido,pfg7_temp,  pfg7_acido, pfg7_norm, pfg7_ph, pfg7_ce, usu_id) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtTemp', '$cbxDiluido','$txtTemp2', '$txtAcido', '$txtNorm',  '$txtPh', '$txtCe', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 2");

	$respuesta = array('mensaje' => "Fase 7 agregada");
} else {

	for ($i = 1; $i <= 8; $i++) {
		$txtRen = ${"txtRen" . $i};
		$txtAcidoF = ${"txtAcidoF" . $i};
		$txtPhF = ${"txtPhF" . $i};
		$txtCeF = ${"txtCeF" . $i};
		$txtTempF = ${"txtTempF" . $i};
		$txtNormF = ${"txtNormF" . $i};


		if ($txtTempF == '' || !isset($txtTempF)) {
			$temp_ren = 0;
		} else {
			$temp_ren = $txtTempF;
		}
		if ($txtAcidoF == '' || !isset($txtAcidoF)) {
			$acido_ren = 0;
		} else {

			$acido_ren = $txtAcidoF;
		}

		if (isset($acido_ren) && isset($txtPhF) && isset($txtCeF) && isset($temp_ren) && isset($txtNormF)) {

			/* echo "TEMPERATURA". */
			$temp = $temp_ren;/* ."<br>"; */
			$acido = $acido_ren;



			if ($txtPhF != '' and $txtCeF != '' and $txtNormF != '') {
				mysqli_query($cnx, "INSERT INTO procesos_fase_7b_d(pfg7_id, pfd7_ren, pfd7_acido, pfd7_ph, pfd7_ce, pfd7_temp, pfd7_norm, usu_id, pfd7_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen', '$acido_ren', '$txtPhF', '$txtCeF', '$temp_ren', '$txtNormF','" . $_SESSION['idUsu'] . "', SYSDATE() ) ") or die(mysqli_error($cnx) . " Error al insertar " . $i);
				$strMsj = "Se agrego el renglon " . $i;

				//fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhF, $_SESSION['idUsu'], $lavador, $paleto, 'R', 'N', $txtNormF);
			}
		}
	}

	for ($i = 1; $i <= 6; $i++) {
		$txtRen2 = ${"txtRen2" . $i};
		$txtIniMovD = ${"txtIniMovD" . $i};
		$txtIniRepD = ${"txtIniRepD" . $i};
		$txtPhD = ${"txtPhD" . $i};
		$txtCeD = ${"txtCeD" . $i};
		$txtNormD = ${"txtNormD" . $i};
		$txtTempD = ${"txtTempD" . $i};

		if ($txtNormD == '' || !isset($txtNormD)) {
			$txtNormD = 0;
		} else {
			$txtNormD = $txtNormD;
		}


		if ($txtIniMovD != '' and $txtIniRepD != '') //  or ( and $i <= 3)
		{

			if ($txtPhD == '' and $txtCeD == '' and $txtNormD == '' and $txtTempD == '') {
				$txtPhD = 0;
				$txtCeD = 0;
				$txtNormD = 0;
				$txtTempD = 0;
			}

			mysqli_query($cnx, "INSERT INTO procesos_fase_7b_d2(pfg7_id, pfd7_ren, pfd7_ini_mov, pfd7_ini_reposo, pfd7_ph,pfd7_ce, pfd7_norm, pfd7_temp, usu_id, pfd7_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen2', '$txtIniMovD', '$txtIniRepD', '$txtPhD','$txtCeD', '$txtNormD', '$txtTempD', '" . $_SESSION['idUsu'] . "', SYSDATE() ) ") or die(mysqli_error($cnx) . " Error al insertar B" . $i);
			$strMsj = "Se agrego el renglon " . $i;

			//fnc_alertas($etapa, $parametro, $proceso, $valor, $usuario, $lavador, $paleto, $tipo);
			//fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhD, $_SESSION['idUsu'], $lavador, $paleto, 'L', 'N', $txtNormD);
			/*fnc_alertas($hdd_pe_id, 'pH', $hdd_pro_id, $txtPhD, $_SESSION['idUsu'], $lavador, $paleto, 'R');
			fnc_alertas_v2($hdd_pe_id, 'N', $hdd_pro_id, $txtNormD, $_SESSION['idUsu'], $lavador, $paleto, 'R');*/
		}
	}

	if ($txtHrTotales1 != '' and $txtFeTermA != '' and $txtHrTermA != '') {
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_hr_totales = '$txtHrTotales1', pfg7_fe_fin = '$txtFeTermA', pfg7_hr_fin = '$txtHrTermA', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - A";
	}

	if ($txtHrIniC != '' and $txtPhR1 != '' and $txtPhR2 != '') {
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_hr_ini_co = '$txtHrIniC', pfg7_agua_ph = '$txtPhR1', pfg7_cocido_ph = '$txtPhR2', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7");

		//$strMsj = "Fase 7 actualizada - B";
	}

	if ($txtCeR1 != '' and $txtCeR2 != '') {
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_agua_ce = '$txtCeR1', pfg7_cocido_ce = '$txtCeR2'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - C";
	}

	if ($cbxAgua != '') {
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET taa_id = '$cbxAgua'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7");

		//$strMsj = "Fase 7 actualizada - D";
	}

	if ($txtHrsReales != '') {
		mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_horas_reales = '$txtHrsReales'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7");

		$strMsj = "Fase 7 actualizada - E";
	}

	if ($strMsj != '') {
		$respuesta = array('mensaje' => $strMsj);
	} else {
		if ($txtFeTerm != '') {
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");

			/*mysqli_query($cnx, "UPDATE procesos_fase_5b_g SET pfg5_cocido_ph = '$txtCocido', pfg5_ph_agua = '$txtPhAgua', pfg5_ce_agua = '$txtCeAgua', pfg5_agua_a = '$txtAguaA' WHERE pfg5_id = '$hdd_pfg' ") or die(mysqli_error($cnx)." Error al actualizar 4g");*/

			$strMsj = "Fase 7 actualizada";
		} else {
			//si los dos primeros renglones de cocidos se captura algo inserta
			if ($txtPhLib1 != '' and $txtCeLib1 != '' and $txtpor_ext1 != '') {
				if ($txtSolides == '') {
					$txtSolides = 0;
				}

				if ($txtPhLib2 == '') {
					$txtPhLib2 = 'NULL';
				} else {
					$txtPhLib2 = "'$txtPhLib2'";
				}
				if ($txtCeLib2 == '') {
					$txtCeLib2 = 'NULL';
				} else {
					$txtCeLib2 = "'$txtCeLib2'";
				}


				mysqli_query($cnx, "INSERT INTO procesos_liberacion_b (usu_id, pro_id, pe_id, prol_fecha, prol_hora, prol_cocido_ph1, prol_ce1, prol_cocido_ph2, prol_ce2,prol_color_caldo, prol_color, prol_solides,prol_observaciones,prol_hr_totales) VALUES('" . $_SESSION['idUsu'] . "', '$hdd_pro_id', '$hdd_pe_id', ' " . date("Y-m-d") . "', '" . date("H:i") . "', '$txtPhLib1', '$txtCeLib1', $txtPhLib2, $txtCeLib2,'$cbxColor_caldo','$cbxColor','$txtSolides','$txta_obs','$txtHrTotales' ) ") or die(mysqli_error($cnx) . " Error al insertar x");

				$sql_liberacion = mysqli_query($cnx, "SELECT * FROM procesos_liberacion_b WHERE pro_id = '$hdd_pro_id' and pe_id = '$hdd_pe_id'") or die(mysqli_error($cnx) . "Error: en consultar procesos_liberacion_b");
				$reg_liberacion = mysqli_fetch_assoc($sql_liberacion);
				$tot = mysqli_num_rows($sql_liberacion);

				//if ($tot > 0) {

				for ($i = 1; $i <= 2; $i++) {
					$renglon = ${"R" . $i};
					$cocido = ${"txtPhLib" . $i};
					$ce = ${"txtCeLib" . $i};
					$ext = ${"txtpor_ext" . $i};
					$txtFeLib = ${"txtFeLib" . $i};
					$txtHrLib = ${"txtHrLib" . $i};


					$fecha_hora = $txtFeLib . ' ' . $txtHrLib;
					if ($cocido != '' and $ce != '' and $ext != '') {
						mysqli_query($cnx, "INSERT INTO procesos_liberacion_b_cocidos (prol_id,prol_ren , prol_cocido, prol_ce, prol_cuero_sob, prol_por_extrac,prol_fecha) VALUES('$reg_liberacion[prol_id]','$renglon', $cocido, $ce, '0', '$ext','$fecha_hora') ") or die(mysqli_error($cnx) . " Error al insertar L");
					} /*else {
							$strMsj = "No pueden quedar campos vacios del renglon cocidos" . $i;
						}*/
				}

				mysqli_query($cnx, "update equipos_preparacion set le_id = 15 WHERE ep_id = '$hdd_equipo'") or die(mysqli_error($cnx) . " Error1");

				$strMsj = "Fase 7b parametros capturados";
			} else {
				$strMsj = "Debe capturar al menos los 2 primeros renglones de cocidos";
			}
		}

		$respuesta = array('mensaje' => $strMsj);
	}
}

echo json_encode($respuesta);
