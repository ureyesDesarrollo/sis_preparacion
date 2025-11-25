<?php
header('Content-Type: application/json');

include '../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cnx = Conectarse();
        $data = json_decode(file_get_contents('php://input'), true);

        // Verificar que los parámetros necesarios estén presentes
        if (!isset($data['tarima_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Faltan parámetros necesarios']);
            exit;
        }

        $tarima_id = intval($data['tarima_id']);
        $tarima_id = mysqli_real_escape_string($cnx, $tarima_id);

        // Obtener el niv_id asociado a la tarima
        $niv_result = mysqli_query($cnx, "SELECT niv_id FROM rev_nivel_posicion_detalle WHERE tar_id = $tarima_id");
        if (!$niv_result || mysqli_num_rows($niv_result) == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Tarima no encontrada o no tiene una posición asociada']);
            exit;
        }

        $niv_row = mysqli_fetch_assoc($niv_result);
        $niv_id = $niv_row['niv_id'];

        // Escapar niv_id para evitar SQL Injection
        $niv_id = mysqli_real_escape_string($cnx, $niv_id);

        // Actualizar estado de la posición y tarima, y eliminar el detalle de la posición
        $sql_desocupar = "UPDATE rev_nivel_posicion SET niv_ocupado = 0 WHERE niv_id = '$niv_id'";
        $sql_actualizar_estatus_tarima = "UPDATE rev_tarimas SET tar_estatus = 8 WHERE tar_id = '$tarima_id'"; // 8 PARA VENTA
        $eliminar_detalle = "DELETE FROM rev_nivel_posicion_detalle WHERE niv_id = '$niv_id'";

        if (mysqli_query($cnx, $sql_desocupar) && mysqli_query($cnx, $sql_actualizar_estatus_tarima) && mysqli_query($cnx, $eliminar_detalle)) {
            echo json_encode(['status' => 'success', 'message' => 'Tarima retirada con éxito']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al retirar la tarima']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
    mysqli_close($cnx);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
