<?php
include "../../conexion/conexion.php";
$cnx = Conectarse();
header('Content-Type: application/json');

$oe_id = null;

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (
        !isset($data['cte_id']) ||
        !isset($data['empaques']) ||
        !is_array($data['empaques']) ||
        count($data['empaques']) === 0
    ) {
        throw new Exception('Datos incompletos');
    }

    $cte_id = (int)$data['cte_id'];
    $empaquesValidados = [];

    /* =========================
       1. VALIDAR TODO PRIMERO
       ========================= */
    foreach ($data['empaques'] as $index => $empaque) {
        $fila = $index + 1;

        $tipo_producto = isset($empaque['tipo_producto']) ? trim($empaque['tipo_producto']) : '';
        $cantidad = isset($empaque['cantidad']) ? (float)$empaque['cantidad'] : 0;

        if ($tipo_producto !== 'REVOLTURA' && $tipo_producto !== 'EXTERNO') {
            throw new Exception("Tipo de producto inválido en la fila $fila");
        }

        if ($cantidad <= 0) {
            throw new Exception("Cantidad inválida en la fila $fila");
        }

        if ($tipo_producto === 'REVOLTURA') {
            $rr_id = isset($empaque['rr_id']) && $empaque['rr_id'] !== '' ? (int)$empaque['rr_id'] : null;
            $rrc_id = isset($empaque['rrc_id']) && $empaque['rrc_id'] !== '' ? (int)$empaque['rrc_id'] : null;
            $bloom = isset($empaque['bloom']) && $empaque['bloom'] !== '' ? (int)$empaque['bloom'] : null;

            if (!$bloom) {
                throw new Exception("Falta bloom en la fila $fila");
            }

            $empaquesValidados[] = [
                'tipo_producto' => 'REVOLTURA',
                'rr_id' => $rr_id,
                'rrc_id' => $rrc_id,
                'pe_id' => null,
                'cantidad' => $cantidad,
                'bloom' => $bloom
            ];
        } else {
            $pe_id = isset($empaque['pe_id']) && $empaque['pe_id'] !== '' ? (int)$empaque['pe_id'] : null;

            if (!$pe_id) {
                throw new Exception("Falta pe_id en la fila $fila");
            }

            $empaquesValidados[] = [
                'tipo_producto' => 'EXTERNO',
                'rr_id' => null,
                'rrc_id' => null,
                'pe_id' => $pe_id,
                'cantidad' => $cantidad,
                'bloom' => null
            ];
        }
    }

    /* =========================
       2. INSERTAR CABECERA
       ========================= */
    $query = "INSERT INTO rev_orden_embarque (cte_id) VALUES ($cte_id)";
    if (!mysqli_query($cnx, $query)) {
        throw new Exception('Error al insertar la orden: ' . mysqli_error($cnx));
    }

    $oe_id = mysqli_insert_id($cnx);

    /* =========================
       3. INSERTAR DETALLES
       ========================= */
    foreach ($empaquesValidados as $index => $item) {
        $fila = $index + 1;

        $tipo_producto = $item['tipo_producto'];
        $rr_id_sql = is_null($item['rr_id']) ? "NULL" : (int)$item['rr_id'];
        $rrc_id_sql = is_null($item['rrc_id']) ? "NULL" : (int)$item['rrc_id'];
        $pe_id_sql = is_null($item['pe_id']) ? "NULL" : (int)$item['pe_id'];
        $cantidad_sql = (float)$item['cantidad'];
        $bloom_sql = is_null($item['bloom']) ? "NULL" : (int)$item['bloom'];

        $queryDetalle = "
            INSERT INTO rev_orden_embarque_detalle
            (
                oe_id,
                oed_tipo_producto,
                rrc_id,
                rr_id,
                pe_id,
                cantidad,
                bloom_vendido
            )
            VALUES
            (
                $oe_id,
                '$tipo_producto',
                $rrc_id_sql,
                $rr_id_sql,
                $pe_id_sql,
                $cantidad_sql,
                $bloom_sql
            )
        ";

        if (!mysqli_query($cnx, $queryDetalle)) {
            throw new Exception('Error al insertar detalle en fila ' . $fila . ': ' . mysqli_error($cnx));
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Orden de embarque registrada correctamente.',
        'oe_id' => $oe_id
    ]);
} catch (Exception $e) {

    /* =====================================
       LIMPIEZA MANUAL PORQUE ES MYISAM
       ===================================== */
    if (!empty($oe_id)) {
        mysqli_query($cnx, "DELETE FROM rev_orden_embarque_detalle WHERE oe_id = " . (int)$oe_id);
        mysqli_query($cnx, "DELETE FROM rev_orden_embarque WHERE oe_id = " . (int)$oe_id);
    }

    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Error al registrar orden: ' . $e->getMessage()
    ]);
} finally {
    mysqli_close($cnx);
}
