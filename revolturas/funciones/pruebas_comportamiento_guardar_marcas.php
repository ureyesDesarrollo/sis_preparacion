<?php

include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

header('Content-Type: application/json; charset=utf-8');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Método no permitido');
    }

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data) {
        throw new Exception('JSON inválido');
    }

    $reporte_id = isset($data['reporte_id']) ? (int)$data['reporte_id'] : 0;

    if ($reporte_id <= 0) {
        throw new Exception('Reporte inválido');
    }

    if (empty($data['marcas']) || !is_array($data['marcas'])) {
        throw new Exception('No se recibieron marcas');
    }

    $usuario_id = isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : null;

    mysqli_begin_transaction($cnx);

    $sql_update = "UPDATE lab_reporte_muestras
                   SET
                        marca_id = ?,
                        marca_estatus = ?,
                        marca_usuario_id = ?,
                        marca_fecha = NOW()
                   WHERE reporte_muestra_id = ?
                     AND reporte_id = ?";

    $stmt_update = mysqli_prepare($cnx, $sql_update);

    foreach ($data['marcas'] as $item) {
        $reporte_muestra_id = isset($item['reporte_muestra_id']) ? (int)$item['reporte_muestra_id'] : 0;
        $marca_id = isset($item['marca_id']) && $item['marca_id'] !== '' ? (int)$item['marca_id'] : null;

        if ($reporte_muestra_id <= 0) {
            continue;
        }

        $marca_estatus = $marca_id !== null ? 'ASIGNADA' : 'PENDIENTE';

        mysqli_stmt_bind_param(
            $stmt_update,
            "isiii",
            $marca_id,
            $marca_estatus,
            $usuario_id,
            $reporte_muestra_id,
            $reporte_id
        );

        mysqli_stmt_execute($stmt_update);
    }

    mysqli_commit($cnx);

    echo json_encode(array(
        'success' => true,
        'message' => 'Marcas guardadas correctamente'
    ));

} catch (Throwable $e) {
    if (isset($cnx)) {
        mysqli_rollback($cnx);
    }

    http_response_code(500);

    echo json_encode(array(
        'success' => false,
        'message' => $e->getMessage()
    ));
}