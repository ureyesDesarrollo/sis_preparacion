<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$cte_id = $_POST['cte_id'];
$sql = "UPDATE rev_clientes SET cte_estatus = 'B' WHERE cte_id = $cte_id";
try {
    if (mysqli_query($cnx, $sql)) {
        $res = "Registro dado de baja exitosamente";
        ins_bit_acciones($_SESSION['idUsu'], 'B', $cte_id, '49');
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
