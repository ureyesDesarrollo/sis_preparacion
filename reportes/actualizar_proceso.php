<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*29 - febrero - 2020*/



include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

extract($_POST); 

mysqli_query($cnx, "UPDATE lotes_procesos SET  prop_id = '$txtProNew' WHERE lote_id = '$txtLote' and prop_id = '$txtProActual' ") or die(mysqli_error($cnx)." Error al actualizar el proceso");

ins_bit_acciones($_SESSION['idUsu'],'E', $txtLote, '19');

$respuesta = array('mensaje' => "Lote modificado ");
echo json_encode($respuesta);
?> 