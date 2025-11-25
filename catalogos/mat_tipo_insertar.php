<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

extract($_POST); 

mysqli_query($cnx, "INSERT INTO materiales_tipo(mt_descripcion,mt_est) VALUES('$txtTipo','$cbxEstatus')") or die(mysqli_error($cnx)." Error al insertar");

$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(mt_id) as res from materiales_tipo"));

ins_bit_acciones($_SESSION['idUsu'],'A', $reg_ultimo_id['res'], '8');

$respuesta = array('mensaje' => "Tipo de material agregado");
echo json_encode($respuesta);
?>