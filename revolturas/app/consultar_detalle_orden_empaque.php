<?php

header('Content-Type: application/json');
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        $data = json_decode(file_get_contents('php://input'), true);

        $roe_id = isset($data['roe_id']) ? $data['roe_id'] : null;
        if ($roe_id === null) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de orden de empaque no proporcionado']);
            exit;
        }

        $sql = "SELECT 
        roed.roed_id AS detalle_id,
        roed.roe_id AS orden_id,
        rev.rev_id AS revoltura_id,
        rev.rev_folio AS folio_revoltura,
        rev.rev_kilos AS kilos_disponibles,
        rev.rev_teo_cliente AS cliente_id,
        cli.cte_nombre AS nombre_cliente,
        pres.pres_id AS presentacion_id,
        pres.pres_descrip AS nombre_presentacion,
        pres.pres_kg AS kilos_por_unidad,
        roed.roed_cantidad AS cantidad_solicitada,
        roed.roed_cantidad_capturada AS cantidad_capturada,
        (roed.roed_cantidad * pres.pres_kg) AS kilos_totales
        FROM rev_orden_empaque_detalle roed
        JOIN rev_revolturas rev ON roed.rev_id = rev.rev_id
        JOIN rev_presentacion pres ON roed.pres_id = pres.pres_id
        JOIN rev_clientes cli ON rev.rev_teo_cliente = cli.cte_id
        WHERE roed.roe_id = '$roe_id';";

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
