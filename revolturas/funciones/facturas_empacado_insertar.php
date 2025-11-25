<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Octubre-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
$cnx = Conectarse();

function log_orden_embarque($oe_id, $accion, $estado_anterior, $estado_nuevo) {
    $fecha = date("Y-m-d H:i:s");
    $mensaje = "[$fecha] | OrdenID: $oe_id | Acción: $accion | Estado anterior: $estado_anterior | Estado nuevo: $estado_nuevo" . PHP_EOL;
    file_put_contents(__DIR__ . '/log_ordenes.txt', $mensaje, FILE_APPEND);
}

try {
    // Validación básica de los datos recibidos
    if (isset($_POST['rr_id'], $_POST['fe_factura'], $_POST['fe_cantidad'], $_POST['fe_fecha'], $_POST['cte_id'], $_POST['tipo'])) {

        // Escapar los valores para prevenir inyección SQL
        $rr_id = mysqli_real_escape_string($cnx, $_POST['rr_id']);
        $fe_factura = mysqli_real_escape_string($cnx, $_POST['fe_factura']);
        $fe_cantidad = mysqli_real_escape_string($cnx, $_POST['fe_cantidad']);
        $fe_fecha = mysqli_real_escape_string($cnx, $_POST['fe_fecha']);
        $cte_id = mysqli_real_escape_string($cnx, $_POST['cte_id']);
        $fe_tipo = mysqli_real_escape_string($cnx, $_POST['tipo']);
        $fe_cartaporte = mysqli_real_escape_string($cnx, $_POST['fe_cartaporte'] ?? '');
        $oe_id = mysqli_real_escape_string($cnx, $_POST['oe_id']);
        $sql = '';

        // Tipo de cliente determina la columna a usar
        if ($_POST['cliente'] == 'CLIENTE') {
            $sql = "INSERT INTO rev_revolturas_pt_facturas (rrc_id, fe_factura, fe_cantidad, cte_id, fe_tipo,fe_cartaporte) 
                        VALUES ('$rr_id', '$fe_factura', '$fe_cantidad', '$cte_id', '$fe_tipo','$fe_cartaporte')";
        } else {
            $sql = "INSERT INTO rev_revolturas_pt_facturas (rr_id, fe_factura, fe_cantidad, cte_id, fe_tipo,fe_cartaporte) 
                        VALUES ('$rr_id', '$fe_factura', '$fe_cantidad', '$cte_id', '$fe_tipo','$fe_cartaporte')";
        }

        // --- LOG Y ESTADO ---
        $sql_verify_status = "SELECT oe_estado FROM rev_orden_embarque WHERE oe_id = '$oe_id'";
        $estado_anterior = mysqli_fetch_assoc(mysqli_query($cnx, $sql_verify_status))['oe_estado'];

        $accion = '';
        $estado_nuevo = $estado_anterior;
        $status = $estado_anterior;
        $rem = 0;

        if ($estado_anterior === 'LIBERADO') {
            $accion = 'Marcar remisión';
            $estado_nuevo = $estado_anterior;
            $status = $estado_anterior;
            $rem = 1;
        } elseif ($estado_anterior === 'COMPLETADA') {
            $accion = 'Completar y marcar como facturada';
            $estado_nuevo = 'FACTURADA';
            $status = 'FACTURADA';
            $rem = 0;
        }

        // Registrar en log si hubo acción relevante
        if ($accion !== '') {
            log_orden_embarque($oe_id, $accion, $estado_anterior, $estado_nuevo);
        }

        // --- ACTUALIZA ESTADO ---
        $sql_update_orden = "UPDATE rev_orden_embarque SET oe_estado = '$status', remision_ban = '$rem' WHERE oe_id = '$oe_id'";

        $msg = $fe_tipo == 'F' ? 'Factura' : 'Remisión';
        //Validar que la cantidad no sea 0
        if ($fe_cantidad != '0') {
            // Inserción en rev_revolturas_pt_facturas
            $sql_insert = $sql;

            if (mysqli_query($cnx, $sql_insert)) {

                if (mysqli_query($cnx, $sql_update_orden)) {
                    $res = "$msg registrada correctamente.";
                    echo json_encode(["success" => $res]);
                } else {
                    $res = "Error al actualizar la orden: " . mysqli_error($cnx);
                    echo json_encode(["error" => $res]);
                }
            } else {
                $res = "Error al insertar el registro: " . mysqli_error($cnx);
                echo json_encode(["error" => $res]);
            }
        } else {
            $res = "La cantidad debe ser mayor que 0.";
            echo json_encode(["error" => $res]);
        }
    } else {
        $res = "Todos los campos son obligatorios";
        echo json_encode(["error" => $res]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
