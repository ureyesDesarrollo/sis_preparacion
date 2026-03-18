<?php
header('Content-Type: application/json');

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

function log_orden_embarque($oe_id, $accion, $estado_anterior, $estado_nuevo)
{
    $fecha = date("Y-m-d H:i:s");
    $mensaje = "[$fecha] | OrdenID: $oe_id | Acción: $accion | Estado anterior: $estado_anterior | Estado nuevo: $estado_nuevo" . PHP_EOL;
    file_put_contents(__DIR__ . '/log_ordenes.txt', $mensaje, FILE_APPEND);
}

try {
    if (
        isset($_POST['fe_factura'], $_POST['fe_cantidad'], $_POST['fe_fecha'], $_POST['cte_id'], $_POST['tipo'], $_POST['oe_id'], $_POST['tipo_producto'], $_POST['empaque_id'])
    ) {
        $tipo_producto = trim($_POST['tipo_producto']);
        $tipo_revoltura = trim($_POST['tipo_revoltura'] ?? '');
        $empaque_id = (int)$_POST['empaque_id'];

        $fe_factura = mysqli_real_escape_string($cnx, $_POST['fe_factura']);
        $fe_cantidad = (float)$_POST['fe_cantidad'];
        $fe_fecha = mysqli_real_escape_string($cnx, $_POST['fe_fecha']);
        $cte_id = (int)$_POST['cte_id'];
        $fe_tipo = mysqli_real_escape_string($cnx, $_POST['tipo']);
        $fe_cartaporte = mysqli_real_escape_string($cnx, $_POST['fe_cartaporte'] ?? '');
        $oe_id = (int)$_POST['oe_id'];

        if ($fe_cantidad <= 0) {
            echo json_encode(["error" => "La cantidad debe ser mayor que 0."]);
            exit;
        }

        if ($empaque_id <= 0) {
            echo json_encode(["error" => "Empaque inválido."]);
            exit;
        }

        $rr_id_sql = "NULL";
        $rrc_id_sql = "NULL";
        $pe_id_sql = "NULL";

        if ($tipo_producto === 'EXTERNO') {
            $pe_id_sql = $empaque_id;
        } elseif ($tipo_producto === 'REVOLTURA') {
            if ($tipo_revoltura === 'CLIENTE') {
                $rrc_id_sql = $empaque_id;
            } else {
                $rr_id_sql = $empaque_id;
            }
        } else {
            echo json_encode(["error" => "Tipo de producto inválido."]);
            exit;
        }

        $sql_verify_status = "SELECT oe_estado FROM rev_orden_embarque WHERE oe_id = '$oe_id'";
        $res_verify = mysqli_query($cnx, $sql_verify_status);
        $row_verify = mysqli_fetch_assoc($res_verify);

        if (!$row_verify) {
            echo json_encode(["error" => "No se encontró la orden de embarque."]);
            exit;
        }

        $estado_anterior = $row_verify['oe_estado'];

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

        if ($accion !== '') {
            log_orden_embarque($oe_id, $accion, $estado_anterior, $estado_nuevo);
        }

        $sql_insert = "
            INSERT INTO rev_revolturas_pt_facturas
            (
                rr_id,
                rrc_id,
                pe_id,
                fe_tipo_producto,
                fe_factura,
                fe_cantidad,
                fe_fecha,
                cte_id,
                fe_tipo,
                fe_cartaporte,
                orden_embarque_id
            )
            VALUES
            (
                $rr_id_sql,
                $rrc_id_sql,
                $pe_id_sql,
                '$tipo_producto',
                '$fe_factura',
                '$fe_cantidad',
                '$fe_fecha',
                '$cte_id',
                '$fe_tipo',
                '$fe_cartaporte',
                '$oe_id'
            )
        ";

        $sql_update_orden = "UPDATE rev_orden_embarque
                             SET oe_estado = '$status', remision_ban = '$rem'
                             WHERE oe_id = '$oe_id'";

        $msg = $fe_tipo == 'F' ? 'Factura' : 'Remisión';

        if (mysqli_query($cnx, $sql_insert)) {
            if (mysqli_query($cnx, $sql_update_orden)) {
                echo json_encode(["success" => "$msg registrada correctamente."]);
            } else {
                echo json_encode(["error" => "Error al actualizar la orden: " . mysqli_error($cnx)]);
            }
        } else {
            echo json_encode(["error" => "Error al insertar el registro: " . mysqli_error($cnx)]);
        }
    } else {
        echo json_encode(["error" => "Todos los campos son obligatorios"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
