<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/

require_once('../../conexion/conexion.php');

require_once('../../funciones/funciones.php');

$cnx =  Conectarse();

$id = $_GET['id'];

$cad_cbx =  mysqli_query($cnx, "SELECT prv_tipo, prv_ban FROM proveedores WHERE prv_id = '$id'") or die(mysqli_error($cnx) . "Error: en consultar el proveedor");
$reg_cbx =  mysqli_fetch_array($cad_cbx);

if ($reg_cbx['prv_tipo'] == 'L' and $reg_cbx['prv_ban'] == '1') {
	require "../inventario_local_a_maquila.php";
} else if ($reg_cbx['prv_tipo'] == 'L' and $reg_cbx['prv_ban'] == '0') {
	require "../inventario_local.php";
} else {
	require "../inventario_extranjero.php";
}
