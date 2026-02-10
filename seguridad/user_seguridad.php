<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
/*Realizado: 21 - Agosto - 2018*/
if (session_status() == PHP_SESSION_NONE) { // Verifica si el estado de sesión está iniciado
	session_start(); // Si no está iniciada, la inicia
}

// Valida que el usuario esté autenticado
if ($_SESSION["autentificado"] != "SI") {
	echo "<script>window.location.href = '../index.php';</script>";
	exit();
} else {
	// Calcula el tiempo transcurrido  
	$fechaGuardada = $_SESSION["ultimoAcceso"];
	$ahora = time();
	$tiempo_transcurrido = $ahora - $fechaGuardada;

	// Compara el tiempo transcurrido  
	if ($tiempo_transcurrido >= 600) { // 10 minutos
		// Si ha pasado 10 minutos o más  
		session_destroy(); // Destruye la sesión  
		echo "<script>window.location.href = 'index.php';</script>"; // Redirige usando JavaScript
		exit(); // Termina la ejecución
	} else {
		// Si no, actualiza la fecha de la sesión  
		$_SESSION["ultimoAcceso"] = $ahora;
	}
}
