<?php
header('Content-Type: application/json');
include "../../conexion/conexion.php";
$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $sql = "SELECT * 
            FROM rev_racks  
            WHERE rac_zona = 'EMBARQUE'";
        $result = mysqli_query($cnx, $sql);
        if (!$result) {
            http_response_code(404);
            echo json_encode(['error' => 'Rack no encontrado']);
            exit;
        }

        $rack_data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rack_data[] = $row;
        }

        $res = [
            'status' => 'success',
            'data' => [
                'rack' => $rack_data
            ]
        ];

        echo json_encode($res);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }finally{
        mysqli_close($cnx);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
