<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();
date_default_timezone_set('America/Mexico_City');

extract($_POST);
// Verificar la conexión a la base de datos
if ($cnx) {
	//Valida el estatus del equipo nuevo
	$cad_en = mysqli_query($cnx, "SELECT * FROM equipos_preparacion WHERE ep_id = '$cbxEquipo'");
	$reg_en = mysqli_fetch_array($cad_en);

	//libera equipo anterior
	mysqli_query($cnx, "update equipos_preparacion set le_id = 14 WHERE ep_id = '$txtEquipo'") or die(mysqli_error($cnx) . " Error1");

	mysqli_query($cnx, "INSERT INTO bitacora_cambio_estatus(bce_fecha, usu_id, bce_est_actual, bce_est_nuevo, bce_descripcion, pp_id, pl_id, ep_id) VALUES ('" . date("Y-m-d H:i:s") . "', '" . $_SESSION['idUsu'] . "', 11, 9, 'Movimiento de equipo 1', 0, 0,'$txtEquipo') ") or die(mysqli_error($cnx) . " Error al actualizar");

	//marca como ocupado el equipo nuevo
	mysqli_query($cnx, "update equipos_preparacion set le_id = 11 WHERE ep_id = '$cbxEquipo'") or die(mysqli_error($cnx) . " Error2");

	mysqli_query($cnx, "INSERT INTO bitacora_cambio_estatus(bce_fecha, usu_id, bce_est_actual, bce_est_nuevo, bce_descripcion, pp_id, pl_id, ep_id) VALUES ('" . date("Y-m-d H:i:s") . "', '" . $_SESSION['idUsu'] . "', 9, 11, 'Movimiento de equipo 1', 0, 0,'$cbxEquipo') ") or die(mysqli_error($cnx) . " Error al actualizar");

	//marca como equipo inactivo el equipo anterior
	mysqli_query($cnx, "update procesos_equipos set pe_ban_activo = 0 WHERE ep_id = '$txtEquipo'") or die(mysqli_error($cnx) . " Error3");

	if ($reg_en['le_id'] == 9) {

		//CREO QUE ESTE INSERT YA NO ES NECESARIO, CON LA BITACORA SE RESUELVE
		//si esta libre el equipo ejecuta siguientes sentencias
		mysqli_query($cnx, "INSERT INTO procesos_equipos(pro_id, ep_id) 
	VALUES('$txtPro', '$cbxEquipo')") or die(mysqli_error($cnx) . " Error al insertar equipos");

		//selecciona el dato agrupador del proceso
		$cad_pa_new = mysqli_query($cnx, "SELECT pa_id FROM procesos_agrupados where pro_id = '$txtPro'") or die(mysqli_error($cnx) . " Error5");
		$reg_pa_new = mysqli_fetch_array($cad_pa_new);


		mysqli_query($cnx, "INSERT INTO bitacora_equipos (pro_id, pa_id, ep_anterior, ep_nuevo, usu_id, be_fecha,be_comentarios) VALUES ('$txtPro', '$reg_pa_new[pa_id]', '$txtEquipo','$cbxEquipo', '" . $_SESSION['idUsu'] . "', '" . date("Y-m-d H:i:s") . "', 'Movimiento a equipo vacio') ") or die(mysqli_error($cnx) . " Error al actualizar");

		$respuesta = array('mensaje' => "Movimiento a equipo libre");
	} else if ($reg_en['le_id'] == 11) {
		//si esta ocupado el equipo ejecuta siguientes sentencias

		//dato agrupador de equipo nuevo con proceso
		$cad_pa = mysqli_query($cnx, "SELECT a.pa_id FROM procesos_agrupados as a inner join procesos_equipos as e on (a.pro_id = e.pro_id) inner join procesos as p on (e.pro_id = p.pro_id) where e.ep_id = '$cbxEquipo' and p.pro_estatus = 1 and e.pe_ban_activo = 1") or die(mysqli_error($cnx) . " Error5");
		$reg_pa = mysqli_fetch_array($cad_pa);

		//dato agrupador de equipo anterior
		$cad_pa2 = mysqli_query($cnx, "SELECT a.pa_id FROM procesos_agrupados as a inner join procesos_equipos as e on (a.pro_id = e.pro_id) where e.ep_id = '$txtEquipo' and e.pro_id = '$txtPro' ") or die(mysqli_error($cnx) . " Error6");
		$reg_pa2 = mysqli_fetch_array($cad_pa2);

		//actualiza dato agrupador de equipo nuevo
		mysqli_query($cnx, "UPDATE procesos_agrupados SET pa_id = '$reg_pa[pa_id]' where pa_id = '$reg_pa2[pa_id]' ") or die(mysqli_error($cnx) . " Error al actualizar procesos juntos");


		mysqli_query($cnx, "INSERT INTO bitacora_equipos (pro_id, pa_id, ep_anterior, ep_nuevo, usu_id, be_fecha,be_comentarios) VALUES ('$txtPro', '$reg_pa[pa_id]', '$txtEquipo','$cbxEquipo', '" . $_SESSION['idUsu'] . "', '" . date("Y-m-d H:i:s") . "', 'Movimiento a equipo ocupado') ") or die(mysqli_error($cnx) . " Error al actualizar");

		$respuesta = array('mensaje' => "Movimiento a equipo ocupado");
	}



	echo json_encode($respuesta);
}
