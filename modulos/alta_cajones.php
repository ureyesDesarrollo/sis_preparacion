<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();
extract($_POST);


try {
    $consulta = "SELECT * FROM almacen_cajones WHERE ac_descripcion = '$txt_cajon_a' and ac_ban = '$cbx_patio_a'";
    $resultado = mysqli_query($cnx, $consulta) or die(mysqli_error($cnx) . " Error al consulta");
    if (mysqli_num_rows($resultado) > 0) {
        $respuesta = array('mensaje' => "El registro ya existe");
    } else {
        $registro = "INSERT INTO almacen_cajones (ac_descripcion,ac_estatus,ac_ban) VALUES('$txt_cajon_a','A','$cbx_patio_a')";
        mysqli_query($cnx, $registro) or die(mysqli_error($cnx) . " Error al registrar");
        $respuesta = array('mensaje' => "Registro realizado");
    }
    echo json_encode($respuesta);
} catch (Exception $e) {
    $respuesta = array('mensaje' => "Error: " . $e->getMessage());
    echo json_encode($respuesta);
    exit;
}
?>
