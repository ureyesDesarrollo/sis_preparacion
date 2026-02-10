<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Mezcla Agregada");
echo json_encode($respuesta);

extract($_POST); 

mysqli_query($cnx, "INSERT INTO mezclas (mez_nombre) VALUES('$txtNombre')") or die(mysqli_error($cnx)." Error al insertar la mezcla");

$reg_ultimo_id = mysqli_fetch_array(mysqli_query($cnx, "select MAX(mez_id) as res from mezclas"));

ins_bit_acciones($_SESSION['idUsu'], 'A', $reg_ultimo_id['res'], '21');
?> 