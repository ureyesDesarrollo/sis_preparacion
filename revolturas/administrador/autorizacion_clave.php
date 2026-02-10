<?php

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {

    $usu_clave_auth = isset($_POST['usu_clave_auth']) ? mysqli_real_escape_string($cnx, $_POST['usu_clave_auth']) : '';


    $query = "SELECT usu_id FROM usuarios WHERE usu_clave_auth = '$usu_clave_auth'";
    $result = mysqli_query($cnx, $query);

    if (!$result) {
        throw new Exception("Error al verificar la autorización de la clave: " . mysqli_error($cnx));
    }

    if (mysqli_num_rows($result) === 0) {
        echo json_encode(["error" => "Clave de autorización incorrecta."]);
        exit;
    }

    echo json_encode(["success" => "Movimiento autorizado.","usu_id" => mysqli_fetch_assoc($result)['usu_id']]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit;
} finally {
    mysqli_close($cnx);
}
