<?php
header('Content-Type: application/json');
require_once '../../conexion/conexion.php';
include "../../seguridad/user_seguridad.php";

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    $cnx = Conectarse();
    $query = "SELECT * FROM rev_vendedores";
    $result = mysqli_query($cnx, $query);

    if (!$result) {
        throw new Exception("Error en la consulta: " . mysqli_error($cnx));
    }

    $vendedores = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $vendedores[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $vendedores]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
