<?php
header('Content-Type: application/json');
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    try{
        $conn = Conectarse();
        $data = json_decode(file_get_contents('php://input'), true);
        $odd_id = isset($data['odd_id']) ? (int)$data['odd_id'] : 0;
        $od_id = isset($data['od_id']) ? (int)$data['od_id'] : 0;
        if ($odd_id <= 0) {
            throw new Exception('ID de detalle de orden de devolución inválido');
        }
        
        // Actualizar estado del lote
        $sql_update = "UPDATE orden_devolucion_detalle SET estado_lote = 'LIBERADO' WHERE odd_id = $odd_id";
        if (!mysqli_query($conn, $sql_update)) {
            throw new Exception("Error al actualizar estado de lote: " . mysqli_error($conn));
        }

        // Verificar si todos los lotes han sido liberados
        $sql_estados_lotes = "SELECT estado_lote FROM orden_devolucion_detalle WHERE od_id = $od_id";
        $result_estados_lotes = mysqli_query($conn, $sql_estados_lotes);
        if (!$result_estados_lotes || mysqli_num_rows($result_estados_lotes) === 0) {
            throw new Exception('Error al obtener estados de lotes: ' . mysqli_error($conn));
        }
        $todos_liberados = true;
        while ($row = mysqli_fetch_assoc($result_estados_lotes)) {
            if ($row['estado_lote'] !== 'LIBERADO') {
                $todos_liberados = false;
                break;
            }
        }

        // Si todos los lotes fueron liberados
        if ($todos_liberados) {
            $msg = "Todos los lotes liberados correctamente.";
            $sql_update_orden = "UPDATE orden_devolucion SET od_estado = 'LIBERADO' WHERE od_id = $od_id";
            if (!mysqli_query($conn, $sql_update_orden)) {
                throw new Exception("Error al actualizar estado de la orden: " . mysqli_error($conn));
            }
        } else {
            $msg = "Lote liberado correctamente.";
        }

        mysqli_commit($conn);
        echo json_encode(['success' => true, 'message' => $msg]);
    }catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }

}