<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$blo_id = $_POST['blo_id'];
$sql = "UPDATE rev_bloom SET blo_estatus = 'B' WHERE blo_id = $blo_id";
try {
    if (mysqli_query($cnx, $sql)) {
        $res = "Registro dado de baja exitosamente";
        ins_bit_acciones($_SESSION['idUsu'], 'B', $blo_id, '37');
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
