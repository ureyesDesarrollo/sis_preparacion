<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$pres_id = $_POST['pres_id'];
$sql = "UPDATE rev_presentacion SET pres_estatus = 'B' WHERE pres_id = $pres_id";
try {
    if (mysqli_query($cnx, $sql)) {
        $res = "Registro dado de baja exitosamente";
        ins_bit_acciones($_SESSION['idUsu'], 'B', $pres_id, '36');
        echo json_encode(["success" => $res]);
    } else {
        $res =  $sql . "<br>" . mysqli_error($cnx);

        echo json_encode(["error" => $res]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($cnx);
}
