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

    $orden_id        = isset($data['roe_id']) ? intval($data['roe_id']) : 0;
    $detalle_id      = isset($data['roed_id']) ? intval($data['roed_id']) : 0;
    $cantidad        = isset($data['cantidad']) ? floatval($data['cantidad']) : 0;
    $presentacion_id = isset($data['presentacion_id']) ? intval($data['presentacion_id']) : 0;

    if ($orden_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de orden de empaque no proporcionado']);
        exit;
    }

    if ($detalle_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de detalle de orden de empaque no proporcionado']);
        exit;
    }

    if ($presentacion_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de presentación no proporcionado']);
        exit;
    }

    if ($cantidad <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'No se permiten cantidades en 0 o negativas']);
        exit;
    }

    mysqli_begin_transaction($cnx);

    /*
        Obtener el detalle exacto y el cliente teórico.
        Importante: ahora sí se filtra por roed_id, para no tomar otro detalle de la misma orden.
    */
    $query = "
        SELECT 
            d.roed_cantidad,
            IFNULL(d.roed_cantidad_capturada, 0) AS cantidad_actual,
            d.rev_id,
            d.pres_id,
            r.rev_teo_cliente AS cte_id
        FROM rev_orden_empaque_detalle d
        INNER JOIN rev_revolturas r
            ON r.rev_id = d.rev_id
        WHERE d.roe_id = ?
          AND d.roed_id = ?
          AND d.pres_id = ?
        LIMIT 1
    ";

    $stmt = mysqli_prepare($cnx, $query);
    mysqli_stmt_bind_param($stmt, "iii", $orden_id, $detalle_id, $presentacion_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        mysqli_rollback($cnx);
        http_response_code(404);
        echo json_encode(['error' => 'Detalle de orden de empaque no encontrado']);
        exit;
    }

    $cantidad_solicitada = floatval($row['roed_cantidad']);
    $cantidad_actual     = floatval($row['cantidad_actual']);
    $rev_id              = intval($row['rev_id']);
    $pres_id_bd          = intval($row['pres_id']);
    $cliente_id          = intval($row['cte_id']);

    $nueva_cantidad = $cantidad_actual + $cantidad;

    if ($nueva_cantidad > $cantidad_solicitada) {
        mysqli_rollback($cnx);
        http_response_code(400);
        echo json_encode([
            'error' => 'La cantidad capturada supera la cantidad solicitada',
            'data' => [
                'cantidad_solicitada' => $cantidad_solicitada,
                'cantidad_actual' => $cantidad_actual,
                'cantidad_intentada' => $cantidad,
                'nueva_cantidad' => $nueva_cantidad
            ]
        ]);
        exit;
    }

    /*
        Actualizar cantidad capturada.
        La condición evita que dos capturas simultáneas superen lo solicitado.
    */
    $update_query = "
        UPDATE rev_orden_empaque_detalle
        SET roed_cantidad_capturada = IFNULL(roed_cantidad_capturada, 0) + ?
        WHERE roe_id = ?
          AND roed_id = ?
          AND pres_id = ?
          AND (IFNULL(roed_cantidad_capturada, 0) + ?) <= roed_cantidad
    ";

    $stmt = mysqli_prepare($cnx, $update_query);
    mysqli_stmt_bind_param(
        $stmt,
        "diiid",
        $cantidad,
        $orden_id,
        $detalle_id,
        $presentacion_id,
        $cantidad
    );
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) <= 0) {
        mysqli_rollback($cnx);
        http_response_code(400);
        echo json_encode(['error' => 'No se pudo actualizar la cantidad capturada. Puede que ya haya sido capturada por otro usuario.']);
        exit;
    }

    /*
        Marcar orden como PROCESO si está pendiente.
    */
    $update_status_orden = "
        UPDATE rev_orden_empaque
        SET roe_estado = 'PROCESO'
        WHERE roe_id = ?
          AND roe_estado = 'PENDIENTE'
    ";

    $stmt = mysqli_prepare($cnx, $update_status_orden);
    mysqli_stmt_bind_param($stmt, "i", $orden_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_errno($stmt)) {
        throw new Exception("Error al actualizar estado de la orden de empaque");
    }

    /*
        Regla actual:
        rev_teo_cliente = 74 significa venta general.
        Entonces entra a rev_revolturas_pt.

        Cualquier otro cliente entra a rev_revolturas_pt_cliente.

        Nueva regla:
        Un solo registro por roed_id.
        Si se captura otra parcialidad del mismo roed_id, suma sobre el mismo registro.
    */
    if ($cliente_id === 74) {

        $insert_empaque = "
            INSERT INTO rev_revolturas_pt (
                rev_id,
                pres_id,
                roed_id,
                rr_ext_inicial,
                rr_ext_real
            )
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                rr_ext_inicial = rr_ext_inicial + VALUES(rr_ext_inicial),
                rr_ext_real = rr_ext_real + VALUES(rr_ext_real)
        ";

        $stmt = mysqli_prepare($cnx, $insert_empaque);
        mysqli_stmt_bind_param(
            $stmt,
            "iiidd",
            $rev_id,
            $pres_id_bd,
            $detalle_id,
            $cantidad,
            $cantidad
        );
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_errno($stmt)) {
            throw new Exception("Error al guardar o actualizar empaque en PT general: " . mysqli_stmt_error($stmt));
        }

    } else {

        $insert_empaque = "
            INSERT INTO rev_revolturas_pt_cliente (
                rev_id,
                pres_id,
                roed_id,
                rrc_ext_inicial,
                rrc_ext_real,
                cte_id
            )
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                rrc_ext_inicial = rrc_ext_inicial + VALUES(rrc_ext_inicial),
                rrc_ext_real = rrc_ext_real + VALUES(rrc_ext_real)
        ";

        $stmt = mysqli_prepare($cnx, $insert_empaque);
        mysqli_stmt_bind_param(
            $stmt,
            "iiiddi",
            $rev_id,
            $pres_id_bd,
            $detalle_id,
            $cantidad,
            $cantidad,
            $cliente_id
        );
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_errno($stmt)) {
            throw new Exception("Error al guardar o actualizar empaque en PT cliente: " . mysqli_stmt_error($stmt));
        }
    }

    mysqli_commit($cnx);

    $cantidad_restante = $cantidad_solicitada - $nueva_cantidad;

    echo json_encode([
        "success" => true,
        "data" => [
            "mensaje" => "Parcialidad guardada correctamente",
            "cantidad_restante" => $cantidad_restante,
            "rev_id" => $rev_id,
            "pres_id" => $pres_id_bd,
            "roed_id" => $detalle_id,
            "cte_id" => $cliente_id,
            "tipo_inventario" => ($cliente_id === 74 ? "PT_GENERAL" : "PT_CLIENTE")
        ]
    ]);

} catch (Exception $e) {
    if ($cnx) {
        mysqli_rollback($cnx);
    }

    http_response_code(500);
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
} finally {
    if ($cnx) {
        mysqli_close($cnx);
    }
}