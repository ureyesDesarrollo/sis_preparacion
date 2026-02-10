<?php
date_default_timezone_set('America/Mexico_City');
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
if (!function_exists('Conectarse')) {

	function Conectarse()
	{
		require 'configuracion.php';

		$cn = mysqli_connect($server, $user, $pass);
		mysqli_select_db($cn, $bd) or die(mysqli_error($cn) . " Error: seleccionando la Base de datos");

		if (!$cn) {
			exit("Error: al conectar al servidor " . $cn);
		} else {
			$msj =  "Conexión realizada";
		}

		return $cn;
	}
}
