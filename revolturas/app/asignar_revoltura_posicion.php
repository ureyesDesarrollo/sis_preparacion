<?php
header('Content-Type: application/json');
include "../../conexion/conexion.php";

$cnx = Conectarse();
$data = json_decode(file_get_contents("php://input"), true);

$rev_id = intval($data['rev_id']);
$niveles = $data['niveles']; // Array con 5 niv_id
$total_kg = floatval($data['cantidad']);

if (!$rev_id || count($niveles) != 5 || $total_kg <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Datos inválidos"]);
    exit;
}

$cantidad_por_posicion = $total_kg / 5;
$error = false;

foreach ($niveles as $niv_id) {
    $niv_id = intval($niv_id);

    $query = "INSERT INTO rev_nivel_posicion_detalle (niv_id, tipo, rev_id, cantidad)
              VALUES ($niv_id, 'revoltura', $rev_id, $cantidad_por_posicion)";
    $result = mysqli_query($cnx, $query);

    if (!$result) {
        $error = true;
        break;
    }

    mysqli_query($cnx, "UPDATE rev_nivel_posicion SET niv_ocupado = 1 WHERE niv_id = $niv_id");
}

if ($error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error al asignar posiciones"]);
} else {
    echo json_encode(["status" => "success", "message" => "Revoltura asignada a 5 posiciones"]);
}
?>
