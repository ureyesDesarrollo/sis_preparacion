<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Realizado: 01-05-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();
extract($_POST);


//marca como ocupado el equipo nuevo
mysqli_query($cnx, "update inventario set inv_solicitado = 'S' WHERE inv_id = '$id'") or die(mysqli_error($cnx) . " Error al solicitar carga");

ins_bit_acciones($_SESSION['idUsu'], 'M', $id, '35');

$respuesta = array('mensaje' => "Registro realizado");
echo json_encode($respuesta);
