<?php

$data = json_decode(file_get_contents("php://input"), true);

$rev_id = isset($data['rev_id']) ? $data['rev_id'] : '';
$cte_id = isset($data['cte_id']) ? $data['cte_id'] : '';

if (!empty($rev_id) && !empty($cte_id)) {

    require_once "../../conexion/conexion.php";
    $conn = Conectarse();

    $stmt = $conn->prepare("
        UPDATE rev_revolturas
        SET rev_teo_cliente = ?
        WHERE rev_id = ?
    ");

    $stmt->bind_param("ii", $cte_id, $rev_id);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Cliente actualizado correctamente.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar el cliente.'
        ]);
    }

    $stmt->close();
    $conn->close();

} else {
    echo json_encode([
        'success' => false,
        'message' => 'Datos incompletos.'
    ]);
}