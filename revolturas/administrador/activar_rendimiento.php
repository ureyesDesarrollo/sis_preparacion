<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Septiembre-2024*/
include "../../seguridad/user_seguridad.php";
include "../../conexion/conexion.php";


$cnx = Conectarse();
try{
$pro_id = isset($_POST['pro_id']) ? $_POST['pro_id'] : '';

$query = "UPDATE rev_tarimas SET tar_rendimiento = NULL WHERE pro_id = '$pro_id'";
if (mysqli_query($cnx, $query)) {
    $res = "Se ha activado correctamente el rendimiento para calcular.";
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