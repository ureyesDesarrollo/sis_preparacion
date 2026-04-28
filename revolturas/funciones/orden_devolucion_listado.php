<?php
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $sql = "SELECT
    od.od_id,
    od.od_fecha,
    od.od_estado,
    od.cte_id,
    ct.cte_nombre,
    odd.odd_id,
    odd.tipo_empaque,
    odd.id_empaque,
    odd.lote,
    odd.factura,
    odd.cantidad,
    oda.cal_id,
    odd.estado_lote,
    pt.rr_id AS empaque_id,
    pt.pres_id,
    p.pres_descrip,
    'rr' AS tipo,
    od.od_motivo,
    u.usu_nombre
FROM orden_devolucion_detalle odd
INNER JOIN orden_devolucion od
    ON od.od_id = odd.od_id
LEFT JOIN (
    SELECT odd_id, MAX(cal_id) AS cal_id
    FROM orden_devolucion_analisis
    GROUP BY odd_id
) oda ON oda.odd_id = odd.odd_id
INNER JOIN rev_clientes ct
    ON ct.cte_id = od.cte_id
INNER JOIN rev_revolturas_pt pt
    ON pt.rr_id = odd.id_empaque
INNER JOIN rev_presentacion p
    ON p.pres_id = pt.pres_id
INNER JOIN usuarios u
    ON u.usu_id = od.usu_id
WHERE odd.tipo_empaque = 'rr'

UNION ALL

SELECT
    od.od_id,
    od.od_fecha,
    od.od_estado,
    od.cte_id,
    ct.cte_nombre,
    odd.odd_id,
    odd.tipo_empaque,
    odd.id_empaque,
    odd.lote,
    odd.factura,
    odd.cantidad,
    oda.cal_id,
    odd.estado_lote,
    ptc.rrc_id AS empaque_id,
    ptc.pres_id,
    p.pres_descrip,
    'rrc' AS tipo,
    od.od_motivo,
    u.usu_nombre
FROM orden_devolucion_detalle odd
INNER JOIN orden_devolucion od
    ON od.od_id = odd.od_id
LEFT JOIN (
    SELECT odd_id, MAX(cal_id) AS cal_id
    FROM orden_devolucion_analisis
    GROUP BY odd_id
) oda ON oda.odd_id = odd.odd_id
INNER JOIN rev_clientes ct
    ON ct.cte_id = od.cte_id
INNER JOIN rev_revolturas_pt_cliente ptc
    ON ptc.rrc_id = odd.id_empaque
INNER JOIN rev_presentacion p
    ON p.pres_id = ptc.pres_id
INNER JOIN usuarios u
    ON u.usu_id = od.usu_id
WHERE odd.tipo_empaque = 'rrc'

UNION ALL

SELECT
    od.od_id,
    od.od_fecha,
    od.od_estado,
    od.cte_id,
    ct.cte_nombre,
    odd.odd_id,
    odd.tipo_empaque,
    odd.id_empaque,
    odd.lote,
    odd.factura,
    odd.cantidad,
    oda.cal_id,
    odd.estado_lote,
    pe.pe_id AS empaque_id,
    pe.pres_id,
    p.pres_descrip,
    'pe' AS tipo,
    od.od_motivo,
    u.usu_nombre
FROM orden_devolucion_detalle odd
INNER JOIN orden_devolucion od
    ON od.od_id = odd.od_id
LEFT JOIN (
    SELECT odd_id, MAX(cal_id) AS cal_id
    FROM orden_devolucion_analisis
    GROUP BY odd_id
) oda ON oda.odd_id = odd.odd_id
INNER JOIN rev_clientes ct
    ON ct.cte_id = od.cte_id
INNER JOIN producto_externo pe
    ON pe.pe_id = odd.id_empaque
INNER JOIN rev_presentacion p
    ON p.pres_id = pe.pres_id
INNER JOIN usuarios u
    ON u.usu_id = od.usu_id
WHERE odd.tipo_empaque = 'pe';
";

        $res = mysqli_query($cnx, $sql);

        if (!$res) {
            throw new Exception("Error al ejecutar la consulta de orden de devoluciones: " . mysqli_error($cnx));
        }

        $devoluciones = [];

        while ($fila = mysqli_fetch_assoc($res)) {
            $devoluciones[] = $fila;
        }


        $response = [
            'success' => true,
            'data' => $devoluciones
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
