<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
/*Realizado: 21 - Agosto - 2018*/
if (session_status() == PHP_SESSION_NONE) { // Verifica si el estado de sesión está iniciado
	session_start(); // Si no está iniciada, la inicia
}

//Valida que el usuario este autentificado 
if ($_SESSION["autentificado"] != "SI") {
	header("Location: ../index.php");
	exit();
} else {
	//sino, calculamos el tiempo transcurrido  
	$fechaGuardada = $_SESSION["ultimoAcceso"];
	$ahora = time();
	$tiempo_transcurrido = $ahora - $fechaGuardada;

	//comparamos el tiempo transcurrido  
	if ($tiempo_transcurrido >= 600) //10 Minutos
	{
		//si pasaron 10 minutos o más  
		session_start();
		session_destroy(); // destruyo la sesión  
		header("Location: index.php"); //envío al usuario a la pag. de autenticación  
		//sino, actualizo la fecha de la sesión  
	} else {
		$_SESSION["ultimoAcceso"] = $ahora;
	}

	//echo "aqui" . $_SESSION["autentificado"];

	// Envía el estado de autenticación a JavaScript
	/* echo '<script>';
    echo 'var sesionAutentificada = ' . ($_SESSION["autentificado"] == "SI" ? 'true' : 'false') . ';';
    echo '</script>'; */
}
?>
