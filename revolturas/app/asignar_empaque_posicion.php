<?php
header('Content-Type: application/json');
include "../../conexion/conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cnx = Conectarse();
    $data = json_decode(file_get_contents("php://input"), true);

    $rr_id = isset($data['rr_id']) ? intval($data['rr_id']) : null;
    $rrc_id = isset($data['rrc_id']) ? intval($data['rrc_id']) : null;
    $niveles = $data['niveles'];

    function pesoPorUnidad($presentacion)
    {
        $pesos = [
            '2' => 25.0,
            '3' => 1.0,
            '4' => 0.25,
            '5' => 1000.0, 
        ];
        if (!isset($pesos[$presentacion])) {
            throw new Exception("Presentación no válida.");
        }
        return $pesos[$presentacion];
    }

    if ((!$rr_id && !$rrc_id) || empty($niveles)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }

    if ($rr_id) {
        $query = "SELECT rr_ext_inicial AS cantidad, pres_id FROM rev_revolturas_pt WHERE rr_id = $rr_id";
    } else {
        $query = "SELECT rrc_ext_inicial AS cantidad, pres_id FROM rev_revolturas_pt_cliente WHERE rrc_id = $rrc_id";
    }

    $result = mysqli_query($cnx, $query);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'No se encontró la cantidad']);
        exit;
    }

    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'No se encontró la cantidad']);
        exit;
    }


    $total = floatval($row['cantidad']);
    $kilosPorUnidad = pesoPorUnidad($row['pres_id']);
    $totalKilos = $total * $kilosPorUnidad;
    $kilosPorPosicion = round($totalKilos / count($niveles), 2);

    $cnx->begin_transaction();

    try {
        foreach ($niveles as $niv_id) {
            $niv_id = intval($niv_id);

            $tipo = $rr_id ? 'general' : 'cliente';
            $rr_id_val = $rr_id ? $rr_id : 'NULL';
            $rrc_id_val = $rrc_id ? $rrc_id : 'NULL';

            $sql = "INSERT INTO rev_nivel_posicion_detalle 
                (niv_id, tipo, rr_id, rrc_id, cantidad)
                VALUES ($niv_id, '$tipo', $rr_id_val, $rrc_id_val, $kilosPorPosicion)";

            $update_ocupado = "UPDATE rev_nivel_posicion SET niv_ocupado = 1 WHERE niv_id = $niv_id";
            if (!mysqli_query($cnx, $update_ocupado)) {
                throw new Exception("Error al actualizar nivel $niv_id: " . $cnx->error);
            }

            if (!mysqli_query($cnx, $sql)) {
                throw new Exception("Error al insertar en nivel $niv_id: " . $cnx->error);
            }
        }

        $cnx->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $cnx->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
