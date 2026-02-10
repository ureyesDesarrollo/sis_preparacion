<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Tipo de material dado de Baja");
echo json_encode($respuesta);

extract($_POST); 

$result = mysqli_query ($cnx, "UPDATE materiales_tipo SET mt_est = 'B' WHERE mt_id = '$id'") or die(mysqli_error($cnx)."Error al dar de baja");		

ins_bit_acciones($_SESSION['idUsu'],'B', $id, '8');
