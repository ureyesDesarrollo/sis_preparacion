<?php
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
include "../utils/funciones.php";
$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        $oe_id = isset($_POST['oe_id']) ? $_POST['oe_id'] : null;
        if ($oe_id === null) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de orden de embarque no proporcionado']);
            exit;
        }

        $sql = "SELECT 
    oe.oe_id AS orden_id, 
    oe.oe_fecha AS fecha_creacion, 
    oe.cte_id AS cliente_id,
    cte.cte_nombre AS cliente_nombre,
    oe.oe_estado AS estado, 
    oed.oed_id, 
    oed.cantidad AS cantidad_solicitada, 
    cal.cal_id,
    CASE 
        WHEN rr.rev_id IS NOT NULL THEN rev.rev_folio
        WHEN rrc.rev_id IS NOT NULL THEN rrc_rev.rev_folio
        ELSE 'Producto General'
    END AS rev_folio,
    COALESCE(rr.rr_id, rrc.rrc_id) AS empaque_id,
    COALESCE(rr.rr_ext_inicial, rrc.rrc_ext_inicial, 0) AS existencia_inicial,
    COALESCE(rr.rr_ext_real, rrc.rrc_ext_real, 0) AS existencia_real,
    COALESCE(rr_pres.pres_id, rrc_pres.pres_id) AS presentacion_id,
    COALESCE(rr_pres.pres_descrip, rrc_pres.pres_descrip) AS presentacion_descripcion,
    COALESCE(rr_pres.pres_kg, rrc_pres.pres_kg) AS pres_kg,
    CASE 
        WHEN rr.rr_id IS NOT NULL THEN 'GENERAL'
        WHEN rrc.rrc_id IS NOT NULL THEN 'CLIENTE'
        ELSE 'GENERAL'
    END AS tipo_revoltura
    FROM 
        rev_orden_embarque oe
    INNER JOIN 
        rev_orden_embarque_detalle oed ON oe.oe_id = oed.oe_id
    LEFT JOIN 
        rev_revolturas_pt rr ON rr.rr_id = oed.rr_id
    LEFT JOIN 
        rev_revolturas rev ON rev.rev_id = rr.rev_id
    LEFT JOIN 
        rev_presentacion rr_pres ON rr_pres.pres_id = rr.pres_id
    LEFT JOIN 
        rev_revolturas_pt_cliente rrc ON rrc.rrc_id = oed.rrc_id
    LEFT JOIN 
        rev_revolturas rrc_rev ON rrc_rev.rev_id = rrc.rev_id
    LEFT JOIN 
        rev_presentacion rrc_pres ON rrc_pres.pres_id = rrc.pres_id
    LEFT JOIN 
        rev_clientes cte ON oe.cte_id = cte.cte_id
    LEFT JOIN rev_calidad cal ON cal.cal_id = COALESCE(rev.cal_id, rrc_rev.cal_id)
    WHERE 
        oe.oe_id = '$oe_id'";

        $listado_detalle_embarque = mysqli_query($cnx, $sql);

        $datos_detalle_embarque = array();
        $calidad = '';

        while ($fila = mysqli_fetch_assoc($listado_detalle_embarque)) {
        $fila['calidad'] = obtenerBloomPorCalidad($fila['cal_id']);
            $datos_detalle_embarque[] = $fila;
        }

        echo json_encode($datos_detalle_embarque);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
