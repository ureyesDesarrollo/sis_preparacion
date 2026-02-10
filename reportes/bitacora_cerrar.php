<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Proceso Cerrado");
echo json_encode($respuesta);

extract($_POST); 

$result = mysqli_query ($cnx, "UPDATE procesos SET pro_estatus = 2 WHERE pro_id = '$id'") or die(mysql_error()."Error al dar de baja");		

ins_bit_acciones($_SESSION['idUsu'],'B', $id, '17');
?> 