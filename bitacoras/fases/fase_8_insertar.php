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

	mysqli_query($cnx, "INSERT INTO procesos_fase_8_g(pro_id, pe_id, usu_id) VALUES('$hdd_pro_id', '$hdd_pe_id', '" . $_SESSION['idUsu'] . "' ) ") or die(mysqli_error($cnx) . " Error al insertar 2");

	$respuesta = array('mensaje' => "Fase 8 agregada");
} else {
	for ($i = 1; $i <= 10; $i++) {
		$txtRen = ${"txtRen" . $i};
		$hddRen = ${"hddRen" . $i};
		$txtMov = ${"txtMov" . $i};
		$txtIniLlen = ${"txtIniLlen" . $i};
		$txtFinLlen = ${"txtFinLlen" . $i};
		$txtPh = ${"txtPh" . $i};
		$txtCe = ${"txtCe" . $i};
		$txtObs = ${"txtObs" . $i};
		$cbxTipAg = ${"cbxTipAg" . $i};

		if ($hddRen == '' and $txtIniLlen != '') {
			mysqli_query($cnx, "INSERT INTO procesos_fase_8_d (pfg8_id, pfd8_ren,tpa_id, pfd8_mov, usu_id, pfd8_fe_hr_sys,pfd8_ini_llenado) VALUES('$hdd_pfg', '$txtRen','0','0', '" . $_SESSION['idUsu'] . "', SYSDATE(),'$txtIniLlen' )") or die(mysqli_error($cnx) . " Error al insertar 1");
			$strMsj = "Se agrego el renglon " . $i;
		} else {
			if ($hddRen != '' and $txtFinLlen  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_fin_llenado = '$txtFinLlen' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}


			if ($hddRen != '' and $txtPh  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_ph = '$txtPh' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
			if ($hddRen != '' and $txtCe  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_ce = '$txtCe' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}

			if ($hddRen != '' and $txtObs  != '') {
				mysqli_query($cnx, "UPDATE procesos_fase_8_d SET pfd8_observaciones = '$txtObs' WHERE pfd8_id = '$hddRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
				$strMsj = "Se modifico el renglon " . $i;
			}
		}
	}

	/* if ($txtFeLibProd != '' and $txtHrLibProd != '') {
		mysqli_query($cnx, "UPDATE procesos_fase_8_g SET pfg8_fe_lib_prod = '$txtFeLibProd', pfg8_hr_lib_prod = '$txtHrLibProd' WHERE pfg8_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 6");

		$strMsj = "Fase 8 actualizada - C";
		$respuesta = array('mensaje' => $strMsj);
	} */

	/* if ($strMsj != '') {
		$respuesta = array('mensaje' => $strMsj);
	} else { */
	if ($txtHrasTotales != '' and $txtHrTotales1 != '') {
		mysqli_query($cnx, "UPDATE procesos_fase_8_g SET pfg8_hr_totales2 = '$txtHrasTotales',pfg8_hr_totales = '$txtHrTotales1', usu_sup = '" . $_SESSION['idUsu'] . "'  WHERE pfg8_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 8");

		mysqli_query($cnx, "UPDATE procesos SET hrs_totales_capturadas = '$txtHrasTotales' , pro_hrs_tot_muerto = '$txtHrsMuerto' WHERE pro_id = '$hdd_pro_id' ") or die(mysqli_error($cnx) . " Error al actualizar 8");

		if ($txtFeTerm != '') {
			mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");
		}
		$strMsj = "Fase 8 actualizada";
	} else {
		if ($reg_usu['up_id'] != '3') {
			//si los dos primeros renglones de cocidos se captura algo inserta
			//if ($txtFeLib != '' and $txtHrLib != '' and $txtPhLib1 != '' and $txtCeLib1 != '' and $txtpor_ext1 != '' and $txtPhLib2 != '' and $txtCeLib2 != '' and $txtpor_ext2 != '') {
			//if ($txtPhLib1 != '' and $txtCeLib1 != '' and $txtpor_ext1 != '' and $txtPhLib2 != '' and $txtCeLib2 != '' and $txtpor_ext2 != '') {
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

				$strMsj = "Fase 8 parametros capturados";
			} else {
				$strMsj = "Debe capturar al menos los 2 primeros renglones de cocidos";
			}
		}
	}

	if ($strMsj != '') {
		$respuesta = array('mensaje' => $strMsj);
	} else {
		$strMsj = "Hay campos vacios";
		$respuesta = array('mensaje' => $strMsj);
	}
	/* } */
}

echo json_encode($respuesta);
