<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();

extract($_POST); 

//Valida el estatus del equipo nuevo
$cad_en = mysqli_query($cnx, "SELECT * FROM equipos_preparacion WHERE ep_id = '$cbxEquipo'");
$reg_en = mysqli_fetch_array($cad_en);

if($reg_en['le_id'] == 9)
{
	//si esta libre el equipo ejecuta siguientes sentencias
	mysqli_query($cnx, "update equipos_preparacion set le_id = 9 WHERE ep_id = '$txtEquipo'") or die(mysqli_error($cnx) . " Error1");

	mysqli_query($cnx, "update equipos_preparacion set le_id = 11 WHERE ep_id = '$cbxEquipo'") or die(mysqli_error($cnx) . " Error2");

	mysqli_query($cnx, "update procesos_equipos set pe_ban_activo = 0 WHERE ep_id = '$txtEquipo'") or die(mysqli_error($cnx) . " Error3");

	mysqli_query($cnx, "INSERT INTO procesos_equipos(pro_id, ep_id) 
	VALUES('$txtPro', '$cbxEquipo')") or die(mysqli_error($cnx) . " Error al insertar equipos");

	$respuesta = array('mensaje' => "Movimiento de equipo realizado");
}
else
{
	// si esta ocupado el equipo
	$respuesta = array('mensaje' => "Movimiento de equipo pendiente");
}

echo json_encode($respuesta);
?>