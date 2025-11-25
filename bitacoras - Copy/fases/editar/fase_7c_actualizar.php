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
	mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni', proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");
}

//Actualiza los datos del general de la tabla
mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_temp_ag = '$txtTemp', pfg7_acido_diluido = '$cbxDiluido',pfg7_temp = '$txtTemp2', pfg7_acido = '$txtAcido', pfg7_norm = '$txtNorm', pfg7_ph = '$txtPh', pfg7_ce = '$txtCe' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx) . " Error al actualizar 2");

//Actualiza los datos del detalle de la tabla
for ($i = 1; $i <= 8; $i++) {
	$txtRen = ${"txtRen" . $i};
	$txtAcidoF = ${"txtAcidoF" . $i};
	$txtPhF = ${"txtPhF" . $i};
	$txtCeF = ${"txtCeF" . $i};
	$txtTempF = ${"txtTempF" . $i};
	$txtNormF = ${"txtNormF" . $i};

	if ($txtAcidoF == '' || !isset($txtAcidoF)) {
		$acido_ren = 0;
	} else {

		$acido_ren = $txtAcidoF;
	}

	/*if($txtAcidoF != '' and $txtPhF != '' and $txtCeF != '' and $txtTempF != ''  and $txtNormF != '')
	{*/
	mysqli_query($cnx, "UPDATE procesos_fase_7b_d SET pfd7_acido = '$txtAcidoF', pfd7_ph = '$txtPhF', pfd7_ce = '$txtCeF', pfd7_temp = '$txtTempF', pfd7_norm = '$txtNormF' WHERE pfg7_id = '$hdd_pfg' AND pfd7_ren = '$txtRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
	//}
}

/*for ($i = 1; $i <= 6; $i++) {
	$txtRen2 = ${"txtRen2" . $i};
	$txtIniMovD = ${"txtIniMovD" . $i};
	$txtIniRepD = ${"txtIniRepD" . $i};
	$txtPhD = ${"txtPhD" . $i};
	$txtCeD = ${"txtCeD" . $i};
	$txtNormD = ${"txtNormD" . $i};
	$txtTempD = ${"txtTempD" . $i};

	//if($txtIniMovD != '' and $txtIniRepD != '' )//  or ( and $i <= 3)
	//{ 

	if ($txtPhD == '' and $txtCeD == '' and $txtNormD == '' and $txtTempD == '') {
		$txtPhD = 0;
		$txtCeD = 0;
		$txtNormD = 0;
		$txtTempD = 0;
	}
	mysqli_query($cnx, "UPDATE procesos_fase_7b_d2 SET pfd7_ini_mov = '$txtIniMovD', pfd7_ini_reposo = '$txtIniRepD', pfd7_ph = '$txtPhD', pfd7_ce = '$txtCeD', pfd7_norm = '$txtNormD', pfd7_temp = '$txtTempD' WHERE pfg7_id = '$hdd_pfg' AND pfd7_ren = '$txtRen2' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
	//}
}*/

//Actualiza los datos de la liberaci�n
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales', prol_ph = '$txtPhLib' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx) . " Error al actualizar 4");

//Acualiza otros datos de la fase en general
if ($txtHrTotales1 != '' and $txtFeTermA != '' and $txtHrTermA != '') {
	mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_hr_totales = '$txtHrTotales1', pfg7_fe_fin = '$txtFeTermA', pfg7_hr_fin = '$txtHrTermA', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7-A");
}

if ($txtPhR1 != '') {
	mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_agua_ph = '$txtPhR1', usu_sup = '" . $_SESSION['idUsu'] . "' WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7-B");
}

if ($txtCeR1 != '') { // and $txtCeR2 != ''
	mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_agua_ce = '$txtCeR1'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7-C");
}

if ($txtTemR1 != '') {
	mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_tem_final = '$txtTemR1'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7-C");
}

if ($cbxAgua != '') {
	mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET taa_id = '$cbxAgua'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7-D");
}

if ($txtHrsReales != '') {
	mysqli_query($cnx, "UPDATE procesos_fase_7b_g SET pfg7_horas_reales = '$txtHrsReales'  WHERE pfg7_id = '$hdd_pfg' ") or die(mysqli_error($cnx) . " Error al actualizar 7-E");
}

ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj);
echo json_encode($respuesta);
