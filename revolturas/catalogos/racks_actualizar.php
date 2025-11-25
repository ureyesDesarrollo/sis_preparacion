<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Junio-2024 */
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx = Conectarse();

$rac_id = mysqli_real_escape_string($cnx, $_POST['rac_id']);
$rac_estatus = isset($_POST['chk_estatus']) ? 'A' : 'B';
$rac_descripcion = mysqli_real_escape_string($cnx, $_POST['rac_descripcion']);
$rac_color = mysqli_real_escape_string($cnx, urldecode($_POST['rac_color']));
$rac_zona = mysqli_real_escape_string($cnx, $_POST['rac_zona']);

// Normalizar la descripción (convertir a minúsculas y eliminar espacios innecesarios)
$rac_descripcion_normalized = strtolower($rac_descripcion);
$rac_zona_normalized = strtolower($rac_zona);

// Verificar si existe otro registro con la misma descripción
$check_sql = "SELECT rac_id FROM rev_racks WHERE LOWER(rac_descripcion) = '$rac_descripcion_normalized' 
AND LOWER(rac_zona) = '$rac_zona' 
AND rac_id != '$rac_id'";
$check_result = mysqli_query($cnx, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    $res = "Un registro con una descripción similar ya existe en esta zona";
    echo json_encode(["error" => $res]);
    mysqli_close($cnx);
    exit();
}

// Obtener los valores actuales del registro
$current_sql = "SELECT rac_descripcion, rac_color, rac_zona, rac_estatus FROM rev_racks WHERE rac_id = '$rac_id'";
$current_result = mysqli_query($cnx, $current_sql);
$current_row = mysqli_fetch_assoc($current_result);

$current_descripcion = $current_row['rac_descripcion'];
$current_estatus = $current_row['rac_estatus'];
$current_color = $current_row['rac_color'];
$current_zona = $current_row['rac_zona'];

// Verificar si se realizaron cambios
$descripcion_changed = $current_descripcion !== $rac_descripcion;
$estatus_changed = $current_estatus != $rac_estatus;
$color_changed = $current_color != $rac_color;
$zona_changed = $current_zona != $rac_zona;

// Si no hay cambios, devolver un mensaje sin ejecutar el UPDATE
if (!$descripcion_changed && !$estatus_changed && !$color_changed && !$zona_changed) {
    echo json_encode(["success" => "No se realizaron cambios en los datos"]);
    exit;
}


$set_clause = [];
if ($descripcion_changed) $set_clause[] = "rac_descripcion = '$rac_descripcion'";
if ($estatus_changed) $set_clause[] = "rac_estatus = '$rac_estatus'";
if ($color_changed) $set_clause[] = "rac_color = '$rac_color'";
if ($zona_changed) $set_clause[] = "rac_zona = '$rac_zona'";

// Unir las partes de la consulta
$sql = "UPDATE rev_racks SET " . implode(', ', $set_clause) . " WHERE rac_id = '$rac_id'";

try {
    if (mysqli_query($cnx, $sql)) {
        $res = "Registro actualizado exitosamente: ";
        $updates = [];
        if ($descripcion_changed) $updates[] = "descripción";
        if ($estatus_changed) $updates[] = "estatus";
        if ($color_changed) $updates[] = "color";
        if ($zona_changed) $updates[] = "zona";

        $res .= implode(", ", $updates);
        ins_bit_acciones($_SESSION['idUsu'], 'E', $rac_id, '39');
        echo json_encode(["success" => $res]);
    } else {
        echo json_encode(["error" => "Error en la consulta: " . mysqli_error($cnx)]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Error inesperado: " . $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
