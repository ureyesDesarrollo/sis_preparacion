<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Materiales obj. Actualizado");
echo json_encode($respuesta);

extract($_POST); 
//"INSERT INTO materiales_tipo_obj(mt_id, mto_kilos, mto_fecha) VALUES('$cbxTipo', '$txtKilos', '$txtFecha')"
mysqli_query($cnx, "UPDATE materiales_tipo_obj SET mto_kilos = '$txtKilos', mto_fecha = '$txtFecha', prv_id = '$slc_proveedor' WHERE mto_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_id, '17');
?> 