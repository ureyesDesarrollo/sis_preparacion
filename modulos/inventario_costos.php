<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*CreadoF: Octubre-2023*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

extract($_POST);

mysqli_query($cnx, "Update inventario SET inv_costo = '$txt_costo' where inv_id = '$hdd_inv'") or die(mysqli_error($cnx) . " Error al insertar");

ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_inv, '2');

$respuesta = array('mensaje' => "Exito");
echo json_encode($respuesta);