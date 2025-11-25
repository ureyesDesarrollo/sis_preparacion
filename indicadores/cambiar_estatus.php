<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Realizado: 07-12-2023*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();
extract($_POST);


//marca como ocupado el equipo nuevo
mysqli_query($cnx, "update equipos_preparacion set le_id = 9 WHERE ep_id = '$id'") or die(mysqli_error($cnx) . " Error2");

mysqli_query($cnx, "INSERT INTO bitacora_cambio_estatus(bce_fecha, usu_id, bce_est_actual, bce_est_nuevo, bce_descripcion, pp_id, pl_id, ep_id) VALUES ('" . date("Y-m-d H:i:s") . "', '" . $_SESSION['idUsu'] . "', 14, 9, 'Movimiento de estatus', 0, 0,'$id') ") or die(mysqli_error($cnx) . " Error al actualizar");


ins_bit_acciones($_SESSION['idUsu'], 'M', $id, '13');

$respuesta = array('mensaje' => "Equipo deshabilitado");
echo json_encode($respuesta);
