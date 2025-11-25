<?php
header('Content-Type: application/json');
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $cnx = Conectarse();
        $data = json_decode(file_get_contents('php://input'), true);

        $rev_id = $data['rev_id'];
        $pres_id = $data['pres_id'];
        $cantidad_empacada = $data['cantidad_empacada'];
        $orden_id = $data['orden_id'];
        $cliente_id = $data['cliente_id'];

        if (empty($rev_id) || empty($pres_id) || empty($cantidad_empacada) || empty($orden_id) || empty($cliente_id)) {
            echo json_encode(['error' => 'Faltan datos requeridos.']);
            exit;
        }

        // Sanitización
        $rev_id = mysqli_real_escape_string($cnx, $rev_id);
        $pres_id = mysqli_real_escape_string($cnx, $pres_id);
        $cantidad_empacada = mysqli_real_escape_string($cnx, $cantidad_empacada);
        $orden_id = mysqli_real_escape_string($cnx, $orden_id);
        $cliente_id = mysqli_real_escape_string($cnx, $cliente_id);

        // Marcar la orden como completada
        $update_status_orden = "UPDATE rev_orden_empaque SET roe_estado = 'COMPLETADA' WHERE roe_id = '$orden_id'";
        $sql_terminar_revoltura = "UPDATE rev_revolturas SET rev_estatus = '3', rev_fecha_empacado = NOW() WHERE rev_id = '$rev_id'";
        
        $posiciones_ocupadas = [];
        $niveles = mysqli_query($cnx, "SELECT niv_id FROM rev_nivel_posicion_detalle WHERE rev_id = '$rev_id'");
        while($nivel = mysqli_fetch_assoc($niveles)){
            $posiciones_ocupadas[] = $nivel['niv_id'];
        }

        $cnx->begin_transaction();
        foreach($posiciones_ocupadas as $niv_id){
            $niv_id = intval($niv_id);
            mysqli_query($cnx, "DELETE FROM rev_nivel_posicion_detalle WHERE niv_id = $niv_id AND rev_id = '$rev_id'");
            mysqli_query($cnx, "UPDATE rev_nivel_posicion SET niv_ocupado = 0 WHERE niv_id = $niv_id");
        }
        if (mysqli_query($cnx, $update_status_orden) && mysqli_query($cnx, $sql_terminar_revoltura)) {
            echo json_encode([
                "success" => true,
                "data" => ["mensaje" => "Empaque terminado correctamente."]
            ]);

            $cnx->commit();
        } else {
            http_response_code(400);
            echo json_encode(['error' => "Error al actualizar el estado de la orden o revoltura"]);
            exit;
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    } finally {
        mysqli_close($cnx);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
