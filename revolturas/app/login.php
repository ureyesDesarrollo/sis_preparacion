<?php
header("Content-Type: application/json");
include '../../conexion/conexion.php';
include "../../funciones/funciones.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $cnx =  Conectarse();

        $data = json_decode(file_get_contents("php://input"), true);

        $usu_usuario = mysqli_real_escape_string($cnx, $data['usu_usuario']);
        $usu_pwr = mysqli_real_escape_string($cnx, $data['usu_pwr']);
        $res = [];
        $hashaded_password = md5($usu_pwr);

        $result = mysqli_query(
            $cnx,
            "SELECT usu_usuario, usu_id, up_id,usu_nombre
            FROM usuarios 
            WHERE usu_usuario = '$usu_usuario' AND usu_pwr = '$hashaded_password' 
            AND usu_est = 'A'");

        if(mysqli_num_rows($result) > 0){
            $response = mysqli_fetch_assoc($result);
            $res = [
                'status' => 'success',
                'data' => [
                    'usu_id' => $response['usu_id'],
                'usu_usuario' => $response['usu_usuario'],
                'up_id' => $response['up_id'],
                'usu_nombre' => $response['usu_nombre']
                ]
            ];

            ins_bit_login($response['usu_id'], getRealIP());
            echo json_encode($res);
        }else{
            http_response_code(401);
            $res = [
                'status' => 'error',
                'message' => 'Usuario o contraseña incorrectos'
            ];

            echo json_encode($res);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
        http_response_code(500); // Código de error 500 (Internal Server Error)
    } finally {
        mysqli_close($cnx);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
