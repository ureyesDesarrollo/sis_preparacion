<?php
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cnx = Conectarse();
        $id = $_POST['tar_id'];

        $sql = "UPDATE rev_tarimas SET tar_rechazado = 'R' WHERE tar_id = '$id'";

        if (mysqli_query($cnx, $sql)) {
            echo json_encode(['success' => 'Estatus actualizado con exito.']);
        } else {
            echo json_encode(['error' => 'Error al actualizar el estatus.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => "Error {$e->getMessage()}"]);
    }finally{
        mysqli_close($cnx);
    }
}
