<?php 
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Mayo-2024*/

include "../seguridad/user_seguridad.php";
require_once('../conexion/conexion.php');
include "../funciones/funciones.php";

$cnx = Conectarse();

$respuesta = array('mensaje' => "Proveedor Mezclado");
echo json_encode($respuesta);

extract($_POST); 

$cadena = mysqli_query($cnx, "SELECT * from proveedores ") or die(mysqli_error($cnx)."Error: en consultar el inventario 1");
$registros = mysqli_fetch_assoc($cadena);

$str_cadena = '';
do{

$str_cadena = $registros['prv_id'].strtoupper($registros['prv_nombre']); 
$str_cadena = xtransforma($str_cadena, 6);
mysqli_query($cnx, "UPDATE proveedores SET prv_ncorto = '$str_cadena' WHERE prv_id = '$registros[prv_id]' ") or die(mysqli_error($cnx)." Error al actualizar");

ins_bit_acciones($_SESSION['idUsu'],'E', 0, '7');
}while($registros = mysqli_fetch_assoc($cadena));
?> 