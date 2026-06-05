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

    if ($cte_id < 0) {
        throw new Exception('Cliente inválido');
    }

    $empaquesValidados = [];

    /*
        Acumuladores para validar disponibilidad total por producto.
        Esto evita que el mismo rr_id / rrc_id / pe_id venga repetido
        y pase la validación por partes.
    */
    $solicitadoGeneral = [];
    $solicitadoCliente = [];
    $solicitadoExterno = [];

    /* =========================
       1. VALIDAR ESTRUCTURA
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

            if ($rr_id === null && $rrc_id === null) {
                throw new Exception("Falta rr_id o rrc_id en la fila $fila");
            }

            if ($rr_id !== null && $rrc_id !== null) {
                throw new Exception("La fila $fila tiene rr_id y rrc_id al mismo tiempo. Solo debe tener uno.");
            }

            if ($rr_id !== null) {
                if (!isset($solicitadoGeneral[$rr_id])) {
                    $solicitadoGeneral[$rr_id] = 0;
                }

                $solicitadoGeneral[$rr_id] += $cantidad;
            }

            if ($rrc_id !== null) {
                if (!isset($solicitadoCliente[$rrc_id])) {
                    $solicitadoCliente[$rrc_id] = 0;
                }

                $solicitadoCliente[$rrc_id] += $cantidad;
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

            if (!isset($solicitadoExterno[$pe_id])) {
                $solicitadoExterno[$pe_id] = 0;
            }

            $solicitadoExterno[$pe_id] += $cantidad;

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
       2. VALIDAR DISPONIBILIDAD PT GENERAL
       ========================= */
    foreach ($solicitadoGeneral as $rr_id => $cantidadSolicitada) {
        $rr_id = (int)$rr_id;

        $sqlDisponible = "
            SELECT 
                rr_id,
                cantidad_disponible
            FROM vw_rev_revolturas_pt_disponible
            WHERE rr_id = $rr_id
            LIMIT 1
        ";

        $resDisponible = mysqli_query($cnx, $sqlDisponible);

        if (!$resDisponible) {
            throw new Exception('Error al validar disponibilidad PT general: ' . mysqli_error($cnx));
        }

        $rowDisponible = mysqli_fetch_assoc($resDisponible);

        if (!$rowDisponible) {
            throw new Exception("No existe inventario PT general para rr_id $rr_id");
        }

        $cantidadDisponible = (float)$rowDisponible['cantidad_disponible'];

        if ($cantidadSolicitada > $cantidadDisponible) {
            throw new Exception(
                "No hay disponibilidad suficiente para rr_id $rr_id. " .
                "Solicitado: $cantidadSolicitada, disponible: $cantidadDisponible"
            );
        }
    }

    /* =========================
       3. VALIDAR DISPONIBILIDAD PT CLIENTE
       ========================= */
    foreach ($solicitadoCliente as $rrc_id => $cantidadSolicitada) {
        $rrc_id = (int)$rrc_id;

        $sqlDisponible = "
            SELECT 
                rrc_id,
                cte_id,
                cantidad_disponible
            FROM vw_rev_revolturas_pt_cliente_disponible
            WHERE rrc_id = $rrc_id
            LIMIT 1
        ";

        $resDisponible = mysqli_query($cnx, $sqlDisponible);

        if (!$resDisponible) {
            throw new Exception('Error al validar disponibilidad PT cliente: ' . mysqli_error($cnx));
        }

        $rowDisponible = mysqli_fetch_assoc($resDisponible);

        if (!$rowDisponible) {
            throw new Exception("No existe inventario PT cliente para rrc_id $rrc_id");
        }

        $cteInventario = (int)$rowDisponible['cte_id'];
        $cantidadDisponible = (float)$rowDisponible['cantidad_disponible'];

        if ($cteInventario !== $cte_id) {
            throw new Exception(
                "El inventario rrc_id $rrc_id pertenece al cliente $cteInventario, no al cliente $cte_id"
            );
        }

        if ($cantidadSolicitada > $cantidadDisponible) {
            throw new Exception(
                "No hay disponibilidad suficiente para rrc_id $rrc_id. " .
                "Solicitado: $cantidadSolicitada, disponible: $cantidadDisponible"
            );
        }
    }

    /* =========================
       4. VALIDAR DISPONIBILIDAD PRODUCTO EXTERNO
       ========================= */
    foreach ($solicitadoExterno as $pe_id => $cantidadSolicitada) {
        $pe_id = (int)$pe_id;

        /*
            Para producto externo todavía no tenemos vista.
            Calculamos disponible así:
            existencia real - comprometido en órdenes abiertas.
        */
        $sqlDisponible = "
            SELECT
                pe.pe_id,
                pe.pe_existencia_real,
                IFNULL(SUM(
                    CASE
                        WHEN oe.oe_estado IN ('PENDIENTE','PROCESO','ETIQUETA LIBERADA','LIBERADO')
                            THEN d.cantidad
                        ELSE 0
                    END
                ), 0) AS cantidad_comprometida,
                pe.pe_existencia_real - IFNULL(SUM(
                    CASE
                        WHEN oe.oe_estado IN ('PENDIENTE','PROCESO','ETIQUETA LIBERADA','LIBERADO')
                            THEN d.cantidad
                        ELSE 0
                    END
                ), 0) AS cantidad_disponible
            FROM producto_externo pe
            LEFT JOIN rev_orden_embarque_detalle d
                ON d.pe_id = pe.pe_id
               AND d.oed_tipo_producto = 'EXTERNO'
            LEFT JOIN rev_orden_embarque oe
                ON oe.oe_id = d.oe_id
            WHERE pe.pe_id = $pe_id
            GROUP BY
                pe.pe_id,
                pe.pe_existencia_real
            LIMIT 1
        ";

        $resDisponible = mysqli_query($cnx, $sqlDisponible);

        if (!$resDisponible) {
            throw new Exception('Error al validar disponibilidad producto externo: ' . mysqli_error($cnx));
        }

        $rowDisponible = mysqli_fetch_assoc($resDisponible);

        if (!$rowDisponible) {
            throw new Exception("No existe producto externo pe_id $pe_id");
        }

        $cantidadDisponible = (float)$rowDisponible['cantidad_disponible'];

        if ($cantidadSolicitada > $cantidadDisponible) {
            throw new Exception(
                "No hay disponibilidad suficiente para producto externo pe_id $pe_id. " .
                "Solicitado: $cantidadSolicitada, disponible: $cantidadDisponible"
            );
        }
    }

    /* =========================
       5. INSERTAR CABECERA
       ========================= */
    $query = "
        INSERT INTO rev_orden_embarque (cte_id)
        VALUES ($cte_id)
    ";

    if (!mysqli_query($cnx, $query)) {
        throw new Exception('Error al insertar la orden: ' . mysqli_error($cnx));
    }

    $oe_id = mysqli_insert_id($cnx);

    /* =========================
       6. INSERTAR DETALLES
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

    /*
        Limpieza manual porque las tablas todavía son MyISAM.
        Cuando se conviertan a InnoDB, esto debe cambiarse por transacción real.
    */
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