<?php
header('Content-Type: application/json; charset=utf-8');
include "../../../conexion/conexion.php";
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$cnx = Conectarse();
$data = json_decode(file_get_contents('php://input'), true);
$cab = $data['FACTURA_CABECERA'] ?? null;

if (!$cab || !isset($data['FACTURA_DETALLE']) || !is_array($data['FACTURA_DETALLE'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos o mal formateados.']);
    exit;
}

$cnx->begin_transaction();

try {

    $tipos_validos = ['Comercial', 'Industrial'];
    if (!in_array($cab['TIPO_VENTA'], $tipos_validos)) {
        http_response_code(400);
        echo json_encode(['error' => 'Tipo de venta inválido']);
        exit;
    }

    $stmt = $cnx->prepare("
    INSERT INTO facturas_sai 
    (factura, vendedor_nombre, cliente_nombre, ubicacion_cliente, tipo_cliente, tipo_venta, total_factura, total_credito, total_real, observaciones, fecha_factura)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

    $stmt->bind_param(
        'isssssdddss',
        $cab['FACTURA'],
        $cab['VENDEDOR_NOMBRE'],
        $cab['CLIENTE_NOMBRE'],
        $cab['UBICACION_CLIENTE'],
        $cab['TIPO_CLIENTE'],
        $cab['TIPO_VENTA'],
        $cab['TOTAL_FACTURADO'],
        $cab['TOTAL_CREDITO'],
        $cab['TOTAL_REAL'],
        $cab['OBSERVACIONES'],
        $cab['FECHA_FACTURA']
    );
    $stmt->execute();
    $factura_id = $cnx->insert_id;
    $stmt->close();

    foreach ($data['FACTURA_DETALLE'] as $det) {
        $stmt2 = $cnx->prepare("
            INSERT INTO factura_sai_detalle 
            (factura_id, producto_cve, producto_descripcion, cantidad, precio_kg)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt2->bind_param(
            'issdd',
            $factura_id,
            $det['PRODUCTO_CVE'],
            $det['PRODUCTO_DESCRIPCION'],
            $det['CANTIDAD'],
            $det['PRECIO_KG']
        );
        $stmt2->execute();
        $detalle_id = $cnx->insert_id;
        $stmt2->close();

        if (isset($det['LOTE']) && is_array($det['LOTE'])) {
            foreach ($det['LOTE'] as $lote) {
                $stmt3 = $cnx->prepare("
                    INSERT INTO factura_sai_detalle_lote (detalle_id, lote) VALUES (?, ?)
                ");
                $stmt3->bind_param('is', $detalle_id, $lote);
                $stmt3->execute();
                $stmt3->close();
            }
        }
    }

    $cnx->commit();
    echo json_encode([
        'success' => true,
        'message' => 'Factura insertada correctamente',
        'factura_id' => $factura_id
    ]);
} catch (Exception $e) {
    $cnx->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor: ' . $e->getMessage()]);
    exit;
} finally {
    $cnx->close();
}
