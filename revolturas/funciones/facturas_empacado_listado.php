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
            t.pres_kg,
            t.pres_id,
            t.cal_id
        FROM (
            SELECT
                'REVOLTURA' AS tipo_producto,
                rev.rev_folio AS revoltura,
                rev.rev_id,
                rr.rr_id,
                NULL AS pe_id,
                pres.pres_descrip,
                rr.rr_ext_inicial,
                rr.rr_ext_real,
                pres.pres_kg,
                pres.pres_id,
                cal.cal_id
            FROM rev_revolturas_pt rr
            INNER JOIN rev_revolturas rev
                ON rev.rev_id = rr.rev_id
            INNER JOIN rev_calidad cal
                ON cal.cal_id = rev.cal_id
            INNER JOIN rev_presentacion pres
                ON pres.pres_id = rr.pres_id
            WHERE rev.rev_count_etiquetado > 0
              AND rr.rr_ext_real > 0

            UNION ALL

            /* PRODUCTO EXTERNO */
            SELECT
                'EXTERNO' AS tipo_producto,
                pe.pe_lote AS revoltura,
                NULL AS rev_id,
                NULL AS rr_id,
                pe.pe_id,
                pres.pres_descrip,
                pe.pe_existencia_inicial AS rr_ext_inicial,
                pe.pe_existencia_real AS rr_ext_real,
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

        $datos[] = $fila;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos);
} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($e->getMessage());
} finally {
    mysqli_close($cnx);
}
