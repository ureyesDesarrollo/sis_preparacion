<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Tipo proceso Agregado");
echo json_encode($respuesta);

extract($_POST); 

mysqli_query($cnx, "INSERT INTO preparacion_tipo(pt_descripcion, pt_revision, pt_estatus, pt_para) VALUES('$txtNombre', '$txtRevision', 'A', '$cbxTipo')") or die(mysqli_error($cnx)." Error al insertar");

$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(pt_id) as res from preparacion_tipo"));

ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '9');
?> 