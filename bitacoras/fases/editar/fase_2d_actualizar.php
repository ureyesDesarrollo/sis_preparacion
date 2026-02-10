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

if ($txtHrasTot == '' || !isset($txtHrasTot)) {
	$txtHrasTot = 'null';
} else {
	$txtHrasTot = '$txtHrasTot';
}


//Actualiza los datos del general de la tabla
mysqli_query($cnx, "UPDATE procesos_fase_2b_g SET pfg2_temp_ag = '$txt_temp', pfg2_enzima = '$txtEnzima',pfg2_ph1 ='$txt_ph',pfg2_hr_totales = $txtHrasTot  WHERE pro_id = '$hdd_pro_id'") or die(mysqli_error($cnx) . " Error al actualizar 2");

//Actualiza los datos del detalle de la tabla
for ($i = 1; $i <= 26; $i++) {
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
	}

	mysqli_query($cnx, "UPDATE procesos_fase_2b_d SET pfd2_hr = '$txtHoraD', pfd2_ph = '$txtPhD', pfd2_sosa = '$sosa', pfd2_temp = '$temp' WHERE pfg2_id = '$hdd_pfg' AND pfd2_ren = '$txtRen';") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
}

//Actualiza los datos de la liberacion
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx) . " Error al actualizar 4");

ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj);
echo json_encode($respuesta);
