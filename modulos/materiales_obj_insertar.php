<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

extract($_POST);

$cad = mysqli_query($cnx, "SELECT mto_id FROM materiales_tipo_obj WHERE mt_id = '$cbxTipo' AND  mto_kilos = '$txtKilos' AND mto_fecha = '$txtFecha' AND prv_id = '$slc_proveedor' ") or die(mysqli_error($cnx) . " Error al consultar");
$reg = mysqli_fetch_assoc($cad);

if (isset($reg['mto_id'])) {
	$respuesta = array('mensaje' => "Mat. Objetivo ya existe");
} else {
	mysqli_query($cnx, "INSERT INTO materiales_tipo_obj(mt_id, mto_kilos, mto_fecha, prv_id) VALUES('$cbxTipo', '$txtKilos', '$txtFecha', '$slc_proveedor')") or die(mysqli_error($cnx) . " Error al insertar");

	$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(mto_id) as res from materiales_tipo_obj"));

	ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '17');

	$respuesta = array('mensaje' => "Mat. Objetivo Agregado");
}

echo json_encode($respuesta);
