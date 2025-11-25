<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";


$cnx = Conectarse();

$respuesta = array('mensaje' => "Estatus Actualizado");
echo json_encode($respuesta);

extract($_POST); 
//echo "1 UPDATE preparacion_paletos SET le_id = '$cbxEstatus' WHERE pp_id = '$hdd_id' ";
mysqli_query($cnx, "UPDATE preparacion_paletos SET le_id = '$cbxEstatus' WHERE pp_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar");

//Si cambia el estatus de libre del paleto 1A y 1B, termina los procesos que tenga guardados.
if($cbxEstatus == '2' and ($hdd_id == '1' or $hdd_id == '2'))
{ //echo "2 UPDATE procesos_paletos SET prop_estatus = 2 WHERE pp_id = '$hdd_id' and prop_estatus = 1 ";
	mysqli_query($cnx, "UPDATE procesos_paletos SET prop_estatus = 2 WHERE pp_id = '$hdd_id' and prop_estatus = 1 ") or die(mysqli_error($cnx)." Error al actualizar proceso");
}
//echo "3 INSERT INTO bitacora_cambio_estatus(bce_fecha, usu_id, bce_est_actual, bce_est_nuevo, bce_descripcion, pp_id, pl_id) VALUES ('".date("Y-m-d H:i:s")."', '".$_SESSION['idUsu']."', '$hdd_est', '$cbxEstatus', '$txaComentarios', 0, '$hdd_id') ";
mysqli_query($cnx, "INSERT INTO bitacora_cambio_estatus(bce_fecha, usu_id, bce_est_actual, bce_est_nuevo, bce_descripcion, pp_id, pl_id) 
					VALUES ('".date("Y-m-d H:i:s")."', '".$_SESSION['idUsu']."', '$hdd_est', '$cbxEstatus', '$txaComentarios', 0, '$hdd_id') ") or die(mysqli_error($cnx)." Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_id, '13');
?> 