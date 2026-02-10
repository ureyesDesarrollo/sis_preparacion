<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();

$respuesta = array('mensaje' => "Etapa activada");
echo json_encode($respuesta);

extract($_POST); 

//Marcar el proceso con estatus 3
mysqli_query($cnx, "DELETE FROM procesos_liberacion WHERE pro_id = '$txtPro' AND pe_id = '$cbxEtapa'") or die(mysqli_error($cnx)." Error al insertar ");

ins_bit_acciones($_SESSION['idUsu'],'E', $txtPro, '14');
?>