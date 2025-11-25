<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();
extract($_POST);

mysqli_query($cnx, "UPDATE equipos_preparacion SET ep_descripcion = '$txt_descripcion_e', ep_tipo = '$cbx_tipo_e', ep_carga_min = '$txt_capacidad_min',ep_carga_max = '$txt_capacidad_max', estatus = '$cbx_estatus'
 WHERE ep_id = '$hdd_id' ") or die(mysqli_error($cnx) . " Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_id, '25');

$respuesta = array('mensaje' => "Registro actualizado");
echo json_encode($respuesta);
?>