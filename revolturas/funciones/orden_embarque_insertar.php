<?php
include "../../conexion/conexion.php";
$cnx = Conectarse();
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['cte_id']) || !isset($data['empaques']) || !is_array($data['empaques'])) {
        throw new Exception('Datos incompletos');
    }

    // Iniciar transacción
    mysqli_begin_transaction($cnx);

    // 1. Insertar cabecera en ordenes_embarque
    $cte_id = $data['cte_id'];
    $query = "INSERT INTO rev_orden_embarque (cte_id) VALUES ('$cte_id')";
    if (!mysqli_query($cnx, $query)) {
        throw new Exception('Error al insertar la orden: ' . mysqli_error($cnx));
    }
    
    $oe_id = mysqli_insert_id($cnx);

    // 2. Insertar detalles en ordenes_embarque_detalle
    foreach ($data['empaques'] as $empaque) {
        $rr_id = isset($empaque['rr_id']) ? $empaque['rr_id'] : 'NULL';
        $rrc_id = isset($empaque['rrc_id']) ? $empaque['rrc_id'] : 'NULL';
        $cantidad = $empaque['cantidad'];
        $bloom = $empaque['bloom'];

        if (($rr_id === 'NULL' && $rrc_id === 'NULL') || ($rr_id !== 'NULL' && $rrc_id !== 'NULL')) {
            throw new Exception('Cada empaque debe tener solo rr_id o rrc_id, no ambos.');
        }

        $queryDetalle = "INSERT INTO rev_orden_embarque_detalle (oe_id, rr_id, rrc_id, cantidad,bloom_vendido) 
                         VALUES ('$oe_id', $rr_id, $rrc_id, '$cantidad','$bloom')";

        if (!mysqli_query($cnx, $queryDetalle)) {
            throw new Exception('Error al insertar detalle: ' . mysqli_error($cnx));
        }
    }

    // Confirmar transacción
    mysqli_commit($cnx);

    echo json_encode([
        'success' => true,
        'message' => 'Orden de embarque registrada correctamente.',
        'oe_id' => $oe_id
    ]);

} catch (Exception $e) {
    mysqli_rollback($cnx);
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Error al registrar orden: ' . $e->getMessage()
    ]);
}
