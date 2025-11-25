<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
try {
    extract($_POST);
    $tar_estatus = '';
    $opcion = '';
    if (isset($_POST['action']) && $_POST['action'] == 'revoltura') {
        $tar_estatus = '2';
        $opcion = 'revoltura';
    } else if (isset($_POST['action']) && $_POST['action'] == 'mezcla') {
        $tar_estatus = '4';
        $opcion = 'mezcla';
    }

    $sql = "UPDATE rev_tarimas SET tar_estatus = '$tar_estatus' WHERE tar_id = $tar_id";

    if (mysqli_query($cnx, $sql)) {
        $res = "Tarima tomada para " . $opcion;
        ins_bit_acciones($_SESSION['idUsu'], 'E', $tar_id, '46');
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
