<?php
header('Content-Type: application/json');
include '../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $cnx = Conectarse();
        $sql = "SELECT rev_id,rev_folio,rev_fecha FROM rev_revolturas WHERE rev_estatus = 0 ORDER BY rev_fecha ASC";
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
