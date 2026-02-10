<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../funciones/funciones.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    $rre_id = $_POST['id_receta'];
    $sql = "UPDATE rev_receta SET rre_estatus = 'B' WHERE rre_id = '$rre_id'";

    if (mysqli_query($cnx, $sql)) {
        $res = 'Receta dada de baja correctamente';
        echo json_encode(["success" => $res]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
