<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
/*Realizado: 21 - Agosto - 2018*/
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../conexion/conexion.php';
require_once __DIR__ . '/../funciones/funciones.php';

$cnx =  Conectarse();

extract($_POST);

$NPass = md5($txtPwr);
$con = mysqli_query($cnx, "SELECT usu_usuario, usu_id, up_id,usu_nombre
						 FROM usuarios 
						 WHERE usu_usuario = '" . $txtUser . "' and usu_pwr = '" . $NPass . "' and usu_est = 'A' ") or die(mysqli_error($cnx) . "Error: en consultar el usuario");
$reg = mysqli_fetch_assoc($con);

if ($reg['usu_id'] == '') {
	redirect('index.php?errorusuario=si');
} else {
	app_session_start();
	$_SESSION['user']	= $txtUser;
	$_SESSION['idUsu']	= $reg['usu_id'];
	$_SESSION['nombre']	= $reg['usu_nombre'];
	$_SESSION['privilegio']	= $reg['up_id'];
	$_SESSION["autentificado"] = "SI";
	$_SESSION["ultimoAcceso"] = time();

	ins_bit_login($reg['usu_id'], getRealIP());

	// Verificar si el tar_id fue pasado en el formulario
	$tar_id = isset($_POST['tar_id']) ? $_POST['tar_id'] : null;

	// Redirigir a la página de detalles de la tarima si se pasó un tar_id
	if ($tar_id) {
		redirect('revolturas/funciones/tarimas_detalle.php?tar_id=' . urlencode($tar_id));
	}

	if (isset($_POST["url"])) {
		$url = $_POST["url"];
		$urlPath = parse_url($url, PHP_URL_PATH);
		$urlPath = str_replace('\\', '/', $urlPath);

		#Verificamos si la url es la indicada para pelambre
		if (substr($urlPath, -strlen('/pelambre/index.php')) === '/pelambre/index.php') {
			redirect('pelambre/tablero_pelambre.php');
		} elseif (substr($urlPath, -strlen('/revolturas/index.php')) === '/revolturas/index.php') {
			redirect('revolturas/index_inicio.php');
		}
	}

	if ($reg['up_id'] == '3') {
		redirect('indicadores/index.php');
	} else {
		redirect('index_inicio.php');
	}
}
