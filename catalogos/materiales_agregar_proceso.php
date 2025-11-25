<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

extract($_POST);
$ban = '';

for ($i = 1; $i < $hdd_cont; $i++) {
	//for ($i = 1; $i < 20; $i++) {

	if (!isset(${"cbx_proceso_" . $i})) {
		$cbx_proceso = '';
	} else {
		$cbx_proceso = ${"cbx_proceso_" . $i};
	}
	if (!isset(${"txt_comentario" . $i})) {
		$comentarios = '';
	} else {
		$comentarios = ${"txt_comentario" . $i};
	}

	if ($cbx_proceso != '' && $comentarios != '') {

		$flt_inventario = ${"hdd_inv_" . $i};

		$cad_inv = mysqli_query($cnx, "SELECT * FROM inventario WHERE inv_id = '$flt_inventario' ");
		$reg_inv = mysqli_fetch_array($cad_inv);

		//mysqli_query($cnx, "INSERT INTO procesos_materiales(pro_id, inv_id, mat_id, pma_kg, pma_fe_entrada, pma_fe_entrada_maquila) VALUES('$cbx_proceso', '$flt_inventario', '$reg_inv[mat_id]',  '$reg_inv[inv_kg_totales]', '$reg_inv[inv_fecha]', '$reg_inv[inv_fecha]')") or die(mysqli_error($cnx) . " Error al insertar en [" . $i . "]");

		mysqli_query($cnx, "INSERT INTO procesos_materiales(pro_id, inv_id, mat_id, pma_kg, pma_fe_entrada) VALUES('$cbx_proceso', '$flt_inventario', '$reg_inv[mat_id]',  '$reg_inv[inv_kg_totales]', '$reg_inv[inv_fecha]')") or die(mysqli_error($cnx) . " Error al insertar en [" . $i . "]");

		mysqli_query($cnx, "UPDATE inventario SET inv_tomado = 1,inv_observaciones = '$comentarios' WHERE inv_id = '$flt_inventario' ") or die(mysqli_error($cnx) . " Error al actualizar en inv [" . $i . "]");

		$cad_pro = mysqli_query($cnx, "SELECT pro_total_kg FROM procesos WHERE pro_id = '$cbx_proceso' ");
		$reg_pro = mysqli_fetch_array($cad_pro);

		$flt_total = $reg_pro['pro_total_kg'] + $reg_inv['inv_kg_totales'];

		mysqli_query($cnx, "UPDATE procesos SET pro_total_kg = '$flt_total' WHERE pro_id = '$cbx_proceso' ") or die(mysqli_error($cnx) . " Error al actualizar proceso"); //Ocupa el Lavador

		//Obtener la existencia actual del material
		$cad_mat = mysqli_query($cnx, "SELECT mat_existencia FROM materiales WHERE mat_id = '$reg_inv[mat_id]' ");
		$reg_mat = mysqli_fetch_array($cad_mat);

		$flt_existencia = $reg_mat['mat_existencia'] - $reg_inv['inv_kg_totales'];

		//Actualiza la existencia del material
		mysqli_query($cnx, "UPDATE materiales SET mat_existencia = '$flt_existencia' WHERE mat_id = '$reg_inv[mat_id]'") or die(mysqli_error($cnx) . " Error al actualizar");

		//Insertar en el diario de almacen el movimiento
		mysqli_query($cnx, "INSERT INTO inventario_diario_materiales (usu_id, idm_fecha, idm_documento, mat_id, idm_cant_ing, idm_cant_ant, idm_cant_new, inv_id)VALUES(" . $_SESSION['idUsu'] . ", '" . date("Y-m-d h:i:s") . "', 'agrega bitacora', '$reg_inv[mat_id]', '$reg_inv[inv_kg_totales]', '$reg_mat[mat_existencia]', '$flt_existencia', '$flt_inventario' )") or die(mysqli_error($cnx) . " Error al insertar en el diario");


		ins_bit_acciones($_SESSION['idUsu'], 'B', $flt_inventario, '5');
		$ban = 'ok';
	}
}
if ($ban == 'ok') {
	$respuesta = array('mensaje' => "Material agregado");
	echo json_encode($respuesta);
}
?>