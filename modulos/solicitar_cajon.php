<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Realizado: 01-05-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();
extract($_POST);

$con = mysqli_query($cnx, "SELECT inv_id FROM inventario WHERE ac_id = " . $id . " AND inv_tomado = 0") or die(mysqli_error($cnx) . "Error: en consultar inventario");;
$reg = mysqli_fetch_assoc($con);

do{
    mysqli_query($cnx, "update inventario set inv_solicitado = 'S' WHERE inv_id = '$reg[inv_id]' ") or die(mysqli_error($cnx) . " Error al solicitar carga");

    ins_bit_acciones($_SESSION['idUsu'], 'M', $reg['inv_id'], '35');
}while ($reg = mysqli_fetch_assoc($con));

$respuesta = array('mensaje' => "Registro realizado");
echo json_encode($respuesta);
