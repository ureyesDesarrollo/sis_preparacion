<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
/*Realizado: 21 - Agosto - 2018*/
require_once __DIR__ . '/../config/app.php';
app_session_start();

// Valida que el usuario esté autenticado
if (!isset($_SESSION["autentificado"]) || $_SESSION["autentificado"] != "SI") {
	redirect('index.php');
} else {
	// Calcula el tiempo transcurrido  
	$fechaGuardada = isset($_SESSION["ultimoAcceso"]) ? $_SESSION["ultimoAcceso"] : time();
	$ahora = time();
	$tiempo_transcurrido = $ahora - $fechaGuardada;

	// Compara el tiempo transcurrido  
	if ($tiempo_transcurrido >= 600) { // 10 minutos
		// Si ha pasado 10 minutos o más  
		$_SESSION = array();
		session_destroy(); // Destruye la sesión  
		redirect('index.php?session_closed=true');
	} else {
		// Si no, actualiza la fecha de la sesión  
		$_SESSION["ultimoAcceso"] = $ahora;
	}
}
