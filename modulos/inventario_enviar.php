<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";


$cnx = Conectarse();

$respuesta = array('mensaje' => "Inventario Enviado");
echo json_encode($respuesta);

extract($_POST); 

$result = mysqli_query ($cnx, "UPDATE inventario SET inv_enviado = 1, inv_fe_enviado = '".date("Y-m-d h:i:s")."' WHERE inv_id = '$id'") or die(mysql_error()."Error al enviar");		

ins_bit_acciones($_SESSION['idUsu'],'E', $id, '3');
?> 