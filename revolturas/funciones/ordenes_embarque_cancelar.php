<?php
include "../../conexion/conexion.php";

$data = json_decode(file_get_contents("php://input"), true);
$cnx = Conectarse();

$orden_id = $data['oe_id'] ?? null;

if(empty($orden_id)) {
    http_response_code(400);
    echo json_encode(['error' => 'El ID de la orden de embarque es requerido']);
    exit;
}

try {
    $sql = "UPDATE rev_orden_embarque SET oe_estado = 'CANCELADA' WHERE oe_id = ?";
    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $orden_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['success' => 'Orden de embarque cancelada correctamente']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'No se encontró la orden de embarque o ya estaba cancelada']);
    }

    mysqli_stmt_close($stmt);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}