<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

try {
    // Escapar valores recibidos por POST
    $cte_id = mysqli_real_escape_string($cnx, $_POST['cte_id']);
    $cte_nombre = mysqli_real_escape_string($cnx, $_POST['cte_nombre']);
    $cte_rfc = mysqli_real_escape_string($cnx, $_POST['cte_rfc']);
    $cte_razon_social = mysqli_real_escape_string($cnx, $_POST['cte_razon_social']);
    $cte_ubicacion = isset($_POST['cte_ubicacion']) ? mysqli_real_escape_string($cnx, $_POST['cte_ubicacion']) : '';
    $cte_tipo = isset($_POST['cte_tipo']) ? mysqli_real_escape_string($cnx, $_POST['cte_tipo']) : '';
    $cte_clasificacion = isset($_POST['cte_clasificacion']) ? mysqli_real_escape_string($cnx, $_POST['cte_clasificacion']) : '';
    $cte_estatus = isset($_POST['chk_estatus']) ? 'A' : 'B';
    $cte_tipo_bloom = mysqli_real_escape_string($cnx, trim($_POST['cte_tipo_bloom'] ?? ''));
    $cte_bloom_min = mysqli_real_escape_string($cnx, trim($_POST['cte_bloom_min'] ?? ''));

    // Verifica existencia del cliente actual
    $existingClientSql = "SELECT cte_nombre, cte_rfc, cte_razon_social FROM rev_clientes WHERE cte_id = '$cte_id'";
    $existingClientResult = mysqli_query($cnx, $existingClientSql);
    $existingClient = mysqli_fetch_assoc($existingClientResult);

    $onlyStatusChanged = (
        $existingClient['cte_nombre'] === $cte_nombre &&
        $existingClient['cte_rfc'] === $cte_rfc &&
        $existingClient['cte_razon_social'] === $cte_razon_social
    );

    if (!$onlyStatusChanged) {
        $checkSql = "SELECT COUNT(*) as count FROM rev_clientes WHERE cte_nombre = '$cte_nombre' AND cte_id != '$cte_id'";
        $result = mysqli_query($cnx, $checkSql);
        $row = mysqli_fetch_assoc($result);
    }

    // Actualización completa incluyendo tipo y clasificación
    $updateSql = "UPDATE rev_clientes SET 
        cte_nombre = '$cte_nombre', 
        cte_razon_social = '$cte_razon_social', 
        cte_rfc = '$cte_rfc', 
        cte_estatus = '$cte_estatus', 
        cte_ubicacion = '$cte_ubicacion', 
        cte_tipo = '$cte_tipo', 
        cte_clasificacion = '$cte_clasificacion',
        cte_tipo_bloom = '$cte_tipo_bloom',
        cte_bloom_min = '$cte_bloom_min'
        WHERE cte_id = '$cte_id'";

        write_log("UPDATE SQL: $updateSql;");


    if (mysqli_query($cnx, $updateSql)) {
        $mensaje = (mysqli_affected_rows($cnx) > 0) ?
            "Cliente actualizado exitosamente." :
            "No se modificó ningún dato (quizás ya estaba igual).";
        ins_bit_acciones($_SESSION['idUsu'], 'E', $cte_id, '49');
        echo json_encode(["success" => $mensaje]);
    } else {
        echo json_encode(["error" => "Error al actualizar el cliente: " . mysqli_error($cnx)]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}

function write_log($message)
{
    $logFile = __DIR__ . '/update_clientes.log';
    $date = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$date] $message\n", FILE_APPEND);
}
