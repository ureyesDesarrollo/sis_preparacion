<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Junio-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";
include "../../funciones/funciones.php";
$cnx =  Conectarse();

$vis_descrip = $_POST['vis_descrip'];
$vis_id = $_POST['vis_id'];
$sql = "UPDATE rev_viscosidades SET vis_estatus = 'B' WHERE vis_id = $vis_id";
try {
    if (mysqli_query($cnx, $sql)) {
        $res = "Registro dado de baja exitosamente";
        ins_bit_acciones($_SESSION['idUsu'], 'B', $vis_id, '38');
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
