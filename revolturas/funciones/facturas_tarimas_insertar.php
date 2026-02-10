<?php

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$cnx = Conectarse();

try {
    if (!isset($_POST['ft_factura'], $_POST['fecha'], $_POST['cte_id_f'], $_POST['tar_id'], $_POST['ft_tipo'])) {
        throw new Exception("Todos los campos son obligatorios");
    }

    $ft_factura = mysqli_real_escape_string($cnx, $_POST['ft_factura']);
    $fecha = mysqli_real_escape_string($cnx, $_POST['fecha']);
    $cte_id_f = mysqli_real_escape_string($cnx, $_POST['cte_id_f']);
    $tar_id = mysqli_real_escape_string($cnx, $_POST['tar_id']);
    $ft_tipo = mysqli_real_escape_string($cnx, $_POST['ft_tipo']);
    $sql = "INSERT INTO rev_tarimas_facturas (tar_id,ft_factura,ft_tipo,ft_fecha,cte_id) 
    VALUES ('$tar_id','$ft_factura','$ft_tipo','$fecha','$cte_id_f')";

    $sql_update = "UPDATE rev_tarimas SET tar_estatus = '9' WHERE tar_id = '$tar_id'"; # 9 estatus de facturado
    if (mysqli_query($cnx, $sql)) {
        if (mysqli_query($cnx, $sql_update)) {
            $res = "Factura registrada correctamente.";
            echo json_encode(["success" => $res]);
        }
    } else {
        $res = "Error al insertar el registro: " . mysqli_error($cnx);
        echo json_encode(["error" => $res]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
