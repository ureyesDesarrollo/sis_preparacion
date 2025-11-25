<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

extract($_POST); 

mysqli_query($cnx, "UPDATE materiales_tipo SET mt_descripcion = '$txtTipo', mt_est = '$cbxEstatus' WHERE mt_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_id, '8');

$respuesta = array('mensaje' => "Origen material Actualizado");
echo json_encode($respuesta);
?> 