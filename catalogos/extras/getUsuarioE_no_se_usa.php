<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/
	
require_once('../../conexion/conexion.php');
$cnx =  Conectarse();

$usuarioE = $_POST['txtUserE'];

$cad_usu = mysqli_query($cnx, "SELECT usu_usuario FROM usuarios WHERE usu_usuario='$usuarioE'") or die(mysqli_error($cnx)."Error de sistema al consultar la usuario");
$reg_usu = mysqli_fetch_array($cad_usu);

//Variable vacía (para evitar los E_NOTICE)
$mensaje = "";

if ($reg_usu['usu_usuario'] != '') 
	{
		$mensaje .= 'El usuario <p style="display:inline-block;font-weight:bold;padding-top:10px"> '.$reg_usu['usu_usuario']. ' </p> ya existe, intente con otro nombre de usuario.';
	} else{
		
	}

echo $mensaje;
?>