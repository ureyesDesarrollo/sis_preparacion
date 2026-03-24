<?php
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";

$cnx = Conectarse();

try {

    // 🔹 Campos principales
    $cte_id = mysqli_real_escape_string($cnx, $_POST['cte_id']);
    $cte_nombre = mysqli_real_escape_string($cnx, trim($_POST['cte_nombre']));
    $cte_rfc = strtoupper(mysqli_real_escape_string($cnx, trim($_POST['cte_rfc'])));
    $cte_razon_social = mysqli_real_escape_string($cnx, trim($_POST['cte_razon_social']));
    $cte_ubicacion = mysqli_real_escape_string($cnx, $_POST['cte_ubicacion'] ?? '');
    $cte_tipo = mysqli_real_escape_string($cnx, $_POST['cte_tipo'] ?? '');
    $cte_clasificacion = mysqli_real_escape_string($cnx, $_POST['cte_clasificacion'] ?? '');
    $cte_estatus = isset($_POST['chk_estatus']) ? 'A' : 'B';
    $cte_tipo_bloom = mysqli_real_escape_string($cnx, trim($_POST['cte_tipo_bloom'] ?? ''));
    $cte_bloom_min = mysqli_real_escape_string($cnx, trim($_POST['cte_bloom_min'] ?? ''));
    $direccion_fiscal = mysqli_real_escape_string($cnx, trim($_POST['cte_direccion_fiscal'] ?? ''));

    // 🔹 Direcciones (opción 3)
    $direcciones = $_POST['direccion_entrega'] ?? [];
    $direcciones_limpias = [];

    foreach ($direcciones as $dir) {
        $dir = trim($dir);
        if ($dir !== '') {
            $direcciones_limpias[] = $dir;
        }
    }

    // ❌ Validar duplicados en request
    if (count($direcciones_limpias) !== count(array_unique($direcciones_limpias))) {
        throw new Exception("No puedes registrar direcciones duplicadas.");
    }

    // 🔹 Validación básica
    if ($cte_nombre === '' || $cte_rfc === '' || $cte_razon_social === '') {
        throw new Exception("Campos obligatorios incompletos.");
    }

    // 🔹 Validar duplicados cliente
    $checkSql = "SELECT COUNT(*) as count
                 FROM rev_clientes
                 WHERE cte_nombre = '$cte_nombre'
                 AND cte_id != '$cte_id'";

    $result = mysqli_query($cnx, $checkSql);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        throw new Exception("Ya existe otro cliente con ese nombre.");
    }

    // 🔹 UPDATE cliente
    $updateSql = "UPDATE rev_clientes SET
        cte_nombre = '$cte_nombre',
        cte_razon_social = '$cte_razon_social',
        cte_rfc = '$cte_rfc',
        cte_estatus = '$cte_estatus',
        cte_ubicacion = '$cte_ubicacion',
        cte_tipo = '$cte_tipo',
        cte_clasificacion = '$cte_clasificacion',
        cte_tipo_bloom = '$cte_tipo_bloom',
        cte_bloom_min = '$cte_bloom_min',
        cte_direccion_fiscal = '$direccion_fiscal'
        WHERE cte_id = '$cte_id'";

    write_log("UPDATE SQL: $updateSql;");

    if (!mysqli_query($cnx, $updateSql)) {
        throw new Exception("Error al actualizar cliente: " . mysqli_error($cnx));
    }

    // 🔥 MANEJO DE DIRECCIONES (MyISAM friendly)

    // 1. eliminar todas
    $deleteSql = "DELETE FROM rev_clientes_direcciones_entrega WHERE cte_id = '$cte_id'";
    if (!mysqli_query($cnx, $deleteSql)) {
        throw new Exception("Error al limpiar direcciones: " . mysqli_error($cnx));
    }

    // 2. insertar nuevas
    $exitos = 0;

    foreach ($direcciones_limpias as $dir) {

        $dir = mysqli_real_escape_string($cnx, $dir);

        $sqlDir = "INSERT INTO rev_clientes_direcciones_entrega
                   (direccion_entrega, cte_id)
                   VALUES ('$dir', '$cte_id')";

        if (!mysqli_query($cnx, $sqlDir)) {
            throw new Exception("Error al guardar dirección: " . mysqli_error($cnx));
        }

        $exitos++;
    }

    ins_bit_acciones($_SESSION['idUsu'], 'E', $cte_id, '49');

    echo json_encode([
        "success" => "Cliente actualizado correctamente. Direcciones: $exitos"
    ]);
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
