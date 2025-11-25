<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();

$respuesta = array('mensaje' => "Lote Actualizado");
echo json_encode($respuesta);

extract($_POST); 

//Marcar el proceso con estatus 3
mysqli_query($cnx, "update lotes_anio set lote_rendimiento = '$txtRendimiento' WHERE lote_id = '$txtLote'") or die(mysqli_error($cnx)." Error al actualizar ");

ins_bit_acciones($_SESSION['idUsu'],'E', $txtPro, '14');
?>