<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Diciembre-2023*/

include "../../seguridad/user_seguridad.php";
require_once('../../conexion/conexion.php');
include "../../funciones/funciones.php";
$cnx = Conectarse();

extract($_POST);
//liberar por control de calidad
mysqli_query($cnx, "update equipos_preparacion set le_id = 15 WHERE ep_id = '$equipo'") or die(mysqli_error($cnx) . " Error1");

$strMsj = "Proceso liberado";

$respuesta = array('mensaje' => $strMsj);
echo json_encode($respuesta);
