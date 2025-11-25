<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();


extract($_POST);
$sql_usu = mysqli_query($cnx, "SELECT * FROM usuarios WHERE usu_id = '" . $_SESSION['idUsu'] . "'") or die(mysqli_error($cnx) . "Error: en consultar usuarios");
$reg_usu = mysqli_fetch_assoc($sql_usu);

if ($txtFeIni != '') {
	mysqli_query($cnx, "INSERT INTO procesos_auxiliar(pro_id, pe_id, proa_fe_ini, proa_hr_ini, usu_op) VALUES('$hdd_pro_id', '$hdd_pe_id', '$txtFeIni', '$txtHrIni', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 1");

	mysqli_query($cnx, "INSERT INTO procesos_fase_7_g(pro_id,pe_id, pfg7_hr_totales, usu_id) VALUES('$hdd_pro_id','$hdd_pe_id', '0', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 2");

	$respuesta = array('mensaje' => "Fase 7 agregada");
} else {
	for ($i = 1; $i <= 10; $i++) {
		$txtRen = ${"txtRen" . $i};
		$hddRen = ${"hddRen" . $i};
		$txtMov = ${"txtMov" . $i};
		$txtHIniDrenado = ${"txtHIniDrenado" . $i};
		$txtHFinDrenado = ${"txtHFinDrenado" . $i};
		$txtPh = ${"txtPh" . $i};
		$txtCe = ${"txtCe" . $i};
		$txtTemp = ${"txtTemp" . $i};
		$txtObs = ${"txtObs" . $i};

		if ($hddRen == '' and $txtMov != '') {
			mysqli_query($cnx, "INSERT INTO procesos_fase_7_d(pfg7_id, pfd7_ren,tpa_id, pfd7_mov, usu_id, pfd7_fe_hr_sys) VALUES('$hdd_pfg', '$txtRen','0', '$txtMov', '" . $_SESSION['idUsu'] . "', SYSDATE() ) ") or die(mysqli_error($cnx) . " Error al insertar renglon " . $i);
			$strMsj = "Se agrego el renglon " . $i;
		} else {

			if ($hddRen != '' and $txtHIniDrenado != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_hr_ini_dren = '$txtHIniDrenado' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtHFinDrenado  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_hr_fin_dren = '$txtHFinDrenado' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtTemp != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_temp = '$txtTemp' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtPh  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_ph = '$txtPh' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtCe  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_ce = '$txtCe' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}

			if ($hddRen != '' and $txtObs  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_7_d SET pfd7_observaciones = '$txtObs' WHERE pfd7_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
		}
	}


	if ($txtFeLibPal != '' and $txtHrLibPal != '') {
		mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_fe_lib_pal = '$txtFeLibPal', pfg7_hr_lib_pal = '$txtHrLibPal' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 6");

		$strMsj = "Fase 7 actualizada - C";
	}

	if ($txtFeLibProd != '' and $txtHrLibProd != '') {
		mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_fe_lib_prod = '$txtFeLibProd', pfg7_hr_lib_prod = '$txtHrLibProd' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 6");

		$strMsj = "Fase 7 actualizada - D";
	}

	if ($strMsj != '') {
		$respuesta = array('mensaje' => $strMsj);
	} else { //penultima llave

		if ($txtHrasTotales != '') {

			mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_hr_totales2 = '$txtHrasTotales' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7");

			mysqli_query($cnx, "UPDATE procesos SET hrs_totales_capturadas = '$txtHrasTotales', pro_hrs_tot_muerto = '$txtHrsMuerto' WHERE pro_id = '$hdd_pro_id' ") or die(mysqli_error($cnx) . " Error al actualizar 7");

			if ($txtFeTerm_1 != '' and $txtHrTerm != '' && $txtFeTerm_1 != '') {
				mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm_1', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");

				mysqli_query($cnx, "UPDATE procesos_fase_7_g SET pfg7_hr_totales = '$txtHrTotales_1', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7");
			}
			$strMsj = "Fase 7 actualizada";
		} else { //anterpenultima llave
			if ($reg_usu['up_id'] != '3') {
				//si los dos primeros renglones de cocidos se captura algo inserta
				if ($txtFeLib != '' and $txtHrLib != '' and $txtPhLib1 != '' and $txtCeLib1 != '' and $txtPhLib2 != '' and $txtCeLib2 != '' and $txt_pro_extr3 != '') {
					if ($txtSolides == '') {
						$txtSolides = 0;
					}

					mysqli_query($cnx, "INSERT INTO procesos_liberacion_b (usu_id, pro_id, pe_id, prol_fecha, prol_hora, prol_cocido_ph1, prol_ce1, prol_cocido_ph2, prol_ce2,prol_color_caldo, prol_color,prol_solides, prol_observaciones,prol_por_extrac) VALUES('" . $_SESSION['idUsu'] . "', '$hdd_pro_id', '$hdd_pe_id', '$txtFeLib', '$txtHrLib', '$txtPhLib1', '0', '$txtPhLib2', '0', '$cbxColor_caldo','$cbxColor', '$txtSolides','$txta_obs','$txt_pro_extr3') ") or die(mysqli_error($cnx) . " Error al insertar L");

					$sql_liberacion = mysqli_query($cnx, "SELECT * FROM procesos_liberacion_b WHERE pro_id = '$hdd_pro_id' and pe_id = '$hdd_pe_id'") or die(mysqli_error($cnx) . "Error: en consultar procesos_liberacion_b");
					$reg_liberacion = mysqli_fetch_assoc($sql_liberacion);
					$tot = mysqli_num_rows($sql_liberacion);

					//if ($tot > 0) {

					for ($i = 1; $i <= 2; $i++) {
						$renglon = ${"R" . $i};
						$cocido = ${"txtPhLib" . $i};
						$ce = ${"txtCeLib" . $i};
						$cuero = ${"txtCue_sob" . $i};
						$ext = ${"txtpor_ext" . $i};

						if ($cocido != '' and $ce != '') {
							mysqli_query($cnx, "INSERT INTO procesos_liberacion_b_cocidos (prol_id,prol_ren , prol_cocido, prol_ce, prol_cuero_sob, prol_por_extrac) VALUES('$reg_liberacion[prol_id]','$renglon', '$cocido', '$ce', '0', '0') ") or die(mysqli_error($cnx) . " Error al insertar L");
						} else {
							$strMsj = "No pueden quedar campos vacios del renglon cocidos" . $i;
						}
					}
					//}
					//liberar por control de calidad
					mysqli_query($cnx, "update equipos_preparacion set le_id = 15 WHERE ep_id = '$hdd_equipo'") or die(mysqli_error($cnx) . " Error1");

					$strMsj = "Fase 7 parametros capturados";
				} else {
					$strMsj = "Debe capturar al menos los 2 primeros renglones de cocidos y la extractibilidad";
				}
			}
		}
		if ($strMsj != '') {
			$respuesta = array('mensaje' => $strMsj);
		} else {
			$strMsj = "Debe capturar al menos los 2 primeros campos del renglon";
			$respuesta = array('mensaje' => $strMsj);
		}
	}
}

echo json_encode($respuesta);
