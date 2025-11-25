<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Usuario agregado");
echo json_encode($respuesta);

extract($_POST); 

$NPass = md5($txtPwr);

mysqli_query($cnx, "INSERT INTO usuarios(usu_usuario, usu_nombre, up_id, usu_pwr,  usu_email, usu_est) VALUES('$txtUser', '$txtNombre', '$cbxperfil', '$NPass', '$txtEmail', 'A')") or die(mysqli_error($cnx)." Error al insertar");

$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(usu_id) as res from usuarios"));

ins_bit_acciones($_SESSION['idUsu'],'A', $reg_ultimo_id['res'], '10');
?> 