<?php
header('Content-Type: application/json');

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    if (
        !isset($_POST['rr_id']) ||
        !isset($_POST['rrc_cantidad']) ||
        !isset($_POST['cte_id'])
    ) {
        http_response_code(400);
        echo json_encode(["error" => "Todos los campos son obligatorios"]);
        exit;
    }

    $rr_id = intval($_POST['rr_id']);
    $rrc_cantidad = floatval($_POST['rrc_cantidad']);
    $cte_id = intval($_POST['cte_id']);

    $cte_tipo = isset($_POST['cte_tipo']) ? trim($_POST['cte_tipo']) : null;
    $cte_clasificacion = isset($_POST['cte_clasificacion']) ? trim($_POST['cte_clasificacion']) : null;

    if ($rr_id <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "rr_id inválido"]);
        exit;
    }

    if ($cte_id <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "Cliente inválido"]);
        exit;
    }

    if ($rrc_cantidad <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "La cantidad debe ser mayor que 0"]);
        exit;
    }

    mysqli_begin_transaction($cnx);

    /*
        1. Obtener información real del PT general.
        No confiamos en rev_id / pres_id que vengan por POST.
        Se toman desde la base usando rr_id.

        Además se valida contra cantidad_disponible:
        existencia real - comprometido en órdenes abiertas.
    */
    $sql_pt = "
        SELECT 
            rr_id,
            rev_id,
            pres_id,
            rr_ext_real,
            cantidad_comprometida,
            cantidad_disponible
        FROM vw_rev_revolturas_pt_disponible
        WHERE rr_id = ?
        LIMIT 1
    ";

    $stmt = mysqli_prepare($cnx, $sql_pt);
    mysqli_stmt_bind_param($stmt, "i", $rr_id);
    mysqli_stmt_execute($stmt);

    $res_pt = mysqli_stmt_get_result($stmt);
    $pt = mysqli_fetch_assoc($res_pt);

    if (!$pt) {
        throw new Exception("No se encontró PT general para rr_id $rr_id");
    }

    $rev_id = intval($pt['rev_id']);
    $pres_id = intval($pt['pres_id']);
    $rr_ext_real = floatval($pt['rr_ext_real']);
    $cantidad_comprometida = floatval($pt['cantidad_comprometida']);
    $cantidad_disponible = floatval($pt['cantidad_disponible']);

    if ($rrc_cantidad > $cantidad_disponible) {
        throw new Exception(
            "No hay disponibilidad suficiente para asignar. " .
            "Existencia real: $rr_ext_real, " .
            "comprometido: $cantidad_comprometida, " .
            "disponible: $cantidad_disponible, " .
            "solicitado: $rrc_cantidad"
        );
    }

    /*
        2. Descontar PT general.
        Aunque ya validamos disponibilidad, protegemos contra negativos.
    */
    $sql_update_pt = "
        UPDATE rev_revolturas_pt
        SET rr_ext_real = rr_ext_real - ?
        WHERE rr_id = ?
          AND rr_ext_real >= ?
    ";

    $stmt = mysqli_prepare($cnx, $sql_update_pt);
    mysqli_stmt_bind_param($stmt, "did", $rrc_cantidad, $rr_id, $rrc_cantidad);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) <= 0) {
        throw new Exception("No se pudo descontar PT general. Puede que la existencia haya cambiado.");
    }

    /*
        3. Insertar en PT cliente.

        Este registro NO lleva roed_id porque no viene directo del empaque.
        Viene de una reasignación desde PT general.
    */
    $sql_insert_cliente = "
        INSERT INTO rev_revolturas_pt_cliente (
            rev_id,
            pres_id,
            roed_id,
            rrc_ext_inicial,
            rrc_ext_real,
            cte_id
        )
        VALUES (
            ?,
            ?,
            NULL,
            ?,
            ?,
            ?
        )
    ";

    $stmt = mysqli_prepare($cnx, $sql_insert_cliente);
    mysqli_stmt_bind_param(
        $stmt,
        "iiddi",
        $rev_id,
        $pres_id,
        $rrc_cantidad,
        $rrc_cantidad,
        $cte_id
    );
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) <= 0) {
        throw new Exception("Error al insertar PT cliente");
    }

    $nuevo_rrc_id = mysqli_insert_id($cnx);

    /*
        4. Actualizar datos del cliente si vienen.
        Esto no debería romper el apartado si falla, pero como forma parte
        del flujo actual, lo dejamos dentro de la transacción.
    */
    if ($cte_tipo !== null && $cte_clasificacion !== null) {
        $sql_cliente = "
            UPDATE rev_clientes
            SET cte_tipo = ?,
                cte_clasificacion = ?
            WHERE cte_id = ?
        ";

        $stmt = mysqli_prepare($cnx, $sql_cliente);
        mysqli_stmt_bind_param($stmt, "ssi", $cte_tipo, $cte_clasificacion, $cte_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_errno($stmt)) {
            throw new Exception("Error al actualizar datos del cliente: " . mysqli_stmt_error($stmt));
        }
    }

    mysqli_commit($cnx);

    echo json_encode([
        "success" => true,
        "message" => "Apartado correctamente.",
        "data" => [
            "rr_id_origen" => $rr_id,
            "rrc_id_nuevo" => $nuevo_rrc_id,
            "rev_id" => $rev_id,
            "pres_id" => $pres_id,
            "cte_id" => $cte_id,
            "cantidad_asignada" => $rrc_cantidad
        ]
    ]);

} catch (Exception $e) {
    if ($cnx) {
        mysqli_rollback($cnx);
    }

    http_response_code(400);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);

} finally {
    if ($cnx) {
        mysqli_close($cnx);
    }
}
?>