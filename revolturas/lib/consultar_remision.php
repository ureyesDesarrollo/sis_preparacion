<?php
header('Content-Type: application/json; charset=utf-8');
include '../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$cnx = Conectarse();
try{
    $query = "SELECT COUNT(id) AS total FROM remisiones WHERE DATE(fecha_remision) = CURDATE()";
    $result = mysqli_query($cnx, $query);

    if (!$result) {
        throw new Exception('Error en la consulta: ' . mysqli_error($cnx));
    }

    $row = mysqli_fetch_assoc($result);
    $total = isset($row['total']) ? (int)$row['total'] : 0;

    echo json_encode(['total' => str_pad($total + 1, 2, "0", STR_PAD_LEFT)]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}