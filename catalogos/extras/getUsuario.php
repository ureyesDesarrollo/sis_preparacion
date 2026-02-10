<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

require_once('../../conexion/conexion.php');
$cnx =  Conectarse();
extract($_POST);
$mensaje = "";

//edicion
if (isset($usuario_anterior)) {
	if ($usuario_anterior != $txtUser) {
		$cad_usu = mysqli_query($cnx, "SELECT usu_usuario FROM usuarios WHERE usu_usuario='$txtUser'") or die(mysqli_error($cnx) . "Error de sistema al consultar la usuario");
		$reg_usu = mysqli_fetch_array($cad_usu);
	}
}

//alta
else {
	$cad_usu = mysqli_query($cnx, "SELECT usu_usuario FROM usuarios WHERE usu_usuario='$txtUser'") or die(mysqli_error($cnx) . "Error de sistema al consultar la usuario");
	$reg_usu = mysqli_fetch_array($cad_usu);
}


//Variable vacía (para evitar los E_NOTICE)


if (isset($reg_usu['usu_usuario'])) {
	$mensaje .= 'El usuario <p style="display:inline-block;font-weight:bold;padding-top:10px"> ' . $reg_usu['usu_usuario'] . ' </p> ya existe, intente con otro nombre de usuario.';
}


echo $mensaje;
?>