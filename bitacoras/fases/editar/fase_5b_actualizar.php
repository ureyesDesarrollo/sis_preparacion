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
mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");

mysqli_query($cnx, "UPDATE procesos_auxiliar SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni', proa_fe_fin = '$txtFeTerm', proa_hr_fin = '$txtHrTerm', proa_observaciones = '$txaObservaciones' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1");

if ($cbxAgua == '') {
	$cbxAgua = 0;
}
//Actualiza los datos del general de la tabla
mysqli_query($cnx, "UPDATE procesos_fase_5b_g  SET pfg5_temp_ag= '$txtTemp',pfg5_temp = '$txtTemp2',pfg5_acido ='$txtAcido',pfg5_termina ='$txtTermina' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx) . " Error al actualizar 2");

/* mysqli_query($cnx, "UPDATE procesos_fase_5b_g SET proa_fe_ini = '$txtFeIni', proa_hr_ini = '$txtHrIni', pfg5_temp_ag= '$txtTemp',pfg5_temp = '$txtTemp2',pfg5_acido ='$txtAcido',pfg5_termina ='$txtTermina' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' AND proa_id = '$hdd_proa' ") or die(mysqli_error($cnx) . " Error al actualizar 1"); */


//Actualiza los datos del detalle de la tabla
for ($i = 1; $i <= 10; $i++) {
	$txtRen = ${"txtRen" . $i};
	$txtAcidoF = ${"txtAcidoF" . $i};
	$txtPhF = ${"txtPhF" . $i};

	$str_update = "pfd5_ren = '$txtRen' ";

	if ($txtAcidoF != '' or $txtPhF != '') {

		if ($txtAcidoF != '') {
			$str_update .= ",pfd5_acido = '$txtAcidoF'";
		}
		if ($txtPhF != '') {
			$str_update .= ", pfd5_ph = '$txtPhF'";
		}

		mysqli_query($cnx, "UPDATE procesos_fase_5b_d SET $str_update WHERE pfg5_id = '$hdd_pfg' AND pfd5_ren = '$txtRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
	}
}

//Actualiza los datos de la liberaci�n
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales', prol_ce = '$txtCeLib' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx) . " Error al actualizar 4");

ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj);
echo json_encode($respuesta);
