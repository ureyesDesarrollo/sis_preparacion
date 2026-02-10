<?php
header('Content-Type: application/json');
require_once '../../conexion/conexion.php';
include "../../seguridad/user_seguridad.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cnx = Conectarse();
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $fe_factura = isset($data['factura']) ? $data['factura'] : '';
        $fe_factura = mysqli_real_escape_string($cnx, $fe_factura);
        $query = "SELECT 
    rpf.fe_factura,
    rpf.fe_cartaporte,
    rpf.fe_cantidad,
    rpf.fe_tipo,
    c.cte_nombre,
    DATE(rpf.fe_fecha) AS fe_fecha,
    rev.rev_folio,
    rev.rev_id,
    rp.pres_descrip,
    rp.pres_kg,
    COALESCE(rr.rr_id, rrc.rrc_id) AS referencia_id,
    CASE 
        WHEN rr.rr_id IS NOT NULL THEN 'rr'
        WHEN rrc.rrc_id IS NOT NULL THEN 'rrc'
        ELSE NULL
    END AS tipo_empaque
FROM 
    rev_revolturas_pt_facturas rpf
INNER JOIN 
    rev_clientes c ON c.cte_id = rpf.cte_id
LEFT JOIN 
    rev_revolturas_pt rr ON rr.rr_id = rpf.rr_id
LEFT JOIN 
    rev_revolturas_pt_cliente rrc ON rrc.rrc_id = rpf.rrc_id
LEFT JOIN 
    rev_presentacion rp ON rp.pres_id = COALESCE(rr.pres_id, rrc.pres_id)
LEFT JOIN 
    rev_revolturas rev ON rev.rev_id = COALESCE(rr.rev_id, rrc.rev_id)
WHERE 
    rpf.fe_factura LIKE '%" . $fe_factura . "%'
    AND NOT EXISTS (
        SELECT 1
        FROM orden_devolucion_detalle odd
        WHERE
            odd.factura = rpf.fe_factura
            AND (
                (odd.tipo_empaque = 'rr' AND odd.id_empaque = rpf.rr_id)
                OR 
                (odd.tipo_empaque = 'rrc' AND odd.id_empaque = rpf.rrc_id)
            )
    )
ORDER BY rpf.fe_fecha DESC";

        $listado_facturas = mysqli_query($cnx, $query);

        if (!$listado_facturas) {
            throw new Exception("Error en la consulta: " . mysqli_error($cnx));
        }

        $res = [];

        while ($fila = mysqli_fetch_assoc($listado_facturas)) {
            $res[] = $fila;
        }

        if (empty($res)) {
            echo json_encode(['success' => false, 'menssage' => 'No se encontraron facturas']);
        } else {
            echo json_encode(['success' => true, 'data' => $res]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
