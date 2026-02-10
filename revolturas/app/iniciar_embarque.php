<?php
header('Content-Type: application/json');
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $data = json_decode(file_get_contents('php://input'), true);
    $orden_id = $data['orden_id'] ?? null;

    if($orden_id === null) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de orden de embarque no proporcionado']);
        exit;
    }

    $sql = "UPDATE rev_orden_embarque SET oe_estado = 'PROCESO' WHERE oe_id = '$orden_id'";
    if(!mysqli_query($cnx, $sql)){
        http_response_code(500);
        echo json_encode(['error' => 'Error al iniciar el embarque']);
        exit;
    }

    echo json_encode(['success' => true, 'mensaje' => 'Embarque iniciado']);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
