<?php

header('Content-Type: application/json; charset=utf-8');

require_once 'funcion_consultar_factura_sai.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'error' => 'Método no permitido'
    ]);

    exit;
}

$folio = $_GET['folio'] ?? '';

$resultado = consultarFacturaSai($folio);

echo json_encode($resultado);