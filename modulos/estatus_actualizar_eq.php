<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

extract($_POST);

mysqli_query($cnx, "UPDATE equipos_preparacion SET le_id = '$cbxEstatus' WHERE ep_id = '$hdd_id' ") or die(mysqli_error($cnx) . " Error al actualizar");

mysqli_query($cnx, "INSERT INTO bitacora_cambio_estatus(bce_fecha, usu_id, bce_est_actual, bce_est_nuevo, bce_descripcion, pp_id, pl_id, ep_id,bce_ot) 
					VALUES ('" . date("Y-m-d H:i:s") . "', '" . $_SESSION['idUsu'] . "', '$hdd_est', '$cbxEstatus', '$txaComentarios', 0, 0,'$hdd_id','$txt_ot') ") or die(mysqli_error($cnx) . " Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_id, '13');

/* LIBERACION DE PROCESOS */
//verifica si el equipo es de tipo almacen
$equipo_tipo = mysqli_query($cnx, "SELECT t.ban_almacena FROM equipos_preparacion as e
inner join equipos_tipos t on(e.ep_tipo = t.et_tipo) 
WHERE e.ep_id = '$hdd_id'") or die(mysqli_error($cnx) . "Error: en consultar el tipo de equipo");
$reg_tipo = mysqli_fetch_assoc($equipo_tipo);

//si es el equipo es de tipo almacen y estatus se va a cambiar a libre
if ($reg_tipo['ban_almacena'] == 'S' && $cbxEstatus == '9') {

	//selecciona el ultimo registro que esta en el receptor
	$slq_ult = mysqli_query($cnx, "SELECT MAX(ped_id) as ped_id FROM procesos_equipos WHERE  ep_id = '$hdd_id'") or die(mysqli_error($cnx) . "Error: en consultar 1");
	$reg_ult = mysqli_fetch_assoc($slq_ult);
	$tot = mysqli_num_rows($slq_ult);

	//obtengo el proceso 
	$eq_almacen = mysqli_query($cnx, "SELECT pro_id FROM procesos_equipos WHERE  ped_id = '" . $reg_ult['ped_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar 2");
	$reg_eq_alm = mysqli_fetch_assoc($eq_almacen);
	$tot = mysqli_num_rows($eq_almacen);

	//obtengo dato agrupador del proceso
	$sql_agrupador = mysqli_query($cnx, "SELECT pa_id FROM procesos_agrupados WHERE pro_id = '" . $reg_eq_alm['pro_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar 3");
	$reg_agrupador = mysqli_fetch_assoc($sql_agrupador);

	//obtengo los procesos que tienen el mismo dato agrupador
	$sql_procesos = mysqli_query($cnx, "SELECT pro_id FROM procesos_agrupados WHERE pa_id = '" . $reg_agrupador['pa_id'] . "'") or die(mysqli_error($cnx) . "Error: en consultar 3");
	$reg_procesos = mysqli_fetch_assoc($sql_procesos);

	do {
		mysqli_query($cnx, "UPDATE procesos SET pro_estatus = '2' WHERE pro_id = '" . $reg_procesos['pro_id'] . "' ") or die(mysqli_error($cnx) . " Error al actualizar");
	} while ($reg_procesos = mysqli_fetch_assoc($sql_procesos));
}

$respuesta = array('mensaje' => "Estatus Actualizado");
echo json_encode($respuesta);
