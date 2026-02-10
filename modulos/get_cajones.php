<?php
/*Desarrollado por: Ca & Ce Technologies */
/*21 - Abril - 2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();
extract($_POST);
$consulta = "SELECT * FROM almacen_cajones WHERE ac_descripcion = '$cajon' and ac_ban = '$patio'";
$resultado = mysqli_query($cnx, $consulta) or die(mysqli_error($cnx) . " Error al consulta");

if (mysqli_num_rows($resultado) > 0) {
    $respuesta = array('mensaje' => "El registro ya existe");
}

echo json_encode($respuesta);
