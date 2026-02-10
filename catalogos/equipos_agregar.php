<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Registro guardado");
echo json_encode($respuesta);

extract($_POST);

mysqli_query($cnx, "INSERT INTO equipos_preparacion(ep_descripcion,ep_tipo,le_id,estatus,ep_carga_min,ep_carga_max) VALUES('$txt_descripcion','$cbx_tipo',9,'A','$txt_capacidad_min','$txt_capacidad_max')") or die(mysqli_error($cnx) . " Error al insertar");

$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(ep_id) as res from equipos_preparacion"));

ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '25');
?>