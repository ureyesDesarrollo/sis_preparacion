<?php
/* Desarrollado por: CCA Consultores TI */
/* Contacto: contacto@ccaconsultoresti.com */
/* Actualizado: Junio-2024 */

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx = Conectarse();

try {
    // Obtener y sanitizar datos de entrada
    $niv_id = mysqli_real_escape_string($cnx, $_POST['niv_id']);
    $niv_nivel = mysqli_real_escape_string($cnx, $_POST['niv_nivel']);
    $niv_posicion = mysqli_real_escape_string($cnx, $_POST['niv_posicion']);
    $rac_id = mysqli_real_escape_string($cnx, $_POST['rac_id']);

    // Normalizar
    $niv_nivel_normalized = trim(strtolower($niv_nivel));
    $niv_posicion_normalized = trim(strtolower($niv_posicion));

    // Verificar si el registro ya existe
    $check_sql = "SELECT niv_id FROM rev_nivel_posicion 
                  WHERE LOWER(TRIM(niv_nivel)) = '$niv_nivel_normalized' 
                  AND LOWER(TRIM(niv_posicion)) = '$niv_posicion_normalized'
                  AND rac_id = '$rac_id'
                  AND niv_id != '$niv_id'";
    $check_result = mysqli_query($cnx, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $res = "Un registro con un valor similar ya existe";
        echo json_encode(["error" => $res]);
    } else {
        // Obtener los valores actuales del registro
        $current_sql = "SELECT niv_nivel, niv_posicion, rac_id FROM rev_nivel_posicion WHERE niv_id = '$niv_id'";
        $current_result = mysqli_query($cnx, $current_sql);
        $current_row = mysqli_fetch_assoc($current_result);

        $current_nivel = trim(strtolower($current_row['niv_nivel']));
        $current_posicion = trim(strtolower($current_row['niv_posicion']));
        $current_rac = $current_row['rac_id'];

        // Verificar si se realizaron cambios
        $nivel_changed = $current_nivel !== $niv_nivel_normalized;
        $posicion_changed = $current_posicion !== $niv_posicion_normalized;
        $rac_changed = $current_rac !== $rac_id;

        if ($nivel_changed || $posicion_changed || $rac_changed) {
            // Construir la consulta de actualización
            $sql = "UPDATE rev_nivel_posicion SET niv_nivel = '$niv_nivel', 
            niv_posicion = '$niv_posicion',
            rac_id = '$rac_id'
            WHERE niv_id = '$niv_id'";

            // Ejecutar la consulta de actualización
            if (mysqli_query($cnx, $sql)) {
                $res = "Registro actualizado exitosamente";
                ins_bit_acciones($_SESSION['idUsu'], 'E', $niv_id, '40');
                echo json_encode(["success" => $res]);
            } else {
                $res = "Error en la actualización: " . mysqli_error($cnx);
                echo json_encode(["error" => $res]);
            }
        } else {
            $res = "No se realizaron cambios en los datos";
            echo json_encode(["success" => $res]);
        }
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
