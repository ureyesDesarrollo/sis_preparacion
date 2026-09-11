<?php

header('Content-Type: application/json; charset=utf-8');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once '../lib/funcion_consultar_factura_sai.php';
require_once "../../conexion/conexion.php";

$data = json_decode(file_get_contents("php://input"), true);

$anticipo = $data['anticipo'] ?? null;

if ($anticipo === null || trim($anticipo) === '') {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'El folio del anticipo es requerido.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Consultar SAI
|--------------------------------------------------------------------------
*/

$consulta = consultarFacturaSai($anticipo);

if (!$consulta['success']) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => $consulta['error'] ?? 'No se pudo consultar el anticipo.'
    ]);

    exit;
}

$resultado = $consulta['data'];

$producto = $resultado['FACTURA_DETALLE'][0]['PRODUCTO_CVE'] ?? null;

if ($producto !== 'ANTICIPO') {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'El folio proporcionado no corresponde a un anticipo.'
    ]);

    exit;
}

$cab = $resultado['FACTURA_CABECERA'] ?? null;

if (!$cab) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'La factura no contiene cabecera.'
    ]);

    exit;
}

$cnx = Conectarse();

try {

    /*
    |--------------------------------------------------------------------------
    | Validar que no exista
    |--------------------------------------------------------------------------
    */

    $stmtExiste = $cnx->prepare("
        SELECT id
        FROM facturas_sai
        WHERE factura = ?
        LIMIT 1
    ");

    $stmtExiste->bind_param(
        'i',
        $cab['FACTURA']
    );

    $stmtExiste->execute();

    $resultadoExiste = $stmtExiste->get_result();

    if ($resultadoExiste->num_rows > 0) {

        $stmtExiste->close();

        http_response_code(409);

        echo json_encode([
            'success' => false,
            'error' => 'El anticipo ya fue registrado.'
        ]);

        exit;
    }

    $stmtExiste->close();

    /*
    |--------------------------------------------------------------------------
    | Iniciar transacción
    |--------------------------------------------------------------------------
    */

    $cnx->begin_transaction();

    $tipoVenta = 'Comercial';

    // SAI no lo estaba enviando en la respuesta mostrada
    $tipoCliente = 'Comercial';

    /*
    |--------------------------------------------------------------------------
    | Insertar cabecera
    |--------------------------------------------------------------------------
    */

    $stmt = $cnx->prepare("
        INSERT INTO facturas_sai (
            factura,
            vendedor_nombre,
            cliente_nombre,
            ubicacion_cliente,
            tipo_cliente,
            tipo_venta,
            total_factura,
            total_credito,
            total_real,
            observaciones,
            fecha_factura
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        'isssssdddss',
        $cab['FACTURA'],
        $cab['VENDEDOR_NOMBRE'],
        $cab['CLIENTE_NOMBRE'],
        $cab['UBICACION_CLIENTE'],
        $tipoCliente,
        $tipoVenta,
        $cab['TOTAL_FACTURADO'],
        $cab['TOTAL_CREDITO'],
        $cab['TOTAL_REAL'],
        $cab['OBSERVACIONES'],
        $cab['FECHA_FACTURA']
    );

    $stmt->execute();

    $factura_id = $cnx->insert_id;
    
    $stmt->close();

    /*
    |--------------------------------------------------------------------------
    | Preparar detalle una sola vez
    |--------------------------------------------------------------------------
    */

    $stmtDetalle = $cnx->prepare("
        INSERT INTO factura_sai_detalle (
            factura_id,
            producto_cve,
            producto_descripcion,
            cantidad,
            precio_kg
        )
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmtLote = $cnx->prepare("
        INSERT INTO factura_sai_detalle_lote (
            detalle_id,
            lote
        )
        VALUES (?, ?)
    ");

    foreach ($resultado['FACTURA_DETALLE'] as $det) {

        $stmtDetalle->bind_param(
            'issdd',
            $factura_id,
            $det['PRODUCTO_CVE'],
            $det['PRODUCTO_DESCRIPCION'],
            $det['CANTIDAD'],
            $det['PRECIO_KG']
        );

        $stmtDetalle->execute();

        $detalle_id = $cnx->insert_id;

        foreach ($det['LOTE'] ?? [] as $lote) {

            $stmtLote->bind_param(
                'is',
                $detalle_id,
                $lote
            );

            $stmtLote->execute();
        }
    }

    $stmtDetalle->close();
    $stmtLote->close();

    /*
    |--------------------------------------------------------------------------
    | Confirmar
    |--------------------------------------------------------------------------
    */

    $cnx->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Anticipo insertado correctamente.',
        'factura_id' => $factura_id
    ]);

} catch (Throwable $e) {

    $cnx->rollback();

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Error interno del servidor.',
        'detalle' => $e->getMessage()
    ]);

} finally {

    $cnx->close();
}