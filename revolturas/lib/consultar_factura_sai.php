<?php
header('Content-Type: application/json; charset=utf-8');
if($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Aquí debes tomar el folio de la URL, por ejemplo: script.php?folio=123
if (!isset($_GET['folio']) || !is_string($_GET['folio'])) {
    die('Folio no válido o no proporcionado');
}
$folio = $_GET['folio'];
if (empty($folio)) {
    die('Folio no puede estar vacío');
}

$url = 'http://192.168.1.104:8000/factura/' . urlencode($folio);

$api_key = 'miclaveultrasecreta123';

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "X-API-Key: $api_key",
        "Accept: application/json"
    ],
]);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    die('Error cURL: ' . curl_error($ch));
}
curl_close($ch);


$response = json_decode($response, true);
if(isset($response['detail'])){
    echo json_encode([
        'success' => false,
        'error' => $response['detail'] ?? 'Error al consultar la factura'
    ]);
    exit;
}
echo json_encode([
    'success' => true,
    'data' => $response
]);
