<?php
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $sql = "SELECT
            r.reporte_id,
            r.titulo,
            DATE_FORMAT(r.fecha, '%Y-%m-%d') AS fecha,
            r.observaciones,
            COUNT(rm.reporte_muestra_id) AS total_muestras,
            SUM(CASE WHEN rm.marca_estatus = 'PENDIENTE' THEN 1 ELSE 0 END) AS marcas_pendientes
        FROM lab_reportes r
        LEFT JOIN lab_reporte_muestras rm 
            ON r.reporte_id = rm.reporte_id
        GROUP BY
            r.reporte_id,
            r.titulo,
            r.fecha,
            r.observaciones
        ORDER BY r.fecha ASC";

        $resultado = mysqli_query($cnx, $sql);

        $datos = array();

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $datos[] = $fila;
        }

        echo json_encode($datos);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array(
            'error' => $e->getMessage()
        ));
    }
} else {
    http_response_code(405);
    echo json_encode(array(
        'error' => 'Método no permitido'
    ));
}
