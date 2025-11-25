<?php
include "../../conexion/conexion.php";
$cnx = Conectarse();

$tarima_id = isset($_POST['tarima_id']) ? intval($_POST['tarima_id']) : 0;
$nueva_posicion = isset($_POST['nueva_posicion']) ? $_POST['nueva_posicion'] : '';
$niv_id = isset($_POST['niv_id']) ? intval($_POST['niv_id']) : 0;
$rac_id = isset($_POST['rac_id']) ? intval($_POST['rac_id']) : 0;
if ($tarima_id > 0 && !empty($nueva_posicion)) {
    // Verificar si la nueva posición está desocupada
    $sql_verificar = "SELECT niv_id, niv_ocupado FROM rev_nivel_posicion 
    WHERE niv_codigo = '$nueva_posicion' 
    AND niv_id != '$niv_id' 
    AND rac_id = '$rac_id'";
    $result_verificar = mysqli_query($cnx, $sql_verificar);
    $posicion_nueva = mysqli_fetch_assoc($result_verificar);
    if ($posicion_nueva && $posicion_nueva['niv_ocupado'] == 0) {
        // Obtener el ID de la posición actual de la tarima
        $sql_actual = "SELECT niv_id FROM rev_nivel_posicion WHERE tar_id = $tarima_id";
        $result_actual = mysqli_query($cnx, $sql_actual);
        $posicion_actual = mysqli_fetch_assoc($result_actual)['niv_id'];

        if ($rac_id != 2) {
            $sql_actualizar_niv_id = "UPDATE rev_tarimas SET niv_id = {$posicion_nueva['niv_id']} WHERE tar_id = $tarima_id";
            mysqli_query($cnx, $sql_actualizar_niv_id);
        }
        // Actualizar la posición actual a desocupada
        $sql_desocupar = "UPDATE rev_nivel_posicion SET tar_id = NULL, niv_ocupado = 0 WHERE niv_id = $posicion_actual";
        mysqli_query($cnx, $sql_desocupar);

        // Actualizar la nueva posición a ocupada
        $sql_ocupar = "UPDATE rev_nivel_posicion SET tar_id = $tarima_id, niv_ocupado = 1 WHERE niv_id = {$posicion_nueva['niv_id']}";
        mysqli_query($cnx, $sql_ocupar);

        echo "success";
    } else {
        echo "ocupado";
    }
} else {
    echo "error";
}
?>