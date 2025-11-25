<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Proveedor Actualizado");
echo json_encode($respuesta);

extract($_POST);

mysqli_query($cnx, "UPDATE proveedores SET prv_nombre = '$txtNombre', prv_nom_comercial = '$txtNombreC', prv_rfc = '$txtRfc', prv_telefono = '$txtTelefono', prv_email = '$txtEmail', prv_contacto = '$txtContacto',prv_tipo='$cbxTipo' ,prv_calle = '$txtCalle', prv_numero = '$txtNo', prv_colonia = '$txtColonia', est_id = '$cbxEstadoE', ciu_id = '$cbxCiudadE', prv_cp =  '$txtCodPos',  prv_mql = '$cbxCategoriaProveedor' WHERE prv_id = '$hdd_id' ") or die(mysqli_error($cnx) . " Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_id, '7');
