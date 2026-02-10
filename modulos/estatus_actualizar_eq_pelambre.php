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

$respuesta = array('mensaje' => "Estatus Actualizado");
echo json_encode($respuesta);
