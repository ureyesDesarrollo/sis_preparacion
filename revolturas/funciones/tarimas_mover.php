<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../funciones/funciones.php";
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    extract($_POST);
    $update_tarima = "UPDATE rev_tarimas SET niv_id = '$niv_id' WHERE tar_id = '$tar_id'";
    $update_desocupado = !empty($niv_id_act) ? "UPDATE rev_nivel_posicion SET niv_ocupado = 0 WHERE niv_id = $niv_id_act" : null;
    $update_ocupado = "UPDATE rev_nivel_posicion SET niv_ocupado = 1 WHERE niv_id = $niv_id";

    $query_success = mysqli_query($cnx, $update_tarima) && mysqli_query($cnx, $update_ocupado);
    if ($update_desocupado) {
        $query_success = $query_success && mysqli_query($cnx, $update_desocupado);
    }

    if ($query_success) {
        $res = "Tarima cambiada exitosamente";
        ins_bit_acciones($_SESSION['idUsu'], 'E', $tar_id, '41');
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
