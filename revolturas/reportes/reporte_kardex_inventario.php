<?php
include "../../conexion/conexion.php";

try {
    // Obtener fechas del POST
    $fecha_ini = $_POST['fecha_ini'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;

    if (!$fecha_ini || !$fecha_fin) {
        throw new Exception("Las fechas de inicio y fin son requeridas.");
    }

    // Conexión a la base de datos
    $cnx = Conectarse();

    if (!$cnx) {
        throw new Exception("Error en la conexión a la base de datos: " . mysqli_connect_error());
    }

    $sql = "SELECT kar_fecha, kar_inventario FROM rev_kardex 
            WHERE kar_fecha >= '$fecha_ini' AND kar_fecha <= '$fecha_fin' 
            ORDER BY kar_fecha ASC";

    $resultado = mysqli_query($cnx, $sql);

    if (!$resultado) {
        throw new Exception("Error en la consulta SQL: " . mysqli_error($cnx));
    }

    $kardex = [];

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $kardex[] = $fila;
    }

    mysqli_close($cnx);

    if (empty($kardex)) {
        throw new Exception("No se encontraron registros en el rango de fechas.");
    }

    // Obtener primer y último registro
    $inicio = $kardex[0];
    $fin = end($kardex); // Último elemento

    echo json_encode([
        'inicio' => $inicio,
        'fin' => $fin
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}
