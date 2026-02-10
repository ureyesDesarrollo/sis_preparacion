<?php

header('Content-Type: application/json');
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $sql = "SELECT 
        roe.roe_id AS orden_id,
        roe.roe_fecha AS fecha_creacion,
        roe.roe_estado AS estado,
        rev.rev_id AS revoltura_id,
        rev.rev_folio AS folio_revoltura
        FROM rev_orden_empaque roe
        JOIN rev_orden_empaque_detalle roed ON roe.roe_id = roed.roe_id
        JOIN rev_revolturas rev ON roed.rev_id = rev.rev_id
        GROUP BY roe.roe_id, rev.rev_id
        ORDER BY roe.roe_id ASC";

        $listado_presenta = mysqli_query($cnx, $sql);

        $datos_presenta = array();

        while ($fila = mysqli_fetch_assoc($listado_presenta)) {
            $datos_presenta[] = $fila;
        }

        $res = [
            'status' => 'success',
            'data' => $datos_presenta
        ];

        echo json_encode($res);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
