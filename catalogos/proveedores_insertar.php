<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Proveedor Agregado");
echo json_encode($respuesta);

extract($_POST);

mysqli_query($cnx, "INSERT INTO proveedores(prv_nombre, prv_rfc, prv_telefono, prv_email, prv_contacto,prv_tipo,prv_calle, prv_numero, prv_colonia, est_id, ciu_id, prv_cp, prv_est, prv_ban, prv_nom_comercial,prv_mql) VALUES('$txtNombre', '$txtRfc', '$txtTelefono', '$txtEmail', '$txtContacto', '$cbxTipo','$txtCalle', '$txtNo', '$txtColonia', '$cbxEstado', '$cbxCiudad', '$txtCodPos', 'A', '0', '$txtNombreC', '$cbxCategoriaProveedor')") or die(mysqli_error($cnx) . " Error al insertar");

$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(prv_id) as res from proveedores"));

ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '7');
