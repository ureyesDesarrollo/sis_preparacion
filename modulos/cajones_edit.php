<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();
extract($_POST);

// Consultas SQL con comillas dobles para valores de cadena
try {
   
    $consulta_almacen_cajones = "UPDATE almacen_cajones SET ac_descripcion = '$txt_cajon_e',ac_ban = '$cbx_patio_e',ac_estatus = '$cbx_estatus_e' WHERE ac_id = '$hdd_cajon' ";
    mysqli_query($cnx, $consulta_almacen_cajones) or die(mysqli_error($cnx) . " Error al actualizar");

    // Registrar acción en bitacora_acciones
    ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_cajon, '32');

    $respuesta = array('mensaje' => "Registro actualizado");
    echo json_encode($respuesta);
} catch (Exception $e) {
    $respuesta = array('mensaje' => "Error: " . $e->getMessage());
    echo json_encode($respuesta);
    exit;
}
?>