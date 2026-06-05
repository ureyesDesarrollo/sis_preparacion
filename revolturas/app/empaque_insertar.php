<?php
header('Content-Type: application/json');

include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$cnx = null;

try {
    $cnx = Conectarse();

    $data = json_decode(file_get_contents('php://input'), true);

    $rev_id   = isset($data['rev_id']) ? intval($data['rev_id']) : 0;
    $orden_id = isset($data['orden_id']) ? intval($data['orden_id']) : 0;

    if ($rev_id <= 0 || $orden_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Faltan datos requeridos.']);
        exit;
    }

    mysqli_begin_transaction($cnx);

    /*
        Validar que la orden exista y no esté completada/cancelada.
    */
    $query_orden = "
        SELECT roe_id, roe_estado
        FROM rev_orden_empaque
        WHERE roe_id = ?
        LIMIT 1
    ";

    $stmt = mysqli_prepare($cnx, $query_orden);
    mysqli_stmt_bind_param($stmt, "i", $orden_id);
    mysqli_stmt_execute($stmt);

    $result_orden = mysqli_stmt_get_result($stmt);
    $orden = mysqli_fetch_assoc($result_orden);

    if (!$orden) {
        mysqli_rollback($cnx);
        http_response_code(404);
        echo json_encode(['error' => 'Orden de empaque no encontrada']);
        exit;
    }

    if ($orden['roe_estado'] === 'COMPLETADA') {
        mysqli_rollback($cnx);
        http_response_code(400);
        echo json_encode(['error' => 'La orden de empaque ya está completada']);
        exit;
    }

    if ($orden['roe_estado'] === 'CANCELADA') {
        mysqli_rollback($cnx);
        http_response_code(400);
        echo json_encode(['error' => 'No se puede completar una orden cancelada']);
        exit;
    }

    /*
        Validar que todos los detalles estén totalmente capturados.
        Esto evita terminar una orden incompleta.
    */
    $query_pendientes = "
        SELECT COUNT(*) AS pendientes
        FROM rev_orden_empaque_detalle
        WHERE roe_id = ?
          AND IFNULL(roed_cantidad_capturada, 0) < roed_cantidad
    ";

    $stmt = mysqli_prepare($cnx, $query_pendientes);
    mysqli_stmt_bind_param($stmt, "i", $orden_id);
    mysqli_stmt_execute($stmt);

    $result_pendientes = mysqli_stmt_get_result($stmt);
    $pendientes = mysqli_fetch_assoc($result_pendientes);

    if (intval($pendientes['pendientes']) > 0) {
        mysqli_rollback($cnx);
        http_response_code(400);
        echo json_encode(['error' => 'No se puede completar la orden porque tiene cantidades pendientes de empacar']);
        exit;
    }

    /*
        Obtener posiciones ocupadas por la revoltura.
    */
    $posiciones_ocupadas = [];

    $query_niveles = "
        SELECT niv_id
        FROM rev_nivel_posicion_detalle
        WHERE rev_id = ?
    ";

    $stmt = mysqli_prepare($cnx, $query_niveles);
    mysqli_stmt_bind_param($stmt, "i", $rev_id);
    mysqli_stmt_execute($stmt);

    $niveles = mysqli_stmt_get_result($stmt);

    while ($nivel = mysqli_fetch_assoc($niveles)) {
        $posiciones_ocupadas[] = intval($nivel['niv_id']);
    }

    /*
        Liberar posiciones.
    */
    foreach ($posiciones_ocupadas as $niv_id) {
        $delete_posicion = "
            DELETE FROM rev_nivel_posicion_detalle
            WHERE niv_id = ?
              AND rev_id = ?
        ";

        $stmt = mysqli_prepare($cnx, $delete_posicion);
        mysqli_stmt_bind_param($stmt, "ii", $niv_id, $rev_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_errno($stmt)) {
            throw new Exception("Error al liberar detalle de posición");
        }

        $update_nivel = "
            UPDATE rev_nivel_posicion
            SET niv_ocupado = 0
            WHERE niv_id = ?
        ";

        $stmt = mysqli_prepare($cnx, $update_nivel);
        mysqli_stmt_bind_param($stmt, "i", $niv_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_errno($stmt)) {
            throw new Exception("Error al actualizar posición");
        }
    }

    /*
        Marcar orden como completada.
    */
    $update_status_orden = "
        UPDATE rev_orden_empaque
        SET roe_estado = 'COMPLETADA'
        WHERE roe_id = ?
    ";

    $stmt = mysqli_prepare($cnx, $update_status_orden);
    mysqli_stmt_bind_param($stmt, "i", $orden_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_errno($stmt)) {
        throw new Exception("Error al actualizar estado de la orden de empaque");
    }

    /*
        Marcar revoltura como empacada.
    */
    $sql_terminar_revoltura = "
        UPDATE rev_revolturas
        SET rev_estatus = '3',
            rev_fecha_empacado = NOW()
        WHERE rev_id = ?
    ";

    $stmt = mysqli_prepare($cnx, $sql_terminar_revoltura);
    mysqli_stmt_bind_param($stmt, "i", $rev_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_errno($stmt)) {
        throw new Exception("Error al actualizar estado de la revoltura");
    }

    mysqli_commit($cnx);

    echo json_encode([
        "success" => true,
        "data" => [
            "mensaje" => "Empaque terminado correctamente."
        ]
    ]);

} catch (Exception $e) {
    if ($cnx) {
        mysqli_rollback($cnx);
    }

    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if ($cnx) {
        mysqli_close($cnx);
    }
}