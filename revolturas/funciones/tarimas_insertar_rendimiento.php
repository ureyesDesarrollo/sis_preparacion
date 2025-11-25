<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $pro_id = mysqli_real_escape_string($cnx, $_POST['pro_id']);
        $tar_rendimiento = mysqli_real_escape_string($cnx, $_POST['tar_rendimiento']);
        $tar_id = $_POST['tar_id'];
        $sql = "UPDATE rev_tarimas SET tar_rendimiento = '$tar_rendimiento' WHERE pro_id = '$pro_id'";

        if (mysqli_query($cnx, $sql)) {

            //MC
            //mysqli_query($cnx, "UPDATE lotes_anio SET lote_rendimiento = '$tar_rendimiento' WHERE lote_id = (select lote_id from procesos_agrupados where pro_id = '$pro_id')");

            $res = "Rendimiento calculado exitosamente";
            ins_bit_acciones($_SESSION['idUsu'], 'E', $tar_id, '41');
            echo json_encode(["success" => $res]);
        } else {
            $res = "Error en la actualización: " . mysqli_error($cnx);
            echo json_encode(["error" => $res]);
        }
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
