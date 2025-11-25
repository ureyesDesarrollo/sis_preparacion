<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: info@ccaconsultoresti.com */
/*Actualizado: Septiembre-2023*/
/*Realizado: 21 - Agosto - 2018*/
include "../conexion/conexion.php";
include "../funciones/funciones.php";
$cnx =  Conectarse();

extract($_POST);

$NPass = md5($txtPwr);

$con = mysqli_query($cnx, "SELECT usu_usuario, usu_id, up_id
						 FROM usuarios 
						 WHERE usu_usuario = '" . $txtUser . "' and usu_pwr = '" . $NPass . "' and usu_est = 'A' ") or die(mysqli_error($cnx) . "Error: en consultar el usuario");
$reg = mysqli_fetch_assoc($con);

if ($reg['usu_id'] == '') {
	header("Location: ../index.php?errorusuario=si");
} else {
	if (!isset($_SESSION)) {
		session_start();
		$_SESSION['user']	= $txtUser;
		$_SESSION['idUsu']	= $reg['usu_id'];
		$_SESSION['privilegio']	= $reg['up_id'];
		$_SESSION["autentificado"] = "SI";
		$_SESSION["ultimoAcceso"] = time();

		ins_bit_login($reg['usu_id'], getRealIP());
	}

	$url = $_POST["url"];
	echo $url;
	#Verificamos si la url es la indicada para pelambre
	if (strpos($url, '../sis_preparacion/pelambre/index.php') !== false) {
		header("location: ../pelambre/tablero_pelambre.php");
		exit();
	}

	if ($reg['up_id'] == '3') {
		header("location: ../indicadores/index.php");
	} else {
		header("location: ../index_inicio.php");
	}
}
