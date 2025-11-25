<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Junio-2024 */
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx = Conectarse();

$pres_descrip = mysqli_real_escape_string($cnx, $_POST['pres_descrip']);
$pres_id = mysqli_real_escape_string($cnx, $_POST['pres_id']);
$pres_kg = mysqli_real_escape_string($cnx, $_POST['pres_kg']);
$pres_estatus = isset($_POST['chk_estatus']) ? 'A' : 'B';

// Normalizar la descripción (convertir a minúsculas y eliminar espacios innecesarios)
$pres_descrip_normalized = trim(strtolower($pres_descrip));
$pres_kg_normalized = trim(strtolower($pres_kg));

// Verificar si existe otro registro con la misma descripción
$check_sql = "SELECT pres_id FROM rev_presentacion WHERE LOWER(TRIM(pres_descrip)) = '$pres_descrip_normalized' 
AND LOWER(TRIM(pres_kg)) = '$pres_kg_normalized' AND pres_id != '$pres_id'";
$check_result = mysqli_query($cnx, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    $res = "Un registro con una descripción similar ya existe";
    echo json_encode(["error" => $res]);
    mysqli_close($cnx);
    exit();
}

// Obtener los valores actuales del registro
$current_sql = "SELECT pres_descrip,pres_kg, pres_estatus FROM rev_presentacion WHERE pres_id = '$pres_id'";
$current_result = mysqli_query($cnx, $current_sql);
$current_row = mysqli_fetch_assoc($current_result);

$current_descrip = $current_row['pres_descrip'];
$current_estatus = $current_row['pres_estatus'];
$current_kg = $current_row['pres_kg'];

// Verificar si se realizaron cambios
$descrip_changed = $current_descrip !== $pres_descrip;
$estatus_changed = $current_estatus != $pres_estatus;
$kg_changed = $current_kg != $pres_kg;

$sql = "UPDATE rev_presentacion SET pres_descrip = '$pres_descrip', pres_estatus = '$pres_estatus', pres_kg = '$pres_kg' WHERE pres_id = '$pres_id'";

try {
    if (mysqli_query($cnx, $sql)) {
        if ($descrip_changed && $estatus_changed) {
            $res = "Registro y estatus actualizados exitosamente";
        } elseif ($descrip_changed) {
            $res = "Registro actualizado exitosamente";
        } elseif ($estatus_changed) {
            $res = "Estatus actualizado exitosamente";
        } else {
            $res = "No se realizaron cambios en los datos";
        }
        ins_bit_acciones($_SESSION['idUsu'], 'E', $pres_id, '36');
        echo json_encode(["success" => $res]);
    } else {
        $res = $sql . "<br>" . mysqli_error($cnx);
        echo json_encode(["error" => $res]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
