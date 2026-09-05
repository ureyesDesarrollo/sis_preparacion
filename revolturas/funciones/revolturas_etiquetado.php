<?php

require_once '../../conexion/conexion.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$rev_id = $data['rev_id'] ?? null;

if (!$rev_id) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'rev_id no proporcionado'
    ]);

    exit;
}

$rev_id = intval($rev_id);

$cnx = Conectarse();

$sql = "
    UPDATE rev_revolturas
    SET rev_etiquetado = 1
    WHERE rev_id = $rev_id
";

if (mysqli_query($cnx, $sql)) {

    echo json_encode([
        'success' => true
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => mysqli_error($cnx)
    ]);
}