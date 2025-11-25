<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

require_once('../conexion/conexion.php');
$cnx =  Conectarse();
extract($_POST);
$mensaje = "";

$cad_registro = mysqli_query($cnx, "SELECT et_descripcion FROM equipos_tipos WHERE et_descripcion='$tipo'") or die(mysqli_error($cnx) . "Error de sistema al consultar tipo");
$reg_cad = mysqli_fetch_array($cad_registro);

if (isset($reg_cad['et_descripcion'])) {

    $respuesta = array('mensaje' => "El tipo de equipo '" . $reg_cad['et_descripcion'] . "' ya existe ");
    echo json_encode($respuesta);
}
?>