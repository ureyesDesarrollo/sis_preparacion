<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Mezcla Actualizada");
echo json_encode($respuesta);

extract($_POST); 

mysqli_query($cnx, "UPDATE mezclas SET mez_nombre = '$txtNombre' WHERE mez_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_id, '21');
?> 