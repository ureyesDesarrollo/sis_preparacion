<?php
include "../seguridad/user_seguridad.php";
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();
extract($_POST);
$consulta =  mysqli_query($cnx, "SELECT * FROM almacen_cajones WHERE ac_ban = '$check_molinos' AND ac_estatus = 'A' ORDER BY ac_descripcion");

$html .= "<option value=''>Selecciona </option>";
while ($reg =  mysqli_fetch_array($consulta)) {
    $html .= "<option value='" . $reg['ac_id'] . "'>" . "Cajón " . $reg['ac_descripcion'] . "</option>";
}

echo $html;
