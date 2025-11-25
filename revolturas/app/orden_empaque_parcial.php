<?php
header('Content-Type: application/json');
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cnx = Conectarse();
        $data = json_decode(file_get_contents('php://input'), true);
        $orden_id  = isset($data['roe_id']) ? $data['roe_id'] : null;
        $detalle_id = isset($data['roed_id']) ? $data['roed_id'] : null;
        $cantidad = isset($data['cantidad']) ? $data['cantidad'] : null;
        $presentacion_id = isset($data['presentacion_id']) ? $data['presentacion_id'] : null;

        if ($orden_id === null) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de orden de empaque no proporcionado']);
            exit;
        }

        if ($detalle_id === null) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de detalle de orden de empaque no proporcionado']);
            exit;
        }

        if ($cantidad === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Cantidad no proporcionada']);
            exit;
        }

        if ($cantidad <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'No se permiten cantidades en 0']);
            exit;
        }

        // Obtener la cantidad solicitada y la cantidad capturada actual
        $query = "SELECT roed_cantidad, IFNULL(roed_cantidad_capturada, 0) AS cantidad_actual 
                  FROM rev_orden_empaque_detalle 
                  WHERE roe_id = '$orden_id' AND roed_id = '$detalle_id' AND pres_id = '$presentacion_id'";

        $result = mysqli_query($cnx, $query);
        if (!$result) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al consultar la base de datos']);
            exit;
        }

        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            http_response_code(404);
            echo json_encode(['error' => 'Detalle de orden de empaque no encontrado']);
            exit;
        }

        $cantidad_solicitada = $row['roed_cantidad'];
        $cantidad_actual = $row['cantidad_actual'];
        $nueva_cantidad = $cantidad_actual + $cantidad;

        if ($nueva_cantidad > $cantidad_solicitada) {
            http_response_code(400);
            echo json_encode(['error' => 'La cantidad capturada supera la cantidad solicitada']);
            exit;
        }

        // Actualizar la cantidad capturada y el estado de la orden
        $update_query = "UPDATE rev_orden_empaque_detalle 
                         SET roed_cantidad_capturada = '$nueva_cantidad'
                         WHERE roe_id = '$orden_id' AND roed_id = '$detalle_id' AND pres_id = '$presentacion_id'";
        $update_status_orden = "UPDATE rev_orden_empaque SET roe_estado = 'PROCESO' WHERE roe_id = '$orden_id'";

        if (!mysqli_query($cnx, $update_query) || !mysqli_query($cnx, $update_status_orden)) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar la cantidad capturada']);
            exit;
        }

        // === REGISTRAR EMPAQUE PARCIAL ===
        $rev_query = "SELECT om.rev_id, r.rev_teo_cliente AS cte_id
            FROM rev_orden_empaque o
            INNER JOIN rev_orden_empaque_detalle om ON om.roe_id = o.roe_id
            INNER JOIN rev_revolturas r ON r.rev_id = om.rev_id
            WHERE o.roe_id = '$orden_id'";
        $rev_result = mysqli_query($cnx, $rev_query);
        if (!$rev_result) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al obtener datos de la orden']);
            exit;
        }
        $rev_data = mysqli_fetch_assoc($rev_result);
        $rev_id = $rev_data['rev_id'];
        $cliente_id = $rev_data['cte_id'];

        if ($cliente_id == 74) {
            $insert_empaque = "INSERT INTO rev_revolturas_pt (rev_id, pres_id, rr_ext_inicial, rr_ext_real)
                               VALUES ('$rev_id', '$presentacion_id', '$cantidad', '$cantidad')";
        } else {
            $insert_empaque = "INSERT INTO rev_revolturas_pt_cliente (rev_id, pres_id, rrc_ext_inicial, rrc_ext_real, cte_id)
                               VALUES ('$rev_id', '$presentacion_id', '$cantidad', '$cantidad', '$cliente_id')";
        }

        if (!mysqli_query($cnx, $insert_empaque)) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar el empaque parcial']);
            exit;
        }

        $cantidad_restante = $cantidad_solicitada - $nueva_cantidad;
        echo json_encode([
            "success" => true,
            "data" => ["mensaje" => "Parcialidad guardada, cantidad restante: $cantidad_restante"]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
