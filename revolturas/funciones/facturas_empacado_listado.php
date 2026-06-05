<?php
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
include "../utils/funciones.php";

$cnx = Conectarse();

try {
    $sql = "SELECT
            t.tipo_producto,
            t.revoltura,
            t.rev_id,
            t.rr_id,
            t.pe_id,
            t.pres_descrip,
            t.rr_ext_inicial,
            t.rr_ext_real,
            t.cantidad_comprometida,
            t.cantidad_disponible,
            t.pres_kg,
            t.pres_id,
            t.cal_id
        FROM (
            SELECT
                'REVOLTURA' AS tipo_producto,
                rev.rev_folio AS revoltura,
                rev.rev_id,
                v.rr_id,
                NULL AS pe_id,
                pres.pres_descrip,
                v.rr_ext_inicial,
                v.rr_ext_real,
                v.cantidad_comprometida,
                v.cantidad_disponible,
                pres.pres_kg,
                pres.pres_id,
                cal.cal_id
            FROM vw_rev_revolturas_pt_disponible v
            INNER JOIN rev_revolturas rev
                ON rev.rev_id = v.rev_id
            INNER JOIN rev_calidad cal
                ON cal.cal_id = rev.cal_id
            INNER JOIN rev_presentacion pres
                ON pres.pres_id = v.pres_id
            WHERE rev.rev_count_etiquetado > 0
              AND v.cantidad_disponible > 0

            UNION ALL

            SELECT
                'EXTERNO' AS tipo_producto,
                pe.pe_lote AS revoltura,
                NULL AS rev_id,
                NULL AS rr_id,
                pe.pe_id,
                pres.pres_descrip,
                pe.pe_existencia_inicial AS rr_ext_inicial,
                pe.pe_existencia_real AS rr_ext_real,
                0 AS cantidad_comprometida,
                pe.pe_existencia_real AS cantidad_disponible,
                pres.pres_kg,
                pres.pres_id,
                NULL AS cal_id
            FROM producto_externo pe
            INNER JOIN rev_presentacion pres
                ON pres.pres_id = pe.pres_id
            WHERE pe.pe_existencia_real > 0
        ) t
        ORDER BY t.revoltura DESC
    ";

    $listado_empacado = mysqli_query($cnx, $sql);

    if (!$listado_empacado) {
        throw new Exception(mysqli_error($cnx));
    }

    $datos = array();

    while ($fila = mysqli_fetch_assoc($listado_empacado)) {

        if ($fila['tipo_producto'] === 'REVOLTURA') {
            $fila['calidad'] = obtenerBloomPorCalidad($fila['cal_id']);
        } else {
            $fila['calidad'] = 'EXTERNO';
        }

        $fila['rr_ext_inicial'] = floatval($fila['rr_ext_inicial']);
        $fila['rr_ext_real'] = floatval($fila['rr_ext_real']);
        $fila['cantidad_comprometida'] = floatval($fila['cantidad_comprometida']);
        $fila['cantidad_disponible'] = floatval($fila['cantidad_disponible']);
        $fila['pres_kg'] = floatval($fila['pres_kg']);

        $datos[] = $fila;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos);

} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}