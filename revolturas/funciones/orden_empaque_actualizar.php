<?php

include '../../conexion/conexion.php';
$cnx = Conectarse();


$detalle_ids = $_POST['detalle_ids'];

foreach ($detalle_ids as $detalle) {
    $id = mysqli_real_escape_string($cnx, $detalle['detalle_id']);
    $cantidad = mysqli_real_escape_string($cnx, $detalle['cantidad']);

    $sql = "UPDATE rev_orden_empaque_detalle SET roed_cantidad = '$cantidad' WHERE roed_id = '$id'";
    mysqli_query($cnx, $sql);
}

mysqli_close($cnx);

echo json_encode(["success" => "Actualización completada correctamente."]);
