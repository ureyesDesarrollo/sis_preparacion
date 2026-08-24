<?php

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$cnx = Conectarse();

try {
    $factura = mysqli_real_escape_string($cnx, $_POST['factura']);
    $cartaporte = mysqli_real_escape_string($cnx, $_POST['cartaporte']);

    $update = "UPDATE rev_revolturas_pt_facturas SET fe_cartaporte = '$cartaporte' WHERE fe_factura = '$factura'";
    $result = mysqli_query($cnx, $update);
    if ($result) {
        $res = "Cartaporte actualizada correctamente.";
        echo json_encode(["success" => $res]);
    } else {
        $res = "Error al actualizar la cartaporte: " . mysqli_error($cnx);
        echo json_encode(["error" => $res]);
    }
} catch (Exception $e) {
    $res = "Error: " . $e->getMessage();
    echo json_encode(["error" => $res]);
} finally {
    mysqli_close($cnx);
}
