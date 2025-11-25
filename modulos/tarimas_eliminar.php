<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";
$cnx = Conectarse();

extract($_POST); 

$cadena = mysqli_query($cnx, "DELETE FROM tarimas WHERE tarima_id = '$id' ") or die(mysqli_error()."Error: al eliminar tarima");
?> 