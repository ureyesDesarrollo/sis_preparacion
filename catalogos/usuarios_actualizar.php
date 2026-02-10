<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Usuario Actualizado");
echo json_encode($respuesta);

extract($_POST); 

if($txtPwr != $hddPwr)
{
    $NPass = md5($txtPwr);

    mysqli_query($cnx, "UPDATE usuarios SET usu_usuario = '$txtUserE', usu_nombre = '$txtNombre', usu_pwr='$NPass', up_id = '$cbxperfil', usu_email = '$txtEmail', usu_est = '$slc_estatus' WHERE usu_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar"); 
}
else{
    mysqli_query($cnx, "UPDATE usuarios SET usu_usuario = '$txtUserE', usu_nombre = '$txtNombre', up_id = '$cbxperfil', usu_email = '$txtEmail', usu_est = '$slc_estatus' WHERE usu_id = '$hdd_id' ") or die(mysqli_error($cnx)." Error al actualizar");
}

ins_bit_acciones($_SESSION['idUsu'],'E', $hdd_id, '10');
?> 