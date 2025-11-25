<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

require_once('../conexion/conexion.php');
$cnx =  Conectarse();
extract($_POST);
$mensaje = "";

/* if (isset($hdd_id)) {
    $cad_registro = mysqli_query($cnx, "SELECT inv_no_ticket FROM equipos_preparacion WHERE inv_no_ticket='$dato' and ep_id != '$hdd_id' ") or die(mysqli_error($cnx) . "Error de sistema al consultar");
} else { */
$cad_registro = mysqli_query($cnx, "SELECT inv_no_ticket FROM inventario WHERE inv_no_ticket='$ticket' and inv_fecha >= '2023-03-01'") or die(mysqli_error($cnx) . "Error de sistema al consultar");
/* } */

$reg_cad = mysqli_fetch_array($cad_registro);

if (isset($reg_cad['inv_no_ticket'])) {

    $respuesta = array('mensaje' => "El no. de ticket '" . $reg_cad['inv_no_ticket'] . "' ya existe ");
    echo json_encode($respuesta);
}
?>