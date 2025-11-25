<?php
header('Content-Type: application/json');
require_once '../../conexion/conexion.php';
include "../../seguridad/user_seguridad.php";

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    $cnx = Conectarse();
    mysqli_begin_transaction($cnx);

    $data = json_decode(file_get_contents('php://input'), true);
    $odd_id = isset($data['odd_id']) ? (int)$data['odd_id'] : 0;
    $od_id = isset($data['od_id']) ? (int)$data['od_id'] : 0;

    if ($odd_id <= 0 || $od_id <= 0) {
        throw new Exception('Datos inválidos');
    }

    // Actualizar estado del lote
    $sql_update = "UPDATE orden_devolucion_detalle SET estado_lote = 'RECIBIDO', fecha_recepcion = NOW() WHERE odd_id = $odd_id";
    if (!mysqli_query($cnx, $sql_update)) {
        throw new Exception("Error al actualizar orden de devolución: " . mysqli_error($cnx));
    }

    $msg = "Lote recibido correctamente.";

    // Verificar si todos los lotes han sido recibidos
    $sql_estados_lotes = "SELECT estado_lote FROM orden_devolucion_detalle WHERE od_id = $od_id";
    $result_estados_lotes = mysqli_query($cnx, $sql_estados_lotes);
    if (!$result_estados_lotes || mysqli_num_rows($result_estados_lotes) === 0) {
        throw new Exception('Error al obtener estados de lotes: ' . mysqli_error($cnx));
    }

    $todos_recibidos = true;
    while ($row = mysqli_fetch_assoc($result_estados_lotes)) {
        if ($row['estado_lote'] !== 'RECIBIDO') {
            $todos_recibidos = false;
            break;
        }
    }

    // Si todos los lotes fueron recibidos, actualiza orden y envía correo
    if ($todos_recibidos) {
        $msg = "Todos los lotes recibidos correctamente.";
        $sql_update_orden = "UPDATE orden_devolucion SET od_estado = 'RECIBIDO' WHERE od_id = $od_id";
        if (!mysqli_query($cnx, $sql_update_orden)) {
            throw new Exception("Error al actualizar estado de la orden: " . mysqli_error($cnx));
        }

        // Consulta para el correo
        $sql = "
        SELECT od.od_id, od.od_fecha, od.od_estado, od.cte_id, ct.cte_nombre, odd.odd_id,
               odd.tipo_empaque, odd.id_empaque, odd.lote, odd.factura, odd.cantidad,
               pt.rr_id AS empaque_id, pt.pres_id, p.pres_descrip, 'rr' AS tipo, od.od_motivo, u.usu_nombre
        FROM orden_devolucion_detalle odd
        INNER JOIN orden_devolucion od ON od.od_id = odd.od_id
        INNER JOIN rev_clientes ct ON ct.cte_id = od.cte_id
        INNER JOIN rev_revolturas_pt pt ON pt.rr_id = odd.id_empaque
        INNER JOIN rev_presentacion p ON p.pres_id = pt.pres_id
        INNER JOIN usuarios u ON u.usu_id = od.usu_id
        WHERE odd.tipo_empaque = 'rr' AND od.od_id = $od_id

        UNION

        SELECT od.od_id, od.od_fecha, od.od_estado, od.cte_id, ct.cte_nombre, odd.odd_id,
               odd.tipo_empaque, odd.id_empaque, odd.lote, odd.factura, odd.cantidad,
               ptc.rrc_id AS empaque_id, ptc.pres_id, p.pres_descrip, 'rrc' AS tipo, od.od_motivo, u.usu_nombre
        FROM orden_devolucion_detalle odd
        INNER JOIN orden_devolucion od ON od.od_id = odd.od_id
        INNER JOIN rev_clientes ct ON ct.cte_id = od.cte_id
        INNER JOIN rev_revolturas_pt_cliente ptc ON ptc.rrc_id = odd.id_empaque
        INNER JOIN rev_presentacion p ON p.pres_id = ptc.pres_id
        INNER JOIN usuarios u ON u.usu_id = od.usu_id
        WHERE odd.tipo_empaque = 'rrc' AND od.od_id = $od_id";

        $result = mysqli_query($cnx, $sql);
        $ordenInfo = null;
        $detalles = [];

        while ($row = mysqli_fetch_assoc($result)) {
            if (!$ordenInfo) {
                $ordenInfo = [
                    'od_id' => $row['od_id'],
                    'cte_nombre' => $row['cte_nombre'],
                    'od_fecha' => $row['od_fecha'],
                    'od_estado' => $row['od_estado'],
                    'od_motivo' => $row['od_motivo'],
                    'responsable' => $row['usu_nombre'],
                    'detalles' => []
                ];
            }

            $detalles[] = [
                'odd_id' => $row['odd_id'],
                'tipo_empaque' => $row['tipo_empaque'],
                'id_empaque' => $row['id_empaque'],
                'lote' => $row['lote'],
                'factura' => $row['factura'],
                'cantidad' => $row['cantidad'],
                'pres_descrip' => $row['pres_descrip']
            ];
        }

        $ordenInfo['detalles'] = $detalles;

        // Enviar correo
        require_once __DIR__ . '/../lib/EmailSender.php';
        $mailer = new MailSender();
        if (!$mailer->sendOrdenDevolucion($ordenInfo)) {
            throw new Exception("No se pudo enviar el correo de confirmación.");
        }
    }

    mysqli_commit($cnx);
    echo json_encode(['success' => true, 'message' => $msg]);
} catch (Exception $e) {
    mysqli_rollback($cnx);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
