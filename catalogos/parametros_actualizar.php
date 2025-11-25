<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Parametro Actualizado");
echo json_encode($respuesta);

extract($_POST); 

mysqli_query($cnx, "UPDATE parametros SET rojo = '$txt_dias_r', amarillo = '$txt_dias_a', verde = '$txt_dias_v', ton_produccion = '$txt_ton' ") or die(mysqli_error($cnx)." Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_id, '33');
?> 