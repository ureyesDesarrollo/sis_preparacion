<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
/*  */

$cnx = Conectarse();



extract($_POST);

mysqli_query($cnx, "INSERT INTO quimicos_almacen(usu_id,qa_fe_entrega,quim_id,qa_lote,qm_cant_entrega,um_id) VALUES('$cbx_operador_entrega','$txt_fecha','$cbx_quimico','$txt_lote','$txt_cantidad','$cbx_unidad')") or die(mysqli_error($cnx) . " Error al insertar");

$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(qa_id) as res from quimicos_almacen"));

ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '27');

$respuesta = array('mensaje' => "Registro guardado");
echo json_encode($respuesta);
?>