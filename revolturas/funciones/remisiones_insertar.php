<?php
include "../../conexion/conexion.php";

header('Content-Type: application/json');

// Recibe el JSON enviado por AJAX
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos invalidos']);
    exit;
}

$cnx = Conectarse();
mysqli_begin_transaction($cnx);

try {
    // --- 1. Insertar en remisiones ---
    $cab = $data['FACTURA_CABECERA'];
    $folio = $cab['FOLIO'];
    $vendedor = $cab['VENDEDOR'];
    $cliente = $cab['CLIENTE'];
    $ubicacion = $cab['UBICACION'];
    $tipo_cliente = $cab['TIPO_CLIENTE'];
    $tipo_venta = $cab['TIPO_VENTA'];
    $total_remision = $cab['TOTAL_REMISION'];
    $total_credito = $cab['TOTAL_NOTA'];
    $total_real = $cab['TOTAL_REAL'];
    $fecha_remision = $cab['FECHA'];

    // Verifica si ya existe el folio
    $sqlCheck = "SELECT id FROM remisiones WHERE remision = ?";
    $stmtCheck = mysqli_prepare($cnx, $sqlCheck);
    mysqli_stmt_bind_param($stmtCheck, "s", $folio);
    mysqli_stmt_execute($stmtCheck);
    mysqli_stmt_store_result($stmtCheck);
    if (mysqli_stmt_num_rows($stmtCheck) > 0) {
        throw new Exception('El folio de remisión ya existe');
    }

    $sqlRem = "INSERT INTO remisiones 
        (remision, vendedor_nombre, cliente_nombre, ubicacion_cliente, tipo_cliente, tipo_venta, total_remision, total_credito, total_real, fecha_remision)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($cnx, $sqlRem);
    mysqli_stmt_bind_param($stmt, "ssssssddds",
        $folio,
        $vendedor,
        $cliente,
        $ubicacion,
        $tipo_cliente,
        $tipo_venta,
        $total_remision,
        $total_credito,
        $total_real,
        $fecha_remision
    );
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Error insertando remisión: ' . mysqli_error($cnx));
    }
    $remision_id = mysqli_insert_id($cnx);

    // --- 2. Insertar en remision_detalle y remision_detalle_lote ---
    foreach ($data['FACTURA_DETALLE'] as $detalle) {
        $producto_cve = $detalle['PRODUCTO_CVE'];
        $producto_descripcion = $detalle['PRODUCTO_DESCRIPCION'];
        $promocion = isset($detalle['PROMOCION']) ? $detalle['PROMOCION'] : 0;
        $cantidad = $detalle['CANTIDAD'];
        $precio_kg = $detalle['PRECIO'];

        $sqlDet = "INSERT INTO remision_detalle 
            (remision_id, producto_cve, producto_descripcion, promocion, cantidad, precio_kg)
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmtDet = mysqli_prepare($cnx, $sqlDet);
        mysqli_stmt_bind_param($stmtDet, "issidd",
            $remision_id,
            $producto_cve,
            $producto_descripcion,
            $promocion,
            $cantidad,
            $precio_kg
        );
        if (!mysqli_stmt_execute($stmtDet)) {
            throw new Exception('Error insertando detalle: ' . mysqli_error($cnx));
        }
        $detalle_id = mysqli_insert_id($cnx);

        // Insertar lotes asociados a este producto
        if (!empty($detalle['LOTES']) && is_array($detalle['LOTES'])) {
            foreach ($detalle['LOTES'] as $lote) {
                $sqlLote = "INSERT INTO remision_detalle_lote (detalle_id, lote) VALUES (?, ?)";
                $stmtLote = mysqli_prepare($cnx, $sqlLote);
                mysqli_stmt_bind_param($stmtLote, "is", $detalle_id, $lote);
                if (!mysqli_stmt_execute($stmtLote)) {
                    throw new Exception('Error insertando lote: ' . mysqli_error($cnx));
                }
            }
        }
    }

    mysqli_commit($cnx);
    echo json_encode(['success' => true, 'id' => $remision_id]);

} catch (Exception $e) {
    mysqli_rollback($cnx);
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
