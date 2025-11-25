<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Julio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$rac_id = $_POST['rac_id'];
$sql = "UPDATE rev_racks SET rac_estatus = 'B' WHERE rac_id = $rac_id";
try {
    if (mysqli_query($cnx, $sql)) {
        $res = "Registro dado de baja exitosamente";
        ins_bit_acciones($_SESSION['idUsu'], 'B', $rac_id, '39');
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
