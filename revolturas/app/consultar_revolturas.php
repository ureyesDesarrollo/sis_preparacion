<?php
header('Content-Type: application/json');
include '../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $cnx = Conectarse();
        $sql = "SELECT r.rev_id,r.rev_folio,r.rev_fecha,c.cte_nombre
        FROM rev_revolturas r INNER JOIN rev_clientes c ON r.rev_teo_cliente = c.cte_id
        WHERE r.rev_estatus = 0 ORDER BY r.rev_fecha ASC";
        $result = mysqli_query($cnx, $sql);
        $revolturas = [];

        while ($fila = mysqli_fetch_assoc($result)) {
            $revolturas[] = $fila;
        }

        $res = [
            'status' => 'success',
            'data' =>  $revolturas
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
