<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

require_once('../conexion/conexion.php');
$cnx =  Conectarse();
extract($_POST);
$mensaje = "";

$cad_registro = mysqli_query($cnx, "SELECT et_orden FROM equipos_tipos WHERE et_orden='$orden'  and et_id <> '$hdd_id'") or die(mysqli_error($cnx) . "Error de sistema al consultar la orden");
$reg_cad = mysqli_fetch_array($cad_registro);

if (isset($reg_cad['et_orden'])) {
    $respuesta = array('mensaje' => "El orden '" . $reg_cad['et_orden'] . "' ya fue asignado a otro tipo de equipo ");
    echo json_encode($respuesta);
}
?>