<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Octubre-2023*/
include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();
$inv_id = mysqli_real_escape_string($cnx, $_POST['inv_id']);
$inv_humedad_origen = mysqli_real_escape_string($cnx, $_POST['inv_humedad_origen']);

$query = "UPDATE inventario SET inv_humedad_origen = '$inv_humedad_origen' WHERE inv_id = '$inv_id'";
if(!mysqli_query($cnx,$query)){
    die(mysqli_error($cnx) . " Error al actualizar el inventario");
}
$respuesta = array('mensaje' => "Humedad registrada");
echo json_encode($respuesta);
