<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();

date_default_timezone_set('America/Mexico_City');

$respuesta = array('mensaje' => "Registro actualizado");
echo json_encode($respuesta);

extract($_POST); 

//Marcar el proceso con estatus 3
mysqli_query($cnx, "UPDATE bitacora_alertas SET ba_fe_seg = '".date("Y-m-d h:i:s")."', usu_seg = '".$_SESSION['idUsu']."', ba_comentarios = '$txaComentarios' WHERE ba_id = '$txtId' ") or die(mysqli_error($cnx)." Error al actualizar ");

ins_bit_acciones($_SESSION['idUsu'],'E', $txtId, '23');
?>