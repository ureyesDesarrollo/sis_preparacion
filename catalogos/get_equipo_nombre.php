<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

require_once('../conexion/conexion.php');
$cnx =  Conectarse();
extract($_POST);
$mensaje = "";

if (isset($hdd_id)) {
    $cad_registro = mysqli_query($cnx, "SELECT ep_descripcion FROM equipos_preparacion WHERE ep_descripcion='$dato' and ep_id != '$hdd_id' ") or die(mysqli_error($cnx) . "Error de sistema al consultar");
} else {
    $cad_registro = mysqli_query($cnx, "SELECT ep_descripcion FROM equipos_preparacion WHERE ep_descripcion='$dato'") or die(mysqli_error($cnx) . "Error de sistema al consultar");
}

$reg_cad = mysqli_fetch_array($cad_registro);

if (isset($reg_cad['ep_descripcion'])) {

    $respuesta = array('mensaje' => "La descripción '" . $reg_cad['ep_descripcion'] . "' ya existe ");
    echo json_encode($respuesta);
}
?>