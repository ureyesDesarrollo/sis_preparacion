<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();



extract($_POST);

if (!isset($cheMolino1)) {
	$cheMolino1 = 0;
}

if (!isset($cheMolino2)) {
	$cheMolino2 = 0;
}

if (!isset($cheMolino3)) {
	$cheMolino3 = 0;
}

if (!isset($cheMolino4)) {
	$cheMolino4 = 0;
}

if (!isset($cheMolino5)) {
	$cheMolino5 = 0;
}

if ($cbxPila2 == '') {
	$cbxPila2 = 0;
}

if ($txtPh2 == '') {
	$txtPh2 = 0;
}

if ($txtCe2 == '') {
	$txtCe2 = 0;
}

if ($txtTemp2 == '') {
	$txtTemp2 = 0;
}

mysqli_query($cnx, "UPDATE procesos SET  pro_fe_carga = '$txtFechaCarga', pro_hr_inicio = '$txtHrIni', pro_hr_fin = '$txtHrFin', pro_molino1 = '$cheMolino1', pro_molino2 = '$cheMolino2', pro_molino3 = '$cheMolino3', pro_molino4 = '$cheMolino4', pro_molino5 = '$cheMolino5', pro_pila = '$cbxPila', pro_ph = '$txtPh', pro_temp = '$txtTemp', pro_ce = '$txtCe', pro_pila2 = '$cbxPila2', pro_ph2 = '$txtPh2', pro_temp2 = '$txtTemp2', pro_ce2 = '$txtCe2', pro_col_limp = '$radColador', pro_cuero = '$radCe', pro_operador = '$hdd_user', pro_fe_sistema = '" . date("Y-m-d H:i:s") . "', pro_observaciones='$textObs', pro_tam_cuero = '$cbx_tamano' WHERE pro_id = '$hdd_pro_id' ") or die(mysqli_error($cnx) . " Error al insertar");

$pro_id = mysqli_insert_id($cnx); //recupera el ultimo id de la conexion

/* mysqli_query($cnx, "SELECT * FROM procesos_equipos where pro_id = '" . $hdd_pro_id . "' and ep_id = '$hdd_equipo'") or die(mysqli_error($cnx) . " Error al insertar equipos");
 */
mysqli_query($cnx, "UPDATE equipos_preparacion SET le_id = '11' WHERE ep_id = '$hdd_equipo' ") or die(mysqli_error($cnx) . " Error al actualizar"); //Ocupa el Lavador

$respuesta = array('mensaje' => "Informacion agregada");
echo json_encode($respuesta);
