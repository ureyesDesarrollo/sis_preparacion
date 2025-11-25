<?php
header('Content-Type: application/json');
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = Conectarse();
        $data = json_decode(file_get_contents('php://input'), true);
        $odd_id = isset($data['odd_id']) ? (int)$data['odd_id'] : 0;

        if ($odd_id <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            exit;
        }

        mysqli_begin_transaction($conn);

        $sql = "SELECT odd.tipo_empaque, odd.id_empaque, odd.cantidad, od.od_id
                FROM orden_devolucion_detalle odd
                INNER JOIN orden_devolucion od ON od.od_id = odd.od_id
                WHERE odd.odd_id = $odd_id
                LIMIT 1";

        $res = mysqli_query($conn, $sql);
        if (!$res || mysqli_num_rows($res) === 0) {
            throw new Exception('No se encontró el detalle.');
        }

        $row = mysqli_fetch_assoc($res);
        $tipo_empaque = $row['tipo_empaque'];
        $id_empaque = (int)$row['id_empaque'];
        $cantidad = (float)$row['cantidad'];
        $od_id = (int)$row['od_id'];

        // Actualizar el stock
        if ($tipo_empaque === 'rr') {
            $update_empaque = "UPDATE rev_revolturas_pt SET rr_ext_real = rr_ext_real + $cantidad WHERE rr_id = $id_empaque";
        } else {
            $update_empaque = "UPDATE rev_revolturas_pt_cliente SET rrc_ext_real = rrc_ext_real + $cantidad WHERE rrc_id = $id_empaque";
        }

        if (!mysqli_query($conn, $update_empaque)) {
            throw new Exception('Error al actualizar el stock del empaque.');
        }

        // Actualizar estado del detalle
        $update_detalle = "UPDATE orden_devolucion_detalle SET estado_lote = 'LIBERADA' WHERE odd_id = $odd_id";
        if (!mysqli_query($conn, $update_detalle)) {
            throw new Exception('Error al actualizar el estado del lote.');
        }

        // Actualizar estado de la orden
        $update_orden = "UPDATE orden_devolucion SET od_estado = 'FINALIZADA' WHERE od_id = $od_id";
        if (!mysqli_query($conn, $update_orden)) {
            throw new Exception('Error al actualizar el estado de la orden.');
        }

        mysqli_commit($conn);

        echo json_encode(['success' => true, 'message' => 'Lote liberado y stock actualizado']);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    } finally {
        mysqli_close($conn);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
