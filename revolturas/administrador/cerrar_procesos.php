<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Sepetiembre-2024*/

include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";

$cnx = Conectarse();

try {
    extract($_POST);
    $query = "UPDATE lotes_anio SET lote_estatus = '3' 
    WHERE lote_id = (SELECT lote_id FROM procesos_agrupados WHERE pro_id = '$pro_id')";

    $tarimas = mysqli_query($cnx, "SELECT tar_id FROM rev_tarimas WHERE pro_id = '$pro_id'");
    if (mysqli_num_rows($tarimas) > 0) {

        if (mysqli_query($cnx, $query)) {
            //ins_bit_acciones($_SESSION['idUsu'], 'A', $pro_id, '41'); Modulo Administrador
            $res = "Proceso:  $pro_id, se ha cerrado correctamente";
            echo json_encode(["success" => $res]);
        } else {
            $res = $query . "<br>" . mysqli_error($cnx);
            echo json_encode(["error" => $res]);
        }
    } else {
        echo json_encode(["error" => "El proceso: $pro_id no puede ser cerrado ya que no cuenta con tarimas creadas."]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
