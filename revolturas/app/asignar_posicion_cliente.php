<?php
header("Content-Type: application/json");
include '../../conexion/conexion.php';
include "../../funciones/funciones.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cnx = Conectarse();
    $data = json_decode(file_get_contents("php://input"), true);

    $niv_id = $data['niv_id']; // ID de la posición en el rack
    $rrc_id = $data['rrc_id']; // ID del empaque asignado a un cliente
    $cte_id = $data['cte_id']; // ID del cliente dueño del empaque
    $usu_id = $data['usu_id'];

    try {
        // Sanitizar los datos
        $niv_id = intval($niv_id);
        $rrc_id = intval($rrc_id);
        $cte_id = intval($cte_id);
        $usu_id = intval($usu_id);

        // Iniciar una transacción
        mysqli_begin_transaction($cnx);

        // Insertar en la tabla rev_nivel_posicion_empaque_cliente
        $sql1 = "INSERT INTO rev_nivel_posicion_empaque_cliente (niv_id, rrc_id, cte_id) 
                 VALUES ($niv_id, $rrc_id, $cte_id)";
                 $result1 = $cnx->insert_id;
                 ins_bit_acciones($usu_id, 'A', $result1, '40');
        if (!mysqli_query($cnx, $sql1)) {
            throw new Exception("Error al insertar en rev_nivel_posicion_empaque_cliente: " . mysqli_error($cnx));
        }

        // Actualizar niv_ocupado en rev_nivel_posicion
        $sql2 = "UPDATE rev_nivel_posicion SET niv_ocupado = 1 WHERE niv_id = $niv_id";
        ins_bit_acciones($usu_id, 'E', $niv_id, '40');
        if (!mysqli_query($cnx, $sql2)) {
            throw new Exception("Error al actualizar niv_ocupado: " . mysqli_error($cnx));
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
