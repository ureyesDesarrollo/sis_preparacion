<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
include('../funciones/funciones_procesos.php');

$cnx = Conectarse();
date_default_timezone_set('America/Mexico_City');


extract($_POST);
// Verificar la conexión a la base de datos
if ($cnx) {
	//--------------------------------------- GENERALES
	//Valida el estatus del equipo nuevo
	$cad_en = mysqli_query($cnx, "SELECT * FROM equipos_preparacion WHERE ep_id = '$cbxEquipo'");
	$reg_en = mysqli_fetch_array($cad_en);

	//libera equipo anterior
	mysqli_query($cnx, "update equipos_preparacion set le_id = 9 WHERE ep_id = '$txtEquipo'") or die(mysqli_error($cnx) . " Error1");

	mysqli_query($cnx, "INSERT INTO bitacora_cambio_estatus(bce_fecha, usu_id, bce_est_actual, bce_est_nuevo, bce_descripcion, pp_id, pl_id, ep_id) VALUES ('" . date("Y-m-d H:i:s") . "', '" . $_SESSION['idUsu'] . "', 11, 9, 'Movimiento de equipo 1', 0, 0,'$txtEquipo') ") or die(mysqli_error($cnx) . " Error al insertar en bitacora estatus 1");

	//marca como ocupado el equipo nuevo
	mysqli_query($cnx, "update equipos_preparacion set le_id = 11 WHERE ep_id = '$cbxEquipo'") or die(mysqli_error($cnx) . " Error2");

	mysqli_query($cnx, "INSERT INTO bitacora_cambio_estatus(bce_fecha, usu_id, bce_est_actual, bce_est_nuevo, bce_descripcion, pp_id, pl_id, ep_id) VALUES ('" . date("Y-m-d H:i:s") . "', '" . $_SESSION['idUsu'] . "', 9, 11, 'Movimiento de equipo 1', 0, 0,'$cbxEquipo') ") or die(mysqli_error($cnx) . " Error al insertar en bitacora estatus 2");

	//marca como equipo inactivo el equipo anterior
	mysqli_query($cnx, "update procesos_equipos set pe_ban_activo = 0 WHERE ep_id = '$txtEquipo'") or die(mysqli_error($cnx) . " Error3");

	//Obtengo dato agrupador del proceso principal
	$cad_pa_g = mysqli_query($cnx, "SELECT pa_id,pro_id FROM procesos_agrupados where pro_id = '$txtPro'") or die(mysqli_error($cnx) . " Error5");
	$reg_pa_g = mysqli_fetch_array($cad_pa_g);

	//--------------------------------------SI EL EQUIPO ESTA LIBRE ejecuta siguientes sentencias
	if ($reg_en['le_id'] == 9) {

		//si esta libre el equipo ejecuta siguientes sentencias
		mysqli_query($cnx, "INSERT INTO procesos_equipos(pro_id, ep_id) 
	VALUES('$txtPro', '$cbxEquipo')") or die(mysqli_error($cnx) . " Error al insertar equipos");

		//obtengo todos los procesos con el mismo dato agrupador que proceso principal
		$cad_pa_p = mysqli_query($cnx, "SELECT pro_id,pa_id FROM procesos_agrupados where pa_id = '$reg_pa_g[pa_id]'") or die(mysqli_error($cnx) . " Error5");
		$reg_pa_p = mysqli_fetch_array($cad_pa_p);

		//NUEVO CC 05-12-23 inserta lote
		mysqli_query($cnx, "INSERT INTO lotes(lote_fecha, lote_hora, lote_mes, lote_folio, lote_turno, usu_id,pa_id) 
		VALUES ('" . date("Y-m-d") . "', '" . date("H:i:s") . "', '" . date("m") . "', '$txt_lote', '$txt_turno', '" . $_SESSION['idUsu'] . "','" . $reg_pa_g['pa_id'] . "') ") or die(mysqli_error($cnx) . " Error al insertar el lote equipo libre general");

		$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(lote_id) as res from lotes"));

		do {
			//NUEVO CC 05-12-23 inserta detalle lotes
			mysqli_query($cnx, "INSERT INTO lotes_procesos(lote_id, pro_id, pa_id) 
			VALUES ('$reg_ultimo_id[res]','$reg_pa_p[pro_id]','" . $reg_pa_p['pa_id'] . "') ") or die(mysqli_error($cnx) . " Error al insertar el lote equipo libre detalle");

			//inserta a bitacora de equipos
			mysqli_query($cnx, "INSERT INTO bitacora_equipos (pro_id, pa_id, ep_anterior, ep_nuevo, usu_id, be_fecha,be_comentarios) VALUES ('$reg_pa_p[pro_id]', '$reg_pa_p[pa_id]', '$txtEquipo','$cbxEquipo', '" . $_SESSION['idUsu'] . "', '" . date("Y-m-d H:i:s") . "', 'Movimiento a equipo almacen vacio') ") or die(mysqli_error($cnx) . " Error al insertar bitacora equipos");

			//NUEVO - 29-11-2023 CC actualiza fecha de proceso terminado
			mysqli_query($cnx, "UPDATE procesos SET pro_fe_termino = '" . date("Y-m-d H:i:s") . "' where pro_id = '$reg_pa_p[pro_id]' ") or die(mysqli_error($cnx) . " Error al actualizar fecha termino proceso");

			//NUEVO - 29-11-2023 CC selecciona fechas de proceso terminado
			$cad_procesos = mysqli_query($cnx, "SELECT pro_fe_carga,pro_hr_inicio, SUBSTRING(pro_fe_termino, 1, 10) as fecha_termino, TIME(pro_fe_termino) as hora_termino FROM procesos where pro_id = '$reg_pa_p[pro_id]'") or die(mysqli_error($cnx) . " Error al consultar fecha termino de proceso");
			$reg_procesos = mysqli_fetch_array($cad_procesos);

			//NUEVO - 29-11-2023 CC obtiene tiempo calculado de proceso terminado
			$horas_calculadas = fnc_horas_insertar($reg_procesos['pro_fe_carga'], $reg_procesos['fecha_termino'], $reg_procesos['pro_hr_inicio'], $reg_procesos['hora_termino']);

			//NUEVO - 29-11-2023 CC actualiza tiempo calculado de termino de proceso
			mysqli_query($cnx, "UPDATE procesos SET  hrs_totales_calculadas = '$horas_calculadas' where pro_id = '$reg_pa_p[pro_id]' ") or die(mysqli_error($cnx) . " Error al actualizar fecha termino proceso");
		} while ($reg_pa_p = mysqli_fetch_array($cad_pa_p));

		$respuesta = array('mensaje' => "Movimiento a equipo libre");
	} else if ($reg_en['le_id'] == 11) {
		//----------------------------------SI EL EQUIPO ESTA OCUPADO ejecuta siguientes sentencias

		//dato agrupador de equipo activo
		$cad_pa = mysqli_query($cnx, "SELECT a.pa_id FROM procesos_agrupados as a inner join procesos_equipos as e on (a.pro_id = e.pro_id) inner join procesos as p on (e.pro_id = p.pro_id) where e.ep_id = '$cbxEquipo' and p.pro_estatus = 1 and e.pe_ban_activo = 1") or die(mysqli_error($cnx) . " Error5");
		$reg_pa = mysqli_fetch_array($cad_pa);

		//dato agrupador de equipo anterior
		$cad_pa2 = mysqli_query($cnx, "SELECT a.pa_id FROM procesos_agrupados as a inner join procesos_equipos as e on (a.pro_id = e.pro_id) where e.ep_id = '$txtEquipo' and e.pro_id = '$txtPro' ") or die(mysqli_error($cnx) . " Error6");
		$reg_pa2 = mysqli_fetch_array($cad_pa2);

		//selecciona procesos con el mismo dato agrupador que proceso principal
		$cad_pa_x = mysqli_query($cnx, "SELECT pro_id,pa_id FROM procesos_agrupados where pa_id = '$reg_pa_g[pa_id]'") or die(mysqli_error($cnx) . " Error5");
		$reg_x = mysqli_fetch_array($cad_pa_x);

		////NUEVO CC 05-12-23 inserta lote
		mysqli_query($cnx, "INSERT INTO lotes(lote_fecha, lote_hora, lote_mes, lote_folio, lote_turno, usu_id,pa_id) 
		VALUES ('" . date("Y-m-d") . "', '" . date("H:i:s") . "', '" . date("m") . "', '$txt_lote', '$txt_turno', '" . $_SESSION['idUsu'] . "','" . $reg_pa['pa_id'] . "') ") or die(mysqli_error($cnx) . " Error al insertar el lote equipo ocupado general");

		$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(lote_id) as res from lotes"));



		//inserta a bitacora de movimientos los procesos que trae el equipo anterior
		do {
			//inserta detalle lotes
			mysqli_query($cnx, "INSERT INTO lotes_procesos(lote_id, pro_id, pa_id) 
			VALUES ('$reg_ultimo_id[res]','$reg_x[pro_id]','" . $reg_pa['pa_id'] . "') ") or die(mysqli_error($cnx) . " Error al insertar el lote equipo ocupado detalle");

			mysqli_query($cnx, "INSERT INTO bitacora_equipos (pro_id, pa_id, ep_anterior, ep_nuevo, usu_id, be_fecha,be_comentarios) VALUES ('$reg_x[pro_id]', '$reg_pa[pa_id]', '$txtEquipo','$cbxEquipo', '" . $_SESSION['idUsu'] . "', '" . date("Y-m-d H:i:s") . "', 'Movimiento a equipo almacen ocupado') ") or die(mysqli_error($cnx) . " Error al actualizar al insertar bitacora equipos 2");

			//NUEVO - 29-11-2023 CC actualiza tiempo de proceso terminado
			mysqli_query($cnx, "UPDATE procesos SET pro_fe_termino = '" . date("Y-m-d H:i:s") . "' where pro_id = '$reg_x[pro_id]' ") or die(mysqli_error($cnx) . " Error al actualizar fecha termino proceso");

			//NUEVO - 29-11-2023 CC selecciona fechas de proceso terminado
			$cad_procesos = mysqli_query($cnx, "SELECT pro_fe_carga,pro_hr_inicio, SUBSTRING(pro_fe_termino, 1, 10) as fecha_termino, TIME(pro_fe_termino) as hora_termino FROM procesos where pro_id = '$reg_x[pro_id]'") or die(mysqli_error($cnx) . " Error al actulizar fecha termino de proceso");
			$reg_procesos = mysqli_fetch_array($cad_procesos);

			//NUEVO - 29-11-2023 CC obtiene tiempo calculado de proceso terminado
			$horas_calculadas = fnc_horas_insertar($reg_procesos['pro_fe_carga'], $reg_procesos['fecha_termino'], $reg_procesos['pro_hr_inicio'], $reg_procesos['hora_termino']);

			//NUEVO - 29-11-2023 CC actualiza tiempo calculado de termino de proceso
			mysqli_query($cnx, "UPDATE procesos SET  hrs_totales_calculadas = '$horas_calculadas' where pro_id = '$reg_x[pro_id]' ") or die(mysqli_error($cnx) . " Error al actualizar fecha termino proceso");
		} while ($reg_x = mysqli_fetch_array($cad_pa_x));

		//actualiza dato agrupador de equipo nuevo
		mysqli_query($cnx, "UPDATE procesos_agrupados SET pa_id = '$reg_pa[pa_id]' where pa_id = '$reg_pa2[pa_id]' ") or die(mysqli_error($cnx) . " Error al actualizar procesos juntos");
		$respuesta = array('mensaje' => "Movimiento a equipo ocupado");
	}


	


	echo json_encode($respuesta);
}
