<?php
include "../../conexion/conexion.php";
$cnx = Conectarse();

$tarima_id = isset($_POST['tarima_id']) ? intval($_POST['tarima_id']) : 0;
$nueva_posicion = isset($_POST['nueva_posicion']) ? $_POST['nueva_posicion'] : '';
$rac_id = isset($_POST['rac_id']) ? intval($_POST['rac_id']) : 0;

if ($tarima_id > 0 && !empty($nueva_posicion)) {
    // Obtener el ID de la nueva posición
    $sql_nueva = "SELECT niv_id FROM rev_nivel_posicion 
                  WHERE niv_codigo = '$nueva_posicion' AND rac_id = $rac_id";
    $result_nueva = mysqli_query($cnx, $sql_nueva);

    if ($row_nueva = mysqli_fetch_assoc($result_nueva)) {
        $nueva_niv_id = $row_nueva['niv_id'];

        // Eliminar asignaciones anteriores de esta tarima
        $sql_delete = "DELETE FROM rev_nivel_posicion_detalle WHERE tar_id = $tarima_id";
        mysqli_query($cnx, $sql_delete);

        // Insertar la tarima en la nueva posición
        $sql_insert = "INSERT INTO rev_nivel_posicion_detalle (niv_id, tar_id) VALUES ($nueva_niv_id, $tarima_id)";
        if (mysqli_query($cnx, $sql_insert)) {
            // Actualizar ocupación de la nueva posición
            $sql_ocupar = "UPDATE rev_nivel_posicion SET niv_ocupado = 1 WHERE niv_id = $nueva_niv_id";
            mysqli_query($cnx, $sql_ocupar);

            // Verificar si la posición anterior quedó vacía y actualizarla a desocupada
            $sql_vacias = "UPDATE rev_nivel_posicion SET niv_ocupado = 0 
                WHERE niv_id NOT IN (SELECT DISTINCT niv_id FROM rev_nivel_posicion_detalle)";
            mysqli_query($cnx, $sql_vacias);

            if ($rac_id != 2) {
                $sql_update_tarima = "UPDATE rev_tarimas SET niv_id = $nueva_niv_id WHERE tar_id = $tarima_id";
                mysqli_query($cnx, $sql_update_tarima);
            }
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }
} else {
    echo "error";
}
