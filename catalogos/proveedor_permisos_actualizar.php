<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();


extract($_POST);

mysqli_query($cnx, "UPDATE usuarios_perfiles SET up_ban = '$autorizado' where up_id = '$id'") or die(mysqli_error($cnx) . " Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'], 'E', $id, '34');

$respuesta = array('mensaje' => "Registro actualizado");
echo json_encode($respuesta);
?>