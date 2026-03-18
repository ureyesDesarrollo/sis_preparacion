<?php
header('Content-Type: application/json');
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);
    $oe_id = isset($data['orden_id']) ? (int)$data['orden_id'] : 0;

    if ($oe_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de orden no válido']);
        exit;
    }

    try {
        $sql_detalles = "SELECT * FROM rev_orden_embarque_detalle WHERE oe_id = $oe_id";
        $res_detalles = mysqli_query($cnx, $sql_detalles);

        if (!$res_detalles) {
            throw new Exception('Error al consultar detalles: ' . mysqli_error($cnx));
        }

        while ($detalle = mysqli_fetch_assoc($res_detalles)) {
            $tipo_producto = isset($detalle['oed_tipo_producto']) ? $detalle['oed_tipo_producto'] : 'REVOLTURA';
            $rr_id = !empty($detalle['rr_id']) ? (int)$detalle['rr_id'] : 0;
            $rrc_id = !empty($detalle['rrc_id']) ? (int)$detalle['rrc_id'] : 0;
            $pe_id = !empty($detalle['pe_id']) ? (int)$detalle['pe_id'] : 0;
            $cantidad_solicitada = (float)$detalle['cantidad'];

            if ($cantidad_solicitada <= 0) {
                continue;
            }

            if ($tipo_producto === 'EXTERNO') {
                // PRODUCTO EXTERNO
                $sql_info = "SELECT pe_id, pe_existencia_real
                             FROM producto_externo
                             WHERE pe_id = $pe_id";
                $res_info = mysqli_query($cnx, $sql_info);
                $info = mysqli_fetch_assoc($res_info);

                if (!$info) {
                    throw new Exception("No se encontró producto externo con pe_id $pe_id");
                }

                $existencia_real = (float)$info['pe_existencia_real'];

                if ($cantidad_solicitada > $existencia_real) {
                    throw new Exception("La cantidad solicitada excede la existencia real del producto externo ID $pe_id");
                }

                $sql_update = "UPDATE producto_externo
                               SET pe_existencia_real = pe_existencia_real - $cantidad_solicitada
                               WHERE pe_id = $pe_id";
                if (!mysqli_query($cnx, $sql_update)) {
                    throw new Exception('Error al actualizar producto externo: ' . mysqli_error($cnx));
                }
            } else {
                // REVOLTURA
                if ($rr_id > 0) {
                    // Empaque GENERAL
                    $sql_info = "SELECT rr.rr_id, rr.pres_id, pres.pres_kg, rr.rr_ext_real
                                 FROM rev_revolturas_pt rr
                                 INNER JOIN rev_presentacion pres ON pres.pres_id = rr.pres_id
                                 WHERE rr.rr_id = $rr_id";
                    $res_info = mysqli_query($cnx, $sql_info);
                    $info = mysqli_fetch_assoc($res_info);

                    if (!$info) {
                        throw new Exception("No se encontró empaque general rr_id $rr_id");
                    }

                    $pres_kg = (float)$info['pres_kg'];
                    $existencia_real = (float)$info['rr_ext_real'];

                    if ($cantidad_solicitada > $existencia_real) {
                        throw new Exception("La cantidad solicitada excede la existencia real del empaque rr_id $rr_id");
                    }

                    $cantidad_solicitada_kg = $cantidad_solicitada * $pres_kg;

                    $sql_pos = "SELECT nvd.nvd_id, nvd.cantidad, nvd.niv_id
                                FROM rev_nivel_posicion_detalle nvd
                                WHERE rr_id = $rr_id AND cantidad > 0
                                ORDER BY nvd.nvd_id ASC";
                    $res_pos = mysqli_query($cnx, $sql_pos);

                    while ($pos = mysqli_fetch_assoc($res_pos)) {
                        $cantidad_en_posicion = (float)$pos['cantidad'];
                        $descontar = min($cantidad_solicitada_kg, $cantidad_en_posicion);
                        $pos_id = (int)$pos['nvd_id'];
                        $niv_id = (int)$pos['niv_id'];

                        $sql_upd_pos = "UPDATE rev_nivel_posicion_detalle
                                        SET cantidad = cantidad - $descontar
                                        WHERE nvd_id = $pos_id";
                        mysqli_query($cnx, $sql_upd_pos);

                        $sql_check_zero = "SELECT cantidad FROM rev_nivel_posicion_detalle WHERE nvd_id = $pos_id";
                        $res_check = mysqli_query($cnx, $sql_check_zero);
                        $check = mysqli_fetch_assoc($res_check);

                        if ($check && (float)$check['cantidad'] <= 0) {
                            mysqli_query($cnx, "DELETE FROM rev_nivel_posicion_detalle WHERE nvd_id = $pos_id");
                            mysqli_query($cnx, "UPDATE rev_nivel_posicion SET niv_ocupado = 0 WHERE niv_id = $niv_id");
                        }

                        $cantidad_solicitada_kg -= $descontar;
                        if ($cantidad_solicitada_kg <= 0) {
                            break;
                        }
                    }

                    $sql_update = "UPDATE rev_revolturas_pt
                                   SET rr_ext_real = rr_ext_real - $cantidad_solicitada
                                   WHERE rr_id = $rr_id";
                    if (!mysqli_query($cnx, $sql_update)) {
                        throw new Exception('Error al actualizar rev_revolturas_pt: ' . mysqli_error($cnx));
                    }
                } elseif ($rrc_id > 0) {
                    // Empaque CLIENTE
                    $sql_info = "SELECT rrc.rrc_id, rrc.pres_id, pres.pres_kg, rrc.rrc_ext_real
                                 FROM rev_revolturas_pt_cliente rrc
                                 INNER JOIN rev_presentacion pres ON pres.pres_id = rrc.pres_id
                                 WHERE rrc.rrc_id = $rrc_id";
                    $res_info = mysqli_query($cnx, $sql_info);
                    $info = mysqli_fetch_assoc($res_info);

                    if (!$info) {
                        throw new Exception("No se encontró empaque cliente rrc_id $rrc_id");
                    }

                    $pres_kg = (float)$info['pres_kg'];
                    $existencia_real = (float)$info['rrc_ext_real'];

                    if ($cantidad_solicitada > $existencia_real) {
                        throw new Exception("La cantidad solicitada excede la existencia real del empaque cliente rrc_id $rrc_id");
                    }

                    $cantidad_solicitada_kg = $cantidad_solicitada * $pres_kg;

                    $sql_pos = "SELECT nvd.nvd_id, nvd.cantidad, nvd.niv_id
                                FROM rev_nivel_posicion_detalle nvd
                                WHERE rrc_id = $rrc_id AND cantidad > 0
                                ORDER BY nvd.nvd_id ASC";
                    $res_pos = mysqli_query($cnx, $sql_pos);

                    while ($pos = mysqli_fetch_assoc($res_pos)) {
                        $cantidad_en_posicion = (float)$pos['cantidad'];
                        $descontar = min($cantidad_solicitada_kg, $cantidad_en_posicion);
                        $pos_id = (int)$pos['nvd_id'];
                        $niv_id = (int)$pos['niv_id'];

                        $sql_upd_pos = "UPDATE rev_nivel_posicion_detalle
                                        SET cantidad = cantidad - $descontar
                                        WHERE nvd_id = $pos_id";
                        mysqli_query($cnx, $sql_upd_pos);

                        $sql_check_zero = "SELECT cantidad FROM rev_nivel_posicion_detalle WHERE nvd_id = $pos_id";
                        $res_check = mysqli_query($cnx, $sql_check_zero);
                        $check = mysqli_fetch_assoc($res_check);

                        if ($check && (float)$check['cantidad'] <= 0) {
                            mysqli_query($cnx, "DELETE FROM rev_nivel_posicion_detalle WHERE nvd_id = $pos_id");
                            mysqli_query($cnx, "UPDATE rev_nivel_posicion SET niv_ocupado = 0 WHERE niv_id = $niv_id");
                        }

                        $cantidad_solicitada_kg -= $descontar;
                        if ($cantidad_solicitada_kg <= 0) {
                            break;
                        }
                    }

                    $sql_update = "UPDATE rev_revolturas_pt_cliente
                                   SET rrc_ext_real = rrc_ext_real - $cantidad_solicitada
                                   WHERE rrc_id = $rrc_id";
                    if (!mysqli_query($cnx, $sql_update)) {
                        throw new Exception('Error al actualizar rev_revolturas_pt_cliente: ' . mysqli_error($cnx));
                    }
                } else {
                    throw new Exception("Detalle inválido: no tiene rr_id, rrc_id ni pe_id");
                }
            }
        }

        $sql_remision_status = "SELECT remision_ban FROM rev_orden_embarque WHERE oe_id = $oe_id";
        $row_status = mysqli_fetch_assoc(mysqli_query($cnx, $sql_remision_status));
        $res_status = $row_status ? $row_status['remision_ban'] : null;

        if ($res_status === '1' || $res_status === 1) {
            $status = 'FACTURADA';
        } else {
            $status = 'COMPLETADA';
        }

        mysqli_query($cnx, "UPDATE rev_orden_embarque SET oe_estado = '$status' WHERE oe_id = $oe_id");

        echo json_encode([
            'success' => true,
            'mensaje' => 'Embarque terminado'
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
