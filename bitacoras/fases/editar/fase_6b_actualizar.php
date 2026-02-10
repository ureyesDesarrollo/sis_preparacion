<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Abril - 2019*/

include "../../../seguridad/user_seguridad.php";
require_once('../../../conexion/conexion.php');
include "../../../funciones/funciones.php";
$cnx = Conectarse();

extract($_POST);

//Actualiza los datos del auxiliar
if ($txtFeTerm == '' and $txtHrTerm == '') {
	mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");
} else {
	mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni', proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones',proa_temp_final = '$txtTempFinal' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");
}


//Actualiza los datos del general de la tabla
mysqli_query($cnx, "UPDATE procesos_fase_6b_g SET pfg6_temp_ag = '$txtTemp' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx) . " Error al actualizar 2");


//Actualiza los datos del detalle de la tabla
for ($i = 1; $i <= 25; $i++) {
	$txtRen = ${"txtRen" . $i};
	$cbxTipAg = ${"cbxTipAg" . $i};
	$txtTemp = ${"txtTemp" . $i};
	$txtHraIni = ${"txtHraIni" . $i};
	$txtHraFin = ${"txtHraFin" . $i};
	$txtPh = ${"txtPh" . $i};
	$txtCe = ${"txtCe" . $i};
	$txtObs = ${"txtObs" . $i};

	$str_update = "pfd6_ren = '$txtRen' ";

	if ($cbxTipAg != '' or $txtTemp != '' or $txtHraIni != '' or $txtHraFin != '' or $txtPh != '' or $txtCe != '') {
		if ($cbxTipAg != '') {
			$str_update .= ", tpa_id = '$cbxTipAg'";
		}
		if ($txtTemp != '') {
			$str_update .= ", pfd6_temp = '$txtTemp'";
		}
		if ($txtHraIni != '') {
			$str_update .= ", pfd6_hr_ini = '$txtHraIni'";
		}
		if ($txtHraFin != '') {
			$str_update .= ", pfd6_hr_fin = '$txtHraFin'";
		}
		if ($txtPh != '') {
			$str_update .= ", pfd6_ph = '$txtPh'";
		}
		if ($txtCe != '') {
			$str_update .= ", pfd6_ce = '$txtCe'";
		}
		if ($txtObs != '') {
			$str_update .= ", pfd6_observaciones = '$txtObs' ";
		}
		mysqli_query($cnx, "UPDATE procesos_fase_6b_d SET $str_update WHERE pfg6_id = '$hdd_pfg' AND pfd6_ren = '$txtRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
	}
}

//Actualiza los datos de la liberaci�n
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales', prol_ce = '$txtCeLib' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx) . " Error al actualizar 4");

ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj);
echo json_encode($respuesta);
