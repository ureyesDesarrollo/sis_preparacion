<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

require_once('../conexion/conexion.php');
$cnx =  Conectarse();
extract($_POST);
$mensaje = "";

$cad_registro = mysqli_query($cnx, "SELECT et_tipo FROM equipos_tipos WHERE et_tipo='$orden' and et_id <> '$hdd_id'") or die(mysqli_error($cnx) . "Error de sistema al consultar la sigla");
$reg_cad = mysqli_fetch_array($cad_registro);

if (isset($reg_cad['et_tipo'])) {

    $respuesta = array('mensaje' => "La sigla '" . $reg_cad['et_tipo'] . "' ya fue asignada a otro tipo de equipo ");
    echo json_encode($respuesta);
}
?>