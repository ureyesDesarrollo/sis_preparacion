<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
/*Realizado: 21 - Agosto - 2018*/
require_once __DIR__ . '/../config/app.php';

app_session_start();

$_SESSION['error'] = 'Has cerrado tu sesión';
$_SESSION["autentificado"] = 'NO';
session_destroy();

extract($_GET);
if (isset($url_tab)) {
	$x = $url_tab;
} else {
	$x = '';
}

if (isset($url_revolturas)) {
	$url_rev = $url_revolturas;
} else {
	$url_rev = '';
}

if ($x == 'pelambre/tablero_pelambre.php') {
	redirect('pelambre/index.php');
} elseif ($url_rev == 'revolturas/index_inicio.php') {
	redirect('revolturas/index.php');
} else {
	$queryString = (isset($_GET['session_closed']) && $_GET['session_closed'] == 'true') ? '?session_closed=true' : '';
	redirect('index.php' . $queryString);
}
