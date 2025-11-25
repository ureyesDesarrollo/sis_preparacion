<?php
header('Content-Type: application/json');
require_once '../../conexion/conexion.php';
include "../../seguridad/user_seguridad.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$cnx = Conectarse();
mysqli_begin_transaction($cnx);

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $devoluciones = $data['devoluciones'] ?? [];
    $observaciones = mysqli_real_escape_string($cnx, $data['observaciones'] ?? '');
    $usu_id = $_SESSION['idUsu'] ?? 0;
    $modo_prueba = false;

    if (empty($devoluciones)) {
        throw new Exception("No se recibieron devoluciones.");
    }

    // Obtener cte_id del primer empaque
    $cte_id = null;
    foreach ($devoluciones as $factura) {
    $facturaEscaped = mysqli_real_escape_string($cnx, $factura['factura']);
    $sql_cte = "SELECT cte_id FROM rev_revolturas_pt_facturas WHERE fe_factura = '$facturaEscaped'";
    $res_cte = mysqli_query($cnx, $sql_cte);
    if ($res_cte && mysqli_num_rows($res_cte) > 0) {
        $row_cte = mysqli_fetch_assoc($res_cte);
        $cte_id = $row_cte['cte_id'];
        break;
    }
}

    if (!$cte_id) {
        throw new Exception("No se encontró cliente asociado a los empaques.");
    }

    $sql_insert_od = "INSERT INTO orden_devolucion (cte_id, usu_id, od_motivo, od_estado) VALUES ($cte_id, $usu_id, '$observaciones', 'PENDIENTE')";
    if (!mysqli_query($cnx, $sql_insert_od)) {
        throw new Exception("Error al insertar orden de devolución: " . mysqli_error($cnx));
    }
    $od_id = mysqli_insert_id($cnx);

    // Insertar detalles
    foreach ($devoluciones as $factura) {
        $factura_num = mysqli_real_escape_string($cnx, $factura['factura']);

        foreach ($factura['empaques'] as $emp) {
            $tipo_empaque = mysqli_real_escape_string($cnx, $emp['tipo_empaque']);
            $id_empaque = (int)$emp['referencia_id'];

            $sql_fact = "SELECT fe_cantidad FROM rev_revolturas_pt_facturas WHERE fe_factura = '$factura_num' AND {$tipo_empaque}_id = $id_empaque";
            $res_fact = mysqli_query($cnx, $sql_fact);
            if (!$res_fact || mysqli_num_rows($res_fact) === 0) {
                continue;
            }
            $row_fact = mysqli_fetch_assoc($res_fact);
            $cantidad = (float)$emp['cantidad'];

            $sql_lote = $tipo_empaque === 'rr' ?
                "SELECT r.rev_folio FROM rev_revolturas r INNER JOIN rev_revolturas_pt pt ON pt.rev_id = r.rev_id WHERE pt.rr_id = $id_empaque" :
                "SELECT r.rev_folio FROM rev_revolturas r INNER JOIN rev_revolturas_pt_cliente ptc ON ptc.rev_id = r.rev_id WHERE ptc.rrc_id = $id_empaque";

            $res_lote = mysqli_query($cnx, $sql_lote);
            $lote = (mysqli_num_rows($res_lote) > 0) ? mysqli_fetch_assoc($res_lote)['rev_folio'] : '';

            $sql_detalle = "INSERT INTO orden_devolucion_detalle (od_id, tipo_empaque, id_empaque, lote, factura, cantidad) VALUES ($od_id, '$tipo_empaque', $id_empaque, '$lote', '$factura_num', $cantidad)";
            if (!mysqli_query($cnx, $sql_detalle)) {
                throw new Exception("Error insertando detalle: " . mysqli_error($cnx));
            }
        }
    }

    if (!$modo_prueba) {
        // Recuperar orden completa para enviar por correo
        $sql = "
        SELECT od.od_id, od.od_fecha, od.od_estado, od.cte_id, ct.cte_nombre, odd.odd_id,
               odd.tipo_empaque, odd.id_empaque, odd.lote, odd.factura, odd.cantidad,
               pt.rr_id AS empaque_id, pt.pres_id, p.pres_descrip, 'rr' AS tipo, od.od_motivo,u.usu_nombre
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
               ptc.rrc_id AS empaque_id, ptc.pres_id, p.pres_descrip, 'rrc' AS tipo, od.od_motivo,u.usu_nombre
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

        require_once __DIR__ . '/../lib/EmailSender.php';
        $mailer = new MailSender();
        if (!$mailer->sendOrdenDevolucion($ordenInfo)) {
            throw new Exception("No se pudo enviar el correo de confirmación.");
        }
    }

    mysqli_commit($cnx);
    echo json_encode(['success' => true, 'message' => 'Orden registrada y correo enviado.']);
} catch (Exception $e) {
    mysqli_rollback($cnx);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
