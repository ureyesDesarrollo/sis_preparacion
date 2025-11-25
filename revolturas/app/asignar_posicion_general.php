<?php
header("Content-Type: application/json");
include '../../conexion/conexion.php';
include "../../funciones/funciones.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cnx = Conectarse();
    $data = json_decode(file_get_contents("php://input"), true);

    $niv_id = $data['niv_id']; // ID de la posición en el rack
    $rr_id = $data['rr_id']; // ID del empaque asignado
    $usu_id = $data['usu_id'];

    try {
        // Sanitizar los datos
        $niv_id = intval($niv_id);
        $rr_id = intval($rr_id);
        $usu_id = intval($usu_id);

        // Iniciar una transacción
        mysqli_begin_transaction($cnx);

        // Insertar en la tabla rev_nivel_posicion_empaque
        $sql1 = "INSERT INTO rev_nivel_posicion_empaque (niv_id, rr_id) 
             VALUES ($niv_id, $rr_id)";
             $result1 = $cnx->insert_id;
             ins_bit_acciones($usu_id, 'A', $result1, '40');
             
        if (!mysqli_query($cnx, $sql1)) {
            throw new Exception("Error al insertar en rev_nivel_posicion_empaque: " . mysqli_error($cnx));
        }

        // Actualizar niv_ocupado en rev_nivel_posicion
        $sql2 = "UPDATE rev_nivel_posicion SET niv_ocupado = 1 WHERE niv_id = $niv_id";
        ins_bit_acciones($usu_id, 'E', $niv_id, '40');
        if (!mysqli_query($cnx, $sql2)) {
            throw new Exception("Error al ins_bit_acciones($usu_id, 'A', $niv_id, '40');actualizar niv_ocupado: " . mysqli_error($cnx));
        }

        // Confirmar la transacción
        mysqli_commit($cnx);
        echo json_encode(["success" => true, "message" => "Datos insertados y posición marcada como ocupada"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }

    mysqli_close($cnx);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
