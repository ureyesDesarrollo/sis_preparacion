<?php
header('Content-Type: application/json');
include '../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cnx = Conectarse();
        $data = json_decode(file_get_contents('php://input'), true);

        $rev_id = $data['rev_id'];
        $sql = "SELECT * FROM rev_revolturas WHERE rev_id = $rev_id";

        $sql_2 = "SELECT e_id, e_estatus FROM rev_equipos";
        $res_e = mysqli_query($cnx, $sql_2);
        $res_equipos = [];
        while ($fila = mysqli_fetch_assoc($res_e)) {
            $res_equipos[] = $fila; // Agregar cada fila como un elemento del arreglo
        }

        $result = mysqli_query($cnx, $sql);
        $revolturas = [];

        while ($fila = mysqli_fetch_assoc($result)) {
            $revolturas[] = $fila;
        }

        $res = [
            'status' => 'success',
            'data' => $revolturas,
            'equipos' => $res_equipos
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