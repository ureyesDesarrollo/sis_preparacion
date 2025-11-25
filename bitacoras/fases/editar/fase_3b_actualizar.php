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
if ($txtHrasTot != '') {
	mysqli_query($cnx, "UPDATE procesos_fase_3b_g SET pfg3_temp_ag = '$txtTempA', pfg3_temp = '$txtTemp', pfg3_lts = '$txtSosa', pfg3_ph = '$txtPh', pfg3_norm = '$txtNorm', pfg3_temp = '$txtTemp', pfg3_hr_totales = '$txtHrasTot' WHERE pro_id = '$hdd_pro_id'  and pe_id = '$hdd_pe_id'") or die(mysqli_error($cnx) . " Error al actualizar 2");
} else {

	mysqli_query($cnx, "UPDATE procesos_fase_3b_g SET pfg3_temp_ag = '$txtTempA', pfg3_temp = '$txtTemp', pfg3_lts = '$txtSosa', pfg3_ph = '$txtPh', pfg3_norm = '$txtNorm', pfg3_temp = '$txtTemp' WHERE pro_id = '$hdd_pro_id'  and pe_id = '$hdd_pe_id'") or die(mysqli_error($cnx) . " Error al actualizar 2");
}


//Actualiza los datos del detalle de la tabla
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
	/*if($txtFecha != '' and $txtHora != '' and $txtTemp != '' and $txtNorm != '' and $txtMovimiento != '' and $txtReposo != '' and $txtSosa != '')
	{*/
	mysqli_query($cnx, "UPDATE procesos_fase_3b_d SET pfd3_fecha = '$txtFecha', pfd3_hr = '$txtHora', pfd3_temp = '$txtTemp', pfd3_norm = '$txtNorm', pfd3_sosa = '$txtSosa', pfd3_movimiento = '$txtMovimiento', pfd3_reposo = '$txtReposo' WHERE pfg3_id = '$hdd_pfg' AND pfd3_ren = '$txtRen' ") or die(mysqli_error($cnx) . " Error al actualizar el renglon " . $i);
	//}
}

//Actualiza los datos de la liberaci�n
mysqli_query($cnx, "UPDATE procesos_liberacion SET prol_hr_totales = '$txtHrTotales' WHERE pro_id = '$hdd_pro_id' AND pe_id = '$hdd_pe_id' ") or die(mysqli_error($cnx) . " Error al actualizar 4");

ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_pro_id, '14');

$strMsj = "Informacion Actualizada";
$respuesta = array('mensaje' => $strMsj);
echo json_encode($respuesta);
