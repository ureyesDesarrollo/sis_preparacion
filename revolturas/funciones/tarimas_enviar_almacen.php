<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        extract($_POST);
        $sql = "UPDATE rev_tarimas SET tar_estatus = 1 WHERE tar_id = '$tar_id'";

        if (mysqli_query($cnx, $sql)) {
            $res = "Tarima enviada a almacén";
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
}
