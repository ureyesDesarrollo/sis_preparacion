<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

extract($_POST);
$chk_ingreso = isset($chk_ingreso) ? 'S' : 'N';
mysqli_query($cnx, "UPDATE materiales SET mt_id = '$cbxTipo', mat_nombre = '$txtMaterial',um_id='$cbxMedida', mat_stock_min = '$txtSMin', mat_stock_max = '$txtSMax',mat_existencia='$txtExistencia',mat_est = '$txtEstatus',mat_comentarios = '$txaNotas', mat_ingreso = '$chk_ingreso'  WHERE mat_id = '$hdd_id' ") or die(mysqli_error($cnx) . " Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'], 'E', $hdd_id, '5');

$respuesta = array('mensaje' => "Material Actualizado");
echo json_encode($respuesta);
