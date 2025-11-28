<?php

require_once '../../../conexion/conexion.php';
include "../../utils/funciones.php";

try {

    // ------------------------
    // MAPEADOR DE MESES
    // ------------------------
    $meses = [
        '01' => 'ENERO', '02' => 'FEBRERO', '03' => 'MARZO', '04' => 'ABRIL',
        '05' => 'MAYO',  '06' => 'JUNIO',   '07' => 'JULIO', '08' => 'AGOSTO',
        '09' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE'
    ];

    function formatear_fecha_espanol(DateTime $fecha, $meses)
    {
        $dia  = $fecha->format('d');
        $mes  = $meses[$fecha->format('m')];
        $anio = $fecha->format('Y');
        return "$dia DE $mes $anio";
    }

    // ------------------------
    // CONEXIÓN
    // ------------------------
    $cnx = Conectarse();

    // ------------------------
    // QUERY PRINCIPAL
    // ------------------------
    $listado_orden = "SELECT 
        oe.oe_id AS orden_id, 
        oe.oe_fecha AS fecha_creacion, 
        oe.cte_id AS cliente_id,

        cte.cte_nombre AS cliente_nombre,
        cte.cte_ubicacion AS cliente_ubicacion,
        cte.cte_tipo_bloom,
        cte.cte_bloom_min,

        oe.oe_estado AS estado, 

        oed.oed_id, 
        oed.cantidad AS cantidad_solicitada,
        oed.bloom_vendido,

        CASE 
            WHEN rr.rev_id IS NOT NULL THEN rev.rev_folio
            WHEN rrc.rev_id IS NOT NULL THEN rrc_rev.rev_folio
            ELSE 'Producto General'
        END AS rev_folio,

        CASE 
            WHEN rr.rev_id IS NOT NULL THEN rev.rev_id
            WHEN rrc.rev_id IS NOT NULL THEN rrc_rev.rev_id
            ELSE 'rev_id'
        END AS rev_id,

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

    FROM rev_orden_embarque oe
    INNER JOIN rev_orden_embarque_detalle oed ON oe.oe_id = oed.oe_id

    LEFT JOIN rev_revolturas_pt rr ON rr.rr_id = oed.rr_id
    LEFT JOIN rev_revolturas rev ON rev.rev_id = rr.rev_id
    LEFT JOIN rev_presentacion rr_pres ON rr_pres.pres_id = rr.pres_id

    LEFT JOIN rev_revolturas_pt_cliente rrc ON rrc.rrc_id = oed.rrc_id
    LEFT JOIN rev_revolturas rrc_rev ON rrc_rev.rev_id = rrc.rev_id
    LEFT JOIN rev_presentacion rrc_pres ON rrc_pres.pres_id = rrc.pres_id

    LEFT JOIN rev_clientes cte ON oe.cte_id = cte.cte_id

    WHERE oe.oe_id = '$oe_id'
    AND COALESCE(rr.rr_id, rrc.rrc_id) = '$empaque_id'
    ";

    $listado_detalle_embarque = mysqli_query($cnx, $listado_orden);

    $datos_detalle_embarque = [];
    while ($fila = mysqli_fetch_assoc($listado_detalle_embarque)) {
        $datos_detalle_embarque[] = $fila;
    }

    if (empty($datos_detalle_embarque)) {
        throw new Exception("No se encontró información del detalle del embarque.");
    }

    // ------------------------
    // VARIABLES CALCULADAS
    // ------------------------
    $cantidad = floatval($datos_detalle_embarque[0]['cantidad_solicitada']) *
                floatval($datos_detalle_embarque[0]['pres_kg']);

    $cliente          = $datos_detalle_embarque[0]['cliente_nombre'];
    $cliente_ubicacion = $datos_detalle_embarque[0]['cliente_ubicacion'];

    $rev_id = $datos_detalle_embarque[0]['rev_id'];

    // ------------------------
    // DATOS DE REVOLTURA
    // ------------------------
    $listado_revolutura = "SELECT r.*, 
            ROUND(r.rev_bloom) AS rev_bloom,
            ROUND(r.rev_viscosidad) AS rev_viscosidad
        FROM rev_revolturas r 
        INNER JOIN rev_calidad c ON c.cal_id = r.cal_id
        WHERE r.rev_id = '$rev_id'
    ";

    $result_revoltura = mysqli_query($cnx, $listado_revolutura);
    $revoltura = mysqli_fetch_assoc($result_revoltura);

    if (!$revoltura) {
        throw new Exception("No existe la revoltura con rev_id = $rev_id");
    }

    // FECHAS
    $fecha_elaboracion = new DateTime($revoltura['rev_fecha']);
    $fecha_caducidad = (clone $fecha_elaboracion)->modify('+5 years');

    $fecha_elaboracion_formateada = formatear_fecha_espanol($fecha_elaboracion, $meses);
    $fecha_caducidad_formateada  = formatear_fecha_espanol($fecha_caducidad, $meses);

    $calidad = $datos_detalle_embarque[0]['bloom_vendido'];

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}
