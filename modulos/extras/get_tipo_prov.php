<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/

require_once('../../conexion/conexion.php');

require_once('../../funciones/funciones.php');

$cnx =  Conectarse();

extract($_POST);

$cad_cbx =  mysqli_query($cnx, "SELECT prv_tipo, prv_ban FROM proveedores WHERE prv_id = '$id'") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
$reg_cbx =  mysqli_fetch_array($cad_cbx);

/* Si es proveedor extranjero y proveedor especial */
if ($reg_cbx['prv_tipo'] == 'E' || ($reg_cbx['prv_tipo'] == 'L' && $reg_cbx['prv_ban'] == '1')) {
    $bloquea_ubicacion = 'SI';
} else {
    $bloquea_ubicacion = 'NO';
}
echo $bloquea_ubicacion;
