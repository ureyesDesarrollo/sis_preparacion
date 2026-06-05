<?php
header('Content-Type: application/json');

include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $oe_id = isset($data['orden_id']) ? (int)$data['orden_id'] : 0;

    if ($oe_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de orden no válido']);
        exit;
    }

    mysqli_begin_transaction($cnx);

    /*
        1. Validar estado de la orden.

        Regla:
        - Si está COMPLETADA o FACTURADA, NO se vuelve a descontar.
        - Si está CANCELADA, no se puede completar.
    */
    $sql_orden = "
        SELECT oe_id, oe_estado, remision_ban
        FROM rev_orden_embarque
        WHERE oe_id = ?
        LIMIT 1
    ";

    $stmt = mysqli_prepare($cnx, $sql_orden);
    mysqli_stmt_bind_param($stmt, "i", $oe_id);
    mysqli_stmt_execute($stmt);
    $res_orden = mysqli_stmt_get_result($stmt);
    $orden = mysqli_fetch_assoc($res_orden);

    if (!$orden) {
        throw new Exception("No se encontró la orden de embarque $oe_id");
    }

    $estado_actual = $orden['oe_estado'];

    if ($estado_actual === 'COMPLETADA' || $estado_actual === 'FACTURADA') {
        throw new Exception("La orden $oe_id ya fue completada/facturada. No se puede descontar dos veces.");
    }

    if ($estado_actual === 'CANCELADA') {
        throw new Exception("La orden $oe_id está cancelada. No se puede completar.");
    }

    /*
        2. Obtener detalles de la orden.
    */
    $sql_detalles = "
        SELECT 
            oed_id,
            oe_id,
            oed_tipo_producto,
            rr_id,
            rrc_id,
            pe_id,
            cantidad
        FROM rev_orden_embarque_detalle
        WHERE oe_id = ?
    ";

    $stmt = mysqli_prepare($cnx, $sql_detalles);
    mysqli_stmt_bind_param($stmt, "i", $oe_id);
    mysqli_stmt_execute($stmt);
    $res_detalles = mysqli_stmt_get_result($stmt);

    if (!$res_detalles) {
        throw new Exception('Error al consultar detalles: ' . mysqli_error($cnx));
    }

    $detalles = [];

    while ($detalle = mysqli_fetch_assoc($res_detalles)) {
        $detalles[] = $detalle;
    }

    if (count($detalles) === 0) {
        throw new Exception("La orden $oe_id no tiene detalles.");
    }

    /*
        3. Validar estructura de detalles antes de descontar.
    */
    foreach ($detalles as $detalle) {
        $oed_id = (int)$detalle['oed_id'];
        $tipo_producto = isset($detalle['oed_tipo_producto']) ? $detalle['oed_tipo_producto'] : 'REVOLTURA';

        $rr_id = !empty($detalle['rr_id']) ? (int)$detalle['rr_id'] : 0;
        $rrc_id = !empty($detalle['rrc_id']) ? (int)$detalle['rrc_id'] : 0;
        $pe_id = !empty($detalle['pe_id']) ? (int)$detalle['pe_id'] : 0;
        $cantidad = (float)$detalle['cantidad'];

        if ($cantidad <= 0) {
            throw new Exception("Cantidad inválida en detalle $oed_id");
        }

        if ($tipo_producto === 'REVOLTURA') {
            if ($rr_id <= 0 && $rrc_id <= 0) {
                throw new Exception("Detalle $oed_id inválido: no tiene rr_id ni rrc_id");
            }

            if ($rr_id > 0 && $rrc_id > 0) {
                throw new Exception("Detalle $oed_id inválido: tiene rr_id y rrc_id al mismo tiempo");
            }

            if ($pe_id > 0) {
                throw new Exception("Detalle $oed_id inválido: es REVOLTURA pero tiene pe_id");
            }
        } elseif ($tipo_producto === 'EXTERNO') {
            if ($pe_id <= 0) {
                throw new Exception("Detalle $oed_id inválido: es EXTERNO pero no tiene pe_id");
            }

            if ($rr_id > 0 || $rrc_id > 0) {
                throw new Exception("Detalle $oed_id inválido: es EXTERNO pero tiene rr_id o rrc_id");
            }
        } else {
            throw new Exception("Tipo de producto inválido en detalle $oed_id");
        }
    }

    /*
        4. Descontar inventario.

        Regla:
        La orden de embarque solo compromete.
        Aquí, al completarse, se descuenta físicamente.
    */
    foreach ($detalles as $detalle) {
        $oed_id = (int)$detalle['oed_id'];
        $tipo_producto = isset($detalle['oed_tipo_producto']) ? $detalle['oed_tipo_producto'] : 'REVOLTURA';

        $rr_id = !empty($detalle['rr_id']) ? (int)$detalle['rr_id'] : 0;
        $rrc_id = !empty($detalle['rrc_id']) ? (int)$detalle['rrc_id'] : 0;
        $pe_id = !empty($detalle['pe_id']) ? (int)$detalle['pe_id'] : 0;
        $cantidad_solicitada = (float)$detalle['cantidad'];

        if ($tipo_producto === 'EXTERNO') {
            /*
                PRODUCTO EXTERNO
            */
            $sql_update = "
                UPDATE producto_externo
                SET pe_existencia_real = pe_existencia_real - ?
                WHERE pe_id = ?
                  AND pe_existencia_real >= ?
            ";

            $stmt = mysqli_prepare($cnx, $sql_update);
            mysqli_stmt_bind_param(
                $stmt,
                "did",
                $cantidad_solicitada,
                $pe_id,
                $cantidad_solicitada
            );
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) <= 0) {
                throw new Exception("No hay existencia suficiente para producto externo pe_id $pe_id");
            }

        } else {
            /*
                REVOLTURA PT GENERAL
            */
            if ($rr_id > 0) {
                $sql_info = "
                    SELECT 
                        rr.rr_id,
                        rr.pres_id,
                        pres.pres_kg,
                        rr.rr_ext_real
                    FROM rev_revolturas_pt rr
                    INNER JOIN rev_presentacion pres 
                        ON pres.pres_id = rr.pres_id
                    WHERE rr.rr_id = ?
                    LIMIT 1
                ";

                $stmt = mysqli_prepare($cnx, $sql_info);
                mysqli_stmt_bind_param($stmt, "i", $rr_id);
                mysqli_stmt_execute($stmt);
                $res_info = mysqli_stmt_get_result($stmt);
                $info = mysqli_fetch_assoc($res_info);

                if (!$info) {
                    throw new Exception("No se encontró empaque general rr_id $rr_id");
                }

                $pres_kg = (float)$info['pres_kg'];
                $cantidad_solicitada_kg = $cantidad_solicitada * $pres_kg;

                /*
                    Validar posiciones antes de tocar inventario.
                    Si no hay posiciones suficientes, no completamos.
                */
                $sql_pos_total = "
                    SELECT IFNULL(SUM(cantidad), 0) AS cantidad_posicion_kg
                    FROM rev_nivel_posicion_detalle
                    WHERE rr_id = ?
                      AND cantidad > 0
                ";

                $stmt = mysqli_prepare($cnx, $sql_pos_total);
                mysqli_stmt_bind_param($stmt, "i", $rr_id);
                mysqli_stmt_execute($stmt);
                $res_pos_total = mysqli_stmt_get_result($stmt);
                $pos_total = mysqli_fetch_assoc($res_pos_total);

                $cantidad_posicion_kg = $pos_total ? (float)$pos_total['cantidad_posicion_kg'] : 0;

                if ($cantidad_posicion_kg < $cantidad_solicitada_kg) {
                    throw new Exception(
                        "No hay kilos suficientes en posiciones para rr_id $rr_id. " .
                        "Necesario: $cantidad_solicitada_kg kg, disponible en posiciones: $cantidad_posicion_kg kg"
                    );
                }

                /*
                    Descontar existencia real con protección contra negativos.
                */
                $sql_update = "
                    UPDATE rev_revolturas_pt
                    SET rr_ext_real = rr_ext_real - ?
                    WHERE rr_id = ?
                      AND rr_ext_real >= ?
                ";

                $stmt = mysqli_prepare($cnx, $sql_update);
                mysqli_stmt_bind_param(
                    $stmt,
                    "did",
                    $cantidad_solicitada,
                    $rr_id,
                    $cantidad_solicitada
                );
                mysqli_stmt_execute($stmt);

                if (mysqli_stmt_affected_rows($stmt) <= 0) {
                    throw new Exception("No hay existencia suficiente para empaque general rr_id $rr_id");
                }

                /*
                    Descontar posiciones FIFO.
                */
                $restante_kg = $cantidad_solicitada_kg;

                $sql_pos = "
                    SELECT 
                        nvd.nvd_id,
                        nvd.cantidad,
                        nvd.niv_id
                    FROM rev_nivel_posicion_detalle nvd
                    WHERE nvd.rr_id = ?
                      AND nvd.cantidad > 0
                    ORDER BY nvd.nvd_id ASC
                ";

                $stmt = mysqli_prepare($cnx, $sql_pos);
                mysqli_stmt_bind_param($stmt, "i", $rr_id);
                mysqli_stmt_execute($stmt);
                $res_pos = mysqli_stmt_get_result($stmt);

                while ($pos = mysqli_fetch_assoc($res_pos)) {
                    if ($restante_kg <= 0) {
                        break;
                    }

                    $cantidad_en_posicion = (float)$pos['cantidad'];
                    $descontar = min($restante_kg, $cantidad_en_posicion);

                    $pos_id = (int)$pos['nvd_id'];
                    $niv_id = (int)$pos['niv_id'];

                    $sql_upd_pos = "
                        UPDATE rev_nivel_posicion_detalle
                        SET cantidad = cantidad - ?
                        WHERE nvd_id = ?
                          AND cantidad >= ?
                    ";

                    $stmt_pos = mysqli_prepare($cnx, $sql_upd_pos);
                    mysqli_stmt_bind_param(
                        $stmt_pos,
                        "did",
                        $descontar,
                        $pos_id,
                        $descontar
                    );
                    mysqli_stmt_execute($stmt_pos);

                    if (mysqli_stmt_affected_rows($stmt_pos) <= 0) {
                        throw new Exception("No se pudo descontar posición nvd_id $pos_id");
                    }

                    /*
                        Si la posición quedó en cero, eliminar detalle y liberar ubicación.
                    */
                    $sql_check_zero = "
                        SELECT cantidad
                        FROM rev_nivel_posicion_detalle
                        WHERE nvd_id = ?
                    ";

                    $stmt_check = mysqli_prepare($cnx, $sql_check_zero);
                    mysqli_stmt_bind_param($stmt_check, "i", $pos_id);
                    mysqli_stmt_execute($stmt_check);
                    $res_check = mysqli_stmt_get_result($stmt_check);
                    $check = mysqli_fetch_assoc($res_check);

                    if ($check && (float)$check['cantidad'] <= 0) {
                        $sql_delete_pos = "
                            DELETE FROM rev_nivel_posicion_detalle
                            WHERE nvd_id = ?
                        ";

                        $stmt_delete = mysqli_prepare($cnx, $sql_delete_pos);
                        mysqli_stmt_bind_param($stmt_delete, "i", $pos_id);
                        mysqli_stmt_execute($stmt_delete);

                        $sql_update_nivel = "
                            UPDATE rev_nivel_posicion
                            SET niv_ocupado = 0
                            WHERE niv_id = ?
                        ";

                        $stmt_nivel = mysqli_prepare($cnx, $sql_update_nivel);
                        mysqli_stmt_bind_param($stmt_nivel, "i", $niv_id);
                        mysqli_stmt_execute($stmt_nivel);
                    }

                    $restante_kg -= $descontar;
                }

                if ($restante_kg > 0.0001) {
                    throw new Exception("No se descontaron todas las posiciones para rr_id $rr_id");
                }

            /*
                REVOLTURA PT CLIENTE
            */
            } elseif ($rrc_id > 0) {
                $sql_info = "
                    SELECT 
                        rrc.rrc_id,
                        rrc.pres_id,
                        pres.pres_kg,
                        rrc.rrc_ext_real
                    FROM rev_revolturas_pt_cliente rrc
                    INNER JOIN rev_presentacion pres 
                        ON pres.pres_id = rrc.pres_id
                    WHERE rrc.rrc_id = ?
                    LIMIT 1
                ";

                $stmt = mysqli_prepare($cnx, $sql_info);
                mysqli_stmt_bind_param($stmt, "i", $rrc_id);
                mysqli_stmt_execute($stmt);
                $res_info = mysqli_stmt_get_result($stmt);
                $info = mysqli_fetch_assoc($res_info);

                if (!$info) {
                    throw new Exception("No se encontró empaque cliente rrc_id $rrc_id");
                }

                $pres_kg = (float)$info['pres_kg'];
                $cantidad_solicitada_kg = $cantidad_solicitada * $pres_kg;

                /*
                    Validar posiciones antes de tocar inventario.
                */
                $sql_pos_total = "
                    SELECT IFNULL(SUM(cantidad), 0) AS cantidad_posicion_kg
                    FROM rev_nivel_posicion_detalle
                    WHERE rrc_id = ?
                      AND cantidad > 0
                ";

                $stmt = mysqli_prepare($cnx, $sql_pos_total);
                mysqli_stmt_bind_param($stmt, "i", $rrc_id);
                mysqli_stmt_execute($stmt);
                $res_pos_total = mysqli_stmt_get_result($stmt);
                $pos_total = mysqli_fetch_assoc($res_pos_total);

                $cantidad_posicion_kg = $pos_total ? (float)$pos_total['cantidad_posicion_kg'] : 0;

                if ($cantidad_posicion_kg < $cantidad_solicitada_kg) {
                    throw new Exception(
                        "No hay kilos suficientes en posiciones para rrc_id $rrc_id. " .
                        "Necesario: $cantidad_solicitada_kg kg, disponible en posiciones: $cantidad_posicion_kg kg"
                    );
                }

                /*
                    Descontar existencia real con protección.
                */
                $sql_update = "
                    UPDATE rev_revolturas_pt_cliente
                    SET rrc_ext_real = rrc_ext_real - ?
                    WHERE rrc_id = ?
                      AND rrc_ext_real >= ?
                ";

                $stmt = mysqli_prepare($cnx, $sql_update);
                mysqli_stmt_bind_param(
                    $stmt,
                    "did",
                    $cantidad_solicitada,
                    $rrc_id,
                    $cantidad_solicitada
                );
                mysqli_stmt_execute($stmt);

                if (mysqli_stmt_affected_rows($stmt) <= 0) {
                    throw new Exception("No hay existencia suficiente para empaque cliente rrc_id $rrc_id");
                }

                /*
                    Descontar posiciones FIFO.
                */
                $restante_kg = $cantidad_solicitada_kg;

                $sql_pos = "
                    SELECT 
                        nvd.nvd_id,
                        nvd.cantidad,
                        nvd.niv_id
                    FROM rev_nivel_posicion_detalle nvd
                    WHERE nvd.rrc_id = ?
                      AND nvd.cantidad > 0
                    ORDER BY nvd.nvd_id ASC
                ";

                $stmt = mysqli_prepare($cnx, $sql_pos);
                mysqli_stmt_bind_param($stmt, "i", $rrc_id);
                mysqli_stmt_execute($stmt);
                $res_pos = mysqli_stmt_get_result($stmt);

                while ($pos = mysqli_fetch_assoc($res_pos)) {
                    if ($restante_kg <= 0) {
                        break;
                    }

                    $cantidad_en_posicion = (float)$pos['cantidad'];
                    $descontar = min($restante_kg, $cantidad_en_posicion);

                    $pos_id = (int)$pos['nvd_id'];
                    $niv_id = (int)$pos['niv_id'];

                    $sql_upd_pos = "
                        UPDATE rev_nivel_posicion_detalle
                        SET cantidad = cantidad - ?
                        WHERE nvd_id = ?
                          AND cantidad >= ?
                    ";

                    $stmt_pos = mysqli_prepare($cnx, $sql_upd_pos);
                    mysqli_stmt_bind_param(
                        $stmt_pos,
                        "did",
                        $descontar,
                        $pos_id,
                        $descontar
                    );
                    mysqli_stmt_execute($stmt_pos);

                    if (mysqli_stmt_affected_rows($stmt_pos) <= 0) {
                        throw new Exception("No se pudo descontar posición nvd_id $pos_id");
                    }

                    $sql_check_zero = "
                        SELECT cantidad
                        FROM rev_nivel_posicion_detalle
                        WHERE nvd_id = ?
                    ";

                    $stmt_check = mysqli_prepare($cnx, $sql_check_zero);
                    mysqli_stmt_bind_param($stmt_check, "i", $pos_id);
                    mysqli_stmt_execute($stmt_check);
                    $res_check = mysqli_stmt_get_result($stmt_check);
                    $check = mysqli_fetch_assoc($res_check);

                    if ($check && (float)$check['cantidad'] <= 0) {
                        $sql_delete_pos = "
                            DELETE FROM rev_nivel_posicion_detalle
                            WHERE nvd_id = ?
                        ";

                        $stmt_delete = mysqli_prepare($cnx, $sql_delete_pos);
                        mysqli_stmt_bind_param($stmt_delete, "i", $pos_id);
                        mysqli_stmt_execute($stmt_delete);

                        $sql_update_nivel = "
                            UPDATE rev_nivel_posicion
                            SET niv_ocupado = 0
                            WHERE niv_id = ?
                        ";

                        $stmt_nivel = mysqli_prepare($cnx, $sql_update_nivel);
                        mysqli_stmt_bind_param($stmt_nivel, "i", $niv_id);
                        mysqli_stmt_execute($stmt_nivel);
                    }

                    $restante_kg -= $descontar;
                }

                if ($restante_kg > 0.0001) {
                    throw new Exception("No se descontaron todas las posiciones para rrc_id $rrc_id");
                }
            }
        }
    }

    /*
        5. Definir estado final.
        Conservé tu regla actual:
        - remision_ban = 1 => FACTURADA
        - si no => COMPLETADA
    */
    $res_status = $orden['remision_ban'];

    if ($res_status === '1' || $res_status === 1) {
        $status = 'FACTURADA';
    } else {
        $status = 'COMPLETADA';
    }

    $sql_update_orden = "
        UPDATE rev_orden_embarque
        SET oe_estado = ?
        WHERE oe_id = ?
          AND oe_estado NOT IN ('COMPLETADA', 'FACTURADA', 'CANCELADA')
    ";

    $stmt = mysqli_prepare($cnx, $sql_update_orden);
    mysqli_stmt_bind_param($stmt, "si", $status, $oe_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) <= 0) {
        throw new Exception("No se pudo actualizar la orden. Puede que ya haya sido completada por otro usuario.");
    }

    mysqli_commit($cnx);

    echo json_encode([
        'success' => true,
        'mensaje' => 'Embarque terminado',
        'oe_id' => $oe_id,
        'estado' => $status
    ]);

} catch (Exception $e) {
    mysqli_rollback($cnx);

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    mysqli_close($cnx);
}