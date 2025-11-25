<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Septiembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";


$cnx = Conectarse();

try {
    $tar_id = isset($_POST['tar_id']) ? $_POST['tar_id'] : '';
    $pro_id_n = isset($_POST['pro_id_n']) ? $_POST['pro_id_n'] : '';
    $pro_id = isset($_POST['pro_id']) ? $_POST['pro_id'] : '';


    $query = "";
    if (!empty($pro_id_n)) {
        $query = "UPDATE rev_tarimas SET pro_id = '$pro_id_n' WHERE tar_id = '$tar_id'";
    } else {
        throw new Exception('No se proporcionó ningún dato para actualizar.');
    }

    if (mysqli_query($cnx, $query)) {
        $ac_rendi = $query = "UPDATE rev_tarimas SET tar_rendimiento = NULL WHERE pro_id IN ($pro_id,$pro_id_n)";
        mysqli_query($cnx, $ac_rendi);
        $res = "Se ha actualizado correctamente la información de la tarima.";
        //ins_bit_acciones($_SESSION['idUsu'], 'A', $pro_id, '41'); Modulo Administrador
        echo json_encode(["success" => $res]);
    } else {
        $res = $query . "<br>" . mysqli_error($cnx);
        echo json_encode(["error" => $res]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
