<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Octubre-2023*/
include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();


extract($_POST);

if (isset($chk_seg) == '') {
    $chk_seg = 0;
}

if (isset($chk_flor) == '') {
    $chk_flor = 0;
}
mysqli_query(
    $cnx,
    "UPDATE inventario SET inv_extrac = '$txt_ext', 
inv_especial = '$chk_seg',
inv_alcalinidad = '$txt_alcalinidad',
inv_calcios = '$txt_calcios',
inv_humedad = '$txt_humedad',
inv_ce = '$txt_ce',
inv_ban_flor = '$chk_flor',
inv_solidos = '$txt_solidos'
WHERE inv_id = '$hdd_id' "
)
    or die(mysqli_error($cnx) . " Error al actualizar el inventario");

$respuesta = array('mensaje' => "Inventario completado");
echo json_encode($respuesta);
