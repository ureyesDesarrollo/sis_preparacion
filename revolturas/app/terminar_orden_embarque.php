<?php
header('Content-Type: application/json');
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);
    $oe_id = $data['orden_id'] ?? null;

    // 1. Obtener todos los empaques de la orden
    $sql_detalles = "SELECT * FROM rev_orden_embarque_detalle WHERE oe_id = $oe_id";
    $res_detalles = mysqli_query($cnx, $sql_detalles);

    while ($detalle = mysqli_fetch_assoc($res_detalles)) {
        $rr_id = $detalle['rr_id'];
        $rrc_id = $detalle['rrc_id'];
        $cantidad_solicitada = $detalle['cantidad'];

        // Identificar si es empaque general o por cliente
        if ($rr_id) {
            // Empaque GENERAL
            $sql_info = "SELECT rr.rr_id, rr.pres_id, pres.pres_kg, rr.rr_ext_real 
                     FROM rev_revolturas_pt rr
                     INNER JOIN rev_presentacion pres ON pres.pres_id = rr.pres_id
                     WHERE rr.rr_id = $rr_id";
            $res_info = mysqli_query($cnx, $sql_info);
            $info = mysqli_fetch_assoc($res_info);

            $pres_kg = $info['pres_kg'];
            $cantidad_solicitada_kg = $cantidad_solicitada * $pres_kg;

            // Descontar de posiciones
            $sql_pos = "SELECT nvd.nvd_id, nvd.cantidad 
                    FROM rev_nivel_posicion_detalle nvd 
                    WHERE rr_id = $rr_id AND cantidad > 0 
                    ORDER BY nvd.nvd_id ASC";
            $res_pos = mysqli_query($cnx, $sql_pos);

            while ($pos = mysqli_fetch_assoc($res_pos)) {
                $cantidad_en_posicion = $pos['cantidad'];
                $descontar = min($cantidad_solicitada_kg, $cantidad_en_posicion);
                $pos_id = $pos['nvd_id'];

                $sql_upd_pos = "UPDATE rev_nivel_posicion_detalle 
                            SET cantidad = cantidad - $descontar 
                            WHERE nvd_id = $pos_id";
                mysqli_query($cnx, $sql_upd_pos);

                // Si la posición queda en 0, eliminar el registro
                $sql_check_zero = "SELECT cantidad FROM rev_nivel_posicion_detalle WHERE nvd_id = $pos_id";
                $res_check = mysqli_query($cnx, $sql_check_zero);
                $check = mysqli_fetch_assoc($res_check);
                if ($check['cantidad'] <= 0) {
                    mysqli_query($cnx, "DELETE FROM rev_nivel_posicion_detalle WHERE nvd_id = $pos_id");
                }

                $cantidad_solicitada_kg -= $descontar;
                if ($cantidad_solicitada_kg <= 0) break;
            }

            // Descontar del inventario real
            mysqli_query($cnx, "UPDATE rev_revolturas_pt 
                                 SET rr_ext_real = rr_ext_real - $cantidad_solicitada
                                 WHERE rr_id = $rr_id");
        } elseif ($rrc_id) {
            // Empaque CLIENTE
            $sql_info = "SELECT rrc.rrc_id, rrc.pres_id, pres.pres_kg, rrc.rrc_ext_real 
                     FROM rev_revolturas_pt_cliente rrc
                     INNER JOIN rev_presentacion pres ON pres.pres_id = rrc.pres_id
                     WHERE rrc.rrc_id = $rrc_id";
            $res_info = mysqli_query($cnx, $sql_info);
            $info = mysqli_fetch_assoc($res_info);

            $pres_kg = $info['pres_kg'];
            $cantidad_solicitada_kg = $cantidad_solicitada * $pres_kg;

            // Descontar de posiciones
            $sql_pos = "SELECT nvd.nvd_id, nvd.cantidad
                    FROM rev_nivel_posicion_detalle nvd 
                    WHERE rrc_id = $rrc_id AND cantidad > 0 
                    ORDER BY nvd.nvd_id ASC";
            $res_pos = mysqli_query($cnx, $sql_pos);

            while ($pos = mysqli_fetch_assoc($res_pos)) {
                $cantidad_en_posicion = $pos['cantidad'];
                $descontar = min($cantidad_solicitada_kg, $cantidad_en_posicion);
                $pos_id = $pos['nvd_id'];

                $sql_upd_pos = "UPDATE rev_nivel_posicion_detalle 
                            SET cantidad = cantidad - $descontar 
                            WHERE nvd_id = $pos_id";
                mysqli_query($cnx, $sql_upd_pos);

                // Si la posición queda en 0, eliminar el registro
                $sql_check_zero = "SELECT cantidad, niv_id FROM rev_nivel_posicion_detalle WHERE nvd_id = $pos_id";
                $res_check = mysqli_query($cnx, $sql_check_zero);
                $check = mysqli_fetch_assoc($res_check);
                if ($check['cantidad'] <= 0) {
                    mysqli_query($cnx, "DELETE FROM rev_nivel_posicion_detalle WHERE nvd_id = $pos_id");
                    mysqli_query($cnx, "UPDATE rev_nivel_posicion SET niv_ocupado = 0 WHERE niv_id = '{$check['niv_id']}'");
                }

                $cantidad_solicitada_kg -= $descontar;
                if ($cantidad_solicitada_kg <= 0) break;
            }

            // Descontar del inventario real
            mysqli_query($cnx, "UPDATE rev_revolturas_pt_cliente 
                                 SET rrc_ext_real = rrc_ext_real -$cantidad_solicitada
                                 WHERE rrc_id = $rrc_id");
        }
    }

    // Finalmente marcar la orden como CERRADA
    $sql_remision_status = "SELECT remision_ban FROM rev_orden_embarque WHERE oe_id = '$oe_id'";
    $row_status = mysqli_fetch_assoc(mysqli_query($cnx, $sql_remision_status));
    $res_status = $row_status ? $row_status['remision_ban'] : null;

    // Considera null y 0 como "COMPLETADA"
    if ($res_status === '1' || $res_status === 1) {
        $status = 'FACTURADA';
    } else {
        $status = 'COMPLETADA';
    }
    mysqli_query($cnx, "UPDATE rev_orden_embarque SET oe_estado = '$status' WHERE oe_id = $oe_id");

    echo json_encode(['success' => true, 'mensaje' => 'Embarque terminado']);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
