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
	//if ($chk_inv = isset(${"chk_baja".$i}));
	if (!isset(${"chk_baja" . $i})) {
		$chk_inv = '';
	} else {
		$chk_inv = ${"chk_baja" . $i};
	}
	if (!isset(${"txt_comentario" . $i})) {
		$comentarios = '';
	} else {
		$comentarios = ${"txt_comentario" . $i};
	}
	if ($chk_inv != '' && $comentarios != '') {
		//echo "inv=".$chk_inv."<br>";
		$result = mysqli_query($cnx, "UPDATE inventario SET inv_enviado = 3, inv_observaciones = '$comentarios' WHERE inv_id = '$chk_inv'") or die(mysqli_error($cnx) . "Error al dar de baja");

		//Obtener la existencia actual del material
		$cad_mat = mysqli_query($cnx, "SELECT m.mat_existencia, m.mat_id, i.inv_kg_totales FROM inventario as i 
										INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)
										WHERE i.inv_id = '$chk_inv' ");
		$reg_mat = mysqli_fetch_array($cad_mat);

		//echo "SELECT m.mat_existencia, m.mat_id, i.inv_kg_totales FROM inventario as i 		INNER JOIN materiales AS m ON (i.mat_id = m.mat_id)		WHERE i.inv_id = '$chk_inv' ";

		$flt_existencia = $reg_mat['mat_existencia'] - $reg_mat['inv_kg_totales'];

		//Actualiza la existencia del material
		mysqli_query($cnx, "UPDATE materiales SET mat_existencia = '$flt_existencia' WHERE mat_id = '$reg_mat[mat_id]'") or die(mysqli_error($cnx) . " Error al actualizar existencia");

		//if ($intMaterial != '') 
		//{

		//Insertar en el diario de almacen el movimiento
		mysqli_query($cnx, "INSERT INTO inventario_diario_materiales (usu_id, idm_fecha, idm_documento, mat_id, idm_cant_ing, idm_cant_ant, idm_cant_new, inv_id)VALUES(" . $_SESSION['idUsu'] . ", '" . date("Y-m-d h:i:s") . "', 'Baja material', '$reg_mat[mat_id]', '$reg_mat[inv_kg_totales]', '$reg_mat[mat_existencia]', '$flt_existencia', '$chk_inv' )") or die(mysqli_error($cnx) . " Error al insertar en el diario");

		//}	

		ins_bit_acciones($_SESSION['idUsu'], 'B', $chk_inv, '5');
		$ban = 'ok';
	}
}
if ($ban == 'ok') {

	$respuesta = array('mensaje' => "Material dado de Baja");
	echo json_encode($respuesta);
}
?>