<?php
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();


extract($_POST);

mysqli_query($cnx, "INSERT INTO procesos_renglones (pro_id, pe_id, pr_ren) VALUES('$txtPro', '$txtEtapa', '$txtRen')") or die(mysqli_error($cnx) . " Error al insertar ");

$respuesta = array('mensaje' => "Renglon agregado");

echo json_encode($respuesta);
