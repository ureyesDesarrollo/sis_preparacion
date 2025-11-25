<?php
include '../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cnx = Conectarse();

        // Verificar si los parámetros están presentes
        if (!isset($_POST['niv_id'], $_POST['cte_id']) || !is_array($_POST['niv_id'])) {
            echo json_encode(["status" => "error", "message" => "Faltan parámetros en la solicitud o el parámetro 'niv_id' no es un array."]);
            exit;
        }

        // Obtener los datos del cliente y las posiciones
        $cte_id = mysqli_real_escape_string($cnx, $_POST['cte_id']);
        $niv_ids = $_POST['niv_id'];  // Array de IDs de las posiciones

        // Comenzar transacción
        mysqli_begin_transaction($cnx);

        $asignadas = 0;
        $fallidas = 0;

        // Iterar sobre cada ID de posición
        foreach ($niv_ids as $niv_id) {
            $niv_id = mysqli_real_escape_string($cnx, $niv_id);

            // Verificar si la posición ya está asignada al cliente
            $queryVerificarPosicion = "SELECT * FROM rev_nivel_posicion WHERE niv_id = '$niv_id' AND cte_id = '$cte_id'";
            $result = mysqli_query($cnx, $queryVerificarPosicion);
            
            if (mysqli_num_rows($result) > 0) {
                $fallidas++; // Contar la cantidad de asignaciones fallidas
                continue; // Saltar esta posición
            }

            // Asignar la posición al cliente
            $queryAsignarPosicion = "UPDATE rev_nivel_posicion SET cte_id = '$cte_id' WHERE niv_id = '$niv_id'";
            $insertResult = mysqli_query($cnx, $queryAsignarPosicion);

            if ($insertResult) {
                $asignadas++; // Contar la cantidad de asignaciones exitosas
            } else {
                $fallidas++; // Contar las fallidas
            }
        }

        // Commit de la transacción
        mysqli_commit($cnx);

        // Construir mensaje de respuesta
        if ($asignadas > 0 && $fallidas === 0) {
            echo json_encode(["status" => "success", "message" => "Se asignaron correctamente todas las posiciones."]);
        } elseif ($asignadas > 0 && $fallidas > 0) {
            echo json_encode(["status" => "warning", "message" => "Algunas posiciones no pudieron ser asignadas."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo asignar ninguna posición."]);
        }

    } catch (Exception $e) {
        // Rollback en caso de error
        mysqli_rollback($cnx);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
