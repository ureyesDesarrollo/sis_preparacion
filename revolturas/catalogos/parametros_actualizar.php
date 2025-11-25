<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

try {
    $rp_inicio = mysqli_real_escape_string($cnx, $_POST['rp_inicio']);
    $rp_fin = mysqli_real_escape_string($cnx, $_POST['rp_fin']);
    $rp_id = $_POST['rp_id'];
    $sql = "UPDATE rev_parametros SET rp_inicio = '$rp_inicio', rp_fin = '$rp_fin' WHERE rp_id = '$rp_id'";

    if (mysqli_query($cnx, $sql)) {
        $res = "Registro actualizado exitosamente";
        ins_bit_acciones($_SESSION['idUsu'], 'E', $rp_id, '47');
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
