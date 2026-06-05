<?php
header('Content-Type: application/json');

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    if (!isset($_POST['cte_id'], $_POST['rrc_id'], $_POST['cantidad'])) {
        http_response_code(400);
        echo json_encode(["error" => "Todos los campos son obligatorios"]);
        exit;
    }

    $cte_id = intval($_POST['cte_id']);
    $rrc_id = intval($_POST['rrc_id']);
    $cantidad = floatval($_POST['cantidad']);

    if ($cte_id <= 0) {
        throw new Exception("Cliente destino inválido");
    }

    if ($rrc_id <= 0) {
        throw new Exception("Empaque cliente inválido");
    }

    if ($cantidad <= 0) {
        throw new Exception("La cantidad debe ser mayor que 0");
    }

    mysqli_begin_transaction($cnx);

    /*
        1. Leer el empaque origen.
        Usamos la vista para validar disponibilidad real:
        rrc_ext_real - órdenes abiertas comprometidas.
    */
    $sql_origen = "
        SELECT 
            rrc_id,
            cte_id,
            rev_id,
            pres_id,
            rrc_ext_real,
            cantidad_comprometida,
            cantidad_disponible
        FROM vw_rev_revolturas_pt_cliente_disponible
        WHERE rrc_id = ?
        LIMIT 1
    ";

    $stmt = mysqli_prepare($cnx, $sql_origen);
    mysqli_stmt_bind_param($stmt, "i", $rrc_id);
    mysqli_stmt_execute($stmt);

    $res_origen = mysqli_stmt_get_result($stmt);
    $origen = mysqli_fetch_assoc($res_origen);

    if (!$origen) {
        throw new Exception("No se encontró el empaque origen rrc_id $rrc_id");
    }

    $cliente_origen = intval($origen['cte_id']);
    $rev_id = intval($origen['rev_id']);
    $pres_id = intval($origen['pres_id']);
    $rrc_ext_real = floatval($origen['rrc_ext_real']);
    $cantidad_comprometida = floatval($origen['cantidad_comprometida']);
    $cantidad_disponible = floatval($origen['cantidad_disponible']);

    if ($cliente_origen === $cte_id) {
        throw new Exception("El empaque ya pertenece a ese cliente");
    }

    if ($cantidad > $cantidad_disponible) {
        throw new Exception(
            "No hay disponibilidad suficiente para reasignar. " .
            "Existencia real: $rrc_ext_real, " .
            "comprometido: $cantidad_comprometida, " .
            "disponible: $cantidad_disponible, " .
            "solicitado: $cantidad"
        );
    }

    /*
        2. Validar cliente destino.
    */
    $sql_cliente = "
        SELECT cte_id
        FROM rev_clientes
        WHERE cte_id = ?
        LIMIT 1
    ";

    $stmt = mysqli_prepare($cnx, $sql_cliente);
    mysqli_stmt_bind_param($stmt, "i", $cte_id);
    mysqli_stmt_execute($stmt);

    $res_cliente = mysqli_stmt_get_result($stmt);
    $cliente = mysqli_fetch_assoc($res_cliente);

    if (!$cliente) {
        throw new Exception("No existe el cliente destino");
    }

    /*
        3. Descontar SOLO la cantidad seleccionada del empaque origen.
    */
    $sql_update_origen = "
        UPDATE rev_revolturas_pt_cliente
        SET rrc_ext_real = rrc_ext_real - ?
        WHERE rrc_id = ?
          AND rrc_ext_real >= ?
    ";

    $stmt = mysqli_prepare($cnx, $sql_update_origen);
    mysqli_stmt_bind_param($stmt, "did", $cantidad, $rrc_id, $cantidad);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) <= 0) {
        throw new Exception("No se pudo descontar del empaque origen. Puede que la existencia haya cambiado.");
    }

    /*
        4. Insertar nuevo empaque para el cliente destino.
        Este nuevo rrc_id representa la parte reasignada.
        roed_id queda NULL porque no nace directamente del empaque, sino de una reasignación.
    */
    $sql_insert_destino = "
        INSERT INTO rev_revolturas_pt_cliente (
            rev_id,
            pres_id,
            roed_id,
            rrc_ext_inicial,
            rrc_ext_real,
            cte_id
        )
        VALUES (?, ?, NULL, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($cnx, $sql_insert_destino);
    mysqli_stmt_bind_param(
        $stmt,
        "iiddi",
        $rev_id,
        $pres_id,
        $cantidad,
        $cantidad,
        $cte_id
    );
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) <= 0) {
        throw new Exception("No se pudo crear el empaque para el cliente destino");
    }

    $nuevo_rrc_id = mysqli_insert_id($cnx);

    mysqli_commit($cnx);

    echo json_encode([
        "success" => true,
        "message" => "Cantidad reasignada correctamente",
        "data" => [
            "rrc_id_origen" => $rrc_id,
            "rrc_id_destino" => $nuevo_rrc_id,
            "cliente_origen" => $cliente_origen,
            "cliente_destino" => $cte_id,
            "rev_id" => $rev_id,
            "pres_id" => $pres_id,
            "cantidad_reasignada" => $cantidad
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